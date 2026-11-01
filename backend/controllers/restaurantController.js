const db = require('../config/db');

exports.getFoods = (req, res) => {
  try {
    const { category } = req.query;
    let sql = 'SELECT * FROM foods WHERE 1=1';
    const params = [];
    if (category && category !== 'all') {
      sql += ' AND category = ?';
      params.push(category);
    }
    sql += ' ORDER BY id ASC';
    const foods = db.all(sql, params);
    res.json(foods);
  } catch (error) {
    res.status(500).json({ message: 'Error retrieving foods', error: error.message });
  }
};

exports.createFood = (req, res) => {
  try {
    const { name, category, price, quantity = 50, status = 'available', image_url, description } = req.body;
    if (!name || !category || !price) {
      return res.status(400).json({ message: 'Name, category, and price are required' });
    }

    const result = db.run(
      `INSERT INTO foods (name, category, price, quantity, status, image_url, description)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [name, category, price, quantity, status, image_url || '', description || '']
    );

    const created = db.get('SELECT * FROM foods WHERE id = ?', [result.lastInsertRowid]);
    res.status(201).json({ message: 'Food item created', food: created });
  } catch (error) {
    res.status(500).json({ message: 'Error creating food', error: error.message });
  }
};

exports.updateFood = (req, res) => {
  try {
    const { id } = req.params;
    const { name, category, price, quantity, status, image_url, description } = req.body;

    db.run(
      `UPDATE foods
       SET name = COALESCE(?, name),
           category = COALESCE(?, category),
           price = COALESCE(?, price),
           quantity = COALESCE(?, quantity),
           status = COALESCE(?, status),
           image_url = COALESCE(?, image_url),
           description = COALESCE(?, description)
       WHERE id = ?`,
      [name, category, price, quantity, status, image_url, description, id]
    );

    const updated = db.get('SELECT * FROM foods WHERE id = ?', [id]);
    res.json({ message: 'Food item updated', food: updated });
  } catch (error) {
    res.status(500).json({ message: 'Error updating food', error: error.message });
  }
};

exports.deleteFood = (req, res) => {
  try {
    db.run('DELETE FROM foods WHERE id = ?', [req.params.id]);
    res.json({ message: 'Food item deleted' });
  } catch (error) {
    res.status(500).json({ message: 'Error deleting food', error: error.message });
  }
};

exports.getBeverages = (req, res) => {
  try {
    const { category } = req.query;
    let sql = 'SELECT * FROM beverages WHERE 1=1';
    const params = [];
    if (category && category !== 'all') {
      sql += ' AND category = ?';
      params.push(category);
    }
    sql += ' ORDER BY id ASC';
    const beverages = db.all(sql, params);
    res.json(beverages);
  } catch (error) {
    res.status(500).json({ message: 'Error retrieving beverages', error: error.message });
  }
};

exports.createBeverage = (req, res) => {
  try {
    const { name, category, purchase_price, price, quantity = 100, unit = 'bottle', status = 'available' } = req.body;
    if (!name || !category || !price) {
      return res.status(400).json({ message: 'Name, category, and price are required' });
    }

    const result = db.run(
      `INSERT INTO beverages (name, category, purchase_price, price, quantity, unit, status)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [name, category, purchase_price || 0, price, quantity, unit, status]
    );

    const created = db.get('SELECT * FROM beverages WHERE id = ?', [result.lastInsertRowid]);
    res.status(201).json({ message: 'Beverage created', beverage: created });
  } catch (error) {
    res.status(500).json({ message: 'Error creating beverage', error: error.message });
  }
};

exports.updateBeverage = (req, res) => {
  try {
    const { id } = req.params;
    const { name, category, purchase_price, price, quantity, unit, status } = req.body;

    db.run(
      `UPDATE beverages
       SET name = COALESCE(?, name),
           category = COALESCE(?, category),
           purchase_price = COALESCE(?, purchase_price),
           price = COALESCE(?, price),
           quantity = COALESCE(?, quantity),
           unit = COALESCE(?, unit),
           status = COALESCE(?, status)
       WHERE id = ?`,
      [name, category, purchase_price, price, quantity, unit, status, id]
    );

    const updated = db.get('SELECT * FROM beverages WHERE id = ?', [id]);
    res.json({ message: 'Beverage updated', beverage: updated });
  } catch (error) {
    res.status(500).json({ message: 'Error updating beverage', error: error.message });
  }
};

exports.deleteBeverage = (req, res) => {
  try {
    db.run('DELETE FROM beverages WHERE id = ?', [req.params.id]);
    res.json({ message: 'Beverage deleted' });
  } catch (error) {
    res.status(500).json({ message: 'Error deleting beverage', error: error.message });
  }
};

exports.getOrders = (req, res) => {
  try {
    const { status, host_id, date } = req.query;
    let sql = 'SELECT * FROM orders WHERE 1=1';
    const params = [];

    if (status && status !== 'all') {
      sql += ' AND status = ?';
      params.push(status);
    }
    if (host_id) {
      sql += ' AND host_id = ?';
      params.push(host_id);
    }
    if (date) {
      sql += ' AND reported_date = ?';
      params.push(date);
    }

    sql += ' ORDER BY id DESC';
    const orders = db.all(sql, params);

    // Attach order items
    const fullOrders = orders.map(order => {
      const items = db.all('SELECT * FROM order_items WHERE order_id = ?', [order.id]);
      return { ...order, items };
    });

    res.json(fullOrders);
  } catch (error) {
    res.status(500).json({ message: 'Error fetching orders', error: error.message });
  }
};

exports.createOrder = (req, res) => {
  try {
    const { customer_name, table_number, room_id, items, payment_method = 'cash', payment_status = 'paid' } = req.body;
    if (!items || !items.length) {
      return res.status(400).json({ message: 'Order must contain at least one item' });
    }

    const today = new Date().toISOString().split('T')[0];
    const order_code = `ORD-${Math.floor(1000 + Math.random() * 9000)}`;
    const host_id = req.user ? req.user.id : 4;
    const host_name = req.user ? req.user.full_name : 'Dining Host';

    let total_amount = 0;
    items.forEach(item => {
      total_amount += (item.quantity * item.unit_price);
    });

    const ordResult = db.run(
      `INSERT INTO orders (order_code, customer_name, table_number, room_id, host_id, host_name, total_amount, status, payment_method, payment_status, reported_date)
       VALUES (?, ?, ?, ?, ?, ?, ?, 'served', ?, ?, ?)`,
      [
        order_code,
        customer_name || `Table ${table_number || 'Walk-in'}`,
        table_number || 'Counter',
        room_id || 'None',
        host_id,
        host_name,
        total_amount,
        payment_method,
        payment_status,
        today
      ]
    );

    const orderId = ordResult.lastInsertRowid;

    // Insert items & deduct stock
    for (const item of items) {
      db.run(
        `INSERT INTO order_items (order_id, item_name, item_type, quantity, unit_price, total_price)
         VALUES (?, ?, ?, ?, ?, ?)`,
        [orderId, item.item_name, item.item_type || 'food', item.quantity, item.unit_price, item.quantity * item.unit_price]
      );

      // Decrement beverage inventory if beverage
      if (item.item_type === 'beverage') {
        db.run('UPDATE beverages SET quantity = MAX(0, quantity - ?) WHERE name = ?', [item.quantity, item.item_name]);
      } else {
        db.run('UPDATE foods SET quantity = MAX(0, quantity - ?) WHERE name = ?', [item.quantity, item.item_name]);
      }
    }

    // Log income in financial transactions
    if (payment_status === 'paid' && total_amount > 0) {
      db.run(
        `INSERT INTO financial_transactions (type, category, amount, reference_id, provider_name, description, transaction_date)
         VALUES ('income', 'restaurant_bar', ?, ?, ?, ?, ?)`,
        [total_amount, order_code, host_name, `POS Dining order settled at ${table_number || 'Bar'}`, today]
      );
    }

    const createdOrder = db.get('SELECT * FROM orders WHERE id = ?', [orderId]);
    const createdItems = db.all('SELECT * FROM order_items WHERE order_id = ?', [orderId]);

    res.status(201).json({
      message: 'Order placed successfully',
      order: { ...createdOrder, items: createdItems }
    });
  } catch (error) {
    res.status(500).json({ message: 'Error placing order', error: error.message });
  }
};

exports.updateOrderStatus = (req, res) => {
  try {
    const { id } = req.params;
    const { status, payment_status } = req.body;

    db.run(
      `UPDATE orders
       SET status = COALESCE(?, status),
           payment_status = COALESCE(?, payment_status)
       WHERE id = ?`,
      [status, payment_status, id]
    );

    const updated = db.get('SELECT * FROM orders WHERE id = ?', [id]);
    res.json({ message: 'Order status updated', order: updated });
  } catch (error) {
    res.status(500).json({ message: 'Error updating order', error: error.message });
  }
};
