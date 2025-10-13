<?php
include('includes/config.php');

echo "<h3>Updating Database Structure...</h3>";

// Add new columns to members table
$queries = [
    "ALTER TABLE `members` ADD COLUMN `password` VARCHAR(255) NOT NULL DEFAULT ''",
    "ALTER TABLE `members` ADD COLUMN `is_active` TINYINT(1) DEFAULT 1",
    "ALTER TABLE `members` ADD COLUMN `last_login` TIMESTAMP NULL"
];

foreach ($queries as $query) {
    echo "Executing: $query<br>";
    if ($conn->query($query)) {
        echo "✓ Success<br>";
    } else {
        echo "✗ Error: " . $conn->error . "<br>";
    }
    echo "<br>";
}

// Set default passwords for existing members
echo "Setting default passwords for existing members...<br>";
$updatePasswordQuery = "UPDATE `members` SET `password` = MD5('temp123') WHERE `password` = '' OR `password` IS NULL";
if ($conn->query($updatePasswordQuery)) {
    echo "✓ Passwords set successfully<br>";
} else {
    echo "✗ Error setting passwords: " . $conn->error . "<br>";
}

echo "<h3>Database update completed!</h3>";
echo "<p>You can now <a href='index.php'>login</a> as a member using your registered email and password 'temp123'</p>";
?>