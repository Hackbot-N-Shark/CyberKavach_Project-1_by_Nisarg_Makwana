<?php
namespace App\Models;

use App\Core\Application;

class Coordinator
{
    public static function createProposal($title, $description, $eventDate, $maxParticipants = null)
    {
        $stmt = Application::$app->db->prepare("INSERT INTO events (title, description, event_date, status, max_participants) VALUES (?, ?, ?, 'pending_approval', ?)");
        return $stmt->execute([$title, $description, $eventDate, $maxParticipants]);
    }

    public static function getProposedEvents()
    {
        $stmt = Application::$app->db->prepare("SELECT * FROM events WHERE status IN ('pending_approval', 'upcoming', 'completed') ORDER BY event_date DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function addVolunteer($eventId, $userId, $roleDesc)
    {
        $stmt = Application::$app->db->prepare("INSERT INTO event_volunteers (event_id, user_id, role_description) VALUES (?, ?, ?)");
        return $stmt->execute([$eventId, $userId, $roleDesc]);
    }

    public static function getVolunteers($eventId)
    {
        $stmt = Application::$app->db->prepare("SELECT ev.*, u.username FROM event_volunteers ev JOIN users u ON ev.user_id = u.id WHERE ev.event_id = ?");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }

    public static function saveAttendance($eventId, $userId, $attended, $rank)
    {
        $markedBy = \App\Core\Auth::user()['id'] ?? 1;
        
        $stmt = Application::$app->db->prepare("DELETE FROM event_attendance WHERE event_id = ? AND user_id = ?");
        $stmt->execute([$eventId, $userId]);
        
        if ($attended) {
            $stmt = Application::$app->db->prepare("INSERT INTO event_attendance (event_id, user_id, rank, marked_by) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$eventId, $userId, $rank, $markedBy]);
        }
        return true;
    }

    public static function getRegistrationsForAttendance($eventId)
    {
        // Returns users registered for the event, along with their attendance if marked
        $stmt = Application::$app->db->prepare("
            SELECT er.user_id, u.username, 
                   CASE WHEN ea.id IS NOT NULL THEN 1 ELSE 0 END as attended, 
                   ea.rank 
            FROM event_registrations er 
            JOIN users u ON er.user_id = u.id 
            LEFT JOIN event_attendance ea ON er.event_id = ea.event_id AND er.user_id = ea.user_id
            WHERE er.event_id = ?
        ");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }
    
    public static function initiateCertificate($eventId, $templatePath, $uploadedBy)
    {
        $stmt = Application::$app->db->prepare("
            INSERT INTO event_certificates (event_id, template_path, uploaded_by) 
            VALUES (?, ?, ?)
            ON CONFLICT(event_id) DO UPDATE SET 
                template_path=excluded.template_path,
                status='Pending Faculty Signature',
                uploaded_by=excluded.uploaded_by,
                uploaded_at=CURRENT_TIMESTAMP
        ");
        return $stmt->execute([$eventId, $templatePath, $uploadedBy]);
    }
}
