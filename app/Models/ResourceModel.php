<?php
namespace App\Models;

use App\Core\Application;

class ResourceModel
{
    public static function getApprovedResources()
    {
        $stmt = Application::$app->db->prepare("
            SELECT rp.*, u.username as author_name 
            FROM resource_publications rp 
            JOIN users u ON rp.submitted_by = u.id 
            WHERE rp.status = 'approved' 
            ORDER BY rp.submitted_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
