import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import { Crown, KeyRound, User, Lock, Mail, Phone, ArrowLeft, ShieldCheck } from 'lucide-react';

const DEMO_ACCOUNTS = [
  { role: 'Manager', user: 'manager', desc: 'Hotel General Manager' },
  { role: 'Receptionist', user: 'receptionist', desc: 'Front Desk & Room Rack' },
  { role: 'Cashier', user: 'cashier', desc: 'Central Cash Desk & Payroll' },
  { role: 'Host', user: 'host', desc: 'Dining POS & Table Service' },
  { role: 'Barman', user: 'barman', desc: 'Bar Stock & Beverage Orders' },
  { role: 'Store', user: 'store', desc: 'Central Warehouse Keeper' },
  { role: 'Purchaser', user: 'purchaser', desc: 'Procurement Officer' },
  { role: 'Staff', user: 'staff', desc: 'Housekeeping & Maintenance' },
  { role: 'Customer', user: 'guest', desc: 'Guest Booking Portal' },
];

export default function LoginPage({ onNavigate, onSuccess }) {
  const { login, register, quickSwitchRole } = useAuth();
  const [isRegister, setIsRegister] = useState(false);
  const [form, setForm] = useState({ username: '', password: '', full_name: '', email: '', phone: '' });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    try {
      if (isRegister) {
        await register(form);
      } else {
        await login(form.username, form.password);
      }
      if (onSuccess) onSuccess();
    } catch (err) {
      setError(err.message || 'Authentication failed');
    } finally {
      setLoading(false);
    }
  };

  const handleInstantDemo = async (roleName) => {
    setLoading(true);
    setError('');
    try {
      await quickSwitchRole(roleName);
      if (onSuccess) onSuccess();
    } catch (err) {
      setError(err.message || 'Failed demo login');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={{
      minHeight: '100vh',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      padding: '40px 20px',
      background: 'radial-gradient(circle at 50% 20%, rgba(212, 175, 55, 0.08) 0%, #090d16 75%)'
    }}>
      <div style={{ width: '100%', maxWidth: '940px', display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(380px, 1fr))', gap: '30px' }}>
        {/* Left: Login / Register Form */}
        <div className="glass-card" style={{ padding: '36px' }}>
          <button
            onClick={() => onNavigate('home')}
            style={{ display: 'flex', alignItems: 'center', gap: '6px', background: 'transparent', color: 'var(--text-muted)', fontSize: '0.825rem', marginBottom: '24px' }}
          >
            <ArrowLeft size={16} /> Back to Hotel Website
          </button>

          <div style={{ display: 'flex', alignItems: 'center', gap: '12px', marginBottom: '20px' }}>
            <div style={{
              width: '44px',
              height: '44px',
              borderRadius: '10px',
              background: 'var(--gold-gradient)',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              boxShadow: 'var(--gold-glow)'
            }}>
              <Crown size={24} color="#090d16" />
            </div>
            <div>
              <h2 style={{ fontSize: '1.4rem', fontWeight: '800' }}>
                {isRegister ? 'Create Guest Account' : 'Hotel Staff & Guest Portal'}
              </h2>
              <div style={{ fontSize: '0.775rem', color: 'var(--gold-primary)', fontWeight: '600' }}>
                Role-Based Access Control System
              </div>
            </div>
          </div>

          {error && (
            <div style={{ padding: '12px', background: 'rgba(244, 63, 94, 0.15)', border: '1px solid rgba(244, 63, 94, 0.3)', borderRadius: '6px', color: '#fb7185', fontSize: '0.85rem', marginBottom: '20px' }}>
              {error}
            </div>
          )}

          <form onSubmit={handleSubmit}>
            {isRegister && (
              <>
                <div className="form-group">
                  <label className="form-label">Full Name</label>
                  <input
                    type="text"
                    required
                    placeholder="e.g. Almaz Bekele"
                    className="form-control"
                    value={form.full_name}
                    onChange={(e) => setForm({ ...form, full_name: e.target.value })}
                  />
                </div>
                <div className="form-group">
                  <label className="form-label">Email Address</label>
                  <input
                    type="email"
                    placeholder="guest@example.com"
                    className="form-control"
                    value={form.email}
                    onChange={(e) => setForm({ ...form, email: e.target.value })}
                  />
                </div>
                <div className="form-group">
                  <label className="form-label">Phone Number</label>
                  <input
                    type="tel"
                    placeholder="+251 9..."
                    className="form-control"
                    value={form.phone}
                    onChange={(e) => setForm({ ...form, phone: e.target.value })}
                  />
                </div>
              </>
            )}

            <div className="form-group">
              <label className="form-label">Username</label>
              <input
                type="text"
                required
                placeholder="Enter username"
                className="form-control"
                value={form.username}
                onChange={(e) => setForm({ ...form, username: e.target.value })}
              />
            </div>

            <div className="form-group">
              <label className="form-label">Password</label>
              <input
                type="password"
                required
                placeholder="Enter password"
                className="form-control"
                value={form.password}
                onChange={(e) => setForm({ ...form, password: e.target.value })}
              />
            </div>

            <button type="submit" className="btn btn-gold" style={{ width: '100%', marginTop: '8px' }} disabled={loading}>
              <KeyRound size={16} />
              {loading ? 'Authenticating...' : (isRegister ? 'Register Account' : 'Sign In')}
            </button>
          </form>

          <div style={{ marginTop: '24px', textAlign: 'center', borderTop: '1px solid var(--border-light)', paddingTop: '16px', fontSize: '0.85rem', color: 'var(--text-muted)' }}>
            {isRegister ? (
              <span>
                Already registered?{' '}
                <button onClick={() => setIsRegister(false)} style={{ background: 'transparent', color: 'var(--gold-light)', fontWeight: '700' }}>
                  Sign In Here
                </button>
              </span>
            ) : (
              <span>
                New guest?{' '}
                <button onClick={() => setIsRegister(true)} style={{ background: 'transparent', color: 'var(--gold-light)', fontWeight: '700' }}>
                  Register Online Profile
                </button>
              </span>
            )}
          </div>
        </div>

        {/* Right: Instant One-Click Role Switcher */}
        <div className="glass-card" style={{ padding: '36px', display: 'flex', flexDirection: 'column', justifyContent: 'space-between' }}>
          <div>
            <div style={{ display: 'flex', alignItems: 'center', gap: '8px', color: 'var(--gold-primary)', fontWeight: '700', fontSize: '0.85rem', textTransform: 'uppercase', marginBottom: '8px' }}>
              <ShieldCheck size={18} /> Instant Evaluation
            </div>
            <h3 style={{ fontSize: '1.35rem', fontWeight: '800', marginBottom: '10px' }}>
              One-Click Persona Login
            </h3>
            <p style={{ color: 'var(--text-muted)', fontSize: '0.85rem', marginBottom: '20px' }}>
              Select any role below to instantly enter that workspace without typing credentials:
            </p>

            <div style={{ display: 'grid', gridTemplateColumns: '1fr', gap: '8px' }}>
              {DEMO_ACCOUNTS.map((acc) => (
                <button
                  key={acc.role}
                  onClick={() => handleInstantDemo(acc.role)}
                  style={{
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'space-between',
                    padding: '10px 14px',
                    borderRadius: '8px',
                    background: 'rgba(255, 255, 255, 0.03)',
                    border: '1px solid var(--border-light)',
                    color: 'var(--text-main)',
                    fontSize: '0.85rem',
                    textAlign: 'left',
                    transition: 'all 0.2s ease',
                    cursor: 'pointer'
                  }}
                  onMouseOver={(e) => {
                    e.currentTarget.style.borderColor = 'var(--gold-primary)';
                    e.currentTarget.style.background = 'rgba(212, 175, 55, 0.08)';
                  }}
                  onMouseOut={(e) => {
                    e.currentTarget.style.borderColor = 'var(--border-light)';
                    e.currentTarget.style.background = 'rgba(255, 255, 255, 0.03)';
                  }}
                >
                  <div>
                    <div style={{ fontWeight: '700', color: 'var(--gold-light)' }}>{acc.role}</div>
                    <div style={{ fontSize: '0.75rem', color: 'var(--text-muted)' }}>{acc.desc}</div>
                  </div>
                  <span className="badge badge-gold" style={{ fontSize: '0.725rem' }}>
                    Launch →
                  </span>
                </button>
              ))}
            </div>
          </div>

          <div style={{ marginTop: '20px', padding: '12px', background: 'rgba(255, 255, 255, 0.02)', borderRadius: '6px', fontSize: '0.75rem', color: 'var(--text-dim)' }}>
            💡 Default password for all seeded accounts is <strong style={{ color: 'var(--text-muted)' }}>password123</strong>.
          </div>
        </div>
      </div>
    </div>
  );
}
