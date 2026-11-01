const express = require('express');
const router = express.Router();
const hotelController = require('../controllers/hotelController');
const { verifyToken, requireRoles } = require('../middlewares/auth');

router.get('/info', hotelController.getHotelInfo);
router.put('/info', verifyToken, requireRoles('Manager'), hotelController.updateHotelInfo);

router.get('/feedback', hotelController.getFeedback);
router.post('/feedback', hotelController.submitFeedback);

module.exports = router;
