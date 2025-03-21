<?php
// This API trains a new machine learning model.

require_once '../../ml_model/train_model.py';

// Function to train the model
function trainModel() {
    // Call the Python script to train the model
    $command = escapeshellcmd('python3 ../../ml_model/train_model.py');
    $output = shell_exec($command);
    
    // Return the output of the training process
    return json_encode(['status' => 'success', 'output' => $output]);
}

// Handle the API request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    echo trainModel();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>