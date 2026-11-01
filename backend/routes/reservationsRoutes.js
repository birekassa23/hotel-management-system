const express = require('express');
const router = express.Router();
const reservationsController = require('../controllers/reservationsController');
const { verifyToken, requireRoles } = require('../middlewares/auth');

// Public booking lookup
router.get('/lookup', reservationsController.lookupBooking);

// Room reservations
router.get('/rooms', verifyToken, requireRoles('Receptionist', 'Manager', 'Cashier'), reservationsController.getRoomReservations);
router.post('/rooms', reservationsController.createRoomReservation); // Guest or Receptionist can create
router.put('/rooms/:id', verifyToken, requireRoles('Receptionist', 'Manager', 'Cashier'), reservationsController.updateReservationStatus);

// Hall reservations
router.get('/halls', verifyToken, requireRoles('Receptionist', 'Manager', 'Cashier'), reservationsController.getHallReservations);
router.post('/halls', reservationsController.createHallReservation);

module.exports = router;
