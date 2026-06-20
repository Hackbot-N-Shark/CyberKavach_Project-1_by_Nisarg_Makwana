<?php
$db = new PDO('sqlite:C:/Users/ASUS/Desktop/CyberKavach/database/cyberkavach.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "CREATE TABLE IF NOT EXISTS notifications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    target_type VARCHAR(50) NOT NULL,
    target_id INTEGER NULL,
    message TEXT NOT NULL,
    sender_id INTEGER NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(sender_id) REFERENCES users(id)
)";

$db->exec($sql);
echo "Notifications table created successfully.\n";
