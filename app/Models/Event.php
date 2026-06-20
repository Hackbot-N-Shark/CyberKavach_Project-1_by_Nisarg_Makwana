<?php
namespace App\Models;

use App\Core\Application;

class Event
{
    public static function getAll()
    {
        $stmt = Application::$app->db->prepare("SELECT * FROM events ORDER BY event_date ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getUpcoming()
    {
        $stmt = Application::$app->db->prepare("SELECT * FROM events WHERE status = 'upcoming' ORDER BY event_date ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getCompleted()
    {
        $stmt = Application::$app->db->prepare("SELECT * FROM events WHERE status = 'completed' ORDER BY event_date DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function registerUser($eventId, $userId)
    {
        // Check if already registered
        $stmt = Application::$app->db->prepare("SELECT COUNT(*) as count FROM event_registrations WHERE event_id = ? AND user_id = ?");
        $stmt->execute([$eventId, $userId]);
        if ($stmt->fetch()['count'] > 0) {
            return false;
        }

        // Check max_participants limit
        $stmt = Application::$app->db->prepare("SELECT max_participants FROM events WHERE id = ?");
        $stmt->execute([$eventId]);
        $event = $stmt->fetch();
        if ($event && $event['max_participants'] !== null) {
            $stmt = Application::$app->db->prepare("SELECT COUNT(*) as count FROM event_registrations WHERE event_id = ?");
            $stmt->execute([$eventId]);
            $currentCount = $stmt->fetch()['count'];
            if ($currentCount >= $event['max_participants']) {
                return false; // Capacity reached
            }
        }

        $stmt = Application::$app->db->prepare("INSERT INTO event_registrations (event_id, user_id) VALUES (?, ?)");
        return $stmt->execute([$eventId, $userId]);
    }

    public static function exists($eventId)
    {
        $stmt = Application::$app->db->prepare("SELECT 1 FROM events WHERE id = ?");
        $stmt->execute([$eventId]);
        return (bool) $stmt->fetchColumn();
    }

    public static function getUserRegistrations($userId)
    {
        $stmt = Application::$app->db->prepare("SELECT e.* FROM events e JOIN event_registrations er ON e.id = er.event_id WHERE er.user_id = ? ORDER BY e.event_date ASC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function deleteEvent($eventId)
    {
        $stmt = Application::$app->db->prepare("DELETE FROM events WHERE id = ?");
        return $stmt->execute([$eventId]);
    }

    public static function addGalleryImage($eventId, $imagePath, $uploadedBy)
    {
        $stmt = Application::$app->db->prepare("INSERT INTO event_gallery (event_id, image_path, uploaded_by) VALUES (?, ?, ?)");
        return $stmt->execute([$eventId, $imagePath, $uploadedBy]);
    }

    public static function getGallery($eventId)
    {
        $stmt = Application::$app->db->prepare("SELECT * FROM event_gallery WHERE event_id = ? ORDER BY uploaded_at DESC");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }

    public static function getAllGalleryImages()
    {
        $stmt = Application::$app->db->prepare("
            SELECT eg.*, e.title as event_title 
            FROM event_gallery eg 
            JOIN events e ON eg.event_id = e.id 
            ORDER BY eg.uploaded_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
