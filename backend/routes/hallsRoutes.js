const express = require('express');
const router = express.Router();
const hallsController = require('../controllers/hallsController');
const { verifyToken, requireRoles } = require('../middlewares/auth');

router.get('/', hallsController.getAllHalls);
router.get('/:id', hallsController.getHallById);

router.post('/', verifyToken, requireRoles('Manager', 'Receptionist'), hallsController.createHall);
router.put('/:id', verifyToken, requireRoles('Manager', 'Receptionist'), hallsController.updateHall);
router.delete('/:id', verifyToken, requireRoles('Manager'), hallsController.deleteHall);

module.exports = router;
