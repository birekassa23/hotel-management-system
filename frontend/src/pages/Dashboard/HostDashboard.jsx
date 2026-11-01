import React, { useState, useEffect } from 'react';
import { api } from '../../services/api';
import { Utensils, Wine, Plus, Minus, Trash2, Send, CheckCircle, Clock } from 'lucide-react';

const TABLES = ['Table 1', 'Table 2', 'Table 3', 'Table 4', 'VIP Terrace 1', 'VIP Terrace 2', 'Bar Counter', 'Room Service'];

export default function HostDashboard({ activeTab = 'pos' }) {
  const [foods, setFoods] = useState([]);
  const [beverages, setBeverages] = useState([]);
  const [orders, setOrders] = useState([]);
  const [selectedTable, setSelectedTable] = useState('Table 1');
  const [customerName, setCustomerName] = useState('');
  const [cart, setCart] = useState([]);
  const [category, setCategory] = useState('all');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadPosData();
  }, []);

  async function loadPosData() {
    try {
      const [fList, bList, ordList] = await Promise.all([
        api.getFoods(),
        api.getBeverages(),
        api.getOrders()
      ]);
      setFoods(fList);
      setBeverages(bList);
      setOrders(ordList);
    } catch (err) {
      console.error('Error loading POS:', err);
    } finally {
      setLoading(false);
    }
  }

  const addToCart = (item, type) => {
    const existing = cart.find(i => i.item_name === item.name);
    if (existing) {
      setCart(cart.map(i => i.item_name === item.name ? { ...i, quantity: i.quantity + 1 } : i));
    } else {
      setCart([...cart, {
        item_name: item.name,
        item_type: type,
        unit_price: item.price,
        quantity: 1
      }]);
    }
  };

  const updateQuantity = (itemName, delta) => {
    setCart(cart.map(i => {
      if (i.item_name === itemName) {
        const newQty = i.quantity + delta;
        return newQty > 0 ? { ...i, quantity: newQty } : null;
      }
      return i;
    }).filter(Boolean));
  };

  const cartTotal = cart.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);

  const handlePlaceOrder = async () => {
    if (!cart.length) return;
    try {
      await api.createOrder({
        customer_name: customerName || `${selectedTable} Guest`,
        table_number: selectedTable,
        items: cart,
        payment_method: 'cash',
        payment_status: 'paid'
      });
      setCart([]);
      setCustomerName('');
      alert('Order sent to kitchen & bar! Stock updated.');
      loadPosData();
    } catch (err) {
      alert(err.message || 'Error placing order');
    }
  };

  const allItems = [
    ...foods.map(f => ({ ...f, type: 'food' })),
    ...beverages.map(b => ({ ...b, type: 'beverage' }))
  ];

  const filteredItems = category === 'all'
    ? allItems
    : allItems.filter(i => i.category === category || i.type === category);

  return (
    <div>
      {/* POS Terminal */}
      {(activeTab === 'pos' || !activeTab) && (
        <div className="responsive-split-grid">
          {/* Menu Catalog */}
          <div>
            {/* Table Selection */}
            <div className="glass-card" style={{ padding: '16px', marginBottom: '18px' }}>
              <div style={{ fontSize: '0.85rem', fontWeight: '700', color: 'var(--text-muted)', marginBottom: '10px' }}>
                Select Active Station / Table:
              </div>
              <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
                {TABLES.map(t => (
                  <button
                    key={t}
                    onClick={() => setSelectedTable(t)}
                    style={{
                      padding: '8px 14px',
                      borderRadius: '8px',
                      fontSize: '0.825rem',
                      fontWeight: '700',
                      background: selectedTable === t ? 'var(--gold-gradient)' : 'rgba(255, 255, 255, 0.05)',
                      color: selectedTable === t ? '#090d16' : 'var(--text-muted)',
                      border: selectedTable === t ? 'none' : '1px solid var(--border-light)'
                    }}
                  >
                    {t}
                  </button>
                ))}
              </div>
            </div>

            {/* Category Filter */}
            <div style={{ display: 'flex', gap: '8px', marginBottom: '18px', overflowX: 'auto', paddingBottom: '4px' }}>
              {['all', 'traditional', 'main', 'fast_food', 'beer', 'wine', 'spirit', 'soft_drink'].map(cat => (
                <button
                  key={cat}
                  onClick={() => setCategory(cat)}
                  style={{
                    padding: '6px 14px',
                    borderRadius: 'var(--radius-full)',
                    fontSize: '0.775rem',
                    fontWeight: '600',
                    background: category === cat ? 'rgba(212, 175, 55, 0.2)' : 'rgba(255, 255, 255, 0.03)',
                    color: category === cat ? 'var(--gold-light)' : 'var(--text-dim)',
                    border: category === cat ? '1px solid var(--border-gold)' : '1px solid var(--border-light)',
                    textTransform: 'capitalize',
                    whiteSpace: 'nowrap'
                  }}
                >
                  {cat.replace('_', ' ')}
                </button>
              ))}
            </div>

            {/* Items Grid */}
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(180px, 1fr))', gap: '14px' }}>
              {filteredItems.map(item => (
                <div
                  key={`${item.type}-${item.id}`}
                  className="glass-card"
                  style={{ padding: '14px', display: 'flex', flexDirection: 'column', justifyContent: 'space-between', cursor: 'pointer' }}
                  onClick={() => addToCart(item, item.type)}
                >
                  <div>
                    <div style={{ fontWeight: '700', fontSize: '0.9rem', marginBottom: '4px' }}>{item.name}</div>
                    <div style={{ fontSize: '0.75rem', color: 'var(--text-dim)', textTransform: 'capitalize', marginBottom: '8px' }}>
                      {item.category} • In stock: {item.quantity}
                    </div>
                  </div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <span style={{ fontWeight: '800', color: 'var(--gold-primary)', fontSize: '0.95rem' }}>
                      {item.price} ETB
                    </span>
                    <button className="btn btn-gold btn-sm" style={{ padding: '4px 8px' }}>
                      <Plus size={14} /> Add
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Active Order Cart */}
          <div className="glass-card" style={{ height: 'fit-content', padding: '20px' }}>
            <h3 style={{ fontSize: '1.15rem', fontWeight: '800', marginBottom: '14px', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
              <span>Station Order</span>
              <span className="badge badge-gold">{selectedTable}</span>
            </h3>

            <div className="form-group" style={{ marginBottom: '14px' }}>
              <input
                type="text"
                placeholder="Guest Identifier (Optional)"
                className="form-control"
                value={customerName}
                onChange={(e) => setCustomerName(e.target.value)}
                style={{ fontSize: '0.85rem', padding: '8px 12px' }}
              />
            </div>

            {/* Cart Items */}
            <div style={{ minHeight: '200px', maxHeight: '340px', overflowY: 'auto', marginBottom: '16px', display: 'flex', flexDirection: 'column', gap: '10px' }}>
              {cart.length === 0 ? (
                <div style={{ textAlign: 'center', padding: '40px 0', color: 'var(--text-dim)', fontSize: '0.85rem' }}>
                  Tap items from menu to add to order
                </div>
              ) : (
                cart.map(item => (
                  <div key={item.item_name} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '8px 10px', background: 'rgba(255, 255, 255, 0.02)', borderRadius: '6px', border: '1px solid var(--border-light)' }}>
                    <div>
                      <div style={{ fontWeight: '600', fontSize: '0.85rem' }}>{item.item_name}</div>
                      <div style={{ fontSize: '0.75rem', color: 'var(--text-dim)' }}>{item.unit_price} ETB each</div>
                    </div>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                      <button onClick={() => updateQuantity(item.item_name, -1)} style={{ background: 'rgba(255, 255, 255, 0.1)', color: '#fff', borderRadius: '4px', padding: '2px 6px' }}>
                        <Minus size={12} />
                      </button>
                      <span style={{ fontWeight: '700', fontSize: '0.85rem' }}>{item.quantity}</span>
                      <button onClick={() => updateQuantity(item.item_name, 1)} style={{ background: 'rgba(255, 255, 255, 0.1)', color: '#fff', borderRadius: '4px', padding: '2px 6px' }}>
                        <Plus size={12} />
                      </button>
                    </div>
                  </div>
                ))
              )}
            </div>

            {/* Order Total & CTA */}
            <div style={{ borderTop: '1px solid var(--border-light)', paddingTop: '14px', marginBottom: '16px' }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '1.2rem', fontWeight: '800' }}>
                <span>Total Due:</span>
                <span style={{ color: 'var(--gold-primary)' }}>{cartTotal} ETB</span>
              </div>
            </div>

            <button
              onClick={handlePlaceOrder}
              disabled={!cart.length}
              className="btn btn-gold"
              style={{ width: '100%', opacity: cart.length ? 1 : 0.5 }}
            >
              <Send size={16} /> Place & Dispatch Order
            </button>
          </div>
        </div>
      )}

      {/* Tab: Orders Stream */}
      {activeTab === 'orders' && (
        <div className="glass-card">
          <h3 style={{ fontSize: '1.2rem', marginBottom: '18px' }}>Active Dining & Bar Orders</h3>
          <div className="table-container">
            <table className="modern-table">
              <thead>
                <tr>
                  <th>Order #</th>
                  <th>Station</th>
                  <th>Customer</th>
                  <th>Items Summary</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody>
                {orders.map(o => (
                  <tr key={o.id}>
                    <td style={{ fontWeight: '800', color: 'var(--gold-primary)' }}>{o.order_code}</td>
                    <td>{o.table_number}</td>
                    <td>{o.customer_name}</td>
                    <td style={{ fontSize: '0.825rem' }}>
                      {o.items?.map(i => `${i.quantity}x ${i.item_name}`).join(', ') || 'Various items'}
                    </td>
                    <td style={{ fontWeight: '700' }}>{o.total_amount} ETB</td>
                    <td><span className={`badge badge-${o.status}`}>{o.status}</span></td>
                    <td style={{ fontSize: '0.8rem', color: 'var(--text-dim)' }}>{o.reported_date}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}
    </div>
  );
}
