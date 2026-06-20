<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use App\Core\Application;
use App\Models\User;

$app = new Application(__DIR__ . '/../');

User::create('operative2', 'operative2@cyberkavach.local', 'password123', 'operative', 'active');
echo "Created new operative user: operative2\n";
