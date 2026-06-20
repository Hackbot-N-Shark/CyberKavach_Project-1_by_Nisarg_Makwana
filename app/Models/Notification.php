<?php
namespace App\Models;

use App\Core\Application;

class Notification
{
    public static function create($targetType, $targetId, $message, $senderId)
    {
        $stmt = Application::$app->db->prepare("
            INSERT INTO notifications (target_type, target_id, message, sender_id)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$targetType, $targetId, $message, $senderId]);
    }

    public static function getForUser($userId, $limit = 10)
    {
        $userEvents = Event::getUserRegistrations($userId);
        $eventIds = array_column($userEvents, 'id');
        
        $placeholders = 'NULL';
        $params = ['general'];

        if (!empty($eventIds)) {
            $placeholders = implode(',', array_fill(0, count($eventIds), '?'));
            $params = array_merge($params, $eventIds);
        }

        $sql = "
            SELECT n.*, u.username as sender_name, u.role as sender_role
            FROM notifications n
            JOIN users u ON n.sender_id = u.id
            WHERE n.target_type = ? 
            OR (n.target_type = 'event' AND n.target_id IN ($placeholders))
            ORDER BY n.created_at DESC
            LIMIT ?
        ";
        
        $params[] = $limit;

        $stmt = Application::$app->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
