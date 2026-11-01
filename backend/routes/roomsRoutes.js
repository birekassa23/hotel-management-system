const express = require('express');
const router = express.Router();
const roomsController = require('../controllers/roomsController');
const { verifyToken, requireRoles } = require('../middlewares/auth');

// Public or Guest routes
router.get('/', roomsController.getAllRooms);
router.get('/stats', roomsController.getRoomStats);
router.get('/:id', roomsController.getRoomById);

// Staff / Receptionist / Manager routes
router.post('/', verifyToken, requireRoles('Manager', 'Receptionist'), roomsController.createRoom);
router.put('/:id', verifyToken, requireRoles('Manager', 'Receptionist', 'Staff'), roomsController.updateRoom);
router.delete('/:id', verifyToken, requireRoles('Manager'), roomsController.deleteRoom);

module.exports = router;
