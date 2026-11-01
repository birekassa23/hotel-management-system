import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import ThemeToggle from './ThemeToggle';
import { Crown, Calendar, Search, LogIn, LayoutDashboard, Menu, X, Sparkles } from 'lucide-react';

export default function Navbar({ onNavigate, onOpenBookingModal, onOpenLookupModal }) {
  const { user } = useAuth();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const handleNavClick = (page) => {
    setMobileMenuOpen(false);
    onNavigate(page);
  };

  const handleBookingClick = () => {
    setMobileMenuOpen(false);
    onOpenBookingModal();
  };

  const handleLookupClick = () => {
    setMobileMenuOpen(false);
    onOpenLookupModal();
  };

  return (
    <header style={{
      position: 'sticky',
      top: 0,
      zIndex: 900,
      background: 'var(--bg-nav)',
      backdropFilter: 'blur(16px)',
      WebkitBackdropFilter: 'blur(16px)',
      borderBottom: '1px solid var(--border-light)',
      padding: '12px 20px',
      transition: 'background-color 0.25s ease'
    }}>
      <div style={{
        maxWidth: '1280px',
        margin: '0 auto',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        gap: '12px'
      }}>
        {/* Brand */}
        <div 
          onClick={() => handleNavClick('home')} 
          style={{ display: 'flex', alignItems: 'center', gap: '10px', cursor: 'pointer', flexShrink: 0 }}
        >
          <div style={{
            width: '38px',
            height: '38px',
            borderRadius: '10px',
            background: 'var(--gold-gradient)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            boxShadow: 'var(--gold-glow)'
          }}>
            <Crown size={20} color="#090d16" strokeWidth={2.5} />
          </div>
          <div>
            <div style={{ fontSize: '1.05rem', fontWeight: '800', letterSpacing: '-0.01em', color: 'var(--text-main)', lineHeight: 1.1 }}>
              GRAND HORIZON
            </div>
            <div style={{ fontSize: '0.65rem', fontWeight: '600', color: 'var(--gold-primary)', letterSpacing: '0.12em', textTransform: 'uppercase' }}>
              Luxury Hotel & Suites
            </div>
          </div>
        </div>

        {/* Desktop Navigation Links */}
        <nav className="desktop-nav" style={{ display: 'flex', alignItems: 'center', gap: '20px' }}>
          <button 
            onClick={() => handleNavClick('rooms')} 
            style={{ background: 'transparent', color: 'var(--text-muted)', fontWeight: '600', fontSize: '0.875rem', transition: 'color 0.2s' }}
            onMouseOver={(e) => e.target.style.color = 'var(--gold-primary)'}
            onMouseOut={(e) => e.target.style.color = 'var(--text-muted)'}
          >
            Rooms & Suites
          </button>
          <button 
            onClick={() => handleNavClick('halls')} 
            style={{ background: 'transparent', color: 'var(--text-muted)', fontWeight: '600', fontSize: '0.875rem', transition: 'color 0.2s' }}
            onMouseOver={(e) => e.target.style.color = 'var(--gold-primary)'}
            onMouseOut={(e) => e.target.style.color = 'var(--text-muted)'}
          >
            Meeting Halls
          </button>
          <button 
            onClick={() => handleNavClick('dining')} 
            style={{ background: 'transparent', color: 'var(--text-muted)', fontWeight: '600', fontSize: '0.875rem', transition: 'color 0.2s' }}
            onMouseOver={(e) => e.target.style.color = 'var(--gold-primary)'}
            onMouseOut={(e) => e.target.style.color = 'var(--text-muted)'}
          >
            Dining & Bar
          </button>
          <button 
            onClick={handleLookupClick} 
            style={{ 
              background: 'var(--chip-bg)', 
              color: 'var(--text-muted)', 
              padding: '6px 12px', 
              borderRadius: '20px', 
              fontSize: '0.825rem', 
              display: 'flex', 
              alignItems: 'center', 
              gap: '6px',
              border: '1px solid var(--border-light)',
              transition: 'all 0.2s ease'
            }}
          >
            <Search size={14} color="var(--gold-primary)" /> Check Booking
          </button>
        </nav>

        {/* Desktop Action CTAs & Theme Toggle */}
        <div className="desktop-actions" style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
          <ThemeToggle compact={true} />

          {user ? (
            <button 
              onClick={() => handleNavClick('dashboard')} 
              className="btn btn-outline btn-sm"
            >
              <LayoutDashboard size={15} color="var(--gold-primary)" />
              <span>{user.role} Portal</span>
            </button>
          ) : (
            <button 
              onClick={() => handleNavClick('login')} 
              className="btn btn-outline btn-sm"
            >
              <LogIn size={15} />
              <span>Staff Login</span>
            </button>
          )}

          <button 
            onClick={handleBookingClick} 
            className="btn btn-gold btn-sm"
          >
            <Calendar size={15} />
            <span>Book Now</span>
          </button>
        </div>

        {/* Mobile Action Controls (< 900px) */}
        <div className="mobile-nav-toggle" style={{ display: 'none', alignItems: 'center', gap: '8px' }}>
          <ThemeToggle compact={true} />
          
          <button
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
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
            aria-label="Toggle navigation menu"
          >
            {mobileMenuOpen ? <X size={20} /> : <Menu size={20} />}
          </button>
        </div>
      </div>

      {/* Mobile Dropdown Menu Drawer */}
      {mobileMenuOpen && (
        <div style={{
          marginTop: '12px',
          padding: '16px',
          background: 'var(--bg-surface)',
          border: '1px solid var(--border-light)',
          borderRadius: 'var(--radius-md)',
          boxShadow: 'var(--shadow-lg)',
          display: 'flex',
          flexDirection: 'column',
          gap: '10px',
          animation: 'slideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1)'
        }}>
          <button
            onClick={() => handleNavClick('rooms')}
            style={{
              textAlign: 'left',
              padding: '10px 14px',
              borderRadius: '6px',
              background: 'var(--chip-bg)',
              color: 'var(--text-main)',
              fontWeight: '600',
              fontSize: '0.9rem'
            }}
          >
            Rooms & Suites
          </button>

          <button
            onClick={() => handleNavClick('halls')}
            style={{
              textAlign: 'left',
              padding: '10px 14px',
              borderRadius: '6px',
              background: 'var(--chip-bg)',
              color: 'var(--text-main)',
              fontWeight: '600',
              fontSize: '0.9rem'
            }}
          >
            Meeting Halls
          </button>

          <button
            onClick={() => handleNavClick('dining')}
            style={{
              textAlign: 'left',
              padding: '10px 14px',
              borderRadius: '6px',
              background: 'var(--chip-bg)',
              color: 'var(--text-main)',
              fontWeight: '600',
              fontSize: '0.9rem'
            }}
          >
            Dining & Bar
          </button>

          <button
            onClick={handleLookupClick}
            style={{
              textAlign: 'left',
              padding: '10px 14px',
              borderRadius: '6px',
              background: 'var(--chip-bg)',
              color: 'var(--gold-primary)',
              fontWeight: '600',
              fontSize: '0.9rem',
              display: 'flex',
              alignItems: 'center',
              gap: '8px'
            }}
          >
            <Search size={16} /> Check Booking Status
          </button>

          <hr style={{ border: 'none', borderTop: '1px solid var(--border-light)', margin: '6px 0' }} />

          {user ? (
            <button
              onClick={() => handleNavClick('dashboard')}
              className="btn btn-outline"
              style={{ width: '100%', justifyContent: 'center' }}
            >
              <LayoutDashboard size={16} color="var(--gold-primary)" />
              <span>Go to {user.role} Dashboard</span>
            </button>
          ) : (
            <button
              onClick={() => handleNavClick('login')}
              className="btn btn-outline"
              style={{ width: '100%', justifyContent: 'center' }}
            >
              <LogIn size={16} />
              <span>Staff Login</span>
            </button>
          )}

          <button
            onClick={handleBookingClick}
            className="btn btn-gold"
            style={{ width: '100%', justifyContent: 'center' }}
          >
            <Calendar size={16} />
            <span>Book Now</span>
          </button>
        </div>
      )}

      {/* Style for navbar desktop vs mobile */}
      <style>{`
        @media (max-width: 900px) {
          .desktop-nav, .desktop-actions {
            display: none !important;
          }
          .mobile-nav-toggle {
            display: flex !important;
          }
        }
      `}</style>
    </header>
  );
}
