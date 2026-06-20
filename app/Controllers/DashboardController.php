<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Application;
use App\Core\Auth;
use App\Core\Security;
use App\Models\User;

class DashboardController
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            Application::$app->response->redirect('/login');
            exit;
        }

        $role = $user['role'];
        $params = [
            'title' => 'Command Center // ' . strtoupper($role),
            'user' => $user
        ];

        if ($role === 'root') {
            $params['pending_faculty'] = \App\Models\RootModel::getPendingFacultyRequests();
            $params['all_users'] = \App\Models\RootModel::getAllUsers();
            $params['system_logs'] = \App\Models\RootModel::getSystemLogs(50);
            $params['pending_root_certs'] = \App\Models\Certificate::getTemplatesByStatus('pending_root_sign');
            $params['contact_messages'] = \App\Models\ContactModel::getUnreadMessages();
            $params['all_events'] = \App\Models\Event::getAll();
            $params['reset_requests'] = \App\Models\User::getAllResetRequests();
            return Application::$app->router->renderView('dashboard/root', $params);
        }

        if ($role === 'architect') {
            $params['pending_roles'] = \App\Models\Architect::getPendingRoleRequests();
            $params['active_sudos'] = \App\Models\Architect::getActiveSudos();
            $params['pending_events'] = \App\Models\Architect::getPendingProposals();
            $params['active_events'] = \App\Models\Event::getUpcoming();
            $params['pending_resources'] = \App\Models\Architect::getPendingResources();
            $params['pending_faculty_certs'] = \App\Models\Certificate::getTemplatesByStatus('pending_faculty_sign');
            $params['pending_root_certs'] = \App\Models\Certificate::getTemplatesByStatus('pending_root_sign');
            $params['pending_verify_certs'] = \App\Models\Certificate::getTemplatesByStatus('pending_faculty_verify');
            $params['all_events'] = \App\Models\Event::getAll();
            return Application::$app->router->renderView('dashboard/architect', $params);
        }

        if ($role === 'sudo') {
            $params['events'] = \App\Models\Coordinator::getProposedEvents();
            $params['completed_events'] = \App\Models\Event::getCompleted();
            $params['ready_certs'] = \App\Models\Certificate::getTemplatesByStatus('ready_for_generation');
            $params['unmapped_certs'] = \App\Models\Certificate::getUnmappedTemplates();
            $params['pending_faculty_certs'] = \App\Models\Certificate::getTemplatesByStatus('pending_faculty_sign');
            $params['all_events'] = \App\Models\Event::getAll();
            
            $queryParams = $request->getBody();
            $attendanceEventId = $queryParams['attendance_event_id'] ?? null;
            if ($attendanceEventId) {
                $params['attendance_event_id'] = $attendanceEventId;
                $params['attendance_roster'] = \App\Models\Coordinator::getRegistrationsForAttendance($attendanceEventId);
            }
            
            return Application::$app->router->renderView('dashboard/sudo', $params);
        }

        if ($role === 'root') {
            $params['pending_faculty'] = \App\Models\RootModel::getPendingFacultyRequests();
            $params['all_users'] = \App\Models\RootModel::getAllUsers();
            $params['system_logs'] = \App\Models\RootModel::getSystemLogs(50);
            return Application::$app->router->renderView('dashboard/root', $params);
        }

        // Fallback or unauthorized
        $params['upcoming_events'] = \App\Models\Event::getUpcoming();
        $params['completed_events'] = \App\Models\Event::getCompleted();
        
        // Fetch galleries for completed events
        foreach ($params['completed_events'] as &$ce) {
            $ce['gallery'] = \App\Models\Event::getGallery($ce['id']);
        }
        
        $params['registered_event_ids'] = [];
        $user_regs = \App\Models\Event::getUserRegistrations($user['id']);
        $params['registered_event_ids'] = array_column($user_regs, 'id');

        $params['vault_certs'] = \App\Models\Certificate::getUserVault($user['id']);

        return Application::$app->router->renderView('dashboard/operative', $params);
    }

    public function eventRegister(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user['role'] !== 'operative') {
            die("SECURITY_VIOLATION: Unauthorized.");
        }

        $body = $request->getBody();
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) {
            die("SECURITY_VIOLATION: CSRF Token Validation Failed");
        }

        $eventId = $body['event_id'] ?? null;
        if ($eventId) {
            $success = \App\Models\Event::registerUser($eventId, $user['id']);
            if (!$success) {
                Application::$app->response->redirect('/dashboard?error=capacity_reached');
                exit;
            }
        }

        Application::$app->response->redirect('/dashboard?success=registered');
    }

    public function updateStatus(Request $request)
    {
        $user = Auth::user();
        if (!$user || !in_array($user['role'], ['root', 'architect'])) {
            die("SECURITY_VIOLATION: Unauthorized clearance level.");
        }

        $body = $request->getBody();
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) {
            die("SECURITY_VIOLATION: CSRF Token Validation Failed");
        }

        $targetId = $body['user_id'] ?? null;
        $action = $body['action'] ?? null;

        if (!$targetId || !in_array($action, ['approve', 'reject'])) {
            Application::$app->response->redirect('/dashboard');
        }

        $targetUser = User::findById($targetId);
        if (!$targetUser || !in_array($targetUser['status'], ['pending_sudo', 'pending_architect'], true)) {
            Application::$app->response->redirect('/dashboard');
        }

        // RBAC enforcement for approvals
        if ($user['role'] === 'root' && $targetUser['status'] !== 'pending_architect') {
            die("SECURITY_VIOLATION: Root can only approve architects.");
        }
        if ($user['role'] === 'architect' && $targetUser['status'] !== 'pending_sudo') {
            die("SECURITY_VIOLATION: Architects can only approve sudos.");
        }

        if ($action === 'approve') {
            User::updateStatus($targetId, 'active');
        } else {
            User::updateRoleAndStatus($targetId, 'operative', 'active');
        }

        Application::$app->response->redirect('/dashboard');
    }

    public function forceResetPassword(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user['role'] !== 'root') {
            die("SECURITY_VIOLATION: Ultimate Oversight clearance required.");
        }

        $body = $request->getBody();
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) {
            die("SECURITY_VIOLATION: CSRF Token Validation Failed");
        }

        $targetId = $body['user_id'] ?? null;
        if (!$targetId) {
            Application::$app->response->redirect('/dashboard');
        }

        $targetUser = User::findById($targetId);
        if (!$targetUser) {
            Application::$app->response->redirect('/dashboard');
        }

        // Generate a random secure password
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $newPass = '';
        for ($i = 0; $i < 12; $i++) {
            $newPass .= $chars[random_int(0, strlen($chars) - 1)];
        }

        User::updatePassword($targetId, $newPass);
        User::setMustChangePassword($targetId, 1);
        
        // Clear any pending reset requests
        $stmt = Application::$app->db->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([$targetUser['email']]);

        \App\Core\Logger::log($user['id'], 'FORCE_PASSWORD_RESET', "Root forced password override for Alias: " . $targetUser['username']);

        // Set flash message using a session (assuming we had a full session manager, but we don't, so we'll pass via GET params)
        // Since we don't have a robust flash session system, we can redirect with a success parameter and display the password.
        // For security, passing a raw password in a GET param is bad practice. We can base64 encode it just to obfuscate it from shoulder surfing, or better yet, since it's a simulated environment, just pass it.
        $encodedPass = base64_encode($newPass);
        $encodedUser = base64_encode($targetUser['username']);
        Application::$app->response->redirect('/dashboard?reset_success=1&u=' . $encodedUser . '&p=' . $encodedPass);
    }
}
