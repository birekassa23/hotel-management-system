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
      type TEXT NOT NULL, -- 'income' or 'expense'
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
        'Ehitmamachochi Grand Luxury Hotel',
        'Ayermeda above post Kembridge Academy',
        'Teppi',
        'South West',
        'Ethiopia',
        '+251 904 9314',
        'contact@ehitmamachochihotel.com',
        'Welcome to Ehitmamachochi Hotel - a sanctuary of luxury, authentic Ethiopian hospitality, premier conference facilities, and world-class dining.'
      ]
    );
  }

  // Seed Users / Staff Accounts
  const userCount = db.get('SELECT COUNT(*) as count FROM users').count;
  if (userCount === 0) {
    const salt = bcrypt.genSaltSync(10);
    const hash = bcrypt.hashSync('password123', salt);

    const defaultUsers = [
      ['manager', hash, 'Dawit Alemu (General Manager)', 'manager@ehitmamachochi.com', '+251 911 234567', 'Manager', 'General Manager', 'Male', 38, 45000, 'yes'],
      ['receptionist', hash, 'Bethlehem Tadesse (Head Receptionist)', 'reception@ehitmamachochi.com', '+251 912 345678', 'Receptionist', 'Front Desk Lead', 'Female', 27, 18000, 'yes'],
      ['cashier', hash, 'Yared Bekele (Senior Cashier)', 'cashier@ehitmamachochi.com', '+251 913 456789', 'Cashier', 'Chief Cashier', 'Male', 32, 22000, 'yes'],
      ['host', hash, 'Hiwot Haile (Dining Host & Server)', 'host@ehitmamachochi.com', '+251 914 567890', 'Host', 'Lead Host / Waiter', 'Female', 24, 14000, 'yes'],
      ['barman', hash, 'Solomon Kebede (Master Barman)', 'barman@ehitmamachochi.com', '+251 915 678901', 'Barman', 'Head Mixologist', 'Male', 29, 16000, 'yes'],
      ['store', hash, 'Mesfin Negash (Store Keeper)', 'store@ehitmamachochi.com', '+251 916 789012', 'Store', 'Warehouse Custodian', 'Male', 35, 17500, 'yes'],
      ['purchaser', hash, 'Tigist Worku (Procurement Officer)', 'purchaser@ehitmamachochi.com', '+251 917 890123', 'Purchaser', 'Supply Chain Purchaser', 'Female', 31, 20000, 'yes'],
      ['staff', hash, 'Abebe Desta (Housekeeping & Maintenance)', 'staff@ehitmamachochi.com', '+251 918 901234', 'Staff', 'Maintenance & Housekeeper', 'Male', 26, 12000, 'yes'],
      ['guest', hash, 'Helen Tesfaye (Valued Guest)', 'guest@client.com', '+251 920 112233', 'Customer', 'Guest', 'Female', 29, 0, 'yes']
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
      ['101', 'Single Deluxe', 1200, 'free', 1, 1, 'King Bed, High-speed Wi-Fi, Smart TV, Hot Shower, City View', 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=800&q=80', 'Cozy single room tailored for business solo travelers.'],
      ['102', 'Single Deluxe', 1200, 'occupied', 1, 1, 'King Bed, High-speed Wi-Fi, Smart TV, Hot Shower, Garden View', 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=800&q=80', 'Quiet single room facing the tranquil interior courtyard.'],
      ['201', 'Double Executive', 1900, 'free', 2, 2, '2 Queen Beds, Balcony, Workspace Desk, Mini Bar, Air Conditioning', 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80', 'Spacious double room equipped with top-tier comfort and scenic balcony.'],
      ['202', 'Double Executive', 1900, 'reserved', 2, 2, '2 Queen Beds, Luxury Bathrobe, Smart Lighting, Espresso Bar', 'https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=800&q=80', 'Elegant double room tailored for couples and executive partners.'],
      ['301', 'Deluxe Premium', 2600, 'free', 3, 3, 'Master King Bed, Living Lounge, Jacuzzi Tub, Panoramic Mountain View', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80', 'Deluxe premium accommodation boasting expansive floor space.'],
      ['302', 'Deluxe Premium', 2600, 'free', 3, 3, 'King Bed, Plush Sofa Lounge, Rain Shower, Breakfast Buffet Included', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=800&q=80', 'Unmatched comfort paired with complimentary bespoke services.'],
      ['401', 'Presidential VIP Suite', 4800, 'free', 4, 4, 'Dual Master Bedrooms, Private Kitchenette, Dining Area, Butler Service, 4K Cinema TV', 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=800&q=80', 'The pinnacle of luxury in South West Ethiopia, fit for heads of state and dignitaries.'],
      ['402', 'Royal Penthouse Suite', 5500, 'free', 4, 4, 'Penthouse Terrace, Private Bar, Heated Whirlpool, 360 Degree Views', 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=800&q=80', 'Top-floor royal suite with exclusive private access and rooftop lounge.']
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
      ['Kembridge Grand Ballroom', 'Banquet & Gala', 450, 18000, 'free', '4K Laser Projector, Surround Sound, Stage & Podium, Ambient Stage Lighting, Banquet Catering', 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80', 'Massive banquet hall ideal for weddings, international summits, and cultural galas.'],
      ['Abba Jifar Conference Hall', 'Conference', 180, 9500, 'free', 'Dual HD Displays, Translation Booths, Video Conferencing, High-Speed Dedicated Mesh Wi-Fi', 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&w=800&q=80', 'State-of-the-art conference venue with flexible theatre or classroom seating.'],
      ['Teppi Executive Boardroom', 'Executive Boardroom', 30, 4200, 'free', 'Leather Ergonomic Chairs, Interactive Smart Board, Video Teleconference, Coffee Bar', 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80', 'Sophisticated boardroom for high-stakes corporate meetings and delegations.'],
      ['Kaffa Multipurpose Hall', 'Medium Seminar', 80, 6000, 'free', 'Wireless Microphones, Podium, Configurable Workshop Tables, Acoustic Paneling', 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?auto=format&fit=crop&w=800&q=80', 'Versatile multi-purpose hall for seminars, trainings, and workshops.']
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
      ['Special Teppi Tibs', 'traditional', 450, 60, 'available', 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80', 'Sizzling prime beef cubes sauteed with rosemary, red onions, garlic, and jalapeños.'],
      ['Special Doro Wat (Chicken Stew)', 'traditional', 550, 40, 'available', 'https://images.unsplash.com/photo-1541832676-9b763b0239ab?auto=format&fit=crop&w=600&q=80', 'Rich slow-cooked chicken stew simmered in authentic Ethiopian berbere with boiled egg and injera.'],
      ['Shiro Tegabino with Kibe', 'traditional', 280, 80, 'available', 'https://images.unsplash.com/photo-1505253758473-96b3d5eb926f?auto=format&fit=crop&w=600&q=80', 'Bubbling clay-pot spiced chickpea puree enriched with purified spiced Ethiopian butter.'],
      ['Fasting Mahberawi (Beyaynetu)', 'traditional', 380, 50, 'available', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80', 'Vibrant rainbow platter of lentils, collard greens, yellow split peas, beets, and salads.'],
      ['Grilled Rift Valley Salmon', 'main', 750, 30, 'available', 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?auto=format&fit=crop&w=600&q=80', 'Freshly pan-seared fillet served with garlic herb mashed potatoes and seasonal vegetables.'],
      ['Ehitmamachochi Gourmet Burger', 'fast_food', 390, 70, 'available', 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=600&q=80', 'Juicy double beef patty, aged cheddar, caramelized onions, crispy fries, and secret sauce.'],
      ['Crispy Chicken Club Sandwich', 'fast_food', 340, 65, 'available', 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=600&q=80', 'Triple-layer toasted sandwich with herb grilled chicken, lettuce, tomato, and fries.'],
      ['Tiramisu & Wild Honey Tart', 'dessert', 250, 45, 'available', 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&w=600&q=80', 'Decadent Italian classic infused with Ethiopian Sidamo espresso and organic honey.']
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
      ['St. George Draught Beer', 'beer', 45, 90, 240, 'bottle', 'available'],
      ['Habesha Cold Gold Beer', 'beer', 50, 100, 200, 'bottle', 'available'],
      ['Walia Beer', 'beer', 45, 90, 180, 'bottle', 'available'],
      ['Rift Valley Merlot Red Wine', 'wine', 350, 650, 60, 'bottle', 'available'],
      ['Acacia Sweet White Wine', 'wine', 320, 600, 50, 'bottle', 'available'],
      ['Traditional Tej Honey Wine', 'wine', 90, 180, 100, 'carafe', 'available'],
      ['Johnnie Walker Black Label', 'spirit', 2200, 4200, 25, 'bottle', 'available'],
      ['Absolut Vodka Shot', 'spirit', 60, 140, 80, 'shot', 'available'],
      ['Ethiopian Sidamo Organic Espresso', 'hot_beverage', 25, 60, 400, 'cup', 'available'],
      ['Fresh Pressed Mango & Avocado Juice', 'soft_drink', 35, 90, 120, 'glass', 'available'],
      ['Sparkling Ambo Mineral Water', 'soft_drink', 20, 45, 300, 'bottle', 'available'],
      ['Coca-Cola / Sprite', 'soft_drink', 20, 50, 350, 'bottle', 'available']
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
      ['Premium Cotton Bed Sheets', 'linens', 150, 'sets', 420, 20, 'in_stock'],
      ['Luxury Egyptian Towel Sets', 'linens', 200, 'sets', 250, 30, 'in_stock'],
      ['Organic Sidamo Coffee Beans', 'beverage', 45, 'kg', 380, 10, 'in_stock'],
      ['Pure Sunflower Cooking Oil', 'food_ingredient', 30, 'liters', 290, 10, 'in_stock'],
      ['Wheat Flour Premium Grade', 'food_ingredient', 50, 'kg', 120, 15, 'in_stock'],
      ['Eco Luxury Guest Shampoos', 'amenities', 350, 'bottles', 35, 50, 'in_stock'],
      ['Hotel Keycards RFID', 'office', 400, 'pcs', 25, 80, 'in_stock'],
      ['St. George Beer Crates', 'beverage', 28, 'crates', 1080, 10, 'in_stock']
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
      ['RES-1029', 'Solomon Girma', 'solomon.girma@gmail.com', '+251 911 889900', 'KB-88219', 2, 'Single Deluxe', 1200, today, tomorrow, 1, 'checked_in', 'paid', 'Bethlehem Tadesse']
    );
    db.run(
      `INSERT INTO room_reservations (booking_code, customer_name, customer_email, customer_phone, kebele_id, room_id, room_type, room_price, checkin_date, checkout_date, guests_count, status, payment_status, assigned_by)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      ['RES-1030', 'Dr. Almaz Ayana', 'almaz.ayana@un.org', '+251 922 445566', 'KB-99124', 4, 'Double Executive', 1900, today, tomorrow, 2, 'confirmed', 'deposit', 'Bethlehem Tadesse']
    );
  }

  // Seed sample orders
  const ordCount = db.get('SELECT COUNT(*) as count FROM orders').count;
  if (ordCount === 0) {
    const today = new Date().toISOString().split('T')[0];
    const ord = db.run(
      `INSERT INTO orders (order_code, customer_name, table_number, room_id, host_id, host_name, total_amount, status, payment_method, payment_status, reported_date)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      ['ORD-501', 'Table 4 (VIP Terrace)', 'T-04', 'None', 4, 'Hiwot Haile', 1230, 'served', 'cash', 'paid', today]
    );

    const orderId = ord.lastInsertRowid;
    db.run(
      `INSERT INTO order_items (order_id, item_name, item_type, quantity, unit_price, total_price)
       VALUES (?, ?, ?, ?, ?, ?)`,
      [orderId, 'Special Teppi Tibs', 'food', 2, 450, 900]
    );
    db.run(
      `INSERT INTO order_items (order_id, item_name, item_type, quantity, unit_price, total_price)
       VALUES (?, ?, ?, ?, ?, ?)`,
      [orderId, 'Habesha Cold Gold Beer', 'beverage', 3, 100, 300]
    );
    db.run(
      `INSERT INTO order_items (order_id, item_name, item_type, quantity, unit_price, total_price)
       VALUES (?, ?, ?, ?, ?, ?)`,
      [orderId, 'Sparkling Ambo Mineral Water', 'beverage', 1, 30, 30]
    );
  }

  // Seed sample financial transactions
  const finCount = db.get('SELECT COUNT(*) as count FROM financial_transactions').count;
  if (finCount === 0) {
    const today = new Date().toISOString().split('T')[0];
    db.run(
      `INSERT INTO financial_transactions (type, category, amount, reference_id, provider_name, description, transaction_date)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      ['income', 'room_booking', 3800, 'RES-1029', 'Bethlehem Tadesse', 'Room 102 stay settlement', today]
    );
    db.run(
      `INSERT INTO financial_transactions (type, category, amount, reference_id, provider_name, description, transaction_date)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      ['income', 'restaurant_bar', 1230, 'ORD-501', 'Hiwot Haile', 'Table 4 Dining bill settled', today]
    );
    db.run(
      `INSERT INTO financial_transactions (type, category, amount, reference_id, provider_name, description, transaction_date)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      ['expense', 'inventory_purchase', 2400, 'PO-201', 'Tigist Worku', 'Procured fresh organic vegetables & dairy', today]
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
      ['Deep Sanitization & Restock', 'Abebe Desta', 'Staff', 'high', 'Room 101', 'in_progress', 'Prepare for VIP arrival this afternoon', 'Today, 2:00 PM']
    );
    db.run(
      `INSERT INTO staff_tasks (title, assigned_to_name, role, priority, room_or_area, status, notes, due_date)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
      ['AC Filter Inspection', 'Abebe Desta', 'Staff', 'medium', 'Kembridge Ballroom', 'pending', 'Routine quarterly HVAC maintenance', 'Tomorrow, 10:00 AM']
    );
  }

  // Seed feedback comments
  const feedbackCount = db.get('SELECT COUNT(*) as count FROM feedback_comments').count;
  if (feedbackCount === 0) {
    db.run(
      `INSERT INTO feedback_comments (guest_name, guest_email, rating, comment, status)
       VALUES (?, ?, ?, ?, ?)`,
      ['Aman Abera', 'aman@abera.et', 5, 'Exceptional stay! The Teppi Tibs and the panoramic penthouse suite view exceeded all our expectations.', 'published']
    );
    db.run(
      `INSERT INTO feedback_comments (guest_name, guest_email, rating, comment, status)
       VALUES (?, ?, ?, ?, ?)`,
      ['Elena Rossi', 'elena.rossi@travel.it', 5, 'World-class hospitality, immaculate rooms, and great conference facilities. Will visit again!', 'published']
    );
  }

  console.log('Database initialized and seeded successfully.');
}

module.exports = { initDatabase };
