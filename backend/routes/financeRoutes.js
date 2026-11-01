const express = require('express');
const router = express.Router();
const financeController = require('../controllers/financeController');
const { verifyToken, requireRoles } = require('../middlewares/auth');

router.get('/transactions', verifyToken, requireRoles('Cashier', 'Manager'), financeController.getTransactions);
router.post('/transactions', verifyToken, requireRoles('Cashier', 'Manager'), financeController.recordTransaction);
router.get('/summary', verifyToken, requireRoles('Cashier', 'Manager'), financeController.getSummary);
router.post('/pay-salary', verifyToken, requireRoles('Cashier', 'Manager'), financeController.payEmployeeSalary);

module.exports = router;
