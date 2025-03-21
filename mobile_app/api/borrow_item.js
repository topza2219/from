const express = require('express');
const router = express.Router();
const db = require('../../database'); // Assuming you have a database module to handle DB operations

// API to borrow an item
router.post('/borrow', async (req, res) => {
    const { itemId, borrowerId, borrowDate, returnDate } = req.body;

    if (!itemId || !borrowerId || !borrowDate || !returnDate) {
        return res.status(400).json({ message: 'All fields are required' });
    }

    try {
        // Insert the borrow record into the database
        const result = await db.query('INSERT INTO borrow_records (item_id, borrower_id, borrow_date, return_date) VALUES (?, ?, ?, ?)', [itemId, borrowerId, borrowDate, returnDate]);

        if (result.affectedRows > 0) {
            return res.status(201).json({ message: 'Item borrowed successfully' });
        } else {
            return res.status(500).json({ message: 'Failed to borrow item' });
        }
    } catch (error) {
        console.error(error);
        return res.status(500).json({ message: 'Server error' });
    }
});

module.exports = router;