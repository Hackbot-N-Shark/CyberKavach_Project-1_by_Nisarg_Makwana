<?php
namespace App\Models;

use App\Core\Application;

class ContactModel
{
    public static function createMessage($name, $email, $message)
    {
        $stmt = Application::$app->db->prepare("
            INSERT INTO contact_messages (name, email, message) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$name, $email, $message]);
    }

    public static function getUnreadMessages()
    {
        $stmt = Application::$app->db->prepare("
            SELECT * FROM contact_messages 
            WHERE status = 'unread' 
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function markAsRead($id)
    {
        $stmt = Application::$app->db->prepare("
            UPDATE contact_messages SET status = 'read' WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }
}
