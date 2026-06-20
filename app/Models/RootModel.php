<?php
namespace App\Models;

use App\Core\Application;

class RootModel
{
    public static function getPendingFacultyRequests()
    {
        $stmt = Application::$app->db->prepare("SELECT id, username, email FROM users WHERE status = 'pending_architect'");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getAllUsers()
    {
        // Don't fetch the root user themselves to prevent self-lockout
        $stmt = Application::$app->db->prepare("SELECT id, username, email, role, status FROM users WHERE role != 'root' ORDER BY role ASC, username ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getSystemLogs($limit = 100)
    {
        $stmt = Application::$app->db->prepare("
            SELECT sl.*, u.username 
            FROM system_logs sl 
            LEFT JOIN users u ON sl.actor_id = u.id 
            ORDER BY sl.created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public static function updateUserStatusAndRole($userId, $newRole, $newStatus)
    {
        $stmt = Application::$app->db->prepare("UPDATE users SET role = ?, status = ? WHERE id = ?");
        return $stmt->execute([$newRole, $newStatus, $userId]);
    }
}
