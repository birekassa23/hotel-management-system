const db = require('../config/db');

exports.getAllRooms = (req, res) => {
  try {
    const { status, type, search } = req.query;
    let sql = 'SELECT * FROM rooms WHERE 1=1';
    const params = [];

    if (status && status !== 'all') {
      sql += ' AND status = ?';
      params.push(status);
    }
    if (type && type !== 'all') {
      sql += ' AND type = ?';
      params.push(type);
    }
    if (search) {
      sql += ' AND (room_number LIKE ? OR type LIKE ? OR amenities LIKE ?)';
      const term = `%${search}%`;
      params.push(term, term, term);
    }

    sql += ' ORDER BY room_number ASC';
    const rooms = db.all(sql, params);
    res.json(rooms);
  } catch (error) {
    res.status(500).json({ message: 'Error retrieving rooms', error: error.message });
  }
};

exports.getRoomById = (req, res) => {
  try {
    const room = db.get('SELECT * FROM rooms WHERE id = ?', [req.params.id]);
    if (!room) {
      return res.status(404).json({ message: 'Room not found' });
    }
    res.json(room);
  } catch (error) {
    res.status(500).json({ message: 'Error fetching room', error: error.message });
  }
};

exports.createRoom = (req, res) => {
  try {
    const { room_number, type, price, status = 'free', capacity = 2, floor = 1, amenities, image_url, description } = req.body;
    if (!room_number || !type || !price) {
      return res.status(400).json({ message: 'Room number, type, and price are required' });
    }

    const result = db.run(
      `INSERT INTO rooms (room_number, type, price, status, capacity, floor, amenities, image_url, description)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [room_number, type, price, status, capacity, floor, amenities || '', image_url || '', description || '']
    );

    const newRoom = db.get('SELECT * FROM rooms WHERE id = ?', [result.lastInsertRowid]);
    res.status(201).json({ message: 'Room created successfully', room: newRoom });
  } catch (error) {
    res.status(500).json({ message: 'Error creating room', error: error.message });
  }
};

exports.updateRoom = (req, res) => {
  try {
    const { id } = req.params;
    const { room_number, type, price, status, capacity, floor, amenities, image_url, description } = req.body;

    db.run(
      `UPDATE rooms
       SET room_number = COALESCE(?, room_number),
           type = COALESCE(?, type),
           price = COALESCE(?, price),
           status = COALESCE(?, status),
           capacity = COALESCE(?, capacity),
           floor = COALESCE(?, floor),
           amenities = COALESCE(?, amenities),
           image_url = COALESCE(?, image_url),
           description = COALESCE(?, description)
       WHERE id = ?`,
      [room_number, type, price, status, capacity, floor, amenities, image_url, description, id]
    );

    const updated = db.get('SELECT * FROM rooms WHERE id = ?', [id]);
    res.json({ message: 'Room updated successfully', room: updated });
  } catch (error) {
    res.status(500).json({ message: 'Error updating room', error: error.message });
  }
};

exports.deleteRoom = (req, res) => {
  try {
    db.run('DELETE FROM rooms WHERE id = ?', [req.params.id]);
    res.json({ message: 'Room deleted successfully' });
  } catch (error) {
    res.status(500).json({ message: 'Error deleting room', error: error.message });
  }
};

exports.getRoomStats = (req, res) => {
  try {
    const total = db.get('SELECT COUNT(*) as count FROM rooms').count;
    const free = db.get("SELECT COUNT(*) as count FROM rooms WHERE status = 'free'").count;
    const occupied = db.get("SELECT COUNT(*) as count FROM rooms WHERE status = 'occupied'").count;
    const reserved = db.get("SELECT COUNT(*) as count FROM rooms WHERE status = 'reserved'").count;
    const maintenance = db.get("SELECT COUNT(*) as count FROM rooms WHERE status = 'maintenance'").count;
    const occupancyRate = total > 0 ? Math.round(((occupied + reserved) / total) * 100) : 0;

    res.json({
      total,
      free,
      occupied,
      reserved,
      maintenance,
      occupancyRate
    });
  } catch (error) {
    res.status(500).json({ message: 'Error getting room stats', error: error.message });
  }
};
