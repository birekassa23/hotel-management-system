const jwt = require('jsonwebtoken');
const db = require('../config/db');

const JWT_SECRET = process.env.JWT_SECRET || 'ehitmamachochi_secret_key_2026';

function verifyToken(req, res, next) {
  const authHeader = req.headers['authorization'];
  if (!authHeader || !authHeader.startsWith('Bearer ')) {
    return res.status(401).json({ message: 'Authorization token missing or invalid' });
  }

  const token = authHeader.split(' ')[1];
  try {
    const decoded = jwt.verify(token, JWT_SECRET);
    const user = db.get('SELECT id, username, full_name, email, phone, role, position, is_present FROM users WHERE id = ?', [decoded.id]);
    if (!user) {
      return res.status(401).json({ message: 'User account not found' });
    }

    if (user.is_present !== 'yes' && user.role !== 'Customer') {
      return res.status(403).json({ message: 'Access denied: Marked as absent in attendance' });
    }

    req.user = user;
    next();
  } catch (err) {
    return res.status(401).json({ message: 'Token expired or invalid', error: err.message });
  }
}

function requireRoles(...roles) {
  return (req, res, next) => {
    if (!req.user) {
      return res.status(401).json({ message: 'Authentication required' });
    }

    // Manager has super-admin access to all endpoints
    if (req.user.role === 'Manager') {
      return next();
    }

    if (roles.includes(req.user.role)) {
      return next();
    }

    return res.status(403).json({
      message: `Access forbidden: requires one of [${roles.join(', ')}] role`
    });
  };
}

module.exports = {
  verifyToken,
  requireRoles,
  JWT_SECRET
};
