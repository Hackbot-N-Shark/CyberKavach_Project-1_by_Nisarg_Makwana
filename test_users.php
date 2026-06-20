<?php
$db = new PDO('sqlite:' . __DIR__ . '/database/cyberkavach.sqlite');
$stmt = $db->query("SELECT username, must_change_password FROM users");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['username'] . ": " . ($row['must_change_password'] ?? 'NULL') . "\n";
}
