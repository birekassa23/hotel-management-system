const db = require('./db');
const bcrypt = require('bcryptjs');

function initDatabase() {
  console.log('Initializing SQLite database schema...');

  // Create tables
  db.exec(`
    CREATE TABLE IF NOT EXISTS hotel_info (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      address TEXT,
      town TEXT,
      region TEXT,
      country TEXT,
      phone TEXT,
      email TEXT,
      description TEXT
    );

    CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      username TEXT UNIQUE NOT NULL,
      password TEXT NOT NULL,
      full_name TEXT NOT NULL,
      email TEXT,
      phone TEXT,
      role TEXT NOT NULL,
      position TEXT,
      sex TEXT,
      age INTEGER,
      salary REAL DEFAULT 0,
      is_present TEXT DEFAULT 'yes',
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS rooms (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      room_number TEXT UNIQUE NOT NULL,
      type TEXT NOT NULL,
      price REAL NOT NULL,
      status TEXT DEFAULT 'free',
      capacity INTEGER DEFAULT 2,
      floor INTEGER DEFAULT 1,
      amenities TEXT,
      image_url TEXT,
      description TEXT
    );

    CREATE TABLE IF NOT EXISTS meeting_halls (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT UNIQUE NOT NULL,
      type TEXT NOT NULL,
      capacity INTEGER NOT NULL,
      price REAL NOT NULL,
      status TEXT DEFAULT 'free',
      amenities TEXT,
      image_url TEXT,
      description TEXT
    );

    CREATE TABLE IF NOT EXISTS room_reservations (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      booking_code TEXT UNIQUE NOT NULL,
      customer_name TEXT NOT NULL,
      customer_email TEXT,
      customer_phone TEXT,
      kebele_id TEXT,
      room_id INTEGER,
      room_type TEXT,
      room_price REAL,
      checkin_date TEXT NOT NULL,
      checkout_date TEXT NOT NULL,
      guests_count INTEGER DEFAULT 1,
      status TEXT DEFAULT 'confirmed',
      payment_status TEXT DEFAULT 'unpaid',
      assigned_by TEXT,
      notes TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE SET NULL
    );

    CREATE TABLE IF NOT EXISTS hall_reservations (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      booking_code TEXT UNIQUE NOT NULL,
      customer_name TEXT NOT NULL,
      customer_email TEXT,
      customer_phone TEXT,
      hall_id INTEGER,
      hall_name TEXT,
      hall_price REAL,
      checkin_date TEXT NOT NULL,
      checkout_date TEXT NOT NULL,
      event_type TEXT,
      status TEXT DEFAULT 'confirmed',
      payment_status TEXT DEFAULT 'unpaid',
      assigned_by TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (hall_id) REFERENCES meeting_halls(id) ON DELETE SET NULL
    );

    CREATE TABLE IF NOT EXISTS foods (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      category TEXT NOT NULL,
      price REAL NOT NULL,
      quantity INTEGER DEFAULT 50,
      status TEXT DEFAULT 'available',
      image_url TEXT,
      description TEXT
    );

    CREATE TABLE IF NOT EXISTS beverages (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      category TEXT NOT NULL,
      purchase_price REAL NOT NULL,
      price REAL NOT NULL,
      quantity INTEGER DEFAULT 100,
      unit TEXT DEFAULT 'bottle',
      status TEXT DEFAULT 'available',
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS orders (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      order_code TEXT UNIQUE NOT NULL,
      customer_name TEXT,
      table_number TEXT,
      room_id TEXT,
      host_id INTEGER,
      host_name TEXT,
      total_amount REAL DEFAULT 0,
      status TEXT DEFAULT 'pending',
      payment_method TEXT DEFAULT 'cash',
      payment_status TEXT DEFAULT 'paid',
      reported_date TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS order_items (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      order_id INTEGER NOT NULL,
      item_name TEXT NOT NULL,
      item_type TEXT DEFAULT 'food',
      quantity INTEGER NOT NULL,
      unit_price REAL NOT NULL,
      total_price REAL NOT NULL,
      FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS inventory (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      item_name TEXT UNIQUE NOT NULL,
      category TEXT NOT NULL,
      quantity REAL NOT NULL,
      unit TEXT DEFAULT 'pcs',
      unit_price REAL NOT NULL,
      reorder_level REAL DEFAULT 10,
      status TEXT DEFAULT 'in_stock',
      updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS inventory_transfers (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      from_dept TEXT DEFAULT 'Store',
      to_dept TEXT NOT NULL,
      item_name TEXT NOT NULL,
      quantity REAL NOT NULL,
      unit TEXT,
      transferred_by TEXT,
      notes TEXT,
      transfer_date TEXT DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS purchase_orders (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      po_number TEXT UNIQUE NOT NULL,
      item_name TEXT NOT NULL,
      category TEXT NOT NULL,
      supplier TEXT NOT NULL,
      quantity REAL NOT NULL,
      unit_price REAL NOT NULL,
      total_cost REAL NOT NULL,
      purchaser_name TEXT,
      status TEXT DEFAULT 'received',
      purchase_date TEXT DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS financial_transactions (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      type TEXT NOT NULL,
      category TEXT NOT NULL,
      amount REAL NOT NULL,
      reference_id TEXT,
      provider_name TEXT,
      description TEXT,
      transaction_date TEXT DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS attendance (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      employee_id INTEGER NOT NULL,
      employee_name TEXT NOT NULL,
      role TEXT NOT NULL,
      attendance_date TEXT NOT NULL,
      status TEXT DEFAULT 'present',
      notes TEXT,
      FOREIGN KEY (employee_id) REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS staff_tasks (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      title TEXT NOT NULL,
      assigned_to_id INTEGER,
      assigned_to_name TEXT,
      role TEXT,
      priority TEXT DEFAULT 'medium',
      room_or_area TEXT,
      status TEXT DEFAULT 'pending',
      notes TEXT,
      due_date TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS feedback_comments (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      guest_name TEXT NOT NULL,
      guest_email TEXT,
      rating INTEGER DEFAULT 5,
      comment TEXT NOT NULL,
      status TEXT DEFAULT 'published',
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
  `);

  // Seed Hotel Info
  const hotelCount = db.get('SELECT COUNT(*) as count FROM hotel_info').count;
  if (hotelCount === 0) {
    db.run(
      `INSERT INTO hotel_info (name, address, town, region, country, phone, email, description)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        'Grand Horizon Luxury Hotel & Suites',
        '742 Evergreen Boulevard, Central District',
        'Metropolis',
        'Capital Region',
        'Global',
        '+1 (555) 019-2834',
        'contact@grandhorizonhotel.com',
        'Welcome to Grand Horizon Hotel & Suites — a sanctuary of modern luxury, world-class hospitality, state-of-the-art conference facilities, and fine dining.'
      ]
    );
  }

  // Seed Users / Staff Accounts
  const userCount = db.get('SELECT COUNT(*) as count FROM users').count;
  if (userCount === 0) {
    const salt = bcrypt.genSaltSync(10);
    const hash = bcrypt.hashSync('password123', salt);

    const defaultUsers = [
      ['manager', hash, 'Alexander Wright (General Manager)', 'manager@hotel.com', '+1 (555) 234-5678', 'Manager', 'General Manager', 'Male', 38, 6500, 'yes'],
      ['receptionist', hash, 'Sophia Bennett (Head Receptionist)', 'reception@hotel.com', '+1 (555) 345-6789', 'Receptionist', 'Front Desk Lead', 'Female', 28, 3800, 'yes'],
      ['cashier', hash, 'David Miller (Chief Cashier)', 'cashier@hotel.com', '+1 (555) 456-7890', 'Cashier', 'Finance Lead', 'Male', 33, 4200, 'yes'],
      ['host', hash, 'Emma Davis (Dining Host)', 'host@hotel.com', '+1 (555) 567-8901', 'Host', 'Lead Host / Server', 'Female', 25, 2900, 'yes'],
      ['barman', hash, 'Marcus Vance (Master Mixologist)', 'barman@hotel.com', '+1 (555) 678-9012', 'Barman', 'Head Barman', 'Male', 30, 3200, 'yes'],
      ['store', hash, 'Robert Hayes (Warehouse Keeper)', 'store@hotel.com', '+1 (555) 789-0123', 'Store', 'Warehouse Custodian', 'Male', 36, 3400, 'yes'],
      ['purchaser', hash, 'Jennifer Cole (Procurement Officer)', 'purchaser@hotel.com', '+1 (555) 890-1234', 'Purchaser', 'Supply Chain Purchaser', 'Female', 32, 4000, 'yes'],
      ['staff', hash, 'Michael Turner (Housekeeping & Maintenance)', 'staff@hotel.com', '+1 (555) 901-2345', 'Staff', 'Maintenance & Housekeeper', 'Male', 27, 2600, 'yes'],
      ['guest', hash, 'Sarah Jenkins (Valued Guest)', 'guest@client.com', '+1 (555) 112-2334', 'Customer', 'Guest', 'Female', 29, 0, 'yes']
    ];

    for (const u of defaultUsers) {
      db.run(
        `INSERT INTO users (username, password, full_name, email, phone, role, position, sex, age, salary, is_present)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
        u
      );
    }
  }

  // Seed Rooms
  const roomCount = db.get('SELECT COUNT(*) as count FROM rooms').count;
  if (roomCount === 0) {
    const initialRooms = [
      ['101', 'Single Deluxe', 150, 'free', 1, 1, 'King Bed, High-speed Wi-Fi, Smart TV, Hot Rain Shower, City View', 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=800&q=80', 'Cozy single room tailored for business travelers and solo explorers.'],
      ['102', 'Single Deluxe', 150, 'occupied', 1, 1, 'King Bed, High-speed Wi-Fi, Smart TV, Hot Rain Shower, Garden View', 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=800&q=80', 'Peaceful single room facing the tranquil interior garden courtyard.'],
      ['201', 'Double Executive', 240, 'free', 2, 2, '2 Queen Beds, Private Balcony, Ergonomic Workspace, Mini Bar, Climate Control', 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80', 'Spacious executive double room equipped with top-tier comfort and scenic balcony.'],
      ['202', 'Double Executive', 240, 'reserved', 2, 2, '2 Queen Beds, Plush Bathrobes, Smart Lighting, Espresso Bar', 'https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=800&q=80', 'Elegant double room tailored for couples and executive partners.'],
      ['301', 'Deluxe Premium', 350, 'free', 3, 3, 'Master King Bed, Living Lounge, Jacuzzi Tub, Panoramic Skyline View', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80', 'Deluxe premium accommodation boasting expansive floor space and bespoke furnishings.'],
      ['302', 'Deluxe Premium', 350, 'free', 3, 3, 'King Bed, Plush Sofa Lounge, Rain Shower, Breakfast Buffet Included', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=800&q=80', 'Unmatched comfort paired with complimentary personalized concierge services.'],
      ['401', 'Presidential VIP Suite', 650, 'free', 4, 4, 'Dual Master Bedrooms, Private Kitchenette, Dining Area, Butler Service, 4K Cinema TV', 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=800&q=80', 'The pinnacle of luxury living, fit for dignitaries and high-profile delegations.'],
      ['402', 'Royal Penthouse Suite', 850, 'free', 4, 4, 'Penthouse Terrace, Private Bar, Heated Whirlpool, 360 Degree Views', 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=800&q=80', 'Exclusive top-floor royal penthouse with private elevator access and terrace.'],
    ];

    for (const r of initialRooms) {
      db.run(
        `INSERT INTO rooms (room_number, type, price, status, capacity, floor, amenities, image_url, description)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
        r
      );
    }
  }

  // Seed Meeting Halls
  const hallCount = db.get('SELECT COUNT(*) as count FROM meeting_halls').count;
  if (hallCount === 0) {
    const initialHalls = [
      ['Grand Crystal Ballroom', 'Banquet & Gala', 500, 2500, 'free', '4K Laser Projector, Surround Audio System, Stage & Podium, Intelligent Ambient Lighting, Banquet Catering', 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80', 'Spectacular ballroom ideal for grand galas, award ceremonies, and international conferences.'],
      ['Metropolitan Conference Hall', 'Conference', 200, 1400, 'free', 'Dual HD Displays, Translation Booths, Video Teleconference, Dedicated High-Speed Fiber', 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&w=800&q=80', 'State-of-the-art conference venue with flexible theatre or classroom seating layouts.'],
      ['Executive Boardroom & Suites', 'Executive Boardroom', 30, 600, 'free', 'Leather Ergonomic Chairs, Interactive Smart Board, Video Teleconference, Coffee Bar', 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80', 'Sophisticated boardroom for high-stakes corporate meetings, executive retreats, and partner sessions.'],
      ['Summit Multipurpose Hall', 'Medium Seminar', 90, 850, 'free', 'Wireless Microphones, Podium, Configurable Workshop Tables, Acoustic Paneling', 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?auto=format&fit=crop&w=800&q=80', 'Versatile multi-purpose hall for seminars, trainings, and collaborative workshops.']
    ];

    for (const h of initialHalls) {
      db.run(
        `INSERT INTO meeting_halls (name, type, capacity, price, status, amenities, image_url, description)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
        h
      );
    }
  }

  // Seed Foods
  const foodCount = db.get('SELECT COUNT(*) as count FROM foods').count;
  if (foodCount === 0) {
    const initialFoods = [
      ['Prime Grilled Ribeye Steak', 'main', 42, 60, 'available', 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80', 'Dry-aged prime steak served with herb roasted potatoes, asparagus, and red wine reduction.'],
      ['Pan-Seared Atlantic Salmon', 'main', 38, 50, 'available', 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?auto=format&fit=crop&w=600&q=80', 'Crispy skin fillet served with garlic saffron risotto, lemon butter glaze, and broccolini.'],
      ['Artisan Truffle Beef Burger', 'fast_food', 24, 70, 'available', 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=600&q=80', 'Wagyu beef patty, black truffle aioli, aged gruyère, caramelized onions, and brioche bun.'],
      ['Crispy Chicken Club Sandwich', 'fast_food', 20, 65, 'available', 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=600&q=80', 'Triple-layer toasted sandwich with herb grilled chicken breast, smoked bacon, and seasoned fries.'],
      ['Fettuccine Alfredo with Wild Mushrooms', 'main', 26, 55, 'available', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80', 'Handmade ribbon pasta tossed in rich parmesan cream with sautéed porcini and shaved truffle.'],
      ['Stone-Baked Margherita Pizza', 'fast_food', 22, 60, 'available', 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=600&q=80', 'San Marzano tomato base, fresh buffalo mozzarella, aromatic basil, and extra virgin olive oil.'],
      ['Classic Caesar Salad with Herb Croutons', 'appetizer', 18, 50, 'available', 'https://images.unsplash.com/photo-1550304943-4f24f54ddde9?auto=format&fit=crop&w=600&q=80', 'Crisp romaine lettuce, aged parmigiano-reggiano, garlic herb croutons, and house Caesar dressing.'],
      ['Authentic Espresso Tiramisu', 'dessert', 14, 45, 'available', 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&w=600&q=80', 'Classic Italian savoiardi soaked in artisan espresso with layered mascarpone and Belgian cocoa.']
    ];

    for (const f of initialFoods) {
      db.run(
        `INSERT INTO foods (name, category, price, quantity, status, image_url, description)
         VALUES (?, ?, ?, ?, ?, ?, ?)`,
        f
      );
    }
  }

  // Seed Beverages
  const bevCount = db.get('SELECT COUNT(*) as count FROM beverages').count;
  if (bevCount === 0) {
    const initialBevs = [
      ['Craft IPA Draught Beer', 'beer', 4, 9, 240, 'bottle', 'available'],
      ['Premium Pilsner Lager', 'beer', 3.5, 8, 200, 'bottle', 'available'],
      ['Cabernet Sauvignon Reserve', 'wine', 25, 65, 60, 'bottle', 'available'],
      ['Chardonnay Blanc Reserve', 'wine', 22, 58, 50, 'bottle', 'available'],
      ['Old Fashioned Cocktail', 'cocktail', 6, 16, 120, 'glass', 'available'],
      ['Single Malt Scotch Whisky', 'spirit', 45, 120, 30, 'bottle', 'available'],
      ['Artisan Roasted Espresso Coffee', 'hot_beverage', 1.5, 5, 400, 'cup', 'available'],
      ['Fresh Pressed Orange & Mango Juice', 'soft_drink', 2, 6, 120, 'glass', 'available'],
      ['Sparkling Mineral Water', 'soft_drink', 1.5, 4, 300, 'bottle', 'available'],
      ['Classic Iced Tea with Lemon', 'soft_drink', 1.5, 4.5, 350, 'glass', 'available']
    ];

    for (const b of initialBevs) {
      db.run(
        `INSERT INTO beverages (name, category, purchase_price, price, quantity, unit, status)
         VALUES (?, ?, ?, ?, ?, ?, ?)`,
        b
      );
    }
  }

  // Seed Inventory
  const invCount = db.get('SELECT COUNT(*) as count FROM inventory').count;
  if (invCount === 0) {
    const initialInventory = [
      ['Premium Cotton Bed Sheet Sets', 'linens', 150, 'sets', 45, 20, 'in_stock'],
      ['Luxury Egyptian Towel Sets', 'linens', 200, 'sets', 25, 30, 'in_stock'],
      ['Artisan Roasted Coffee Beans', 'beverage', 50, 'kg', 22, 10, 'in_stock'],
      ['Pure Olive Cooking Oil', 'food_ingredient', 40, 'liters', 18, 10, 'in_stock'],
      ['Organic Pastry Flour', 'food_ingredient', 60, 'kg', 8, 15, 'in_stock'],
      ['Guest Botanical Toiletries Kit', 'amenities', 350, 'kits', 5, 50, 'in_stock'],
      ['RFID Electronic Hotel Keycards', 'office', 400, 'pcs', 2, 80, 'in_stock'],
      ['Premium Craft Beer Cases', 'beverage', 30, 'cases', 85, 10, 'in_stock']
    ];

    for (const inv of initialInventory) {
      db.run(
        `INSERT INTO inventory (item_name, category, quantity, unit, unit_price, reorder_level, status)
         VALUES (?, ?, ?, ?, ?, ?, ?)`,
        inv
      );
    }
  }

  // Seed sample reservations
  const resCount = db.get('SELECT COUNT(*) as count FROM room_reservations').count;
  if (resCount === 0) {
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date(Date.now() + 86400000 * 2).toISOString().split('T')[0];
    db.run(
      `INSERT INTO room_reservations (booking_code, customer_name, customer_email, customer_phone, kebele_id, room_id, room_type, room_price, checkin_date, checkout_date, guests_count, status, payment_status, assigned_by)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      ['RES-1029', 'James Wilson', 'james.wilson@example.com', '+1 (555) 782-9900', 'ID-88219', 2, 'Single Deluxe', 150, today, tomorrow, 1, 'checked_in', 'paid', 'Sophia Bennett']
    );
    db.run(
      `INSERT INTO room_reservations (booking_code, customer_name, customer_email, customer_phone, kebele_id, room_id, room_type, room_price, checkin_date, checkout_date, guests_count, status, payment_status, assigned_by)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      ['RES-1030', 'Dr. Emily Carter', 'emily.carter@hospital.org', '+1 (555) 892-4455', 'ID-99124', 4, 'Double Executive', 240, today, tomorrow, 2, 'confirmed', 'deposit', 'Sophia Bennett']
    );
  }

  // Seed sample orders
  const ordCount = db.get('SELECT COUNT(*) as count FROM orders').count;
  if (ordCount === 0) {
    const today = new Date().toISOString().split('T')[0];
    const ord = db.run(
      `INSERT INTO orders (order_code, customer_name, table_number, room_id, host_id, host_name, total_amount, status, payment_method, payment_status, reported_date)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      ['ORD-501', 'Table 4 (VIP Terrace)', 'T-04', 'None', 4, 'Emma Davis', 104, 'served', 'cash', 'paid', today]
    );

    const orderId = ord.lastInsertRowid;
    db.run(
      `INSERT INTO order_items (order_id, item_name, item_type, quantity, unit_price, total_price)
       VALUES (?, ?, ?, ?, ?, ?)`,
      [orderId, 'Prime Grilled Ribeye Steak', 'food', 2, 42, 84]
    );
    db.run(
      `INSERT INTO order_items (order_id, item_name, item_type, quantity, unit_price, total_price)
       VALUES (?, ?, ?, ?, ?, ?)`,
      [orderId, 'Premium Pilsner Lager', 'beverage', 2, 8, 16]
    );
    db.run(
      `INSERT INTO order_items (order_id, item_name, item_type, quantity, unit_price, total_price)
       VALUES (?, ?, ?, ?, ?, ?)`,
      [orderId, 'Sparkling Mineral Water', 'beverage', 1, 4, 4]
    );
  }

  // Seed sample financial transactions
  const finCount = db.get('SELECT COUNT(*) as count FROM financial_transactions').count;
  if (finCount === 0) {
    const today = new Date().toISOString().split('T')[0];
    db.run(
      `INSERT INTO financial_transactions (type, category, amount, reference_id, provider_name, description, transaction_date)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      ['income', 'room_booking', 450, 'RES-1029', 'Sophia Bennett', 'Room 102 accommodation settlement', today]
    );
    db.run(
      `INSERT INTO financial_transactions (type, category, amount, reference_id, provider_name, description, transaction_date)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      ['income', 'restaurant_bar', 104, 'ORD-501', 'Emma Davis', 'Table 4 dining bill settled', today]
    );
    db.run(
      `INSERT INTO financial_transactions (type, category, amount, reference_id, provider_name, description, transaction_date)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      ['expense', 'inventory_purchase', 320, 'PO-201', 'Jennifer Cole', 'Procured organic kitchen produce and linens', today]
    );
  }

  // Seed sample attendance
  const attCount = db.get('SELECT COUNT(*) as count FROM attendance').count;
  if (attCount === 0) {
    const today = new Date().toISOString().split('T')[0];
    const users = db.all('SELECT id, full_name, role FROM users');
    for (const u of users) {
      db.run(
        `INSERT INTO attendance (employee_id, employee_name, role, attendance_date, status, notes)
         VALUES (?, ?, ?, ?, ?, ?)`,
        [u.id, u.full_name, u.role, today, 'present', 'On time shift check-in']
      );
    }
  }

  // Seed sample staff tasks
  const taskCount = db.get('SELECT COUNT(*) as count FROM staff_tasks').count;
  if (taskCount === 0) {
    db.run(
      `INSERT INTO staff_tasks (title, assigned_to_name, role, priority, room_or_area, status, notes, due_date)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
      ['Deep Sanitization & Turndown', 'Michael Turner', 'Staff', 'high', 'Room 101', 'in_progress', 'Prepare for VIP arrival this afternoon', 'Today, 2:00 PM']
    );
    db.run(
      `INSERT INTO staff_tasks (title, assigned_to_name, role, priority, room_or_area, status, notes, due_date)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
      ['Acoustic Setup & HVAC Inspection', 'Michael Turner', 'Staff', 'medium', 'Grand Crystal Ballroom', 'pending', 'Routine quarterly conference hall inspection', 'Tomorrow, 10:00 AM']
    );
  }

  // Seed feedback comments
  const feedbackCount = db.get('SELECT COUNT(*) as count FROM feedback_comments').count;
  if (feedbackCount === 0) {
    db.run(
      `INSERT INTO feedback_comments (guest_name, guest_email, rating, comment, status)
       VALUES (?, ?, ?, ?, ?)`,
      ['Marcus Aurelius', 'marcus@globaltravel.com', 5, 'Exceptional experience! The penthouse skyline view and dining services exceeded all our expectations.', 'published']
    );
    db.run(
      `INSERT INTO feedback_comments (guest_name, guest_email, rating, comment, status)
       VALUES (?, ?, ?, ?, ?)`,
      ['Elena Rossi', 'elena.rossi@travel.it', 5, 'World-class hospitality, immaculate suites, and great conference facilities. Will visit again!', 'published']
    );
  }

  console.log('Database initialized and seeded successfully.');
}

module.exports = { initDatabase };
