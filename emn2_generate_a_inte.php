<?php

// Configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'app_tracker';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create table if not exists
$sql = "CREATE TABLE IF NOT EXISTS trackers (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    app_name VARCHAR(50),
    installed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_opened_at TIMESTAMP,
    frequency INT(3)
)";
$conn->query($sql);

// Function to add new tracker
function addTracker($user_id, $app_name) {
    global $conn;
    $sql = "INSERT INTO trackers (user_id, app_name) VALUES ('$user_id', '$app_name')";
    $conn->query($sql);
}

// Function to update tracker frequency
function updateFrequency($user_id, $app_name) {
    global $conn;
    $sql = "UPDATE trackers SET frequency = frequency + 1, last_opened_at = CURRENT_TIMESTAMP WHERE user_id = '$user_id' AND app_name = '$app_name'";
    $conn->query($sql);
}

// API Endpoint to add new tracker
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user_id']) && isset($_POST['app_name'])) {
        addTracker($_POST['user_id'], $_POST['app_name']);
        echo 'Tracker added successfully!';
    } else {
        echo 'Invalid request!';
    }
}

// API Endpoint to update tracker frequency
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    if (isset($_PUT['user_id']) && isset($_PUT['app_name'])) {
        updateFrequency($_PUT['user_id'], $_PUT['app_name']);
        echo 'Frequency updated successfully!';
    } else {
        echo 'Invalid request!';
    }
}

// Close database connection
$conn->close();

?>