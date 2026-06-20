<?php
namespace App\Models;

use App\Core\Application;

class Blog
{
    public static function getAllPublished()
    {
        $stmt = Application::$app->db->prepare("
            SELECT b.*, u.username as author_name 
            FROM blogs b 
            JOIN users u ON b.author_id = u.id 
            WHERE b.status = 'published' 
            ORDER BY b.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getBySlug($slug)
    {
        $stmt = Application::$app->db->prepare("
            SELECT b.*, u.username as author_name 
            FROM blogs b 
            JOIN users u ON b.author_id = u.id 
            WHERE b.slug = ?
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public static function create($title, $slug, $content, $authorId)
    {
        $stmt = Application::$app->db->prepare("
            INSERT INTO blogs (title, slug, content, author_id) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$title, $slug, $content, $authorId]);
    }
}
