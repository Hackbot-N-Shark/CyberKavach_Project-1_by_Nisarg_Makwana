<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use App\Core\Application;
use App\Models\User;

$app = new Application(__DIR__ . '/../');

$users = [
    ['username' => 'boss', 'email' => 'boss@cyberkavach.local', 'password' => 'password123', 'role' => 'root', 'status' => 'active'],
    ['username' => 'faculty1', 'email' => 'faculty1@cyberkavach.local', 'password' => 'password123', 'role' => 'architect', 'status' => 'active'],
    ['username' => 'coord1', 'email' => 'coord1@cyberkavach.local', 'password' => 'password123', 'role' => 'sudo', 'status' => 'active'],
    ['username' => 'user1', 'email' => 'user1@cyberkavach.local', 'password' => 'password123', 'role' => 'operative', 'status' => 'active']
];

foreach ($users as $u) {
    if (!User::findByUsername($u['username'])) {
        User::create($u['username'], $u['email'], $u['password'], $u['role'], $u['status']);
        echo "Created user: {$u['username']} ({$u['role']})\n";
    } else {
        echo "User already exists: {$u['username']}\n";
    }
}
