<?php
namespace App\Core;

class AuthMiddleware extends Middleware
{
    public array $roles = [];

    public function __construct(array $roles = [])
    {
        $this->roles = $roles;
    }

    public function execute()
    {
        if (Auth::isGuest()) {
            Application::$app->response->redirect('/login');
            exit;
        }

        $sessionUser = Auth::user();

        // Fetch fresh data from DB to ensure real-time enforcement of flags
        $user = \App\Models\User::findById($sessionUser['id']);
        if (!$user) {
            Auth::logout();
            Application::$app->response->redirect('/login');
            exit;
        }

        $path = Application::$app->request->getPath();

        if (!empty($user['must_change_password'])) {
            if ($path !== '/force-change-password') {
                Application::$app->response->redirect('/force-change-password');
                exit;
            }
        } else {
            if ($path === '/force-change-password') {
                Application::$app->response->redirect('/dashboard');
                exit;
            }
        }

        if (!empty($this->roles)) {
            if (!in_array($user['role'], $this->roles)) {
                Application::$app->response->setStatusCode(403);
                die("403 Forbidden: You do not have the required clearance level.");
            }
        }
    }
}
