<?php
// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "membershiphp";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Start session only if not started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ UPDATED Function to check membership status (no longer tries to update database)
function syncMemberStatus($conn, $member_id) {
    $current_date = date('Y-m-d');
    
    // Get member data - only check expiry_date
    $sql = "SELECT expiry_date FROM members WHERE id = $member_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Determine status based on expiry_date only
        if ($row['expiry_date'] && $row['expiry_date'] < $current_date) {
            return 'Expired';
        } else {
            return 'Active';
        }
    }
    return 'Active'; // Default fallback
}
?>