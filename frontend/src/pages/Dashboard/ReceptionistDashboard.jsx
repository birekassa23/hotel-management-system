import React, { useState, useEffect } from 'react';
import { api } from '../../services/api';
import Modal from '../../components/Modal';
import {
  Bed,
  CalendarCheck,
  Building,
  Users,
  Search,
  Plus,
  LogIn,
  LogOut,
  CheckCircle,
  Clock,
  DollarSign
} from 'lucide-react';

export default function ReceptionistDashboard({ activeTab = 'rack' }) {
  const [rooms, setRooms] = useState([]);
  const [reservations, setReservations] = useState([]);
  const [halls, setHalls] = useState([]);
  const [hallReservations, setHallReservations] = useState([]);
  const [search, setSearch] = useState('');
  const [loading, setLoading] = useState(true);

  // Walk-in Booking Modal
  const [isWalkinOpen, setIsWalkinOpen] = useState(false);
  const [selectedRoom, setSelectedRoom] = useState(null);
  const [walkinForm, setWalkinForm] = useState({
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    kebele_id: '',
    checkin_date: new Date().toISOString().split('T')[0],
    checkout_date: new Date(Date.now() + 86400000 * 2).toISOString().split('T')[0],
    payment_status: 'paid',
    notes: 'In-person walk-in guest'
  });

  useEffect(() => {
    loadData();
  }, []);

  async function loadData() {
    try {
      const [roomList, resList, hallList, hallResList] = await Promise.all([
        api.getRooms('?status=all'),
        api.getRoomReservations(),
        api.getHalls(),
        api.getHallReservations()
      ]);
      setRooms(roomList);
      setReservations(resList);
      setHalls(hallList);
      setHallReservations(hallResList);
    } catch (err) {
      console.error('Error loading front desk:', err);
    } finally {
      setLoading(false);
    }
  }

  const handleOpenWalkin = (room) => {
    setSelectedRoom(room);
    setIsWalkinOpen(true);
  };

  const handleWalkinSubmit = async (e) => {
    e.preventDefault();
    if (!selectedRoom) return;
    try {
      await api.createRoomReservation({
        ...walkinForm,
        room_id: selectedRoom.id,
        room_type: selectedRoom.type,
        room_price: selectedRoom.price,
        assigned_by: 'Front Desk Reception'
      });
      setIsWalkinOpen(false);
      loadData();
    } catch (err) {
      alert(err.message || 'Error processing reservation');
    }
  };

  const handleCheckIn = async (res) => {
    try {
      await api.updateRoomReservation(res.id, { status: 'checked_in' });
      loadData();
    } catch (err) {
      alert(err.message || 'Error checking in');
    }
  };

  const handleCheckOut = async (res) => {
    try {
      await api.updateRoomReservation(res.id, { status: 'checked_out', payment_status: 'paid' });
      loadData();
    } catch (err) {
      alert(err.message || 'Error checking out');
    }
  };

  const filteredReservations = reservations.filter(r =>
    r.booking_code.toLowerCase().includes(search.toLowerCase()) ||
    r.customer_name.toLowerCase().includes(search.toLowerCase()) ||
    (r.room_number && r.room_number.includes(search))
  );

  return (
    <div>
      {/* Front Desk Rack */}
      {(activeTab === 'rack' || !activeTab) && (
        <div>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <div>
              <h3 style={{ fontSize: '1.25rem', fontWeight: '800' }}>Live Interactive Room Rack</h3>
              <p style={{ color: 'var(--text-muted)', fontSize: '0.85rem' }}>
                Click any free room to immediately process an in-person guest walk-in.
              </p>
            </div>
            <div style={{ display: 'flex', gap: '10px' }}>
              <span className="badge badge-free">Free ({rooms.filter(r => r.status === 'free').length})</span>
              <span className="badge badge-occupied">Occupied ({rooms.filter(r => r.status === 'occupied').length})</span>
              <span className="badge badge-reserved">Reserved ({rooms.filter(r => r.status === 'reserved').length})</span>
            </div>
          </div>

          <div style={{
            display: 'grid',
            gridTemplateColumns: 'repeat(auto-fill, minmax(min(100%, 180px), 1fr))',
            gap: '16px',
            marginBottom: '32px'
          }}>
            {rooms.map((room) => {
              const isFree = room.status === 'free';
              return (
                <div
                  key={room.id}
                  className="glass-card"
                  style={{
                    padding: '18px',
                    borderColor: isFree ? 'rgba(16, 185, 129, 0.4)' : room.status === 'occupied' ? 'rgba(244, 63, 94, 0.4)' : 'rgba(245, 158, 11, 0.4)',
                    cursor: isFree ? 'pointer' : 'default',
                    position: 'relative'
                  }}
                  onClick={() => isFree && handleOpenWalkin(room)}
                >
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '8px' }}>
                    <div style={{ fontSize: '1.3rem', fontWeight: '800' }}>Room {room.room_number}</div>
                    <span className={`badge badge-${room.status}`}>{room.status}</span>
                  </div>

                  <div style={{ fontSize: '0.85rem', color: 'var(--gold-light)', fontWeight: '600', marginBottom: '6px' }}>
                    {room.type}
                  </div>
                  <div style={{ fontSize: '0.8rem', color: 'var(--text-muted)', marginBottom: '12px' }}>
                    Floor {room.floor} • {room.capacity} Persons • {room.price} ETB
                  </div>

                  {isFree ? (
                    <button className="btn btn-emerald btn-sm" style={{ width: '100%' }}>
                      <Plus size={14} /> Walk-in Check-in
                    </button>
                  ) : (
                    <div style={{ fontSize: '0.75rem', color: 'var(--text-dim)', textAlign: 'center' }}>
                      Status: {room.status.toUpperCase()}
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>
      )}

      {/* Room Reservations Tab */}
      {(activeTab === 'reservations' || activeTab === 'rack') && (
        <div className="glass-card" style={{ marginTop: '24px' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px', flexWrap: 'wrap', gap: '12px' }}>
            <h3 style={{ fontSize: '1.2rem', fontWeight: '800' }}>Active Room Stays & Reservations</h3>
            <div style={{ position: 'relative', width: '280px' }}>
              <input
                id="receptionist_search"
                name="search"
                type="text"
                placeholder="Search booking code, name..."
                className="form-control"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                style={{ paddingLeft: '34px' }}
              />
              <Search size={16} color="var(--text-muted)" style={{ position: 'absolute', left: '12px', top: '14px' }} />
            </div>
          </div>

          <div className="table-container">
            <table className="modern-table">
              <thead>
                <tr>
                  <th>Booking Code</th>
                  <th>Guest Name</th>
                  <th>Room</th>
                  <th>Stay Dates</th>
                  <th>Price</th>
                  <th>Status</th>
                  <th>Payment</th>
                  <th>Front Desk Actions</th>
                </tr>
              </thead>
              <tbody>
                {filteredReservations.map((res) => (
                  <tr key={res.id}>
                    <td style={{ fontWeight: '800', color: 'var(--gold-primary)' }}>{res.booking_code}</td>
                    <td>
                      <div style={{ fontWeight: '600' }}>{res.customer_name}</div>
                      <div style={{ fontSize: '0.75rem', color: 'var(--text-dim)' }}>{res.customer_phone}</div>
                    </td>
                    <td>Room {res.room_number || res.room_id} ({res.room_type})</td>
                    <td>{res.checkin_date} → {res.checkout_date}</td>
                    <td style={{ fontWeight: '700' }}>{res.room_price} ETB</td>
                    <td>
                      <span className={`badge badge-${res.status}`}>{res.status}</span>
                    </td>
                    <td>
                      <span className={`badge badge-${res.payment_status}`}>{res.payment_status}</span>
                    </td>
                    <td>
                      <div style={{ display: 'flex', gap: '8px' }}>
                        {res.status === 'confirmed' && (
                          <button
                            onClick={() => handleCheckIn(res)}
                            className="btn btn-emerald btn-sm"
                            style={{ padding: '4px 10px', fontSize: '0.75rem' }}
                          >
                            <LogIn size={13} /> Check In
                          </button>
                        )}
                        {res.status === 'checked_in' && (
                          <button
                            onClick={() => handleCheckOut(res)}
                            className="btn btn-danger btn-sm"
                            style={{ padding: '4px 10px', fontSize: '0.75rem' }}
                          >
                            <LogOut size={13} /> Check Out
                          </button>
                        )}
                        {res.status === 'checked_out' && (
                          <span style={{ fontSize: '0.75rem', color: 'var(--text-dim)' }}>Completed</span>
                        )}
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* Meeting Halls Tab */}
      {activeTab === 'halls' && (
        <div className="glass-card">
          <h3 style={{ fontSize: '1.25rem', marginBottom: '20px' }}>Meeting & Conference Hall Bookings</h3>
          <div className="table-container">
            <table className="modern-table">
              <thead>
                <tr>
                  <th>Booking Code</th>
                  <th>Guest / Org</th>
                  <th>Hall Name</th>
                  <th>Dates</th>
                  <th>Event Type</th>
                  <th>Price</th>
                  <th>Status</th>
                  <th>Payment</th>
                </tr>
              </thead>
              <tbody>
                {hallReservations.map((hr) => (
                  <tr key={hr.id}>
                    <td style={{ fontWeight: '800', color: 'var(--gold-primary)' }}>{hr.booking_code}</td>
                    <td style={{ fontWeight: '600' }}>{hr.customer_name}</td>
                    <td>{hr.hall_name}</td>
                    <td>{hr.checkin_date} → {hr.checkout_date}</td>
                    <td>{hr.event_type}</td>
                    <td style={{ fontWeight: '700' }}>{hr.hall_price} ETB</td>
                    <td><span className={`badge badge-${hr.status}`}>{hr.status}</span></td>
                    <td><span className={`badge badge-${hr.payment_status}`}>{hr.payment_status}</span></td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* Guest Directory Tab */}
      {activeTab === 'guests' && (
        <div className="glass-card">
          <h3 style={{ fontSize: '1.25rem', marginBottom: '20px' }}>Guest Directory & Kebele ID Ledger</h3>
          <div className="table-container">
            <table className="modern-table">
              <thead>
                <tr>
                  <th>Full Name</th>
                  <th>Phone Number</th>
                  <th>Email</th>
                  <th>Kebele / Passport ID</th>
                  <th>Assigned By</th>
                </tr>
              </thead>
              <tbody>
                {reservations.map((r) => (
                  <tr key={r.id}>
                    <td style={{ fontWeight: '700' }}>{r.customer_name}</td>
                    <td>{r.customer_phone || 'N/A'}</td>
                    <td>{r.customer_email || 'N/A'}</td>
                    <td style={{ color: 'var(--gold-light)' }}>{r.kebele_id || 'KB-VERIFIED'}</td>
                    <td>{r.assigned_by || 'Front Desk'}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* Walk-in Reservation Modal */}
      <Modal
        isOpen={isWalkinOpen}
        onClose={() => setIsWalkinOpen(false)}
        title={`Walk-in Registration — Room ${selectedRoom?.room_number} (${selectedRoom?.type})`}
      >
        <form onSubmit={handleWalkinSubmit}>
          <div className="form-row-grid">
            <div className="form-group">
              <label htmlFor="walkin_customer_name" className="form-label">Guest Full Name *</label>
              <input
                id="walkin_customer_name"
                name="customer_name"
                autoComplete="name"
                type="text"
                required
                className="form-control"
                value={walkinForm.customer_name}
                onChange={(e) => setWalkinForm({ ...walkinForm, customer_name: e.target.value })}
              />
            </div>
            <div className="form-group">
              <label htmlFor="walkin_customer_phone" className="form-label">Phone Number *</label>
              <input
                id="walkin_customer_phone"
                name="customer_phone"
                autoComplete="tel"
                type="tel"
                required
                className="form-control"
                value={walkinForm.customer_phone}
                onChange={(e) => setWalkinForm({ ...walkinForm, customer_phone: e.target.value })}
              />
            </div>
          </div>

          <div className="form-row-grid">
            <div className="form-group">
              <label htmlFor="walkin_kebele_id" className="form-label">Kebele / Passport ID *</label>
              <input
                id="walkin_kebele_id"
                name="kebele_id"
                type="text"
                required
                placeholder="KB-99120"
                className="form-control"
                value={walkinForm.kebele_id}
                onChange={(e) => setWalkinForm({ ...walkinForm, kebele_id: e.target.value })}
              />
            </div>
            <div className="form-group">
              <label htmlFor="walkin_customer_email" className="form-label">Email (Optional)</label>
              <input
                id="walkin_customer_email"
                name="customer_email"
                autoComplete="email"
                type="email"
                className="form-control"
                value={walkinForm.customer_email}
                onChange={(e) => setWalkinForm({ ...walkinForm, customer_email: e.target.value })}
              />
            </div>
          </div>

          <div className="form-row-grid">
            <div className="form-group">
              <label htmlFor="walkin_checkin_date" className="form-label">Check-in Date</label>
              <input
                id="walkin_checkin_date"
                name="checkin_date"
                type="date"
                required
                className="form-control"
                value={walkinForm.checkin_date}
                onChange={(e) => setWalkinForm({ ...walkinForm, checkin_date: e.target.value })}
              />
            </div>
            <div className="form-group">
              <label htmlFor="walkin_checkout_date" className="form-label">Check-out Date</label>
              <input
                id="walkin_checkout_date"
                name="checkout_date"
                type="date"
                required
                className="form-control"
                value={walkinForm.checkout_date}
                onChange={(e) => setWalkinForm({ ...walkinForm, checkout_date: e.target.value })}
              />
            </div>
          </div>

          <div className="form-group">
            <label className="form-label">Payment Status</label>
            <select
              className="form-select"
              value={walkinForm.payment_status}
              onChange={(e) => setWalkinForm({ ...walkinForm, payment_status: e.target.value })}
            >
              <option value="paid">Paid Full ({selectedRoom?.price} ETB)</option>
              <option value="deposit">Deposit Paid</option>
              <option value="unpaid">Pay upon Checkout</option>
            </select>
          </div>

          <button type="submit" className="btn btn-gold" style={{ width: '100%', marginTop: '14px' }}>
            Complete Walk-in Check-in
          </button>
        </form>
      </Modal>
    </div>
  );
}
