<?php
// This API assesses risk using machine learning

// Include necessary files
require_once '../../ml_model/predict_risk.php';

// Get input data
$data = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (!isset($data['borrower_id']) || !isset($data['item_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input data']);
    exit;
}

// Extract borrower ID and item ID
$borrower_id = $data['borrower_id'];
$item_id = $data['item_id'];

// Call the function to predict risk
$risk_prediction = predict_risk($borrower_id, $item_id);

// Return the risk prediction result
echo json_encode(['risk_prediction' => $risk_prediction]);
?>