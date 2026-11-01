const API_BASE = '/api';

export async function request(endpoint, options = {}) {
  const token = localStorage.getItem('ehms_token');
  const headers = {
    'Content-Type': 'application/json',
    ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
    ...options.headers,
  };

  const config = {
    ...options,
    headers,
  };

  const response = await fetch(`${API_BASE}${endpoint}`, config);
  const data = await response.json().catch(() => ({}));

  if (!response.ok) {
    const error = new Error(data.message || `Request failed with status ${response.status}`);
    error.status = response.status;
    error.data = data;
    throw error;
  }

  return data;
}

export const api = {
  // Auth
  login: (credentials) => request('/auth/login', { method: 'POST', body: JSON.stringify(credentials) }),
  register: (userData) => request('/auth/register', { method: 'POST', body: JSON.stringify(userData) }),
  getMe: () => request('/auth/me'),
  quickDemoLogin: (role) => request(`/auth/demo-login/${role}`),
  getEmployees: () => request('/auth/employees'),
  createEmployee: (data) => request('/auth/employees', { method: 'POST', body: JSON.stringify(data) }),
  updateEmployee: (id, data) => request(`/auth/employees/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
  deleteEmployee: (id) => request(`/auth/employees/${id}`, { method: 'DELETE' }),

  // Rooms
  getRooms: (params = '') => request(`/rooms${params}`),
  getRoomStats: () => request('/rooms/stats'),
  getRoomById: (id) => request(`/rooms/${id}`),
  createRoom: (data) => request('/rooms', { method: 'POST', body: JSON.stringify(data) }),
  updateRoom: (id, data) => request(`/rooms/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
  deleteRoom: (id) => request(`/rooms/${id}`, { method: 'DELETE' }),

  // Meeting Halls
  getHalls: (params = '') => request(`/halls${params}`),
  createHall: (data) => request('/halls', { method: 'POST', body: JSON.stringify(data) }),
  updateHall: (id, data) => request(`/halls/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
  deleteHall: (id) => request(`/halls/${id}`, { method: 'DELETE' }),

  // Reservations
  getRoomReservations: (params = '') => request(`/reservations/rooms${params}`),
  createRoomReservation: (data) => request('/reservations/rooms', { method: 'POST', body: JSON.stringify(data) }),
  updateRoomReservation: (id, data) => request(`/reservations/rooms/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
  getHallReservations: (params = '') => request(`/reservations/halls${params}`),
  createHallReservation: (data) => request('/reservations/halls', { method: 'POST', body: JSON.stringify(data) }),
  lookupBooking: (codeOrEmail) => request(`/reservations/lookup?${codeOrEmail}`),

  // Restaurant & POS
  getFoods: (params = '') => request(`/restaurant/foods${params}`),
  createFood: (data) => request('/restaurant/foods', { method: 'POST', body: JSON.stringify(data) }),
  updateFood: (id, data) => request(`/restaurant/foods/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
  deleteFood: (id) => request(`/restaurant/foods/${id}`, { method: 'DELETE' }),
  getBeverages: (params = '') => request(`/restaurant/beverages${params}`),
  createBeverage: (data) => request('/restaurant/beverages', { method: 'POST', body: JSON.stringify(data) }),
  updateBeverage: (id, data) => request(`/restaurant/beverages/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
  deleteBeverage: (id) => request(`/restaurant/beverages/${id}`, { method: 'DELETE' }),
  getOrders: (params = '') => request(`/restaurant/orders${params}`),
  createOrder: (data) => request('/restaurant/orders', { method: 'POST', body: JSON.stringify(data) }),
  updateOrderStatus: (id, data) => request(`/restaurant/orders/${id}`, { method: 'PUT', body: JSON.stringify(data) }),

  // Inventory & Store
  getInventory: (params = '') => request(`/inventory${params}`),
  createInventoryItem: (data) => request('/inventory', { method: 'POST', body: JSON.stringify(data) }),
  updateInventoryItem: (id, data) => request(`/inventory/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
  transferStock: (data) => request('/inventory/transfer', { method: 'POST', body: JSON.stringify(data) }),
  getTransfers: () => request('/inventory/transfers'),
  getPurchases: () => request('/inventory/purchases'),
  createPurchase: (data) => request('/inventory/purchases', { method: 'POST', body: JSON.stringify(data) }),

  // Finance
  getTransactions: (params = '') => request(`/finance/transactions${params}`),
  recordTransaction: (data) => request('/finance/transactions', { method: 'POST', body: JSON.stringify(data) }),
  getFinanceSummary: () => request('/finance/summary'),
  paySalary: (data) => request('/finance/pay-salary', { method: 'POST', body: JSON.stringify(data) }),

  // Staff & Tasks
  getTasks: (params = '') => request(`/staff/tasks${params}`),
  createTask: (data) => request('/staff/tasks', { method: 'POST', body: JSON.stringify(data) }),
  updateTask: (id, data) => request(`/staff/tasks/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
  getAttendance: (params = '') => request(`/staff/attendance${params}`),
  markAttendance: (data) => request('/staff/attendance', { method: 'POST', body: JSON.stringify(data) }),

  // Hotel Info & Feedback
  getHotelInfo: () => request('/hotel/info'),
  updateHotelInfo: (data) => request('/hotel/info', { method: 'PUT', body: JSON.stringify(data) }),
  getFeedback: () => request('/hotel/feedback'),
  submitFeedback: (data) => request('/hotel/feedback', { method: 'POST', body: JSON.stringify(data) })
};
