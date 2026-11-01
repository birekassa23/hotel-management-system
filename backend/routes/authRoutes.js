const express = require('express');
const router = express.Router();
const authController = require('../controllers/authController');
const { verifyToken, requireRoles } = require('../middlewares/auth');

router.post('/login', authController.login);
router.post('/register', authController.register);
router.get('/me', verifyToken, authController.getMe);
router.get('/demo-login/:role', authController.quickDemoLogin);

// Employee management (Manager only)
router.get('/employees', verifyToken, requireRoles('Manager'), authController.getEmployees);
router.post('/employees', verifyToken, requireRoles('Manager'), authController.createEmployee);
router.put('/employees/:id', verifyToken, requireRoles('Manager'), authController.updateEmployee);
router.delete('/employees/:id', verifyToken, requireRoles('Manager'), authController.deleteEmployee);

module.exports = router;
