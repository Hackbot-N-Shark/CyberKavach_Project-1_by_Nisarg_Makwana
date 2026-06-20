<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Application;
use App\Core\Auth;
use App\Core\Security;
use App\Models\Coordinator;

class CoordinatorController
{
    private function checkAccess()
    {
        $user = Auth::user();
        if (!$user || $user['role'] !== 'sudo') {
            die("SECURITY_VIOLATION: Sudo clearance required.");
        }
        return $user;
    }

    public function createProposal(Request $request)
    {
        $this->checkAccess();
        $body = $request->getBody();
        
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) {
            die("SECURITY_VIOLATION: CSRF Token Validation Failed");
        }

        $title = $body['title'] ?? '';
        $description = $body['description'] ?? '';
        $eventDate = $body['event_date'] ?? '';
        $maxParticipants = !empty($body['max_participants']) ? (int)$body['max_participants'] : null;

        if ($title && $description && $eventDate) {
            Coordinator::createProposal($title, $description, $eventDate, $maxParticipants);
        }

        Application::$app->response->redirect('/dashboard');
    }

    public function saveAttendance(Request $request)
    {
        $this->checkAccess();
        $body = $request->getBody();
        
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) {
            die("SECURITY_VIOLATION: CSRF Token Validation Failed");
        }

        $eventId = $body['event_id'] ?? null;
        $attendanceData = $body['attendance'] ?? []; // Expected format: ['user_id' => ['attended' => 1, 'rank' => '1st Place']]

        if ($eventId && is_array($attendanceData)) {
            foreach ($attendanceData as $userId => $data) {
                $attended = isset($data['attended']) ? 1 : 0;
                $rank = $data['rank'] ?? 'Participant';
                Coordinator::saveAttendance($eventId, $userId, $attended, $rank);
            }
        }

        Application::$app->response->redirect('/dashboard');
    }
}
