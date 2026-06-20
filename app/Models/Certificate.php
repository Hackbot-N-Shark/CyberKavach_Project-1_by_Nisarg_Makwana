<?php
namespace App\Models;

use App\Core\Application;

class Certificate
{
    public static function createTemplate($eventId, $templatePath, $uploadedBy)
    {
        $stmt = Application::$app->db->prepare("
            INSERT INTO event_certificates (event_id, template_path, uploaded_by, status) 
            VALUES (?, ?, ?, 'pending_faculty_sign')
            ON CONFLICT(event_id) DO UPDATE SET 
                template_path = excluded.template_path,
                uploaded_by = excluded.uploaded_by,
                status = 'pending_faculty_sign'
        ");
        return $stmt->execute([$eventId, $templatePath, $uploadedBy]);
    }

    public static function getTemplatesByStatus($status)
    {
        $stmt = Application::$app->db->prepare("
            SELECT ec.*, e.title as event_title 
            FROM event_certificates ec 
            JOIN events e ON ec.event_id = e.id 
            WHERE ec.status = ?
        ");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    public static function getUnmappedTemplates()
    {
        $stmt = Application::$app->db->prepare("
            SELECT ec.*, e.title as event_title 
            FROM event_certificates ec 
            JOIN events e ON ec.event_id = e.id 
            WHERE ec.mapping_config IS NULL
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function updateStatus($id, $status)
    {
        $stmt = Application::$app->db->prepare("UPDATE event_certificates SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public static function getTemplateById($id)
    {
        $stmt = Application::$app->db->prepare("
            SELECT ec.*, e.title as event_title 
            FROM event_certificates ec 
            JOIN events e ON ec.event_id = e.id 
            WHERE ec.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function saveGeneratedCertificate($eventId, $userId, $certPath)
    {
        // First check if a vault table exists. We'll use a simple vault table. 
        // Let's create it if it doesn't exist, but typically we do migrations.
        // I will add a migration for `vault_certificates` or we can just use `event_certificates` but it's 1-to-many.
        // Let's assume a table `vault` exists or create it dynamically.
        $stmt = Application::$app->db->prepare("
            CREATE TABLE IF NOT EXISTS user_vault (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                event_id INTEGER NOT NULL,
                cert_path VARCHAR(255) NOT NULL,
                generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY(user_id) REFERENCES users(id),
                FOREIGN KEY(event_id) REFERENCES events(id)
            )
        ");
        $stmt->execute();

        $stmt = Application::$app->db->prepare("INSERT INTO user_vault (user_id, event_id, cert_path) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $eventId, $certPath]);
    }

    public static function getUserVault($userId)
    {
        // First ensure table exists to prevent crash if vault is empty
        $stmt = Application::$app->db->prepare("
            CREATE TABLE IF NOT EXISTS user_vault (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                event_id INTEGER NOT NULL,
                cert_path VARCHAR(255) NOT NULL,
                generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY(user_id) REFERENCES users(id),
                FOREIGN KEY(event_id) REFERENCES events(id)
            )
        ");
        $stmt->execute();

        $stmt = Application::$app->db->prepare("
            SELECT uv.*, e.title as event_title 
            FROM user_vault uv 
            JOIN events e ON uv.event_id = e.id 
            WHERE uv.user_id = ? 
            ORDER BY uv.generated_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
