const db = require('../config/db');

exports.getAllHalls = (req, res) => {
  try {
    const { status, type } = req.query;
    let sql = 'SELECT * FROM meeting_halls WHERE 1=1';
    const params = [];

    if (status && status !== 'all') {
      sql += ' AND status = ?';
      params.push(status);
    }
    if (type && type !== 'all') {
      sql += ' AND type = ?';
      params.push(type);
    }

    sql += ' ORDER BY id ASC';
    const halls = db.all(sql, params);
    res.json(halls);
  } catch (error) {
    res.status(500).json({ message: 'Error retrieving meeting halls', error: error.message });
  }
};

exports.getHallById = (req, res) => {
  try {
    const hall = db.get('SELECT * FROM meeting_halls WHERE id = ?', [req.params.id]);
    if (!hall) {
      return res.status(404).json({ message: 'Meeting hall not found' });
    }
    res.json(hall);
  } catch (error) {
    res.status(500).json({ message: 'Error fetching hall', error: error.message });
  }
};

exports.createHall = (req, res) => {
  try {
    const { name, type, capacity, price, status = 'free', amenities, image_url, description } = req.body;
    if (!name || !capacity || !price) {
      return res.status(400).json({ message: 'Name, capacity, and price are required' });
    }

    const result = db.run(
      `INSERT INTO meeting_halls (name, type, capacity, price, status, amenities, image_url, description)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
      [name, type || 'Conference', capacity, price, status, amenities || '', image_url || '', description || '']
    );

    const newHall = db.get('SELECT * FROM meeting_halls WHERE id = ?', [result.lastInsertRowid]);
    res.status(201).json({ message: 'Meeting hall created successfully', hall: newHall });
  } catch (error) {
    res.status(500).json({ message: 'Error creating meeting hall', error: error.message });
  }
};

exports.updateHall = (req, res) => {
  try {
    const { id } = req.params;
    const { name, type, capacity, price, status, amenities, image_url, description } = req.body;

    db.run(
      `UPDATE meeting_halls
       SET name = COALESCE(?, name),
           type = COALESCE(?, type),
           capacity = COALESCE(?, capacity),
           price = COALESCE(?, price),
           status = COALESCE(?, status),
           amenities = COALESCE(?, amenities),
           image_url = COALESCE(?, image_url),
           description = COALESCE(?, description)
       WHERE id = ?`,
      [name, type, capacity, price, status, amenities, image_url, description, id]
    );

    const updated = db.get('SELECT * FROM meeting_halls WHERE id = ?', [id]);
    res.json({ message: 'Meeting hall updated successfully', hall: updated });
  } catch (error) {
    res.status(500).json({ message: 'Error updating hall', error: error.message });
  }
};

exports.deleteHall = (req, res) => {
  try {
    db.run('DELETE FROM meeting_halls WHERE id = ?', [req.params.id]);
    res.json({ message: 'Meeting hall deleted successfully' });
  } catch (error) {
    res.status(500).json({ message: 'Error deleting hall', error: error.message });
  }
};
