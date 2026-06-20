<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Application;
use App\Core\Auth;
use App\Core\Security;
use App\Models\Architect;

class ArchitectController
{
    private function checkAccess()
    {
        $user = Auth::user();
        if (!$user || $user['role'] !== 'architect') {
            die("SECURITY_VIOLATION: Core Faculty (Architect) clearance required.");
        }
        return $user;
    }

    public function manageRoleRequest(Request $request)
    {
        $this->checkAccess();
        $body = $request->getBody();
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) die("CSRF Failed");

        $userId = $body['user_id'] ?? null;
        $action = $body['action'] ?? null;

        if ($userId && $action) {
            $targetUser = \App\Models\User::findById($userId);
            if ($targetUser) {
                if ($action === 'approve' && $targetUser['status'] === 'pending_sudo') {
                    Architect::updateRoleStatus($userId, 'sudo', 'active');
                } elseif ($action === 'reject' && $targetUser['status'] === 'pending_sudo') {
                    Architect::updateRoleStatus($userId, 'operative', 'active');
                } elseif ($action === 'revoke' && $targetUser['role'] === 'sudo' && $targetUser['status'] === 'active') {
                    Architect::updateRoleStatus($userId, 'operative', 'active');
                }
            }
        }
        Application::$app->response->redirect('/dashboard');
    }

    public function manageEvent(Request $request)
    {
        $this->checkAccess();
        $body = $request->getBody();
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) die("CSRF Failed");

        $eventId = $body['event_id'] ?? null;
        $action = $body['action'] ?? null;

        if ($eventId && $action) {
            if ($action === 'publish') {
                Architect::updateEventStatus($eventId, 'upcoming');
            } elseif ($action === 'reject') {
                Architect::updateEventStatus($eventId, 'rejected');
            }
        }
        Application::$app->response->redirect('/dashboard');
    }

    public function manageResource(Request $request)
    {
        $this->checkAccess();
        $body = $request->getBody();
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) die("CSRF Failed");

        $resourceId = $body['resource_id'] ?? null;
        $action = $body['action'] ?? null;

        if ($resourceId && $action) {
            if ($action === 'approve') {
                Architect::updateResourceStatus($resourceId, 'published');
            } elseif ($action === 'reject') {
                Architect::updateResourceStatus($resourceId, 'rejected');
            }
        }
        Application::$app->response->redirect('/dashboard');
    }
}
