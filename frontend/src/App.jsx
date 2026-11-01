import React, { useState } from 'react';
import { AuthProvider, useAuth } from './context/AuthContext';
import LandingPage from './pages/Landing/LandingPage';
import LoginPage from './pages/Auth/LoginPage';
import DashboardLayout from './pages/Dashboard/DashboardLayout';

function MainApp() {
  const { user } = useAuth();
  const [currentPage, setCurrentPage] = useState('home');

  const handleNavigate = (page) => {
    if (page === 'rooms' || page === 'halls' || page === 'dining') {
      setCurrentPage('home');
      setTimeout(() => {
        const el = document.getElementById(page);
        if (el) el.scrollIntoView({ behavior: 'smooth' });
      }, 100);
      return;
    }
    setCurrentPage(page);
  };

  if (currentPage === 'login') {
    return (
      <LoginPage
        onNavigate={handleNavigate}
        onSuccess={() => setCurrentPage('dashboard')}
      />
    );
  }

  if (currentPage === 'dashboard') {
    return (
      <DashboardLayout
        onNavigate={handleNavigate}
      />
    );
  }

  return (
    <LandingPage
      onNavigate={handleNavigate}
    />
  );
}

export default function App() {
  return (
    <AuthProvider>
      <MainApp />
    </AuthProvider>
  );
}
