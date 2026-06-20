<?php
namespace App\Core;

class Security
{
    public static function generateCsrfToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateCsrfToken($token)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }
        return true;
    }

    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    // Basic brute-force prevention rate limiter using session (for local dev purposes)
    public static function checkRateLimit($action, $limit = 5, $windowSeconds = 60)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $key = 'rate_limit_' . $action;
        $now = time();
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 1, 'start_time' => $now];
            return true;
        }

        if ($now - $_SESSION[$key]['start_time'] > $windowSeconds) {
            $_SESSION[$key] = ['count' => 1, 'start_time' => $now];
            return true;
        }

        if ($_SESSION[$key]['count'] >= $limit) {
            return false;
        }

        $_SESSION[$key]['count']++;
        return true;
    }
}
