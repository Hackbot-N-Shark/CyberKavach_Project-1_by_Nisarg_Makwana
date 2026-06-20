<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Application;
use App\Core\Auth;
use App\Core\Security;
use App\Core\Logger;
use App\Core\CertificateEngine;
use App\Models\Certificate;
use App\Models\Event;

class CertificateController
{
    private function checkAccess($allowedRoles)
    {
        $user = Auth::user();
        if (!$user || !in_array($user['role'], $allowedRoles)) {
            die("SECURITY_VIOLATION: Insufficient clearance for certificate workflows.");
        }
        return $user;
    }

    public function uploadTemplate(Request $request)
    {
        $user = $this->checkAccess(['sudo']);
        
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Security::validateCsrfToken($csrf)) die("CSRF Failed");

        $eventId = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);

        if ($eventId && Event::exists($eventId) && isset($_FILES['template_image']) && $_FILES['template_image']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['template_image']['tmp_name'];
            $name = basename($_FILES['template_image']['name']);
            $maxSize = 2 * 1024 * 1024; // 2MB

            $uploadDir = __DIR__ . '/../../public/uploads/templates/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $tmpName);
            finfo_close($fileInfo);

            $allowedMimeTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
            if (is_uploaded_file($tmpName) && filesize($tmpName) <= $maxSize && isset($allowedMimeTypes[$mimeType])) {
                $ext = $allowedMimeTypes[$mimeType];
                $newName = 'template_ev' . $eventId . '_' . time() . '.' . $ext;
                if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                    chmod($uploadDir . $newName, 0644);
                    Certificate::createTemplate($eventId, '/uploads/templates/' . $newName, $user['id']);
                    Logger::log($user['id'], 'CERT_INITIATED', "Sudo uploaded base template for Event ID: $eventId");
                }
            }
        }
        Application::$app->response->redirect('/dashboard');
    }

    public function saveMapping(Request $request)
    {
        $user = $this->checkAccess(['sudo']);
        $body = $request->getBody();
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) die("CSRF Failed");

        $certId = filter_var($body['cert_id'] ?? '', FILTER_VALIDATE_INT);
        if ($certId) {
            $config = [
                'name_y' => filter_var($body['name_y'] ?? 50, FILTER_VALIDATE_INT),
                'name_size' => filter_var($body['name_size'] ?? 32, FILTER_VALIDATE_INT),
                'name_color' => $body['name_color'] ?? '#FFFFFF',
                'event_y' => filter_var($body['event_y'] ?? 70, FILTER_VALIDATE_INT),
                'event_size' => filter_var($body['event_size'] ?? 20, FILTER_VALIDATE_INT),
                'event_color' => $body['event_color'] ?? '#FFFFFF',
                'rank_y' => filter_var($body['rank_y'] ?? 80, FILTER_VALIDATE_INT),
                'rank_size' => filter_var($body['rank_size'] ?? 24, FILTER_VALIDATE_INT),
                'rank_color' => $body['rank_color'] ?? '#FF003C'
            ];

            $configJson = json_encode($config);
            $stmt = Application::$app->db->prepare("UPDATE event_certificates SET mapping_config = ? WHERE id = ?");
            $stmt->execute([$configJson, $certId]);
            Logger::log($user['id'], 'CERT_MAPPED', "Sudo configured visual mapping for Cert ID: $certId");
        }
        Application::$app->response->redirect('/dashboard');
    }

    private function handleFileUpload($fileInputName, $certId)
    {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES[$fileInputName]['tmp_name'];
            $maxSize = 2 * 1024 * 1024;
            $uploadDir = __DIR__ . '/../../public/uploads/templates/';
            
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $tmpName);
            finfo_close($fileInfo);

            $allowedMimeTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
            if (is_uploaded_file($tmpName) && filesize($tmpName) <= $maxSize && isset($allowedMimeTypes[$mimeType])) {
                $ext = $allowedMimeTypes[$mimeType];
                $newName = 'template_cert' . $certId . '_' . time() . '.' . $ext;
                if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                    chmod($uploadDir . $newName, 0644);
                    return '/uploads/templates/' . $newName;
                }
            }
        }
        return false;
    }

    public function manageWorkflow(Request $request)
    {
        $user = Auth::user();
        if (!$user) die("Unauthorized");

        $body = $request->getBody();
        if (!Security::validateCsrfToken($body['csrf_token'] ?? '')) die("CSRF Failed");

        $certId = $body['cert_id'] ?? null;
        $action = $body['action'] ?? null;

        if ($certId && $action) {
            if ($user['role'] === 'architect') {
                if ($action === 'sign_faculty') {
                    $newPath = $this->handleFileUpload('signed_image', $certId);
                    if ($newPath) {
                        $stmt = Application::$app->db->prepare("UPDATE event_certificates SET template_path = ? WHERE id = ?");
                        $stmt->execute([$newPath, $certId]);
                        Certificate::updateStatus($certId, 'pending_root_sign');
                        Logger::log($user['id'], 'CERT_FACULTY_SIGNED', "Faculty applied signature overlay to Cert ID: $certId");
                    }
                } elseif ($action === 'reupload_faculty') {
                    $newPath = $this->handleFileUpload('signed_image', $certId);
                    if ($newPath) {
                        $stmt = Application::$app->db->prepare("UPDATE event_certificates SET template_path = ? WHERE id = ?");
                        $stmt->execute([$newPath, $certId]);
                        Logger::log($user['id'], 'CERT_FACULTY_UPDATED', "Faculty re-uploaded signed template for Cert ID: $certId");
                    }
                } elseif ($action === 'verify_generate') {
                    Certificate::updateStatus($certId, 'ready_for_generation');
                    Logger::log($user['id'], 'CERT_VERIFIED', "Faculty verified Root signature. Cert ID: $certId is ready for Sudo Generation.");
                }
            } elseif ($user['role'] === 'root') {
                if ($action === 'sign_root') {
                    $newPath = $this->handleFileUpload('signed_image', $certId);
                    if ($newPath) {
                        $stmt = Application::$app->db->prepare("UPDATE event_certificates SET template_path = ? WHERE id = ?");
                        $stmt->execute([$newPath, $certId]);
                        Certificate::updateStatus($certId, 'pending_faculty_verify');
                        Logger::log($user['id'], 'CERT_ROOT_SIGNED', "Boss applied final signature to Cert ID: $certId");
                    }
                }
            } elseif ($user['role'] === 'sudo') {
                if ($action === 'generate_all') {
                    Logger::log($user['id'], 'CERT_MASS_GENERATE', "Sudo triggered auto-generator for Cert ID: $certId");
                    CertificateEngine::generate($certId);
                } elseif ($action === 'reupload_base') {
                    $newPath = $this->handleFileUpload('base_image', $certId);
                    if ($newPath) {
                        $stmt = Application::$app->db->prepare("UPDATE event_certificates SET template_path = ? WHERE id = ?");
                        $stmt->execute([$newPath, $certId]);
                        Logger::log($user['id'], 'CERT_BASE_UPDATED', "Sudo re-uploaded base template for Cert ID: $certId");
                    }
                }
            }
        }
        Application::$app->response->redirect('/dashboard');
    }
}
