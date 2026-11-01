import React, { useState, useEffect } from 'react';
import { api } from '../../services/api';
import Modal from '../../components/Modal';
import { Package, Send, Plus, AlertTriangle, ArrowRight } from 'lucide-react';

export default function StoreDashboard({ activeTab = 'inventory' }) {
  const [inventory, setInventory] = useState([]);
  const [transfers, setTransfers] = useState([]);
  const [loading, setLoading] = useState(true);

  // Dispatch Modal
  const [isDispatchOpen, setIsDispatchOpen] = useState(false);
  const [dispatchForm, setDispatchForm] = useState({ to_dept: 'Bar', item_name: '', quantity: 1, notes: '' });

  // Add Item Modal
  const [isAddOpen, setIsAddOpen] = useState(false);
  const [addForm, setAddForm] = useState({ item_name: '', category: 'beverage', quantity: 100, unit: 'pcs', unit_price: 50, reorder_level: 20 });

  useEffect(() => {
    loadStoreData();
  }, []);

  async function loadStoreData() {
    try {
      const [invList, trfList] = await Promise.all([
        api.getInventory(),
        api.getTransfers()
      ]);
      setInventory(invList);
      setTransfers(trfList);
    } catch (err) {
      console.error('Error loading store data:', err);
    } finally {
      setLoading(false);
    }
  }

  const handleDispatch = async (e) => {
    e.preventDefault();
    try {
      await api.transferStock(dispatchForm);
      setIsDispatchOpen(false);
      alert(`Dispatched ${dispatchForm.quantity} to ${dispatchForm.to_dept}!`);
      loadStoreData();
    } catch (err) {
      alert(err.message || 'Dispatch error');
    }
  };

  const handleAddItem = async (e) => {
    e.preventDefault();
    try {
      await api.createInventoryItem(addForm);
      setIsAddOpen(false);
      loadStoreData();
    } catch (err) {
      alert(err.message || 'Error adding item');
    }
  };

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
        <div>
          <h3 style={{ fontSize: '1.25rem', fontWeight: '800' }}>Central Warehouse & Stockroom</h3>
          <p style={{ color: 'var(--text-muted)', fontSize: '0.85rem' }}>
            Manage hotel supplies, linens, beverages, and department requisitions.
          </p>
        </div>
        <div style={{ display: 'flex', gap: '10px' }}>
          <button onClick={() => setIsAddOpen(true)} className="btn btn-outline btn-sm">
            <Plus size={15} /> Add Item
          </button>
          <button onClick={() => setIsDispatchOpen(true)} className="btn btn-gold btn-sm">
            <Send size={15} /> Dispatch to Department
          </button>
        </div>
      </div>

      {/* Inventory Catalog */}
      <div className="glass-card" style={{ marginBottom: '24px' }}>
        <h4 style={{ fontSize: '1.1rem', marginBottom: '16px' }}>Current Warehouse Inventory</h4>
        <div className="table-container">
          <table className="modern-table">
            <thead>
              <tr>
                <th>Item Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Unit Value</th>
                <th>Status</th>
                <th>Quick Dispatch</th>
              </tr>
            </thead>
            <tbody>
              {inventory.map(item => (
                <tr key={item.id}>
                  <td style={{ fontWeight: '700' }}>{item.item_name}</td>
                  <td style={{ textTransform: 'capitalize' }}>{item.category.replace('_', ' ')}</td>
                  <td style={{ fontWeight: '800', fontSize: '1rem', color: item.status === 'low_stock' ? '#fb7185' : 'var(--text-main)' }}>
                    {item.quantity}
                  </td>
                  <td>{item.unit}</td>
                  <td>{item.unit_price} ETB</td>
                  <td>
                    <span className={`badge badge-${item.status}`}>
                      {item.status.replace('_', ' ')}
                    </span>
                  </td>
                  <td>
                    <button
                      onClick={() => {
                        setDispatchForm({ ...dispatchForm, item_name: item.item_name });
                        setIsDispatchOpen(true);
                      }}
                      className="btn btn-outline btn-sm"
                      style={{ padding: '3px 8px', fontSize: '0.75rem' }}
                    >
                      Dispatch →
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Transfers Log */}
      <div className="glass-card">
        <h4 style={{ fontSize: '1.1rem', marginBottom: '16px' }}>Department Requisition & Dispatch Log</h4>
        <div className="table-container">
          <table className="modern-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>To Department</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Custodian</th>
                <th>Notes</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              {transfers.map(tr => (
                <tr key={tr.id}>
                  <td>#{tr.id}</td>
                  <td style={{ fontWeight: '700', color: 'var(--gold-light)' }}>{tr.to_dept}</td>
                  <td>{tr.item_name}</td>
                  <td style={{ fontWeight: '700' }}>{tr.quantity} {tr.unit}</td>
                  <td>{tr.transferred_by}</td>
                  <td style={{ fontSize: '0.8rem', color: 'var(--text-muted)' }}>{tr.notes}</td>
                  <td>{tr.transfer_date?.slice(0, 10)}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Dispatch Modal */}
      <Modal isOpen={isDispatchOpen} onClose={() => setIsDispatchOpen(false)} title="Dispatch Stock to Department">
        <form onSubmit={handleDispatch}>
          <div className="form-group">
            <label className="form-label">Destination Department</label>
            <select
              className="form-select"
              value={dispatchForm.to_dept}
              onChange={(e) => setDispatchForm({ ...dispatchForm, to_dept: e.target.value })}
            >
              <option value="Bar">Bar Lounge (Barman)</option>
              <option value="Kitchen">Kitchen / Dining</option>
              <option value="Housekeeping">Housekeeping & Rooms</option>
              <option value="Reception">Front Desk Reception</option>
            </select>
          </div>

          <div className="form-group">
            <label className="form-label">Item to Dispatch</label>
            <select
              className="form-select"
              value={dispatchForm.item_name}
              onChange={(e) => setDispatchForm({ ...dispatchForm, item_name: e.target.value })}
              required
            >
              <option value="">Select Inventory Item...</option>
              {inventory.map(i => (
                <option key={i.id} value={i.item_name}>
                  {i.item_name} (Available: {i.quantity} {i.unit})
                </option>
              ))}
            </select>
          </div>

          <div className="form-group">
            <label className="form-label">Quantity to Dispatch</label>
            <input
              type="number"
              min="1"
              required
              className="form-control"
              value={dispatchForm.quantity}
              onChange={(e) => setDispatchForm({ ...dispatchForm, quantity: parseFloat(e.target.value) || 1 })}
            />
          </div>

          <div className="form-group">
            <label className="form-label">Requisition Notes</label>
            <input
              type="text"
              placeholder="e.g. Weekend restock request"
              className="form-control"
              value={dispatchForm.notes}
              onChange={(e) => setDispatchForm({ ...dispatchForm, notes: e.target.value })}
            />
          </div>

          <button type="submit" className="btn btn-gold" style={{ width: '100%', marginTop: '12px' }}>
            Confirm & Dispatch Stock
          </button>
        </form>
      </Modal>

      {/* Add Item Modal */}
      <Modal isOpen={isAddOpen} onClose={() => setIsAddOpen(false)} title="Add Warehouse Inventory Item">
        <form onSubmit={handleAddItem}>
          <div className="form-group">
            <label className="form-label">Item Name</label>
            <input
              type="text"
              required
              className="form-control"
              value={addForm.item_name}
              onChange={(e) => setAddForm({ ...addForm, item_name: e.target.value })}
            />
          </div>

          <div className="form-row-grid">
            <div className="form-group">
              <label className="form-label">Category</label>
              <select
                className="form-select"
                value={addForm.category}
                onChange={(e) => setAddForm({ ...addForm, category: e.target.value })}
              >
                <option value="beverage">Beverage</option>
                <option value="food_ingredient">Food Ingredient</option>
                <option value="linens">Linens & Bedding</option>
                <option value="amenities">Guest Amenities</option>
                <option value="office">Office & Front Desk</option>
              </select>
            </div>
            <div className="form-group">
              <label className="form-label">Unit</label>
              <input
                type="text"
                placeholder="pcs, kg, liters, sets"
                className="form-control"
                value={addForm.unit}
                onChange={(e) => setAddForm({ ...addForm, unit: e.target.value })}
              />
            </div>
          </div>

          <div className="form-row-grid">
            <div className="form-group">
              <label className="form-label">Initial Quantity</label>
              <input
                type="number"
                required
                className="form-control"
                value={addForm.quantity}
                onChange={(e) => setAddForm({ ...addForm, quantity: parseFloat(e.target.value) || 0 })}
              />
            </div>
            <div className="form-group">
              <label className="form-label">Unit Price (ETB)</label>
              <input
                type="number"
                required
                className="form-control"
                value={addForm.unit_price}
                onChange={(e) => setAddForm({ ...addForm, unit_price: parseFloat(e.target.value) || 0 })}
              />
            </div>
          </div>

          <button type="submit" className="btn btn-gold" style={{ width: '100%', marginTop: '12px' }}>
            Register Item in Store
          </button>
        </form>
      </Modal>
    </div>
  );
}
