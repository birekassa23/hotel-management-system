import React from 'react';
import { useAuth } from '../context/AuthContext';
import { Crown, Calendar, Search, LogIn, LayoutDashboard, User } from 'lucide-react';

export default function Navbar({ onNavigate, onOpenBookingModal, onOpenLookupModal }) {
  const { user } = useAuth();

  return (
    <header style={{
      position: 'sticky',
      top: 0,
      zIndex: 900,
      background: 'rgba(9, 13, 22, 0.85)',
      backdropFilter: 'blur(16px)',
      borderBottom: '1px solid var(--border-light)',
      padding: '14px 28px'
    }}>
      <div style={{
        maxWidth: '1280px',
        margin: '0 auto',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        flexWrap: 'wrap',
        gap: '16px'
      }}>
        {/* Brand */}
        <div 
          onClick={() => onNavigate('home')} 
          style={{ display: 'flex', alignItems: 'center', gap: '12px', cursor: 'pointer' }}
        >
          <div style={{
            width: '42px',
            height: '42px',
            borderRadius: '10px',
            background: 'var(--gold-gradient)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            boxShadow: 'var(--gold-glow)'
          }}>
            <Crown size={22} color="#090d16" strokeWidth={2.5} />
          </div>
          <div>
            <div style={{ fontSize: '1.15rem', fontWeight: '800', letterSpacing: '-0.01em', color: 'var(--text-main)', lineHeight: 1.1 }}>
              EHITMAMACHOCHI
            </div>
            <div style={{ fontSize: '0.7rem', fontWeight: '600', color: 'var(--gold-primary)', letterSpacing: '0.12em', textTransform: 'uppercase' }}>
              Grand Luxury Hotel & Suites
            </div>
          </div>
        </div>

        {/* Navigation Links */}
        <nav style={{ display: 'flex', alignItems: 'center', gap: '22px' }}>
          <button 
            onClick={() => onNavigate('rooms')} 
            style={{ background: 'transparent', color: 'var(--text-muted)', fontWeight: '600', fontSize: '0.9rem', transition: 'color 0.2s' }}
            onMouseOver={(e) => e.target.style.color = 'var(--gold-light)'}
            onMouseOut={(e) => e.target.style.color = 'var(--text-muted)'}
          >
            Rooms & Suites
          </button>
          <button 
            onClick={() => onNavigate('halls')} 
            style={{ background: 'transparent', color: 'var(--text-muted)', fontWeight: '600', fontSize: '0.9rem', transition: 'color 0.2s' }}
            onMouseOver={(e) => e.target.style.color = 'var(--gold-light)'}
            onMouseOut={(e) => e.target.style.color = 'var(--text-muted)'}
          >
            Meeting Halls
          </button>
          <button 
            onClick={() => onNavigate('dining')} 
            style={{ background: 'transparent', color: 'var(--text-muted)', fontWeight: '600', fontSize: '0.9rem', transition: 'color 0.2s' }}
            onMouseOver={(e) => e.target.style.color = 'var(--gold-light)'}
            onMouseOut={(e) => e.target.style.color = 'var(--text-muted)'}
          >
            Dining & Bar
          </button>
          <button 
            onClick={onOpenLookupModal} 
            style={{ 
              background: 'rgba(255, 255, 255, 0.05)', 
              color: 'var(--text-muted)', 
              padding: '6px 12px', 
              borderRadius: '20px', 
              fontSize: '0.85rem', 
              display: 'flex', 
              alignItems: 'center', 
              gap: '6px',
              border: '1px solid var(--border-light)' 
            }}
          >
            <Search size={14} color="var(--gold-primary)" /> Check Booking
          </button>
        </nav>

        {/* Action CTAs */}
        <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
          {user ? (
            <button 
              onClick={() => onNavigate('dashboard')} 
              className="btn btn-outline"
              style={{ padding: '8px 16px', fontSize: '0.85rem' }}
            >
              <LayoutDashboard size={16} color="var(--gold-primary)" />
              <span>{user.role} Portal</span>
            </button>
          ) : (
            <button 
              onClick={() => onNavigate('login')} 
              className="btn btn-outline"
              style={{ padding: '8px 16px', fontSize: '0.85rem' }}
            >
              <LogIn size={16} />
              <span>Staff Login</span>
            </button>
          )}

          <button 
            onClick={onOpenBookingModal} 
            className="btn btn-gold"
            style={{ padding: '9px 18px', fontSize: '0.875rem' }}
          >
            <Calendar size={16} />
            <span>Book Now</span>
          </button>
        </div>
      </div>
    </header>
  );
}
