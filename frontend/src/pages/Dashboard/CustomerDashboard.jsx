import React, { useState, useEffect } from 'react';
import { api } from '../../services/api';
import { Bed, CalendarCheck, Utensils, Star, CheckCircle, Plus } from 'lucide-react';

export default function CustomerDashboard({ activeTab = 'bookings' }) {
  const [reservations, setReservations] = useState([]);
  const [foods, setFoods] = useState([]);
  const [beverages, setBeverages] = useState([]);
  const [roomServiceCart, setRoomServiceCart] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadGuestData();
  }, []);

  async function loadGuestData() {
    try {
      const [resList, fList, bList] = await Promise.all([
        api.getRoomReservations(),
        api.getFoods(),
        api.getBeverages()
      ]);
      setReservations(resList.slice(0, 3));
      setFoods(fList);
      setBeverages(bList);
    } catch (err) {
      console.error('Error loading guest data:', err);
    } finally {
      setLoading(false);
    }
  }

  const handleOrderRoomService = async () => {
    if (!roomServiceCart.length) return;
    try {
      await api.createOrder({
        customer_name: 'Resident Guest',
        table_number: 'Room Service',
        items: roomServiceCart,
        payment_method: 'room_bill',
        payment_status: 'paid'
      });
      setRoomServiceCart([]);
      alert('Room service order received! Our kitchen is preparing your meal.');
    } catch (err) {
      alert(err.message || 'Error ordering room service');
    }
  };

  const addFoodToCart = (item) => {
    const existing = roomServiceCart.find(i => i.item_name === item.name);
    if (existing) {
      setRoomServiceCart(roomServiceCart.map(i => i.item_name === item.name ? { ...i, quantity: i.quantity + 1 } : i));
    } else {
      setRoomServiceCart([...roomServiceCart, { item_name: item.name, item_type: 'food', unit_price: item.price, quantity: 1 }]);
    }
  };

  return (
    <div>
      <div style={{ marginBottom: '24px' }}>
        <h3 style={{ fontSize: '1.35rem', fontWeight: '800' }}>Welcome to Your Guest Sanctuary</h3>
        <p style={{ color: 'var(--text-muted)', fontSize: '0.9rem' }}>
          Manage your luxury stay, review billing, and order complimentary or in-room dining services.
        </p>
      </div>

      {/* Tab: Bookings */}
      {(activeTab === 'bookings' || !activeTab) && (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '24px' }}>
          <div className="glass-card">
            <h4 style={{ fontSize: '1.15rem', marginBottom: '16px' }}>My Active Stays & Bookings</h4>
            <div className="table-container">
              <table className="modern-table">
                <thead>
                  <tr>
                    <th>Booking Code</th>
                    <th>Room Type</th>
                    <th>Stay Dates</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Payment</th>
                  </tr>
                </thead>
                <tbody>
                  {reservations.map(r => (
                    <tr key={r.id}>
                      <td style={{ fontWeight: '800', color: 'var(--gold-primary)' }}>{r.booking_code}</td>
                      <td>{r.room_type} (Room {r.room_number || r.room_id})</td>
                      <td>{r.checkin_date} → {r.checkout_date}</td>
                      <td style={{ fontWeight: '800', color: 'var(--gold-light)' }}>{r.room_price} ETB</td>
                      <td><span className={`badge badge-${r.status}`}>{r.status}</span></td>
                      <td><span className={`badge badge-${r.payment_status}`}>{r.payment_status}</span></td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {/* Tab: In-Room Dining */}
      {activeTab === 'room_service' && (
        <div className="responsive-split-grid">
          <div>
            <h4 style={{ fontSize: '1.15rem', marginBottom: '16px' }}>In-Room Gourmet Dining Menu</h4>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(200px, 1fr))', gap: '14px' }}>
              {foods.map(f => (
                <div key={f.id} className="glass-card" style={{ padding: '14px', display: 'flex', flexDirection: 'column', justifyContent: 'space-between' }}>
                  <div>
                    <div style={{ fontWeight: '700', fontSize: '0.9rem' }}>{f.name}</div>
                    <div style={{ fontSize: '0.75rem', color: 'var(--text-muted)', marginBottom: '8px' }}>{f.description?.slice(0, 45)}...</div>
                  </div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <span style={{ fontWeight: '800', color: 'var(--gold-primary)' }}>{f.price} ETB</span>
                    <button onClick={() => addFoodToCart(f)} className="btn btn-gold btn-sm" style={{ padding: '3px 8px' }}>
                      <Plus size={14} /> Add
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </div>

          <div className="glass-card" style={{ height: 'fit-content', padding: '20px' }}>
            <h4 style={{ fontSize: '1.1rem', marginBottom: '14px' }}>Room Service Tray</h4>
            {roomServiceCart.length === 0 ? (
              <div style={{ textAlign: 'center', padding: '30px 0', color: 'var(--text-dim)', fontSize: '0.85rem' }}>
                Select delicacies to order to your suite
              </div>
            ) : (
              <div>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '8px', marginBottom: '16px' }}>
                  {roomServiceCart.map(item => (
                    <div key={item.item_name} style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.85rem' }}>
                      <span>{item.quantity}x {item.item_name}</span>
                      <span style={{ fontWeight: '700' }}>{item.quantity * item.unit_price} ETB</span>
                    </div>
                  ))}
                </div>
                <div style={{ borderTop: '1px solid var(--border-light)', paddingTop: '10px', display: 'flex', justifyContent: 'space-between', fontWeight: '800', fontSize: '1.1rem', marginBottom: '16px' }}>
                  <span>Total:</span>
                  <span style={{ color: 'var(--gold-primary)' }}>
                    {roomServiceCart.reduce((s, i) => s + (i.quantity * i.unit_price), 0)} ETB
                  </span>
                </div>
                <button onClick={handleOrderRoomService} className="btn btn-gold" style={{ width: '100%' }}>
                  Send Order to Suite
                </button>
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
}
