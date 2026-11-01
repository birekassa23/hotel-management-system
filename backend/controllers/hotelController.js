const db = require('../config/db');

exports.getHotelInfo = (req, res) => {
  try {
    const info = db.get('SELECT * FROM hotel_info LIMIT 1');
    res.json(info || {
      name: 'Grand Horizon Luxury Hotel & Suites',
      address: '742 Evergreen Boulevard, Central District',
      town: 'Metropolis',
      region: 'Capital Region',
      country: 'Global',
      phone: '+1 (555) 019-2834',
      email: 'contact@grandhorizonhotel.com'
    });
  } catch (error) {
    res.status(500).json({ message: 'Error retrieving hotel info', error: error.message });
  }
};

exports.updateHotelInfo = (req, res) => {
  try {
    const { name, address, town, region, country, phone, email, description } = req.body;
    db.run(
      `UPDATE hotel_info
       SET name = COALESCE(?, name),
           address = COALESCE(?, address),
           town = COALESCE(?, town),
           region = COALESCE(?, region),
           country = COALESCE(?, country),
           phone = COALESCE(?, phone),
           email = COALESCE(?, email),
           description = COALESCE(?, description)
       WHERE id = 1`,
      [name, address, town, region, country, phone, email, description]
    );

    const updated = db.get('SELECT * FROM hotel_info LIMIT 1');
    res.json({ message: 'Hotel profile updated', hotel: updated });
  } catch (error) {
    res.status(500).json({ message: 'Error updating hotel info', error: error.message });
  }
};

exports.getFeedback = (req, res) => {
  try {
    const feedback = db.all('SELECT * FROM feedback_comments ORDER BY id DESC LIMIT 50');
    res.json(feedback);
  } catch (error) {
    res.status(500).json({ message: 'Error retrieving guest feedback', error: error.message });
  }
};

exports.submitFeedback = (req, res) => {
  try {
    const { guest_name, guest_email, rating = 5, comment } = req.body;
    if (!guest_name || !comment) {
      return res.status(400).json({ message: 'Guest name and review comments are required' });
    }

    const result = db.run(
      `INSERT INTO feedback_comments (guest_name, guest_email, rating, comment, status)
       VALUES (?, ?, ?, ?, 'published')`,
      [guest_name, guest_email || '', rating, comment]
    );

    const created = db.get('SELECT * FROM feedback_comments WHERE id = ?', [result.lastInsertRowid]);
    res.status(201).json({ message: 'Thank you for your feedback!', review: created });
  } catch (error) {
    res.status(500).json({ message: 'Error submitting feedback', error: error.message });
  }
};
