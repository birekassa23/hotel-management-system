const db = require('../config/db');

function generateBookingCode(prefix = 'RES') {
  const rand = Math.floor(1000 + Math.random() * 9000);
  return `${prefix}-${rand}`;
}

exports.getRoomReservations = (req, res) => {
  try {
    const { status, search } = req.query;
    let sql = `
      SELECT r.*, rm.room_number, rm.floor
      FROM room_reservations r
      LEFT JOIN rooms rm ON r.room_id = rm.id
      WHERE 1=1
    `;
    const params = [];

    if (status && status !== 'all') {
      sql += ' AND r.status = ?';
      params.push(status);
    }
    if (search) {
      sql += ' AND (r.booking_code LIKE ? OR r.customer_name LIKE ? OR r.customer_phone LIKE ? OR r.customer_email LIKE ?)';
      const term = `%${search}%`;
      params.push(term, term, term, term);
    }

    sql += ' ORDER BY r.id DESC';
    const reservations = db.all(sql, params);
    res.json(reservations);
  } catch (error) {
    res.status(500).json({ message: 'Error retrieving room reservations', error: error.message });
  }
};

exports.createRoomReservation = (req, res) => {
  try {
    const {
      customer_name,
      customer_email,
      customer_phone,
      kebele_id,
      room_id,
      room_type,
      room_price,
      checkin_date,
      checkout_date,
      guests_count = 1,
      payment_status = 'unpaid',
      assigned_by,
      notes
    } = req.body;

    if (!customer_name || !room_id || !checkin_date || !checkout_date) {
      return res.status(400).json({ message: 'Customer name, room, and stay dates are required' });
    }

    const booking_code = generateBookingCode('RES');
    const assigned = assigned_by || (req.user ? req.user.full_name : 'Online Guest');

    const result = db.run(
      `INSERT INTO room_reservations (
        booking_code, customer_name, customer_email, customer_phone, kebele_id,
        room_id, room_type, room_price, checkin_date, checkout_date,
        guests_count, status, payment_status, assigned_by, notes
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed', ?, ?, ?)`,
      [
        booking_code,
        customer_name,
        customer_email || '',
        customer_phone || '',
        kebele_id || '',
        room_id,
        room_type,
        room_price || 0,
        checkin_date,
        checkout_date,
        guests_count,
        payment_status,
        assigned,
        notes || ''
      ]
    );

    // Update room status to 'reserved'
    db.run("UPDATE rooms SET status = 'reserved' WHERE id = ?", [room_id]);

    // If paid, log financial income
    if (payment_status === 'paid' && room_price > 0) {
      db.run(
        `INSERT INTO financial_transactions (type, category, amount, reference_id, provider_name, description)
         VALUES ('income', 'room_booking', ?, ?, ?, ?)`,
        [room_price, booking_code, assigned, `Room reservation payment for ${customer_name}`]
      );
    }

    const created = db.get('SELECT * FROM room_reservations WHERE id = ?', [result.lastInsertRowid]);
    res.status(201).json({ message: 'Reservation created successfully', reservation: created });
  } catch (error) {
    res.status(500).json({ message: 'Error creating reservation', error: error.message });
  }
};

exports.updateReservationStatus = (req, res) => {
  try {
    const { id } = req.params;
    const { status, payment_status } = req.body;

    const reservation = db.get('SELECT * FROM room_reservations WHERE id = ?', [id]);
    if (!reservation) {
      return res.status(404).json({ message: 'Reservation not found' });
    }

    db.run(
      `UPDATE room_reservations
       SET status = COALESCE(?, status),
           payment_status = COALESCE(?, payment_status)
       WHERE id = ?`,
      [status, payment_status, id]
    );

    // Synchronize room status
    if (reservation.room_id) {
      if (status === 'checked_in') {
        db.run("UPDATE rooms SET status = 'occupied' WHERE id = ?", [reservation.room_id]);
      } else if (status === 'checked_out' || status === 'cancelled') {
        db.run("UPDATE rooms SET status = 'free' WHERE id = ?", [reservation.room_id]);
      } else if (status === 'confirmed') {
        db.run("UPDATE rooms SET status = 'reserved' WHERE id = ?", [reservation.room_id]);
      }
    }

    // If marked paid just now
    if (payment_status === 'paid' && reservation.payment_status !== 'paid') {
      db.run(
        `INSERT INTO financial_transactions (type, category, amount, reference_id, provider_name, description)
         VALUES ('income', 'room_booking', ?, ?, ?, ?)`,
        [reservation.room_price, reservation.booking_code, req.user ? req.user.full_name : 'Reception', `Room checkout settlement for ${reservation.customer_name}`]
      );
    }

    const updated = db.get('SELECT * FROM room_reservations WHERE id = ?', [id]);
    res.json({ message: 'Reservation updated', reservation: updated });
  } catch (error) {
    res.status(500).json({ message: 'Error updating reservation', error: error.message });
  }
};

exports.getHallReservations = (req, res) => {
  try {
    const { status } = req.query;
    let sql = `
      SELECT hr.*, mh.name as hall_name_full, mh.capacity
      FROM hall_reservations hr
      LEFT JOIN meeting_halls mh ON hr.hall_id = mh.id
      WHERE 1=1
    `;
    const params = [];

    if (status && status !== 'all') {
      sql += ' AND hr.status = ?';
      params.push(status);
    }

    sql += ' ORDER BY hr.id DESC';
    const reservations = db.all(sql, params);
    res.json(reservations);
  } catch (error) {
    res.status(500).json({ message: 'Error retrieving hall reservations', error: error.message });
  }
};

exports.createHallReservation = (req, res) => {
  try {
    const {
      customer_name,
      customer_email,
      customer_phone,
      hall_id,
      hall_name,
      hall_price,
      checkin_date,
      checkout_date,
      event_type,
      payment_status = 'unpaid',
      assigned_by
    } = req.body;

    if (!customer_name || !hall_id || !checkin_date || !checkout_date) {
      return res.status(400).json({ message: 'Customer name, hall, and booking dates are required' });
    }

    const booking_code = generateBookingCode('HALL');
    const assigned = assigned_by || (req.user ? req.user.full_name : 'Online Guest');

    const result = db.run(
      `INSERT INTO hall_reservations (
        booking_code, customer_name, customer_email, customer_phone, hall_id,
        hall_name, hall_price, checkin_date, checkout_date, event_type,
        status, payment_status, assigned_by
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed', ?, ?)`,
      [
        booking_code,
        customer_name,
        customer_email || '',
        customer_phone || '',
        hall_id,
        hall_name,
        hall_price || 0,
        checkin_date,
        checkout_date,
        event_type || 'Conference',
        payment_status,
        assigned
      ]
    );

    // Update hall status
    db.run("UPDATE meeting_halls SET status = 'reserved' WHERE id = ?", [hall_id]);

    if (payment_status === 'paid' && hall_price > 0) {
      db.run(
        `INSERT INTO financial_transactions (type, category, amount, reference_id, provider_name, description)
         VALUES ('income', 'hall_booking', ?, ?, ?, ?)`,
        [hall_price, booking_code, assigned, `Meeting hall reservation: ${hall_name} for ${customer_name}`]
      );
    }

    const created = db.get('SELECT * FROM hall_reservations WHERE id = ?', [result.lastInsertRowid]);
    res.status(201).json({ message: 'Hall reservation created', reservation: created });
  } catch (error) {
    res.status(500).json({ message: 'Error creating hall reservation', error: error.message });
  }
};

exports.lookupBooking = (req, res) => {
  try {
    const { code, email } = req.query;
    if (!code && !email) {
      return res.status(400).json({ message: 'Please provide a booking code or email address' });
    }

    let roomBooking = null;
    let hallBooking = null;

    if (code) {
      roomBooking = db.get('SELECT * FROM room_reservations WHERE booking_code = ? COLLATE NOCASE', [code.trim()]);
      hallBooking = db.get('SELECT * FROM hall_reservations WHERE booking_code = ? COLLATE NOCASE', [code.trim()]);
    } else if (email) {
      roomBooking = db.all('SELECT * FROM room_reservations WHERE customer_email = ? COLLATE NOCASE ORDER BY id DESC', [email.trim()]);
      hallBooking = db.all('SELECT * FROM hall_reservations WHERE customer_email = ? COLLATE NOCASE ORDER BY id DESC', [email.trim()]);
    }

    res.json({
      roomBooking,
      hallBooking
    });
  } catch (error) {
    res.status(500).json({ message: 'Error looking up booking', error: error.message });
  }
};
