const db = require('../config/db');

exports.getInventory = (req, res) => {
  try {
    const { category, status } = req.query;
    let sql = 'SELECT * FROM inventory WHERE 1=1';
    const params = [];

    if (category && category !== 'all') {
      sql += ' AND category = ?';
      params.push(category);
    }
    if (status && status !== 'all') {
      sql += ' AND status = ?';
      params.push(status);
    }

    sql += ' ORDER BY item_name ASC';
    const items = db.all(sql, params);
    res.json(items);
  } catch (error) {
    res.status(500).json({ message: 'Error fetching inventory', error: error.message });
  }
};

exports.createInventoryItem = (req, res) => {
  try {
    const { item_name, category, quantity, unit = 'pcs', unit_price, reorder_level = 10 } = req.body;
    if (!item_name || !category || quantity === undefined || !unit_price) {
      return res.status(400).json({ message: 'Item name, category, quantity, and unit price required' });
    }

    const status = quantity <= reorder_level ? 'low_stock' : 'in_stock';
    const result = db.run(
      `INSERT INTO inventory (item_name, category, quantity, unit, unit_price, reorder_level, status)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [item_name, category, quantity, unit, unit_price, reorder_level, status]
    );

    const created = db.get('SELECT * FROM inventory WHERE id = ?', [result.lastInsertRowid]);
    res.status(201).json({ message: 'Item added to inventory', item: created });
  } catch (error) {
    res.status(500).json({ message: 'Error adding inventory item', error: error.message });
  }
};

exports.updateInventoryItem = (req, res) => {
  try {
    const { id } = req.params;
    const { item_name, category, quantity, unit, unit_price, reorder_level } = req.body;

    const existing = db.get('SELECT * FROM inventory WHERE id = ?', [id]);
    if (!existing) {
      return res.status(404).json({ message: 'Item not found' });
    }

    const newQty = quantity !== undefined ? quantity : existing.quantity;
    const newReorder = reorder_level !== undefined ? reorder_level : existing.reorder_level;
    const status = newQty <= newReorder ? 'low_stock' : 'in_stock';

    db.run(
      `UPDATE inventory
       SET item_name = COALESCE(?, item_name),
           category = COALESCE(?, category),
           quantity = COALESCE(?, quantity),
           unit = COALESCE(?, unit),
           unit_price = COALESCE(?, unit_price),
           reorder_level = COALESCE(?, reorder_level),
           status = ?,
           updated_at = CURRENT_TIMESTAMP
       WHERE id = ?`,
      [item_name, category, quantity, unit, unit_price, reorder_level, status, id]
    );

    const updated = db.get('SELECT * FROM inventory WHERE id = ?', [id]);
    res.json({ message: 'Item updated successfully', item: updated });
  } catch (error) {
    res.status(500).json({ message: 'Error updating inventory', error: error.message });
  }
};

// Store out-stock process: Transfer goods to Bar, Kitchen, Housekeeping, etc.
exports.transferStock = (req, res) => {
  try {
    const { to_dept, item_name, quantity, notes } = req.body;
    if (!to_dept || !item_name || !quantity || quantity <= 0) {
      return res.status(400).json({ message: 'Target department, item name, and valid quantity required' });
    }

    const item = db.get('SELECT * FROM inventory WHERE item_name = ? COLLATE NOCASE', [item_name.trim()]);
    if (!item) {
      return res.status(404).json({ message: `Inventory item "${item_name}" not found in store` });
    }

    if (item.quantity < quantity) {
      return res.status(400).json({ message: `Insufficient stock. Current inventory has only ${item.quantity} ${item.unit}` });
    }

    // Deduct from inventory
    const newQty = item.quantity - quantity;
    const status = newQty <= item.reorder_level ? 'low_stock' : 'in_stock';
    db.run('UPDATE inventory SET quantity = ?, status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?', [newQty, status, item.id]);

    // Record transfer log
    const transferred_by = req.user ? req.user.full_name : 'Store Keeper';
    const result = db.run(
      `INSERT INTO inventory_transfers (from_dept, to_dept, item_name, quantity, unit, transferred_by, notes)
       VALUES ('Store', ?, ?, ?, ?, ?, ?)`,
      [to_dept, item.item_name, quantity, item.unit, transferred_by, notes || `Requisition by ${to_dept}`]
    );

    // If transferred to Bar, also boost beverages if matched
    if (to_dept === 'Bar') {
      const bev = db.get('SELECT id, quantity FROM beverages WHERE name LIKE ?', [`%${item.item_name}%`]);
      if (bev) {
        db.run('UPDATE beverages SET quantity = quantity + ? WHERE id = ?', [quantity, bev.id]);
      }
    }

    const transfer = db.get('SELECT * FROM inventory_transfers WHERE id = ?', [result.lastInsertRowid]);
    res.status(201).json({
      message: `Successfully transferred ${quantity} ${item.unit} to ${to_dept}`,
      transfer,
      remainingStock: newQty
    });
  } catch (error) {
    res.status(500).json({ message: 'Error during stock transfer', error: error.message });
  }
};

exports.getTransfers = (req, res) => {
  try {
    const transfers = db.all('SELECT * FROM inventory_transfers ORDER BY id DESC LIMIT 100');
    res.json(transfers);
  } catch (error) {
    res.status(500).json({ message: 'Error retrieving transfers', error: error.message });
  }
};

// Purchaser: Purchase Orders
exports.getPurchases = (req, res) => {
  try {
    const purchases = db.all('SELECT * FROM purchase_orders ORDER BY id DESC');
    res.json(purchases);
  } catch (error) {
    res.status(500).json({ message: 'Error fetching purchase orders', error: error.message });
  }
};

exports.createPurchase = (req, res) => {
  try {
    const { item_name, category, supplier, quantity, unit_price, status = 'received' } = req.body;
    if (!item_name || !supplier || !quantity || !unit_price) {
      return res.status(400).json({ message: 'Item name, supplier, quantity, and unit price required' });
    }

    const total_cost = quantity * unit_price;
    const po_number = `PO-${Math.floor(100 + Math.random() * 900)}`;
    const purchaser_name = req.user ? req.user.full_name : 'Procurement Officer';

    const result = db.run(
      `INSERT INTO purchase_orders (po_number, item_name, category, supplier, quantity, unit_price, total_cost, purchaser_name, status)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [po_number, item_name, category || 'Supplies', supplier, quantity, unit_price, total_cost, purchaser_name, status]
    );

    // If status is received, automatically add/update inventory
    if (status === 'received') {
      const existing = db.get('SELECT * FROM inventory WHERE item_name = ? COLLATE NOCASE', [item_name.trim()]);
      if (existing) {
        db.run('UPDATE inventory SET quantity = quantity + ?, unit_price = ?, status = "in_stock", updated_at = CURRENT_TIMESTAMP WHERE id = ?', [quantity, unit_price, existing.id]);
      } else {
        db.run(
          `INSERT INTO inventory (item_name, category, quantity, unit_price, status)
           VALUES (?, ?, ?, ?, 'in_stock')`,
          [item_name, category || 'Supplies', quantity, unit_price]
        );
      }

      // Log expense in financial transactions
      db.run(
        `INSERT INTO financial_transactions (type, category, amount, reference_id, provider_name, description)
         VALUES ('expense', 'inventory_purchase', ?, ?, ?, ?)`,
        [total_cost, po_number, purchaser_name, `Procured ${quantity} units of ${item_name} from ${supplier}`]
      );
    }

    const created = db.get('SELECT * FROM purchase_orders WHERE id = ?', [result.lastInsertRowid]);
    res.status(201).json({ message: 'Purchase logged and stock updated', purchase: created });
  } catch (error) {
    res.status(500).json({ message: 'Error recording purchase', error: error.message });
  }
};
