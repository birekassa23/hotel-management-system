import React from 'react';
import { useAuth } from '../context/AuthContext';
import ThemeToggle from './ThemeToggle';
import { ShieldCheck, Home } from 'lucide-react';

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
      {/* Top / Left: Active Persona & Actions */}
      <div className="demo-role-bar-top" style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '10px', flexWrap: 'wrap' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
          <ShieldCheck size={17} color="var(--gold-primary)" />
          <span style={{ fontWeight: '600', color: 'var(--text-muted)', fontSize: '0.825rem' }}>
            Active:
          </span>
          <span className="badge badge-gold" style={{ fontSize: '0.8rem', padding: '3px 8px' }}>
            {user ? user.role : 'Guest'}
          </span>
          {user && (
            <span style={{ color: 'var(--text-dim)', fontSize: '0.775rem', display: 'inline-block', maxWidth: '140px', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
              ({user.full_name})
            </span>
          )}
        </div>

        <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
          <ThemeToggle compact={true} />

          {onGoHome && (
            <button
              onClick={onGoHome}
              className="btn btn-outline btn-sm"
              style={{ padding: '4px 10px', fontSize: '0.75rem', height: '28px' }}
              title="Return to Public Website"
            >
              <Home size={13} /> <span className="hide-on-very-small">Public Website</span>
            </button>
          )}
        </div>
      </div>

      {/* Role Chips Carousel */}
      <div style={{ display: 'flex', alignItems: 'center', gap: '8px', width: '100%', overflow: 'hidden' }}>
        <span style={{ fontSize: '0.725rem', color: 'var(--text-dim)', textTransform: 'uppercase', letterSpacing: '0.05em', flexShrink: 0 }}>
          Switch:
        </span>
        <div className="role-chips" style={{ flex: 1 }}>
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
      </div>

      <style>{`
        @media (max-width: 480px) {
          .hide-on-very-small {
            display: none;
          }
        }
      `}</style>
    </div>
  );
}
