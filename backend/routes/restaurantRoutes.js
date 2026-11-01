const express = require('express');
const router = express.Router();
const restaurantController = require('../controllers/restaurantController');
const { verifyToken, requireRoles } = require('../middlewares/auth');

// Foods
router.get('/foods', restaurantController.getFoods);
router.post('/foods', verifyToken, requireRoles('Manager', 'Host', 'Staff'), restaurantController.createFood);
router.put('/foods/:id', verifyToken, requireRoles('Manager', 'Host', 'Staff'), restaurantController.updateFood);
router.delete('/foods/:id', verifyToken, requireRoles('Manager'), restaurantController.deleteFood);

// Beverages
router.get('/beverages', restaurantController.getBeverages);
router.post('/beverages', verifyToken, requireRoles('Manager', 'Barman', 'Store', 'Purchaser'), restaurantController.createBeverage);
router.put('/beverages/:id', verifyToken, requireRoles('Manager', 'Barman', 'Store'), restaurantController.updateBeverage);
router.delete('/beverages/:id', verifyToken, requireRoles('Manager', 'Barman'), restaurantController.deleteBeverage);

// Orders / POS
router.get('/orders', verifyToken, requireRoles('Host', 'Barman', 'Cashier', 'Manager'), restaurantController.getOrders);
router.post('/orders', verifyToken, requireRoles('Host', 'Barman', 'Receptionist', 'Manager'), restaurantController.createOrder);
router.put('/orders/:id', verifyToken, requireRoles('Host', 'Barman', 'Cashier', 'Manager'), restaurantController.updateOrderStatus);

module.exports = router;
