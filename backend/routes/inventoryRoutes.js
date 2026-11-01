const express = require('express');
const router = express.Router();
const inventoryController = require('../controllers/inventoryController');
const { verifyToken, requireRoles } = require('../middlewares/auth');

router.get('/', verifyToken, requireRoles('Store', 'Purchaser', 'Barman', 'Manager'), inventoryController.getInventory);
router.post('/', verifyToken, requireRoles('Store', 'Purchaser', 'Manager'), inventoryController.createInventoryItem);
router.put('/:id', verifyToken, requireRoles('Store', 'Purchaser', 'Manager'), inventoryController.updateInventoryItem);

// Transfer stock out to Bar, Kitchen, etc.
router.post('/transfer', verifyToken, requireRoles('Store', 'Manager'), inventoryController.transferStock);
router.get('/transfers', verifyToken, requireRoles('Store', 'Barman', 'Manager'), inventoryController.getTransfers);

// Purchases (Purchaser actor)
router.get('/purchases', verifyToken, requireRoles('Purchaser', 'Store', 'Manager', 'Cashier'), inventoryController.getPurchases);
router.post('/purchases', verifyToken, requireRoles('Purchaser', 'Manager'), inventoryController.createPurchase);

module.exports = router;
