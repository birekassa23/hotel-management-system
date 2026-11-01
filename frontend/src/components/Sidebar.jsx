import React from 'react';
import { useAuth } from '../context/AuthContext';
import ThemeToggle from './ThemeToggle';
import {
  LayoutDashboard,
  Users,
  Bed,
  Building,
  CalendarCheck,
  UtensilsCrossed,
  Wine,
  Package,
  ShoppingCart,
  DollarSign,
  ClipboardList,
  CheckCircle,
  Settings,
  LogOut,
  Home,
  UserCheck,
  X
} from 'lucide-react';

const ROLE_NAV_CONFIG = {
  Manager: [
    { id: 'overview', label: 'Executive Overview', icon: LayoutDashboard },
    { id: 'employees', label: 'Staff Management', icon: Users },
    { id: 'rooms', label: 'Rooms & Halls Admin', icon: Bed },
    { id: 'finance', label: 'Finance & Ledger', icon: DollarSign },
    { id: 'attendance', label: 'Attendance Records', icon: UserCheck },
    { id: 'settings', label: 'Hotel Settings', icon: Settings },
  ],
  Receptionist: [
    { id: 'rack', label: 'Live Room Rack', icon: Bed },
    { id: 'reservations', label: 'Room Reservations', icon: CalendarCheck },
    { id: 'halls', label: 'Hall Bookings', icon: Building },
    { id: 'guests', label: 'Guest Directory', icon: Users },
    { id: 'tasks', label: 'Housekeeping Check', icon: ClipboardList },
  ],
  Host: [
    { id: 'pos', label: 'Dining POS Terminal', icon: UtensilsCrossed },
    { id: 'orders', label: 'Active Table Orders', icon: ClipboardList },
    { id: 'menu', label: 'Menu & Stock', icon: Wine },
    { id: 'sales', label: 'Host Shift Total', icon: DollarSign },
  ],
  Barman: [
    { id: 'inventory', label: 'Bar Beverage Stock', icon: Wine },
    { id: 'orders', label: 'Drink Dispense Log', icon: UtensilsCrossed },
    { id: 'transfers', label: 'Stock Requisitions', icon: Package },
    { id: 'reports', label: 'Daily Bar Sales', icon: DollarSign },
  ],
  Cashier: [
    { id: 'billing', label: 'Central Cash Desk', icon: DollarSign },
    { id: 'ledger', label: 'Financial Ledger', icon: ClipboardList },
    { id: 'payroll', label: 'Staff Payroll', icon: Users },
    { id: 'summary', label: 'Daily Fiscal Summary', icon: LayoutDashboard },
  ],
  Store: [
    { id: 'inventory', label: 'Warehouse Stock', icon: Package },
    { id: 'transfers', label: 'Department Dispatch', icon: ShoppingCart },
    { id: 'alerts', label: 'Reorder Alerts', icon: ClipboardList },
  ],
  Purchaser: [
    { id: 'purchases', label: 'Purchase Orders', icon: ShoppingCart },
    { id: 'new_po', label: 'New Procurement', icon: Package },
    { id: 'finance', label: 'Expense Summary', icon: DollarSign },
  ],
  Staff: [
    { id: 'tasks', label: 'Assigned Duties', icon: ClipboardList },
    { id: 'maintenance', label: 'Report Incident', icon: Settings },
    { id: 'attendance', label: 'Shift Check-in', icon: UserCheck },
  ],
  Customer: [
    { id: 'bookings', label: 'My Reservations', icon: CalendarCheck },
    { id: 'room_service', label: 'Order Room Service', icon: UtensilsCrossed },
    { id: 'reviews', label: 'My Reviews', icon: CheckCircle },
  ]
};

export default function Sidebar({ activeTab, onSelectTab, onGoHome, isOpen = false, onClose }) {
  const { user, logout } = useAuth();
  const role = user?.role || 'Customer';
  const navItems = ROLE_NAV_CONFIG[role] || ROLE_NAV_CONFIG['Customer'];

  const handleItemClick = (id) => {
    onSelectTab(id);
    if (onClose) onClose();
  };

  return (
    <aside
      className={`sidebar-drawer ${isOpen ? 'open' : ''}`}
      style={{
        width: '260px',
        background: 'var(--bg-sidebar)',
        borderRight: '1px solid var(--border-light)',
        display: 'flex',
        flexDirection: 'column',
        height: '100%',
        flexShrink: 0,
        zIndex: 100,
        transition: 'background-color 0.25s ease'
      }}
    >
      {/* Brand Header */}
      <div style={{
        padding: '20px 18px',
        borderBottom: '1px solid var(--border-light)',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between'
      }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
          <div style={{
            width: '36px',
            height: '36px',
            borderRadius: '8px',
            background: 'var(--gold-gradient)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            boxShadow: 'var(--gold-glow)'
          }}>
            <span style={{ fontWeight: '800', color: '#090d16', fontSize: '1.05rem' }}>H</span>
          </div>
          <div>
            <div style={{ fontWeight: '700', fontSize: '0.95rem', color: 'var(--text-main)' }}>
              Grand Horizon
            </div>
            <div style={{ fontSize: '0.7rem', color: 'var(--gold-primary)', fontWeight: '600', textTransform: 'uppercase' }}>
              {role} Workspace
            </div>
          </div>
        </div>

        {/* Close Button on Mobile Drawer */}
        {onClose && (
          <button
            onClick={onClose}
            className="mobile-sidebar-close"
            style={{
              background: 'var(--chip-bg)',
              color: 'var(--text-muted)',
              padding: '6px',
              borderRadius: '6px',
              display: 'none',
              alignItems: 'center',
              justifyContent: 'center'
            }}
            aria-label="Close menu"
          >
            <X size={18} />
          </button>
        )}
      </div>

      {/* User Info Card */}
      <div style={{ padding: '14px 18px', background: 'var(--chip-bg)', borderBottom: '1px solid var(--border-light)' }}>
        <div style={{ fontSize: '0.85rem', fontWeight: '600', color: 'var(--text-main)', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>
          {user?.full_name || 'Guest User'}
        </div>
        <div style={{ fontSize: '0.725rem', color: 'var(--text-muted)' }}>
          {user?.position || user?.role || 'Guest'}
        </div>
      </div>

      {/* Navigation Links */}
      <nav style={{ padding: '14px 10px', flex: 1, overflowY: 'auto', display: 'flex', flexDirection: 'column', gap: '4px' }}>
        {navItems.map((item) => {
          const Icon = item.icon;
          const isActive = activeTab === item.id;
          return (
            <button
              key={item.id}
              onClick={() => handleItemClick(item.id)}
              style={{
                display: 'flex',
                alignItems: 'center',
                gap: '12px',
                width: '100%',
                padding: '10px 14px',
                borderRadius: '8px',
                fontSize: '0.85rem',
                fontWeight: isActive ? '700' : '500',
                background: isActive ? 'rgba(212, 175, 55, 0.15)' : 'transparent',
                color: isActive ? 'var(--gold-primary)' : 'var(--text-muted)',
                border: isActive ? '1px solid var(--border-gold)' : '1px solid transparent',
                textAlign: 'left',
                transition: 'all 0.2s ease',
                cursor: 'pointer'
              }}
              onMouseOver={(e) => {
                if (!isActive) {
                  e.currentTarget.style.background = 'var(--chip-bg)';
                  e.currentTarget.style.color = 'var(--text-main)';
                }
              }}
              onMouseOut={(e) => {
                if (!isActive) {
                  e.currentTarget.style.background = 'transparent';
                  e.currentTarget.style.color = 'var(--text-muted)';
                }
              }}
            >
              <Icon size={17} color={isActive ? 'var(--gold-primary)' : 'inherit'} />
              <span>{item.label}</span>
            </button>
          );
        })}
      </nav>

      {/* Footer Actions & Theme Toggle */}
      <div style={{ padding: '14px 12px', borderTop: '1px solid var(--border-light)', display: 'flex', flexDirection: 'column', gap: '8px' }}>
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '0 4px 6px' }}>
          <span style={{ fontSize: '0.775rem', color: 'var(--text-dim)', fontWeight: '600' }}>Appearance</span>
          <ThemeToggle compact={false} showLabel={true} />
        </div>

        <button
          onClick={onGoHome}
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '10px',
            width: '100%',
            padding: '8px 12px',
            borderRadius: '6px',
            background: 'transparent',
            color: 'var(--text-muted)',
            fontSize: '0.825rem',
            fontWeight: '600'
          }}
          className="btn-outline"
        >
          <Home size={15} /> Public Website
        </button>

        <button
          onClick={logout}
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '10px',
            width: '100%',
            padding: '8px 12px',
            borderRadius: '6px',
            background: 'rgba(244, 63, 94, 0.1)',
            color: 'var(--rose-text)',
            border: '1px solid rgba(244, 63, 94, 0.2)',
            fontSize: '0.825rem',
            fontWeight: '600'
          }}
        >
          <LogOut size={15} /> Sign Out
        </button>
      </div>

      <style>{`
        @media (max-width: 900px) {
          .mobile-sidebar-close {
            display: flex !important;
          }
        }
      `}</style>
    </aside>
  );
}
