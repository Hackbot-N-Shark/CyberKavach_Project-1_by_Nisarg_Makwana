<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Application;
use App\Core\Auth;
use App\Core\Security;
use App\Core\Logger;
use App\Models\RootModel;

class RootController
{
    private function checkAccess()
    {
        $user = Auth::user();
        if (!$user || $user['role'] !== 'root') {
            die("SECURITY_VIOLATION: Director (Root) clearance required.");
        }
        return $user;
    }

    public function manageFaculty(Request $request)
    {
        $admin = $this->checkAccess();
        $body = $request->getBody();
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) die("CSRF Failed");

        $userId = $body['user_id'] ?? null;
        $action = $body['action'] ?? null;

        if ($userId && $action) {
            $targetUser = \App\Models\User::findById($userId);
            if ($targetUser && $targetUser['status'] === 'pending_architect') {
                if ($action === 'approve') {
                    RootModel::updateUserStatusAndRole($userId, 'architect', 'active');
                    Logger::log($admin['id'], 'FACULTY_APPROVED', "Approved Architect role for User ID: $userId");
                } elseif ($action === 'reject') {
                    RootModel::updateUserStatusAndRole($userId, 'operative', 'active');
                    Logger::log($admin['id'], 'FACULTY_REJECTED', "Rejected Architect role for User ID: $userId");
                }
            }
        }
        Application::$app->response->redirect('/dashboard');
    }

    public function overrideUser(Request $request)
    {
        $admin = $this->checkAccess();
        $body = $request->getBody();
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) die("CSRF Failed");

        $userId = $body['user_id'] ?? null;
        $role = $body['role'] ?? 'operative';
        $status = $body['status'] ?? 'active';

        if ($userId) {
            RootModel::updateUserStatusAndRole($userId, $role, $status);
            Logger::log($admin['id'], 'EMERGENCY_OVERRIDE', "Forced User ID: $userId to Role: $role, Status: $status");
        }
        Application::$app->response->redirect('/dashboard');
    }

    public function downloadBackup(Request $request)
    {
        $admin = $this->checkAccess();
        Logger::log($admin['id'], 'BACKUP_INITIATED', "Director downloaded the raw SQLite database.");

        $dbPath = __DIR__ . '/../../database/cyberkavach.sqlite';
        if (file_exists($dbPath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="cyberkavach_backup_' . date('Y-m-d_H-i') . '.sqlite"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($dbPath));
            readfile($dbPath);
            exit;
        } else {
            die("Database file not found.");
        }
    }
}
