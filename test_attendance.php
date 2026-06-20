<?php
$pdo = new PDO('sqlite:database/cyberkavach.sqlite');
print_r($pdo->query('PRAGMA table_info(event_attendance)')->fetchAll(PDO::FETCH_ASSOC));
