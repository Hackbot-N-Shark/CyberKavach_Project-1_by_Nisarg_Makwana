<?php
$pdo = new PDO('sqlite:database/cyberkavach.sqlite');
print_r($pdo->query('PRAGMA table_info(system_logs)')->fetchAll(PDO::FETCH_ASSOC));
