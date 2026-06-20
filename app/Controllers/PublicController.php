<?php
namespace App\Controllers;

use App\Core\Application;
use App\Models\Blog;
use App\Models\ResourceModel;

class PublicController
{
    public function index()
    {
        // Public homepage or redirect to blog for now
        Application::$app->response->redirect('/blog');
    }

    public function blogIndex()
    {
        if (\App\Core\Auth::user()['role'] !== 'root') {
            Application::$app->response->setStatusCode(403);
            die("Access Denied: Root Clearance Required.");
        }
        $params = [
            'blogs' => Blog::getAllPublished()
        ];
        return Application::$app->router->renderView('public/blog', $params);
    }

    public function blogView()
    {
        if (\App\Core\Auth::user()['role'] !== 'root') {
            Application::$app->response->setStatusCode(403);
            die("Access Denied: Root Clearance Required.");
        }
        $slug = $_GET['slug'] ?? '';
        if (!$slug) {
            Application::$app->response->redirect('/blog');
        }

        $blog = Blog::getBySlug($slug);
        if (!$blog) {
            // 404
            Application::$app->response->setStatusCode(404);
            return Application::$app->router->renderView('_404');
        }

        $params = [
            'blog' => $blog
        ];
        return Application::$app->router->renderView('public/blog_view', $params);
    }


    public function globalGallery()
    {
        $params = [
            'gallery_images' => \App\Models\Event::getAllGalleryImages()
        ];
        return Application::$app->router->renderView('public/gallery', $params);
    }

    public function contactForm()
    {
        return Application::$app->router->renderView('public/contact');
    }

    public function submitContact(\App\Core\Request $request)
    {
        if (!Security::validateCsrfToken($request->getBody()['csrf_token'] ?? '')) {
            die("SECURITY_VIOLATION: CSRF Token Validation Failed");
        }

        $body = $request->getBody();
        $name = trim($body['name'] ?? '');
        $email = filter_var(trim($body['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $message = trim($body['message'] ?? '');

        if ($name && $email && $message) {
            \App\Models\ContactModel::createMessage(strip_tags($name), $email, strip_tags($message));
            Application::$app->response->redirect('/contact?success=1');
            return;
        }

        Application::$app->response->redirect('/contact?success=0');
    }

    public function newsletter()
    {
        return Application::$app->router->renderView('public/newsletter');
    }
}
