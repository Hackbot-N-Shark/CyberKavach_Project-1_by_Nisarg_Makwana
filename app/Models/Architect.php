<?php
namespace App\Models;

use App\Core\Application;

class Architect
{
    public static function getPendingRoleRequests()
    {
        $stmt = Application::$app->db->prepare("SELECT id, username, email FROM users WHERE status = 'pending_sudo'");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getActiveSudos()
    {
        $stmt = Application::$app->db->prepare("SELECT id, username, email FROM users WHERE role = 'sudo' AND status = 'active'");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function updateRoleStatus($userId, $newRole, $newStatus)
    {
        $stmt = Application::$app->db->prepare("UPDATE users SET role = ?, status = ? WHERE id = ?");
        return $stmt->execute([$newRole, $newStatus, $userId]);
    }

    public static function getPendingProposals()
    {
        $stmt = Application::$app->db->prepare("SELECT * FROM events WHERE status = 'pending_approval' ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function updateEventStatus($eventId, $newStatus)
    {
        $stmt = Application::$app->db->prepare("UPDATE events SET status = ? WHERE id = ?");
        return $stmt->execute([$newStatus, $eventId]);
    }

    public static function getPendingResources()
    {
        $stmt = Application::$app->db->prepare("SELECT rp.*, u.username FROM resource_publications rp JOIN users u ON rp.submitted_by = u.id WHERE rp.status = 'pending'");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function updateResourceStatus($resourceId, $newStatus)
    {
        $stmt = Application::$app->db->prepare("UPDATE resource_publications SET status = ? WHERE id = ?");
        return $stmt->execute([$newStatus, $resourceId]);
    }
}
