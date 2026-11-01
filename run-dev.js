const { spawn } = require('child_process');
const path = require('path');

console.log('\x1b[36m%s\x1b[0m', '═════════════════════════════════════════════════════════════');
console.log('\x1b[33m%s\x1b[0m', '      ENTERPRISE HOTEL MANAGEMENT SYSTEM (REACT + EXPRESS)    ');
console.log('\x1b[36m%s\x1b[0m', '═════════════════════════════════════════════════════════════');

const backend = spawn('node', ['server.js'], {
  cwd: path.join(__dirname, 'backend'),
  stdio: 'inherit',
  shell: true,
  env: { ...process.env, PORT: process.env.PORT || 5000 }
});

const frontend = spawn('npx', ['vite', '--host', '0.0.0.0'], {
  cwd: path.join(__dirname, 'frontend'),
  stdio: 'inherit',
  shell: true
});

function cleanup() {
  console.log('\nShutting down dev servers...');
  backend.kill('SIGINT');
  frontend.kill('SIGINT');
  process.exit();
}

process.on('SIGINT', cleanup);
process.on('SIGTERM', cleanup);
