require('dotenv').config();
const http = require('http');
const app = require('./app');
const { initDatabase } = require('./config/initDb');

const PORT = process.env.PORT || 5000;

try {
  // Initialize and seed database if not already done
  initDatabase();

  const server = http.createServer(app);

  server.listen(PORT, '0.0.0.0', () => {
    console.log('\x1b[32m%s\x1b[0m', `✓ Hotel Management API Server running on http://localhost:${PORT}`);
    console.log('\x1b[36m%s\x1b[0m', `✓ Health check available at http://localhost:${PORT}/api/health`);
  });
} catch (error) {
  console.error('\x1b[31mFailed to start server:\x1b[0m', error);
  process.exit(1);
}
