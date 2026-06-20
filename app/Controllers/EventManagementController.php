<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Application;
use App\Core\Auth;
use App\Core\Security;
use App\Core\Logger;
use App\Models\Event;
use App\Models\Coordinator;

class EventManagementController
{
    private function checkAccess($allowedRoles = ['architect'])
    {
        $user = Auth::user();
        if (!$user || !in_array($user['role'], $allowedRoles)) {
            die("SECURITY_VIOLATION: Insufficient clearance.");
        }
        return $user;
    }

    public function deleteEvent(Request $request)
    {
        $admin = $this->checkAccess(['architect', 'root']);
        $body = $request->getBody();
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) die("CSRF Failed");

        $eventId = $body['event_id'] ?? null;
        if ($eventId) {
            Event::deleteEvent($eventId);
            Logger::log($admin['id'], 'EVENT_DELETED', "Obliterated Event ID: $eventId");
        }
        Application::$app->response->redirect('/dashboard');
    }

    public function completeEvent(Request $request)
    {
        $admin = $this->checkAccess(['architect', 'root']);
        $body = $request->getBody();
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) die("CSRF Failed");

        $eventId = $body['event_id'] ?? null;
        if ($eventId && Event::exists($eventId)) {
            // Reusing updateStatus logic or doing it inline since it's just one query
            $stmt = Application::$app->db->prepare("UPDATE events SET status = 'completed' WHERE id = ?");
            $stmt->execute([$eventId]);
            Logger::log($admin['id'], 'EVENT_COMPLETED', "Concluded Event ID: $eventId");
        }
        Application::$app->response->redirect('/dashboard');
    }

    public function exportRegistrations(Request $request)
    {
        $admin = $this->checkAccess(['architect', 'root']);
        $eventId = filter_input(INPUT_GET, 'event_id', FILTER_VALIDATE_INT);
        if (!$eventId || !Event::exists($eventId)) {
            Application::$app->response->setStatusCode(400);
            die("Invalid or missing Event ID.");
        }

        Logger::log($admin['id'], 'CSV_EXPORT', "Exported Registrants for Event ID: $eventId");

        $registrants = Coordinator::getRegistrationsForAttendance($eventId);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="event_' . $eventId . '_registrations.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['User ID', 'Alias', 'Attended', 'Rank']);

        foreach ($registrants as $reg) {
            $attendedStr = $reg['attended'] ? 'Yes' : 'No';
            fputcsv($output, [$reg['user_id'], $reg['username'], $attendedStr, $reg['rank'] ?? 'Participant']);
        }
        fclose($output);
        exit;
    }

    public function uploadGallery(Request $request)
    {
        $user = $this->checkAccess(['sudo', 'architect', 'root']);
        $body = $request->getBody();
        // Since we are uploading files, we use $_POST for CSRF directly if not in $body
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Security::validateCsrfToken($csrf)) die("CSRF Failed");

        $eventId = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);

        if ($eventId && Event::exists($eventId) && isset($_FILES['gallery_image']) && $_FILES['gallery_image']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['gallery_image']['tmp_name'];
            $name = basename($_FILES['gallery_image']['name']);
            $maxSize = 2 * 1024 * 1024; // 2MB

            $uploadDir = __DIR__ . '/../../public/uploads/gallery/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $tmpName);
            finfo_close($fileInfo);

            $allowedMimeTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
            if (is_uploaded_file($tmpName) && filesize($tmpName) <= $maxSize && isset($allowedMimeTypes[$mimeType])) {
                $ext = $allowedMimeTypes[$mimeType];
                $newName = 'ev' . $eventId . '_' . time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                    chmod($uploadDir . $newName, 0644);
                    Event::addGalleryImage($eventId, '/uploads/gallery/' . $newName, $user['id']);
                    Logger::log($user['id'], 'GALLERY_UPLOAD', "Uploaded image to Event ID: $eventId");
                }
            }
        }
        Application::$app->response->redirect('/dashboard');
    }
}
