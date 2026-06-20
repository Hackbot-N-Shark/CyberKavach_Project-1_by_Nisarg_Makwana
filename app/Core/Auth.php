<?php
namespace App\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class Auth
{
    public static function generateToken($user)
    {
        $secret = $_ENV['JWT_SECRET'] ?? 'default_secret';
        $expiration = $_ENV['JWT_EXPIRATION'] ?? 86400;

        $payload = [
            'iss' => $_ENV['APP_URL'] ?? 'http://localhost',
            'aud' => $_ENV['APP_URL'] ?? 'http://localhost',
            'iat' => time(),
            'exp' => time() + $expiration,
            'data' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ]
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    public static function validateToken($token)
    {
        $secret = $_ENV['JWT_SECRET'] ?? 'default_secret';
        try {
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            return (array) $decoded->data;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function login($user)
    {
        $token = self::generateToken($user);
        // Set HTTP Only cookie for security
        setcookie('auth_token', $token, time() + ($_ENV['JWT_EXPIRATION'] ?? 86400), '/', '', false, true);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
    }

    public static function logout()
    {
        setcookie('auth_token', '', time() - 3600, '/');
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['user']);
        session_destroy();
    }

    public static function user()
    {
        // Try session first
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }

        // Fallback to JWT Cookie
        if (isset($_COOKIE['auth_token'])) {
            $data = self::validateToken($_COOKIE['auth_token']);
            if ($data) {
                $_SESSION['user'] = $data;
                return $data;
            }
        }
        return null;
    }

    public static function isGuest()
    {
        return self::user() === null;
    }
}
