<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Application;
use App\Core\Auth;
use App\Core\Security;
use App\Core\Logger;
use App\Models\Blog;

class AdminBlogController
{
    public function createBlog(Request $request)
    {
        $user = Auth::user();
        if (!$user || !in_array($user['role'], ['architect', 'sudo', 'root'])) {
            die("Unauthorized");
        }

        $body = $request->getBody();
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) {
            die("CSRF Failed");
        }

        $title = $body['title'] ?? '';
        $content = $body['content'] ?? '';
        
        if (!empty($title) && !empty($content)) {
            // Simple slug generation
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            // Ensure unique
            $slug .= '-' . time();

            if (Blog::create($title, $slug, $content, $user['id'])) {
                Logger::log($user['id'], 'BLOG_PUBLISHED', "Published article: $title");
            }
        }

        Application::$app->response->redirect('/dashboard');
    }
}
