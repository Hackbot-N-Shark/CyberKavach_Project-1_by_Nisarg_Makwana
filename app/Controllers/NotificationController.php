<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Application;
use App\Core\Auth;
use App\Core\Security;
use App\Models\Notification;
use App\Core\Logger;

class NotificationController
{
    public function getNotifications(Request $request)
    {
        header('Content-Type: application/json');
        
        if (Auth::isGuest()) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            return;
        }

        $user = Auth::user();
        $notifications = Notification::getForUser($user['id']);
        
        echo json_encode(['success' => true, 'data' => $notifications]);
    }

    public function broadcast(Request $request)
    {
        $user = Auth::user();
        if (!$user || !in_array($user['role'], ['sudo', 'architect', 'root'])) {
            die("SECURITY_VIOLATION: Unauthorized broadcast attempt.");
        }

        $body = $request->getBody();
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) {
            die("CSRF Failed");
        }

        $target = $body['target'] ?? '';
        $message = trim($body['message'] ?? '');

        if ($target && $message) {
            if ($target === 'general') {
                Notification::create('general', null, $message, $user['id']);
                Logger::log($user['id'], 'SYS_BROADCAST', "Sent general broadcast: " . substr($message, 0, 30));
            } else {
                $eventId = filter_var($target, FILTER_VALIDATE_INT);
                if ($eventId) {
                    Notification::create('event', $eventId, $message, $user['id']);
                    Logger::log($user['id'], 'EVT_BROADCAST', "Sent event broadcast to ID $eventId: " . substr($message, 0, 30));
                }
            }
        }

        Application::$app->response->redirect('/dashboard');
    }
}
