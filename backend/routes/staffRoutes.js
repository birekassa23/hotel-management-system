const express = require('express');
const router = express.Router();
const staffController = require('../controllers/staffController');
const { verifyToken, requireRoles } = require('../middlewares/auth');

router.get('/tasks', verifyToken, staffController.getTasks);
router.post('/tasks', verifyToken, requireRoles('Manager', 'Receptionist', 'Staff'), staffController.createTask);
router.put('/tasks/:id', verifyToken, staffController.updateTaskStatus);

router.get('/attendance', verifyToken, requireRoles('Manager', 'Receptionist', 'Cashier'), staffController.getAttendance);
router.post('/attendance', verifyToken, requireRoles('Manager', 'Receptionist'), staffController.markAttendance);

module.exports = router;
