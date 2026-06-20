<?php
namespace App\Core;

class Logger
{
    public static function log($userId, $action, $details = '')
    {
        $stmt = Application::$app->db->prepare("INSERT INTO system_logs (actor_id, action, details) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $action, $details]);
    }
}
