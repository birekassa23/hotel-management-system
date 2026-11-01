const db = require('../config/db');

exports.getTasks = (req, res) => {
  try {
    const { status, role } = req.query;
    let sql = 'SELECT * FROM staff_tasks WHERE 1=1';
    const params = [];

    if (status && status !== 'all') {
      sql += ' AND status = ?';
      params.push(status);
    }
    if (role) {
      sql += ' AND role = ?';
      params.push(role);
    }

    sql += ' ORDER BY id DESC';
    const tasks = db.all(sql, params);
    res.json(tasks);
  } catch (error) {
    res.status(500).json({ message: 'Error fetching staff tasks', error: error.message });
  }
};

exports.createTask = (req, res) => {
  try {
    const { title, assigned_to_id, assigned_to_name, role = 'Staff', priority = 'medium', room_or_area, notes, due_date } = req.body;
    if (!title) {
      return res.status(400).json({ message: 'Task title is required' });
    }

    const result = db.run(
      `INSERT INTO staff_tasks (title, assigned_to_id, assigned_to_name, role, priority, room_or_area, status, notes, due_date)
       VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, ?)`,
      [title, assigned_to_id || null, assigned_to_name || 'Staff Member', role, priority, room_or_area || 'General Hotel Area', notes || '', due_date || 'Today']
    );

    const created = db.get('SELECT * FROM staff_tasks WHERE id = ?', [result.lastInsertRowid]);
    res.status(201).json({ message: 'Task assigned successfully', task: created });
  } catch (error) {
    res.status(500).json({ message: 'Error assigning task', error: error.message });
  }
};

exports.updateTaskStatus = (req, res) => {
  try {
    const { id } = req.params;
    const { status, notes } = req.body;

    db.run(
      `UPDATE staff_tasks
       SET status = COALESCE(?, status),
           notes = COALESCE(?, notes)
       WHERE id = ?`,
      [status, notes, id]
    );

    const updated = db.get('SELECT * FROM staff_tasks WHERE id = ?', [id]);
    res.json({ message: 'Task updated', task: updated });
  } catch (error) {
    res.status(500).json({ message: 'Error updating task', error: error.message });
  }
};

exports.getAttendance = (req, res) => {
  try {
    const { date } = req.query;
    let sql = 'SELECT * FROM attendance WHERE 1=1';
    const params = [];

    if (date) {
      sql += ' AND attendance_date = ?';
      params.push(date);
    }

    sql += ' ORDER BY id DESC LIMIT 100';
    const records = db.all(sql, params);
    res.json(records);
  } catch (error) {
    res.status(500).json({ message: 'Error fetching attendance', error: error.message });
  }
};

exports.markAttendance = (req, res) => {
  try {
    const { employee_id, status, notes } = req.body;
    if (!employee_id || !status) {
      return res.status(400).json({ message: 'Employee ID and status are required' });
    }

    const employee = db.get('SELECT id, full_name, role FROM users WHERE id = ?', [employee_id]);
    if (!employee) {
      return res.status(404).json({ message: 'Employee not found' });
    }

    const today = new Date().toISOString().split('T')[0];
    const isPresent = status === 'present' ? 'yes' : 'no';

    // Update user attendance flag
    db.run('UPDATE users SET is_present = ? WHERE id = ?', [isPresent, employee_id]);

    // Insert or update attendance log
    const existing = db.get('SELECT id FROM attendance WHERE employee_id = ? AND attendance_date = ?', [employee_id, today]);
    if (existing) {
      db.run('UPDATE attendance SET status = ?, notes = ? WHERE id = ?', [status, notes || '', existing.id]);
    } else {
      db.run(
        `INSERT INTO attendance (employee_id, employee_name, role, attendance_date, status, notes)
         VALUES (?, ?, ?, ?, ?, ?)`,
        [employee.id, employee.full_name, employee.role, today, status, notes || '']
      );
    }

    res.json({ message: `Attendance for ${employee.full_name} marked as ${status}` });
  } catch (error) {
    res.status(500).json({ message: 'Error marking attendance', error: error.message });
  }
};
