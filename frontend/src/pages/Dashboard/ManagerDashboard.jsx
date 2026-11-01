import React, { useState, useEffect } from 'react';
import { api } from '../../services/api';
import Modal from '../../components/Modal';
import {
  Users,
  DollarSign,
  Bed,
  Building,
  TrendingUp,
  Package,
  Plus,
  Trash,
  Edit,
  CheckCircle,
  AlertTriangle,
  Calendar,
  Save
} from 'lucide-react';

export default function ManagerDashboard({ activeTab = 'overview' }) {
  const [stats, setStats] = useState({ totalRooms: 0, occupancyRate: 0, totalIncome: 0, netProfit: 0, staffCount: 0 });
  const [employees, setEmployees] = useState([]);
  const [rooms, setRooms] = useState([]);
  const [halls, setHalls] = useState([]);
  const [transactions, setTransactions] = useState([]);
  const [attendance, setAttendance] = useState([]);
  const [hotelInfo, setHotelInfo] = useState({});
  const [loading, setLoading] = useState(true);

  // Modals
  const [isEmpModalOpen, setIsEmpModalOpen] = useState(false);
  const [empForm, setEmpForm] = useState({ username: '', password: '', full_name: '', email: '', phone: '', role: 'Receptionist', position: 'Front Desk Lead', salary: 18000, sex: 'Female', age: 26 });
  
  const [isRoomModalOpen, setIsRoomModalOpen] = useState(false);
  const [roomForm, setRoomForm] = useState({ room_number: '', type: 'Single Deluxe', price: 1400, capacity: 2, floor: 1, amenities: 'Wi-Fi, Smart TV, Hot Shower', description: 'Modern deluxe room' });

  useEffect(() => {
    loadDashboardData();
  }, []);

  async function loadDashboardData() {
    try {
      const [roomStats, empList, roomList, hallList, finSummary, txList, attList, hotel] = await Promise.all([
        api.getRoomStats(),
        api.getEmployees(),
        api.getRooms('?status=all'),
        api.getHalls(),
        api.getFinanceSummary(),
        api.getTransactions(),
        api.getAttendance(),
        api.getHotelInfo()
      ]);

      setStats({
        totalRooms: roomStats.total,
        occupancyRate: roomStats.occupancyRate,
        totalIncome: finSummary.totalIncome,
        netProfit: finSummary.netProfit,
        staffCount: empList.length
      });
      setEmployees(empList);
      setRooms(roomList);
      setHalls(hallList);
      setTransactions(txList);
      setAttendance(attList);
      setHotelInfo(hotel);
    } catch (err) {
      console.error('Error loading manager dashboard:', err);
    } finally {
      setLoading(false);
    }
  }

  const handleCreateEmployee = async (e) => {
    e.preventDefault();
    try {
      await api.createEmployee(empForm);
      setIsEmpModalOpen(false);
      loadDashboardData();
    } catch (err) {
      alert(err.message || 'Error creating employee');
    }
  };

  const handleDeleteEmployee = async (id) => {
    if (!window.confirm('Are you sure you want to remove this employee?')) return;
    try {
      await api.deleteEmployee(id);
      loadDashboardData();
    } catch (err) {
      alert(err.message || 'Error removing employee');
    }
  };

  const handleCreateRoom = async (e) => {
    e.preventDefault();
    try {
      await api.createRoom(roomForm);
      setIsRoomModalOpen(false);
      loadDashboardData();
    } catch (err) {
      alert(err.message || 'Error creating room');
    }
  };

  const handleSaveHotelInfo = async (e) => {
    e.preventDefault();
    try {
      await api.updateHotelInfo(hotelInfo);
      alert('Hotel configuration saved successfully!');
    } catch (err) {
      alert(err.message || 'Error saving settings');
    }
  };

  if (loading) {
    return <div style={{ padding: '40px', color: 'var(--text-muted)' }}>Loading Executive Dashboard...</div>;
  }

  return (
    <div>
      {/* KPI Cards */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '20px', marginBottom: '28px' }}>
        <div className="stat-card">
          <div>
            <div className="stat-label">Total Revenue</div>
            <div className="stat-val" style={{ color: 'var(--gold-primary)' }}>{stats.totalIncome} ETB</div>
            <div style={{ fontSize: '0.75rem', color: 'var(--emerald)' }}>Net Profit: {stats.netProfit} ETB</div>
          </div>
          <div className="stat-icon" style={{ background: 'rgba(212, 175, 55, 0.15)' }}>
            <DollarSign size={26} color="var(--gold-primary)" />
          </div>
        </div>

        <div className="stat-card">
          <div>
            <div className="stat-label">Occupancy Rate</div>
            <div className="stat-val" style={{ color: 'var(--sapphire)' }}>{stats.occupancyRate}%</div>
            <div style={{ fontSize: '0.75rem', color: 'var(--text-muted)' }}>Across {stats.totalRooms} Total Rooms</div>
          </div>
          <div className="stat-icon" style={{ background: 'rgba(2, 132, 199, 0.15)' }}>
            <Bed size={26} color="var(--sapphire)" />
          </div>
        </div>

        <div className="stat-card">
          <div>
            <div className="stat-label">Active Hotel Staff</div>
            <div className="stat-val" style={{ color: 'var(--emerald)' }}>{stats.staffCount}</div>
            <div style={{ fontSize: '0.75rem', color: 'var(--text-muted)' }}>8 Specialized Roles</div>
          </div>
          <div className="stat-icon" style={{ background: 'rgba(16, 185, 129, 0.15)' }}>
            <Users size={26} color="var(--emerald)" />
          </div>
        </div>

        <div className="stat-card">
          <div>
            <div className="stat-label">Conference Halls</div>
            <div className="stat-val" style={{ color: 'var(--purple)' }}>{halls.length}</div>
            <div style={{ fontSize: '0.75rem', color: 'var(--text-muted)' }}>Banquet & Seminars</div>
          </div>
          <div className="stat-icon" style={{ background: 'rgba(139, 92, 246, 0.15)' }}>
            <Building size={26} color="var(--purple)" />
          </div>
        </div>
      </div>

      {/* Tab: Overview */}
      {(activeTab === 'overview' || !activeTab) && (
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(400px, 1fr))', gap: '24px' }}>
          {/* Recent Financial Entries */}
          <div className="glass-card">
            <h3 style={{ fontSize: '1.15rem', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
              <TrendingUp size={18} color="var(--gold-primary)" />
              Recent Ledger Transactions
            </h3>
            <div className="table-container">
              <table className="modern-table">
                <thead>
                  <tr>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Provider</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                  {transactions.slice(0, 6).map((tx) => (
                    <tr key={tx.id}>
                      <td>
                        <span className={`badge badge-${tx.type === 'income' ? 'success' : 'danger'}`}>
                          {tx.type}
                        </span>
                      </td>
                      <td>{tx.category.replace('_', ' ')}</td>
                      <td style={{ fontWeight: '700', color: tx.type === 'income' ? 'var(--emerald)' : '#fb7185' }}>
                        {tx.type === 'income' ? '+' : '-'}{tx.amount} ETB
                      </td>
                      <td>{tx.provider_name}</td>
                      <td>{tx.transaction_date?.slice(0, 10)}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>

          {/* Rooms Summary */}
          <div className="glass-card">
            <h3 style={{ fontSize: '1.15rem', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Bed size={18} color="var(--gold-primary)" />
              Live Room Inventory Overview
            </h3>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(90px, 1fr))', gap: '10px' }}>
              {rooms.map((r) => (
                <div
                  key={r.id}
                  style={{
                    padding: '12px 8px',
                    borderRadius: '8px',
                    textAlign: 'center',
                    background: r.status === 'free' ? 'rgba(16, 185, 129, 0.12)' : r.status === 'occupied' ? 'rgba(244, 63, 94, 0.12)' : 'rgba(245, 158, 11, 0.12)',
                    border: `1px solid ${r.status === 'free' ? 'rgba(16, 185, 129, 0.3)' : r.status === 'occupied' ? 'rgba(244, 63, 94, 0.3)' : 'rgba(245, 158, 11, 0.3)'}`
                  }}
                >
                  <div style={{ fontWeight: '800', fontSize: '1rem', color: 'var(--text-main)' }}>{r.room_number}</div>
                  <div style={{ fontSize: '0.7rem', color: 'var(--text-muted)', textTransform: 'capitalize' }}>{r.status}</div>
                </div>
              ))}
            </div>
          </div>
        </div>
      )}

      {/* Tab: Employees */}
      {activeTab === 'employees' && (
        <div className="glass-card">
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '1.25rem' }}>Hotel Employees & Staff Directory</h3>
            <button onClick={() => setIsEmpModalOpen(true)} className="btn btn-gold btn-sm">
              <Plus size={16} /> Add Employee
            </button>
          </div>

          <div className="table-container">
            <table className="modern-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Username</th>
                  <th>Role</th>
                  <th>Position</th>
                  <th>Contact</th>
                  <th>Salary</th>
                  <th>Attendance</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {employees.map((emp) => (
                  <tr key={emp.id}>
                    <td style={{ fontWeight: '600' }}>{emp.full_name}</td>
                    <td style={{ color: 'var(--gold-light)' }}>@{emp.username}</td>
                    <td>
                      <span className="badge badge-gold">{emp.role}</span>
                    </td>
                    <td>{emp.position}</td>
                    <td>
                      <div>{emp.phone}</div>
                      <div style={{ fontSize: '0.75rem', color: 'var(--text-dim)' }}>{emp.email}</div>
                    </td>
                    <td style={{ fontWeight: '700' }}>{emp.salary} ETB</td>
                    <td>
                      <span className={`badge badge-${emp.is_present === 'yes' ? 'present' : 'absent'}`}>
                        {emp.is_present === 'yes' ? 'Present' : 'Absent'}
                      </span>
                    </td>
                    <td>
                      <button
                        onClick={() => handleDeleteEmployee(emp.id)}
                        className="btn btn-danger btn-sm"
                        style={{ padding: '4px 8px' }}
                        title="Remove Employee"
                      >
                        <Trash size={14} />
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* Tab: Rooms & Halls Admin */}
      {activeTab === 'rooms' && (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '24px' }}>
          <div className="glass-card">
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '16px' }}>
              <h3 style={{ fontSize: '1.2rem' }}>Rooms Catalog Admin</h3>
              <button onClick={() => setIsRoomModalOpen(true)} className="btn btn-gold btn-sm">
                <Plus size={16} /> Create Room
              </button>
            </div>
            <div className="table-container">
              <table className="modern-table">
                <thead>
                  <tr>
                    <th>Room #</th>
                    <th>Type</th>
                    <th>Floor</th>
                    <th>Capacity</th>
                    <th>Nightly Rate</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {rooms.map((r) => (
                    <tr key={r.id}>
                      <td style={{ fontWeight: '800' }}>{r.room_number}</td>
                      <td>{r.type}</td>
                      <td>Floor {r.floor}</td>
                      <td>{r.capacity} Persons</td>
                      <td style={{ fontWeight: '700', color: 'var(--gold-light)' }}>{r.price} ETB</td>
                      <td>
                        <span className={`badge badge-${r.status}`}>{r.status}</span>
                      </td>
                      <td>
                        <button
                          onClick={async () => {
                            const newStatus = r.status === 'free' ? 'maintenance' : 'free';
                            await api.updateRoom(r.id, { status: newStatus });
                            loadDashboardData();
                          }}
                          className="btn btn-outline btn-sm"
                          style={{ padding: '4px 8px', fontSize: '0.75rem' }}
                        >
                          Toggle Status
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {/* Tab: Finance */}
      {activeTab === 'finance' && (
        <div className="glass-card">
          <h3 style={{ fontSize: '1.25rem', marginBottom: '20px' }}>Comprehensive Financial Ledger</h3>
          <div className="table-container">
            <table className="modern-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Type</th>
                  <th>Category</th>
                  <th>Amount</th>
                  <th>Reference</th>
                  <th>Provider</th>
                  <th>Description</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                {transactions.map((t) => (
                  <tr key={t.id}>
                    <td>#{t.id}</td>
                    <td>
                      <span className={`badge badge-${t.type === 'income' ? 'success' : 'danger'}`}>
                        {t.type}
                      </span>
                    </td>
                    <td>{t.category}</td>
                    <td style={{ fontWeight: '700', color: t.type === 'income' ? 'var(--emerald)' : '#fb7185' }}>
                      {t.type === 'income' ? '+' : '-'}{t.amount} ETB
                    </td>
                    <td style={{ color: 'var(--gold-light)' }}>{t.reference_id}</td>
                    <td>{t.provider_name}</td>
                    <td style={{ fontSize: '0.825rem', color: 'var(--text-muted)' }}>{t.description}</td>
                    <td>{t.transaction_date?.slice(0, 10)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* Tab: Attendance */}
      {activeTab === 'attendance' && (
        <div className="glass-card">
          <h3 style={{ fontSize: '1.25rem', marginBottom: '20px' }}>Staff Attendance Registry</h3>
          <div className="table-container">
            <table className="modern-table">
              <thead>
                <tr>
                  <th>Employee Name</th>
                  <th>Role</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Notes</th>
                  <th>Quick Mark</th>
                </tr>
              </thead>
              <tbody>
                {employees.map((emp) => (
                  <tr key={emp.id}>
                    <td style={{ fontWeight: '700' }}>{emp.full_name}</td>
                    <td><span className="badge badge-gold">{emp.role}</span></td>
                    <td>{new Date().toISOString().split('T')[0]}</td>
                    <td>
                      <span className={`badge badge-${emp.is_present === 'yes' ? 'present' : 'absent'}`}>
                        {emp.is_present === 'yes' ? 'Present' : 'Absent'}
                      </span>
                    </td>
                    <td style={{ color: 'var(--text-dim)', fontSize: '0.8rem' }}>On shift duty</td>
                    <td>
                      <div style={{ display: 'flex', gap: '6px' }}>
                        <button
                          onClick={async () => {
                            await api.markAttendance({ employee_id: emp.id, status: 'present', notes: 'Checked in' });
                            loadDashboardData();
                          }}
                          className="btn btn-emerald btn-sm"
                          style={{ padding: '3px 8px', fontSize: '0.75rem' }}
                        >
                          Present
                        </button>
                        <button
                          onClick={async () => {
                            await api.markAttendance({ employee_id: emp.id, status: 'absent', notes: 'Absent from duty' });
                            loadDashboardData();
                          }}
                          className="btn btn-danger btn-sm"
                          style={{ padding: '3px 8px', fontSize: '0.75rem' }}
                        >
                          Absent
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* Tab: Settings */}
      {activeTab === 'settings' && (
        <div className="glass-card" style={{ maxWidth: '700px' }}>
          <h3 style={{ fontSize: '1.25rem', marginBottom: '20px' }}>Hotel Settings & Configuration</h3>
          <form onSubmit={handleSaveHotelInfo}>
            <div className="form-group">
              <label className="form-label">Hotel Name</label>
              <input
                type="text"
                className="form-control"
                value={hotelInfo.name || ''}
                onChange={(e) => setHotelInfo({ ...hotelInfo, name: e.target.value })}
                required
              />
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px' }}>
              <div className="form-group">
                <label className="form-label">Town</label>
                <input
                  type="text"
                  className="form-control"
                  value={hotelInfo.town || ''}
                  onChange={(e) => setHotelInfo({ ...hotelInfo, town: e.target.value })}
                />
              </div>
              <div className="form-group">
                <label className="form-label">Region & Country</label>
                <input
                  type="text"
                  className="form-control"
                  value={hotelInfo.region || ''}
                  onChange={(e) => setHotelInfo({ ...hotelInfo, region: e.target.value })}
                />
              </div>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px' }}>
              <div className="form-group">
                <label className="form-label">Phone</label>
                <input
                  type="text"
                  className="form-control"
                  value={hotelInfo.phone || ''}
                  onChange={(e) => setHotelInfo({ ...hotelInfo, phone: e.target.value })}
                />
              </div>
              <div className="form-group">
                <label className="form-label">Email</label>
                <input
                  type="email"
                  className="form-control"
                  value={hotelInfo.email || ''}
                  onChange={(e) => setHotelInfo({ ...hotelInfo, email: e.target.value })}
                />
              </div>
            </div>

            <div className="form-group">
              <label className="form-label">Address</label>
              <input
                type="text"
                className="form-control"
                value={hotelInfo.address || ''}
                onChange={(e) => setHotelInfo({ ...hotelInfo, address: e.target.value })}
              />
            </div>

            <button type="submit" className="btn btn-gold">
              <Save size={16} /> Save Settings
            </button>
          </form>
        </div>
      )}

      {/* Add Employee Modal */}
      <Modal isOpen={isEmpModalOpen} onClose={() => setIsEmpModalOpen(false)} title="Add Hotel Staff">
        <form onSubmit={handleCreateEmployee}>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px' }}>
            <div className="form-group">
              <label className="form-label">Username *</label>
              <input
                type="text"
                required
                className="form-control"
                value={empForm.username}
                onChange={(e) => setEmpForm({ ...empForm, username: e.target.value })}
              />
            </div>
            <div className="form-group">
              <label className="form-label">Password *</label>
              <input
                type="password"
                required
                className="form-control"
                value={empForm.password}
                onChange={(e) => setEmpForm({ ...empForm, password: e.target.value })}
              />
            </div>
          </div>

          <div className="form-group">
            <label className="form-label">Full Name *</label>
            <input
              type="text"
              required
              className="form-control"
              value={empForm.full_name}
              onChange={(e) => setEmpForm({ ...empForm, full_name: e.target.value })}
            />
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px' }}>
            <div className="form-group">
              <label className="form-label">Role *</label>
              <select
                className="form-select"
                value={empForm.role}
                onChange={(e) => setEmpForm({ ...empForm, role: e.target.value })}
              >
                <option value="Manager">Manager</option>
                <option value="Receptionist">Receptionist</option>
                <option value="Cashier">Cashier</option>
                <option value="Host">Host</option>
                <option value="Barman">Barman</option>
                <option value="Store">Store</option>
                <option value="Purchaser">Purchaser</option>
                <option value="Staff">Staff</option>
              </select>
            </div>
            <div className="form-group">
              <label className="form-label">Monthly Salary (ETB)</label>
              <input
                type="number"
                className="form-control"
                value={empForm.salary}
                onChange={(e) => setEmpForm({ ...empForm, salary: parseFloat(e.target.value) || 0 })}
              />
            </div>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px' }}>
            <div className="form-group">
              <label className="form-label">Phone</label>
              <input
                type="text"
                className="form-control"
                value={empForm.phone}
                onChange={(e) => setEmpForm({ ...empForm, phone: e.target.value })}
              />
            </div>
            <div className="form-group">
              <label className="form-label">Email</label>
              <input
                type="email"
                className="form-control"
                value={empForm.email}
                onChange={(e) => setEmpForm({ ...empForm, email: e.target.value })}
              />
            </div>
          </div>

          <button type="submit" className="btn btn-gold" style={{ width: '100%', marginTop: '12px' }}>
            Confirm & Add Staff
          </button>
        </form>
      </Modal>

      {/* Create Room Modal */}
      <Modal isOpen={isRoomModalOpen} onClose={() => setIsRoomModalOpen(false)} title="Create New Hotel Room">
        <form onSubmit={handleCreateRoom}>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px' }}>
            <div className="form-group">
              <label className="form-label">Room Number *</label>
              <input
                type="text"
                required
                placeholder="e.g. 501"
                className="form-control"
                value={roomForm.room_number}
                onChange={(e) => setRoomForm({ ...roomForm, room_number: e.target.value })}
              />
            </div>
            <div className="form-group">
              <label className="form-label">Room Type *</label>
              <input
                type="text"
                required
                placeholder="e.g. Deluxe Suite"
                className="form-control"
                value={roomForm.type}
                onChange={(e) => setRoomForm({ ...roomForm, type: e.target.value })}
              />
            </div>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px' }}>
            <div className="form-group">
              <label className="form-label">Nightly Rate (ETB) *</label>
              <input
                type="number"
                required
                className="form-control"
                value={roomForm.price}
                onChange={(e) => setRoomForm({ ...roomForm, price: parseFloat(e.target.value) || 0 })}
              />
            </div>
            <div className="form-group">
              <label className="form-label">Floor</label>
              <input
                type="number"
                className="form-control"
                value={roomForm.floor}
                onChange={(e) => setRoomForm({ ...roomForm, floor: parseInt(e.target.value) || 1 })}
              />
            </div>
          </div>

          <div className="form-group">
            <label className="form-label">Amenities</label>
            <input
              type="text"
              placeholder="King Bed, Balcony, Mini Bar..."
              className="form-control"
              value={roomForm.amenities}
              onChange={(e) => setRoomForm({ ...roomForm, amenities: e.target.value })}
            />
          </div>

          <button type="submit" className="btn btn-gold" style={{ width: '100%', marginTop: '12px' }}>
            Create Room
          </button>
        </form>
      </Modal>
    </div>
  );
}
