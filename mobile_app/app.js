const express = require('express');
const bodyParser = require('body-parser');
const app = express();
const port = 3000;

// Middleware
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Importing API routes
const borrowItemApi = require('./api/borrow_item');
const returnItemApi = require('./api/return_item');
const scanNfcApi = require('./api/scan_nfc');
const riskPredictionApi = require('./api/risk_prediction');

// API Routes
app.use('/api/borrow', borrowItemApi);
app.use('/api/return', returnItemApi);
app.use('/api/nfc', scanNfcApi);
app.use('/api/risk', riskPredictionApi);

// Home route
app.get('/', (req, res) => {
    res.send('Welcome to the Borrow System Mobile App');
});

// Start the server
app.listen(port, () => {
    console.log(`Mobile app listening at http://localhost:${port}`);
});