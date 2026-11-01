import React from 'react';
import { useAuth } from '../context/AuthContext';
import { ShieldCheck, UserCheck, ArrowRight, Home } from 'lucide-react';

const ROLES = [
  { id: 'Manager', label: '👑 Manager' },
  { id: 'Receptionist', label: '🛎️ Receptionist' },
  { id: 'Cashier', label: '💰 Cashier' },
  { id: 'Host', label: '🍽️ Dining Host' },
  { id: 'Barman', label: '🍸 Barman' },
  { id: 'Store', label: '📦 Store Keeper' },
  { id: 'Purchaser', label: '🛒 Purchaser' },
  { id: 'Staff', label: '🧹 Staff / Service' },
  { id: 'Customer', label: '✨ Guest Portal' }
];

export default function RoleBanner({ onGoHome }) {
  const { user, quickSwitchRole } = useAuth();

  return (
    <div className="demo-role-bar">
      <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
        <ShieldCheck size={18} color="var(--gold-primary)" />
        <span style={{ fontWeight: '600', color: 'var(--text-muted)' }}>
          Active Persona:
        </span>
        <span className="badge badge-gold" style={{ fontSize: '0.825rem' }}>
          {user ? user.role : 'Guest'}
        </span>
        {user && (
          <span style={{ color: 'var(--text-dim)', fontSize: '0.8rem' }}>
            ({user.full_name})
          </span>
        )}
      </div>

      <div style={{ display: 'flex', alignItems: 'center', gap: '8px', flexWrap: 'wrap' }}>
        <span style={{ fontSize: '0.75rem', color: 'var(--text-dim)', textTransform: 'uppercase', letterSpacing: '0.05em' }}>
          Fast Role Switch:
        </span>
        <div className="role-chips">
          {ROLES.map((r) => {
            const isActive = user?.role === r.id;
            return (
              <button
                key={r.id}
                onClick={() => quickSwitchRole(r.id)}
                className={`role-chip ${isActive ? 'active' : ''}`}
                title={`Switch session to ${r.id}`}
              >
                {r.label}
              </button>
            );
          })}
        </div>

        {onGoHome && (
          <button
            onClick={onGoHome}
            className="btn btn-outline btn-sm"
            style={{ marginLeft: '8px', padding: '4px 10px', fontSize: '0.775rem' }}
          >
            <Home size={14} /> Public Website
          </button>
        )}
      </div>
    </div>
  );
}
