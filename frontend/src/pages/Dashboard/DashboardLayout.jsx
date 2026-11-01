import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import Sidebar from '../../components/Sidebar';
import RoleBanner from '../../components/RoleBanner';
import ThemeToggle from '../../components/ThemeToggle';
import { Menu, Crown } from 'lucide-react';

import ManagerDashboard from './ManagerDashboard';
import ReceptionistDashboard from './ReceptionistDashboard';
import HostDashboard from './HostDashboard';
import BarmanDashboard from './BarmanDashboard';
import CashierDashboard from './CashierDashboard';
import StoreDashboard from './StoreDashboard';
import PurchaserDashboard from './PurchaserDashboard';
import StaffDashboard from './StaffDashboard';
import CustomerDashboard from './CustomerDashboard';

export default function DashboardLayout({ onNavigate }) {
  const { user } = useAuth();
  const [activeTab, setActiveTab] = useState(null);
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const role = user?.role || 'Customer';

  const renderRoleDashboard = () => {
    switch (role) {
      case 'Manager':
        return <ManagerDashboard activeTab={activeTab || 'overview'} />;
      case 'Receptionist':
        return <ReceptionistDashboard activeTab={activeTab || 'rack'} />;
      case 'Host':
        return <HostDashboard activeTab={activeTab || 'pos'} />;
      case 'Barman':
        return <BarmanDashboard activeTab={activeTab || 'inventory'} />;
      case 'Cashier':
        return <CashierDashboard activeTab={activeTab || 'billing'} />;
      case 'Store':
        return <StoreDashboard activeTab={activeTab || 'inventory'} />;
      case 'Purchaser':
        return <PurchaserDashboard activeTab={activeTab || 'purchases'} />;
      case 'Staff':
        return <StaffDashboard activeTab={activeTab || 'tasks'} />;
      case 'Customer':
      default:
        return <CustomerDashboard activeTab={activeTab || 'bookings'} />;
    }
  };

  return (
    <div style={{ display: 'flex', flexDirection: 'column', height: '100vh', height: '100dvh', overflow: 'hidden' }}>
      {/* Top Fast Role Switcher */}
      <RoleBanner onGoHome={() => onNavigate('home')} />

      {/* Mobile Top Navigation Header (< 900px) */}
      <div className="dashboard-mobile-header">
        <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
          <button
            onClick={() => setSidebarOpen(true)}
            style={{
              background: 'var(--chip-bg)',
              color: 'var(--text-main)',
              border: '1px solid var(--border-light)',
              padding: '8px',
              borderRadius: '8px',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center'
            }}
            aria-label="Open navigation sidebar"
          >
            <Menu size={20} />
          </button>

          <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
            <div style={{
              width: '28px',
              height: '28px',
              borderRadius: '6px',
              background: 'var(--gold-gradient)',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center'
            }}>
              <Crown size={16} color="#090d16" />
            </div>
            <span style={{ fontWeight: '700', fontSize: '0.9rem', color: 'var(--text-main)' }}>
              {role} Workspace
            </span>
          </div>
        </div>

        <ThemeToggle compact={true} />
      </div>

      {/* Main Container */}
      <div style={{ display: 'flex', flex: 1, overflow: 'hidden', position: 'relative' }}>
        {/* Mobile Backdrop Overlay */}
        {sidebarOpen && (
          <div
            className="sidebar-backdrop"
            onClick={() => setSidebarOpen(false)}
            aria-hidden="true"
          />
        )}

        <Sidebar
          isOpen={sidebarOpen}
          onClose={() => setSidebarOpen(false)}
          activeTab={activeTab}
          onSelectTab={(tabId) => setActiveTab(tabId)}
          onGoHome={() => onNavigate('home')}
        />

        <main className="dashboard-main-content">
          <div style={{ maxWidth: '1400px', margin: '0 auto' }}>
            {renderRoleDashboard()}
          </div>
        </main>
      </div>
    </div>
  );
}
