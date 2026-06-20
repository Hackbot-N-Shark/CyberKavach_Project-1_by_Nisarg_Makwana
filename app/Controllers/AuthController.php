<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Application;
use App\Core\Security;
use App\Core\Auth;
use App\Models\User;

class AuthController
{
    public function login(Request $request)
    {
        if (!Auth::isGuest()) {
            Application::$app->response->redirect('/');
        }
        return Application::$app->router->renderView('auth/login', ['title' => 'ACCESS_SYS // Login']);
    }

    public function loginPost(Request $request)
    {
        if (!Security::validateCsrfToken($request->getBody()['csrf_token'] ?? '')) {
            die("SECURITY_VIOLATION: CSRF Token Validation Failed");
        }
        if (!Security::checkRateLimit('login_attempt', 5, 60)) {
            die("SECURITY_VIOLATION: Rate limit exceeded. Too many login attempts. Cooldown initiated.");
        }

        $body = $request->getBody();
        $username = $body['username'] ?? '';
        $password = $body['password'] ?? '';

        $user = User::findByUsername($username);
        if ($user && password_verify($password, $user['password_hash'])) {
            if (in_array($user['status'], ['pending', 'pending_sudo', 'pending_architect'], true)) {
                 return Application::$app->router->renderView('auth/login', [
                    'title' => 'ACCESS_SYS // Login',
                    'error' => 'Your clearance is currently PENDING approval.'
                ]);
            }
            if ($user['status'] === 'rejected') {
                 return Application::$app->router->renderView('auth/login', [
                    'title' => 'ACCESS_SYS // Login',
                    'error' => 'Your clearance request has been REJECTED.'
                ]);
            }
            
            Auth::login($user);
            Application::$app->response->redirect('/dashboard');
        }

        return Application::$app->router->renderView('auth/login', [
            'title' => 'ACCESS_SYS // Login',
            'error' => 'Invalid credentials. Access Denied.'
        ]);
    }

    public function register(Request $request)
    {
        if (!Auth::isGuest()) {
            Application::$app->response->redirect('/');
        }
        return Application::$app->router->renderView('auth/register', ['title' => 'INIT_PROTOCOL // Register']);
    }

    public function registerPost(Request $request)
    {
        if (!Security::validateCsrfToken($request->getBody()['csrf_token'] ?? '')) {
            die("SECURITY_VIOLATION: CSRF Token Validation Failed");
        }

        $body = $request->getBody();
        $username = trim($body['username'] ?? '');
        $email = filter_var(trim($body['email'] ?? ''), FILTER_VALIDATE_EMAIL) ?: '';
        $password = $body['password'] ?? '';
        $role = $body['role'] ?? 'operative';

        if (!in_array($role, ['operative', 'sudo', 'architect'], true)) {
            $role = 'operative';
        }

        if (!$username || !$email || !$password) {
            return Application::$app->router->renderView('auth/register', [
                'title' => 'INIT_PROTOCOL // Register',
                'error' => 'All fields are required to initiate enrollment.'
            ]);
        }

        if (strlen($password) < 8) {
            return Application::$app->router->renderView('auth/register', [
                'title' => 'INIT_PROTOCOL // Register',
                'error' => 'Passkey must be at least 8 characters long.'
            ]);
        }

        if (User::findByUsername($username) || User::findByEmail($email)) {
            return Application::$app->router->renderView('auth/register', [
                'title' => 'INIT_PROTOCOL // Register',
                'error' => 'Alias or Email already active in the mainframe.'
            ]);
        }

        $status = 'active';
        if ($role === 'sudo') {
            $status = 'pending_sudo';
        } elseif ($role === 'architect') {
            $status = 'pending_architect';
        }

        if (in_array($role, ['sudo', 'architect'], true)) {
            $pendingCount = User::countPending(['pending_sudo', 'pending_architect']);
            if ($pendingCount >= 5) {
                return Application::$app->router->renderView('auth/register', [
                    'title' => 'INIT_PROTOCOL // Register',
                    'error' => 'Registrations are Temporarily Closed.'
                ]);
            }
        }

        if (User::create($username, $email, $password, $role, $status)) {
            if (in_array($status, ['pending_sudo', 'pending_architect'], true)) {
                return Application::$app->router->renderView('auth/login', [
                    'title' => 'ACCESS_SYS // Login',
                    'error' => 'Registration successful. Awaiting higher clearance approval.'
                ]);
            }
            Application::$app->response->redirect('/login');
        }

        return Application::$app->router->renderView('auth/register', [
            'title' => 'INIT_PROTOCOL // Register',
            'error' => 'Registration failed due to an internal system anomaly.'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Application::$app->response->redirect('/');
    }

    public function forgotPassword(Request $request)
    {
        if (!Auth::isGuest()) {
            Application::$app->response->redirect('/dashboard');
        }
        return Application::$app->router->renderView('auth/forgot-password', ['title' => 'RECOVERY_PROTOCOL // Forgot Passkey']);
    }

    public function forgotPasswordPost(Request $request)
    {
        if (!Security::validateCsrfToken($request->getBody()['csrf_token'] ?? '')) {
            die("SECURITY_VIOLATION: CSRF Token Validation Failed");
        }

        $body = $request->getBody();
        $username = trim($body['username'] ?? '');

        if (!$username) {
            return Application::$app->router->renderView('auth/forgot-password', [
                'title' => 'RECOVERY_PROTOCOL // Forgot Passkey',
                'error' => 'Valid operative alias required.'
            ]);
        }

        $user = User::findByUsername($username);
        if ($user) {
            // Save request to password_resets table using a special token flag 'REQUESTED'
            // We use the email to adhere to the existing schema, but we query by it later
            User::savePasswordResetToken($user['email'], 'REQUESTED');
            
            return Application::$app->router->renderView('auth/forgot-password', [
                'title' => 'RECOVERY_PROTOCOL // Forgot Passkey',
                'success' => 'Request transmitted to the Director. Await secure comms.'
            ]);
        }

        // Always show success to prevent alias enumeration
        return Application::$app->router->renderView('auth/forgot-password', [
            'title' => 'RECOVERY_PROTOCOL // Forgot Passkey',
            'success' => 'If the alias exists, a reset request has been transmitted.'
        ]);
    }

    public function forceChangePassword(Request $request)
    {
        return Application::$app->router->renderView('auth/force-change-password', ['title' => 'MANDATORY_OVERRIDE']);
    }

    public function forceChangePasswordPost(Request $request)
    {
        if (!Security::validateCsrfToken($request->getBody()['csrf_token'] ?? '')) {
            die("SECURITY_VIOLATION: CSRF Token Validation Failed");
        }

        $body = $request->getBody();
        $password = $body['password'] ?? '';
        
        if (strlen($password) < 8) {
            return Application::$app->router->renderView('auth/force-change-password', [
                'title' => 'MANDATORY_OVERRIDE',
                'error' => 'Passkey must be at least 8 characters long.'
            ]);
        }
        
        $user = Auth::user();
        User::updatePassword($user['id'], $password);
        
        // Update session to reflect new user state (must_change_password is now 0)
        $_SESSION['user'] = User::findById($user['id']);
        
        Application::$app->response->redirect('/dashboard');
    }

    public function resetPassword(Request $request)
    {
        if (!Auth::isGuest()) {
            Application::$app->response->redirect('/dashboard');
        }

        $token = $_GET['token'] ?? '';
        if (!$token || !User::getPasswordResetToken($token)) {
            die("SECURITY_VIOLATION: Invalid or expired reset token.");
        }

        return Application::$app->router->renderView('auth/reset-password', [
            'title' => 'RECOVERY_PROTOCOL // Reset Passkey',
            'token' => $token
        ]);
    }

    public function resetPasswordPost(Request $request)
    {
        if (!Security::validateCsrfToken($request->getBody()['csrf_token'] ?? '')) {
            die("SECURITY_VIOLATION: CSRF Token Validation Failed");
        }

        $body = $request->getBody();
        $token = $body['token'] ?? '';
        $password = $body['password'] ?? '';

        $resetRecord = User::getPasswordResetToken($token);
        if (!$resetRecord) {
            die("SECURITY_VIOLATION: Invalid or expired reset token.");
        }

        if (strlen($password) < 8) {
            return Application::$app->router->renderView('auth/reset-password', [
                'title' => 'RECOVERY_PROTOCOL // Reset Passkey',
                'token' => $token,
                'error' => 'Passkey must be at least 8 characters long.'
            ]);
        }

        $user = User::findByEmail($resetRecord['email']);
        if ($user) {
            User::updatePassword($user['id'], $password);
            User::deletePasswordResetToken($token);
            \App\Core\Logger::log($user['id'], 'PASSWORD_RESET', "User recovered access via reset token.");
            return Application::$app->router->renderView('auth/login', [
                'title' => 'ACCESS_SYS // Login',
                'success' => 'Passkey successfully overridden. You may now log in.'
            ]);
        }

        die("SECURITY_VIOLATION: Associated user not found.");
    }
}
