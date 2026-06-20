<?php
$db = new PDO('sqlite:' . __DIR__ . '/cyberkavach.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "ALTER TABLE users ADD COLUMN must_change_password INTEGER DEFAULT 0;";

try {
    $db->exec($sql);
    echo "Migration Phase 12: must_change_password added successfully.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'duplicate column name') !== false) {
        echo "Column already exists.\n";
    } else {
        echo "Migration Error: " . $e->getMessage() . "\n";
    }
}
