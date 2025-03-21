const express = require('express');
const router = express.Router();
const db = require('../../database'); // Assuming you have a database module to handle DB operations

// API to handle returning items
router.post('/return', async (req, res) => {
    const { itemId, userId } = req.body;

    if (!itemId || !userId) {
        return res.status(400).json({ message: 'Item ID and User ID are required.' });
    }

    try {
        // Logic to update the item's status in the database
        const result = await db.query('UPDATE items SET status = "returned" WHERE id = ? AND user_id = ?', [itemId, userId]);

        if (result.affectedRows > 0) {
            return res.status(200).json({ message: 'Item returned successfully.' });
        } else {
            return res.status(404).json({ message: 'Item not found or not borrowed by this user.' });
        }
    } catch (error) {
        console.error(error);
        return res.status(500).json({ message: 'An error occurred while processing your request.' });
    }
});

module.exports = router;