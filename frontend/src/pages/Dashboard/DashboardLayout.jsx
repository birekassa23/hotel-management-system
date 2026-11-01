import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import Sidebar from '../../components/Sidebar';
import RoleBanner from '../../components/RoleBanner';

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
    <div style={{ display: 'flex', flexDirection: 'column', height: '100vh', overflow: 'hidden' }}>
      {/* Top Fast Role Switcher */}
      <RoleBanner onGoHome={() => onNavigate('home')} />

      {/* Main Container */}
      <div style={{ display: 'flex', flex: 1, overflow: 'hidden' }}>
        <Sidebar
          activeTab={activeTab}
          onSelectTab={(tabId) => setActiveTab(tabId)}
          onGoHome={() => onNavigate('home')}
        />

        <main style={{
          flex: 1,
          padding: '28px 32px',
          overflowY: 'auto',
          background: 'var(--bg-dark)'
        }}>
          <div style={{ maxWidth: '1400px', margin: '0 auto' }}>
            {renderRoleDashboard()}
          </div>
        </main>
      </div>
    </div>
  );
}
