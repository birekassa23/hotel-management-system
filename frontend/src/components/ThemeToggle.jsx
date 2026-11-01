import React from 'react';
import { Sun, Moon } from 'lucide-react';
import { useTheme } from '../context/ThemeContext';

export default function ThemeToggle({ compact = false, showLabel = false, className = '' }) {
  const { theme, toggleTheme, isDark } = useTheme();

  return (
    <button
      onClick={toggleTheme}
      type="button"
      className={`theme-toggle-btn ${className}`}
      title={isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'}
      aria-label={isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'}
      style={{
        display: 'inline-flex',
        alignItems: 'center',
        justifyContent: 'center',
        gap: '8px',
        background: isDark ? 'rgba(255, 255, 255, 0.06)' : 'rgba(15, 23, 42, 0.05)',
        border: '1px solid var(--border-light)',
        color: isDark ? 'var(--gold-primary)' : '#b45309',
        padding: compact ? '8px' : '7px 14px',
        borderRadius: 'var(--radius-full)',
        cursor: 'pointer',
        fontSize: '0.825rem',
        fontWeight: '600',
        transition: 'all 0.25s cubic-bezier(0.16, 1, 0.3, 1)',
        flexShrink: 0
      }}
      onMouseOver={(e) => {
        e.currentTarget.style.borderColor = 'var(--gold-primary)';
        e.currentTarget.style.transform = 'translateY(-1px)';
        e.currentTarget.style.boxShadow = 'var(--gold-glow)';
      }}
      onMouseOut={(e) => {
        e.currentTarget.style.borderColor = 'var(--border-light)';
        e.currentTarget.style.transform = 'translateY(0)';
        e.currentTarget.style.boxShadow = 'none';
      }}
    >
      {isDark ? (
        <Sun size={17} style={{ transition: 'transform 0.4s ease' }} />
      ) : (
        <Moon size={17} style={{ transition: 'transform 0.4s ease' }} />
      )}
      {showLabel && (
        <span style={{ fontSize: '0.8rem', color: 'var(--text-muted)' }}>
          {isDark ? 'Light' : 'Dark'}
        </span>
      )}
    </button>
  );
}
