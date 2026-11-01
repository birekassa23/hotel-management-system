import React, { useState, useEffect } from 'react';
import { api } from '../../services/api';
import { Wine, Package, CheckCircle, AlertTriangle, RefreshCw } from 'lucide-react';

export default function BarmanDashboard({ activeTab = 'inventory' }) {
  const [beverages, setBeverages] = useState([]);
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadBarData();
  }, []);

  async function loadBarData() {
    try {
      const [bList, ordList] = await Promise.all([
        api.getBeverages(),
        api.getOrders()
      ]);
      setBeverages(bList);
      setOrders(ordList);
    } catch (err) {
      console.error('Error loading bar data:', err);
    } finally {
      setLoading(false);
    }
  }

  const handleUpdateStatus = async (orderId, newStatus) => {
    try {
      await api.updateOrderStatus(orderId, { status: newStatus });
      loadBarData();
    } catch (err) {
      alert(err.message || 'Error updating order status');
    }
  };

  return (
    <div>
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '16px', marginBottom: '24px' }}>
        <div className="stat-card">
          <div>
            <div className="stat-label">Bar Beverage Types</div>
            <div className="stat-val" style={{ color: 'var(--gold-primary)' }}>{beverages.length}</div>
          </div>
          <div className="stat-icon" style={{ background: 'rgba(212, 175, 55, 0.15)' }}>
            <Wine size={24} color="var(--gold-primary)" />
          </div>
        </div>

        <div className="stat-card">
          <div>
            <div className="stat-label">Total Bottles in Stock</div>
            <div className="stat-val" style={{ color: 'var(--emerald)' }}>
              {beverages.reduce((acc, b) => acc + (b.quantity || 0), 0)}
            </div>
          </div>
          <div className="stat-icon" style={{ background: 'rgba(16, 185, 129, 0.15)' }}>
            <Package size={24} color="var(--emerald)" />
          </div>
        </div>
      </div>

      {/* Beverage Inventory Catalog */}
      <div className="glass-card">
        <h3 style={{ fontSize: '1.2rem', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
          <Wine size={18} color="var(--gold-primary)" />
          Bar Beverage Stock & Dispense Levels
        </h3>

        <div className="table-container">
          <table className="modern-table">
            <thead>
              <tr>
                <th>Beverage</th>
                <th>Category</th>
                <th>Unit</th>
                <th>Current Stock</th>
                <th>Serving Price</th>
                <th>Stock Level</th>
                <th>Quick Restock</th>
              </tr>
            </thead>
            <tbody>
              {beverages.map((b) => (
                <tr key={b.id}>
                  <td style={{ fontWeight: '700' }}>{b.name}</td>
                  <td style={{ textTransform: 'capitalize' }}>{b.category}</td>
                  <td>{b.unit}</td>
                  <td style={{ fontWeight: '800', fontSize: '1.05rem', color: b.quantity < 20 ? '#fb7185' : 'var(--text-main)' }}>
                    {b.quantity}
                  </td>
                  <td style={{ fontWeight: '700', color: 'var(--gold-light)' }}>{b.price} ETB</td>
                  <td>
                    <span className={`badge badge-${b.quantity < 20 ? 'danger' : 'success'}`}>
                      {b.quantity < 20 ? 'Low Stock' : 'Sufficient'}
                    </span>
                  </td>
                  <td>
                    <button
                      onClick={async () => {
                        await api.updateBeverage(b.id, { quantity: b.quantity + 24 });
                        loadBarData();
                      }}
                      className="btn btn-outline btn-sm"
                      style={{ padding: '3px 8px', fontSize: '0.75rem' }}
                    >
                      + 24 Crate
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
