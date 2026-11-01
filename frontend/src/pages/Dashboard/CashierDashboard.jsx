import React, { useState, useEffect } from 'react';
import { api } from '../../services/api';
import Modal from '../../components/Modal';
import { DollarSign, CreditCard, ArrowDownRight, ArrowUpRight, Plus, Users, CheckCircle } from 'lucide-react';

export default function CashierDashboard({ activeTab = 'billing' }) {
  const [summary, setSummary] = useState({ totalIncome: 0, totalExpenses: 0, netProfit: 0 });
  const [transactions, setTransactions] = useState([]);
  const [employees, setEmployees] = useState([]);
  const [roomReservations, setRoomReservations] = useState([]);
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);

  // Modal for Direct Entry
  const [isTxModalOpen, setIsTxModalOpen] = useState(false);
  const [txForm, setTxForm] = useState({ type: 'income', category: 'miscellaneous', amount: '', description: '' });

  useEffect(() => {
    loadCashierData();
  }, []);

  async function loadCashierData() {
    try {
      const [sum, txs, emps, rooms, ords] = await Promise.all([
        api.getFinanceSummary(),
        api.getTransactions(),
        api.getEmployees(),
        api.getRoomReservations('?status=all'),
        api.getOrders()
      ]);
      setSummary(sum);
      setTransactions(txs);
      setEmployees(emps);
      setRoomReservations(rooms);
      setOrders(ords);
    } catch (err) {
      console.error('Error loading cashier data:', err);
    } finally {
      setLoading(false);
    }
  }

  const handlePaySalary = async (emp) => {
    if (!window.confirm(`Confirm salary disbursement of ${emp.salary} ETB to ${emp.full_name}?`)) return;
    try {
      await api.paySalary({ employee_id: emp.id, notes: `Paid by Senior Cashier` });
      alert(`Payroll processed for ${emp.full_name}`);
      loadCashierData();
    } catch (err) {
      alert(err.message || 'Error processing payroll');
    }
  };

  const handleCreateTx = async (e) => {
    e.preventDefault();
    try {
      await api.recordTransaction({
        ...txForm,
        amount: parseFloat(txForm.amount) || 0
      });
      setIsTxModalOpen(false);
      setTxForm({ type: 'income', category: 'miscellaneous', amount: '', description: '' });
      loadCashierData();
    } catch (err) {
      alert(err.message || 'Error recording transaction');
    }
  };

  const unpaidRooms = roomReservations.filter(r => r.payment_status === 'unpaid' || r.payment_status === 'deposit');

  return (
    <div>
      {/* Metric Cards */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '20px', marginBottom: '28px' }}>
        <div className="stat-card">
          <div>
            <div className="stat-label">Total Cash Collections</div>
            <div className="stat-val" style={{ color: 'var(--emerald)' }}>{summary.totalIncome} ETB</div>
          </div>
          <div className="stat-icon" style={{ background: 'rgba(16, 185, 129, 0.15)' }}>
            <ArrowDownRight size={26} color="var(--emerald)" />
          </div>
        </div>

        <div className="stat-card">
          <div>
            <div className="stat-label">Total Hotel Disbursements</div>
            <div className="stat-val" style={{ color: '#fb7185' }}>{summary.totalExpenses} ETB</div>
          </div>
          <div className="stat-icon" style={{ background: 'rgba(244, 63, 94, 0.15)' }}>
            <ArrowUpRight size={26} color="#fb7185" />
          </div>
        </div>

        <div className="stat-card">
          <div>
            <div className="stat-label">Net Operating Cash Balance</div>
            <div className="stat-val" style={{ color: 'var(--gold-primary)' }}>{summary.netProfit} ETB</div>
          </div>
          <div className="stat-icon" style={{ background: 'rgba(212, 175, 55, 0.15)' }}>
            <DollarSign size={26} color="var(--gold-primary)" />
          </div>
        </div>
      </div>

      {/* Tab: Billing & Pending Settlements */}
      {(activeTab === 'billing' || !activeTab) && (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '24px' }}>
          <div className="glass-card">
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '16px' }}>
              <h3 style={{ fontSize: '1.2rem', fontWeight: '800' }}>Pending Guest Invoices (Rooms)</h3>
              <button onClick={() => setIsTxModalOpen(true)} className="btn btn-gold btn-sm">
                <Plus size={15} /> Record Manual Entry
              </button>
            </div>

            <div className="table-container">
              <table className="modern-table">
                <thead>
                  <tr>
                    <th>Booking Code</th>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Due Amount</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  {unpaidRooms.length === 0 ? (
                    <tr>
                      <td colSpan="6" style={{ textAlign: 'center', padding: '24px', color: 'var(--text-dim)' }}>
                        All current room invoices are settled.
                      </td>
                    </tr>
                  ) : (
                    unpaidRooms.map(r => (
                      <tr key={r.id}>
                        <td style={{ fontWeight: '800', color: 'var(--gold-primary)' }}>{r.booking_code}</td>
                        <td>{r.customer_name}</td>
                        <td>Room {r.room_number || r.room_id}</td>
                        <td style={{ fontWeight: '800', color: 'var(--gold-light)' }}>{r.room_price} ETB</td>
                        <td><span className={`badge badge-${r.payment_status}`}>{r.payment_status}</span></td>
                        <td>
                          <button
                            onClick={async () => {
                              await api.updateRoomReservation(r.id, { payment_status: 'paid' });
                              loadCashierData();
                            }}
                            className="btn btn-emerald btn-sm"
                            style={{ padding: '4px 10px', fontSize: '0.75rem' }}
                          >
                            <CreditCard size={13} /> Settle Invoice
                          </button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {/* Tab: Payroll */}
      {activeTab === 'payroll' && (
        <div className="glass-card">
          <h3 style={{ fontSize: '1.25rem', marginBottom: '18px' }}>Staff Payroll Disbursement</h3>
          <div className="table-container">
            <table className="modern-table">
              <thead>
                <tr>
                  <th>Employee Name</th>
                  <th>Role</th>
                  <th>Position</th>
                  <th>Monthly Salary</th>
                  <th>Attendance</th>
                  <th>Payroll Action</th>
                </tr>
              </thead>
              <tbody>
                {employees.map(emp => (
                  <tr key={emp.id}>
                    <td style={{ fontWeight: '700' }}>{emp.full_name}</td>
                    <td><span className="badge badge-gold">{emp.role}</span></td>
                    <td>{emp.position}</td>
                    <td style={{ fontWeight: '800', color: 'var(--gold-light)' }}>{emp.salary} ETB</td>
                    <td>
                      <span className={`badge badge-${emp.is_present === 'yes' ? 'present' : 'absent'}`}>
                        {emp.is_present === 'yes' ? 'Present' : 'Absent'}
                      </span>
                    </td>
                    <td>
                      <button
                        onClick={() => handlePaySalary(emp)}
                        className="btn btn-emerald btn-sm"
                        style={{ padding: '4px 10px', fontSize: '0.75rem' }}
                      >
                        <DollarSign size={13} /> Disburse Salary
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* Tab: Financial Ledger */}
      {(activeTab === 'ledger' || activeTab === 'summary') && (
        <div className="glass-card" style={{ marginTop: '24px' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '16px' }}>
            <h3 style={{ fontSize: '1.2rem', fontWeight: '800' }}>Complete Financial Transaction Ledger</h3>
            <button onClick={() => setIsTxModalOpen(true)} className="btn btn-gold btn-sm">
              <Plus size={15} /> Record Cash Entry
            </button>
          </div>

          <div className="table-container">
            <table className="modern-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Type</th>
                  <th>Category</th>
                  <th>Amount</th>
                  <th>Reference</th>
                  <th>Description</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                {transactions.map(t => (
                  <tr key={t.id}>
                    <td>#{t.id}</td>
                    <td><span className={`badge badge-${t.type === 'income' ? 'success' : 'danger'}`}>{t.type}</span></td>
                    <td>{t.category}</td>
                    <td style={{ fontWeight: '800', color: t.type === 'income' ? 'var(--emerald)' : '#fb7185' }}>
                      {t.type === 'income' ? '+' : '-'}{t.amount} ETB
                    </td>
                    <td style={{ color: 'var(--gold-light)' }}>{t.reference_id}</td>
                    <td style={{ fontSize: '0.825rem', color: 'var(--text-muted)' }}>{t.description}</td>
                    <td>{t.transaction_date?.slice(0, 10)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* Manual Entry Modal */}
      <Modal isOpen={isTxModalOpen} onClose={() => setIsTxModalOpen(false)} title="Record Ledger Transaction">
        <form onSubmit={handleCreateTx}>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px' }}>
            <div className="form-group">
              <label className="form-label">Transaction Type</label>
              <select
                className="form-select"
                value={txForm.type}
                onChange={(e) => setTxForm({ ...txForm, type: e.target.value })}
              >
                <option value="income">Income / Deposit (+)</option>
                <option value="expense">Expense / Withdrawal (-)</option>
              </select>
            </div>
            <div className="form-group">
              <label className="form-label">Category</label>
              <select
                className="form-select"
                value={txForm.category}
                onChange={(e) => setTxForm({ ...txForm, category: e.target.value })}
              >
                <option value="room_booking">Room Booking</option>
                <option value="restaurant_bar">Restaurant & Bar</option>
                <option value="inventory_purchase">Inventory Purchase</option>
                <option value="staff_salary">Staff Salary</option>
                <option value="utilities">Utilities & Maintenance</option>
                <option value="miscellaneous">Miscellaneous</option>
              </select>
            </div>
          </div>

          <div className="form-group">
            <label className="form-label">Amount (ETB) *</label>
            <input
              type="number"
              required
              className="form-control"
              value={txForm.amount}
              onChange={(e) => setTxForm({ ...txForm, amount: e.target.value })}
            />
          </div>

          <div className="form-group">
            <label className="form-label">Description / Receipt Note</label>
            <input
              type="text"
              placeholder="e.g. Guest banquet deposit settlement"
              className="form-control"
              value={txForm.description}
              onChange={(e) => setTxForm({ ...txForm, description: e.target.value })}
            />
          </div>

          <button type="submit" className="btn btn-gold" style={{ width: '100%', marginTop: '12px' }}>
            Commit to Ledger
          </button>
        </form>
      </Modal>
    </div>
  );
}
