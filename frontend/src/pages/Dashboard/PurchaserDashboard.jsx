import React, { useState, useEffect } from 'react';
import { api } from '../../services/api';
import Modal from '../../components/Modal';
import { ShoppingCart, Plus, CheckCircle, Clock, Truck } from 'lucide-react';

export default function PurchaserDashboard({ activeTab = 'purchases' }) {
  const [purchases, setPurchases] = useState([]);
  const [loading, setLoading] = useState(true);

  // New PO Modal
  const [isPoModalOpen, setIsPoModalOpen] = useState(false);
  const [poForm, setPoForm] = useState({
    item_name: '',
    category: 'beverage',
    supplier: 'BGI Ethiopia Brewery',
    quantity: 50,
    unit_price: 45,
    status: 'received'
  });

  useEffect(() => {
    loadPurchases();
  }, []);

  async function loadPurchases() {
    try {
      const data = await api.getPurchases();
      setPurchases(data);
    } catch (err) {
      console.error('Error loading purchases:', err);
    } finally {
      setLoading(false);
    }
  }

  const handleCreatePo = async (e) => {
    e.preventDefault();
    try {
      await api.createPurchase(poForm);
      setIsPoModalOpen(false);
      alert('Purchase order created! Stock updated and financial expense recorded.');
      loadPurchases();
    } catch (err) {
      alert(err.message || 'Error creating purchase order');
    }
  };

  const totalProcurementSpend = purchases.reduce((acc, p) => acc + (p.total_cost || 0), 0);

  return (
    <div>
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '20px', marginBottom: '28px' }}>
        <div className="stat-card">
          <div>
            <div className="stat-label">Total Purchase Orders</div>
            <div className="stat-val" style={{ color: 'var(--gold-primary)' }}>{purchases.length}</div>
          </div>
          <div className="stat-icon" style={{ background: 'rgba(212, 175, 55, 0.15)' }}>
            <ShoppingCart size={24} color="var(--gold-primary)" />
          </div>
        </div>

        <div className="stat-card">
          <div>
            <div className="stat-label">Cumulative Procurement Spend</div>
            <div className="stat-val" style={{ color: '#fb7185' }}>{totalProcurementSpend} ETB</div>
          </div>
          <div className="stat-icon" style={{ background: 'rgba(244, 63, 94, 0.15)' }}>
            <Truck size={24} color="#fb7185" />
          </div>
        </div>
      </div>

      <div className="glass-card">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
          <div>
            <h3 style={{ fontSize: '1.25rem', fontWeight: '800' }}>Procurement & Purchase Orders</h3>
            <p style={{ color: 'var(--text-muted)', fontSize: '0.85rem' }}>
              Track supply chain deliveries and vendor invoices.
            </p>
          </div>
          <button onClick={() => setIsPoModalOpen(true)} className="btn btn-gold btn-sm">
            <Plus size={15} /> Log New Purchase
          </button>
        </div>

        <div className="table-container">
          <table className="modern-table">
            <thead>
              <tr>
                <th>PO Number</th>
                <th>Item</th>
                <th>Category</th>
                <th>Supplier / Vendor</th>
                <th>Qty</th>
                <th>Unit Cost</th>
                <th>Total Cost</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              {purchases.map(p => (
                <tr key={p.id}>
                  <td style={{ fontWeight: '800', color: 'var(--gold-primary)' }}>{p.po_number}</td>
                  <td style={{ fontWeight: '600' }}>{p.item_name}</td>
                  <td style={{ textTransform: 'capitalize' }}>{p.category}</td>
                  <td>{p.supplier}</td>
                  <td>{p.quantity}</td>
                  <td>{p.unit_price} ETB</td>
                  <td style={{ fontWeight: '800', color: 'var(--gold-light)' }}>{p.total_cost} ETB</td>
                  <td>
                    <span className={`badge badge-${p.status === 'received' ? 'success' : 'warning'}`}>
                      {p.status}
                    </span>
                  </td>
                  <td>{p.purchase_date?.slice(0, 10)}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* New Purchase Modal */}
      <Modal isOpen={isPoModalOpen} onClose={() => setIsPoModalOpen(false)} title="Log New Supply Purchase Order">
        <form onSubmit={handleCreatePo}>
          <div className="form-group">
            <label className="form-label">Item Name *</label>
            <input
              type="text"
              required
              placeholder="e.g. St. George Beer Crates"
              className="form-control"
              value={poForm.item_name}
              onChange={(e) => setPoForm({ ...poForm, item_name: e.target.value })}
            />
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px' }}>
            <div className="form-group">
              <label className="form-label">Category</label>
              <select
                className="form-select"
                value={poForm.category}
                onChange={(e) => setPoForm({ ...poForm, category: e.target.value })}
              >
                <option value="beverage">Beverage</option>
                <option value="food_ingredient">Food & Produce</option>
                <option value="linens">Linens & Housekeeping</option>
                <option value="amenities">Guest Amenities</option>
                <option value="equipment">Hotel Equipment</option>
              </select>
            </div>
            <div className="form-group">
              <label className="form-label">Supplier / Vendor *</label>
              <input
                type="text"
                required
                className="form-control"
                value={poForm.supplier}
                onChange={(e) => setPoForm({ ...poForm, supplier: e.target.value })}
              />
            </div>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px' }}>
            <div className="form-group">
              <label className="form-label">Quantity</label>
              <input
                type="number"
                min="1"
                required
                className="form-control"
                value={poForm.quantity}
                onChange={(e) => setPoForm({ ...poForm, quantity: parseFloat(e.target.value) || 1 })}
              />
            </div>
            <div className="form-group">
              <label className="form-label">Unit Price (ETB)</label>
              <input
                type="number"
                min="1"
                required
                className="form-control"
                value={poForm.unit_price}
                onChange={(e) => setPoForm({ ...poForm, unit_price: parseFloat(e.target.value) || 1 })}
              />
            </div>
          </div>

          <div style={{ padding: '12px', background: 'rgba(212, 175, 55, 0.1)', borderRadius: '6px', marginBottom: '16px', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <span style={{ fontWeight: '600', fontSize: '0.9rem' }}>Total Cost:</span>
            <span style={{ fontSize: '1.25rem', fontWeight: '800', color: 'var(--gold-primary)' }}>
              {poForm.quantity * poForm.unit_price} ETB
            </span>
          </div>

          <button type="submit" className="btn btn-gold" style={{ width: '100%' }}>
            Log Purchase & Update Stock
          </button>
        </form>
      </Modal>
    </div>
  );
}
