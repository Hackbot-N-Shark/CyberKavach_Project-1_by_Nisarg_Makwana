<?php
namespace App\Core;

use App\Models\Coordinator;
use App\Models\Certificate;

class CertificateEngine
{
    public static function generate($certificateId)
    {
        // 1. Fetch template data
        $template = Certificate::getTemplateById($certificateId);
        if (!$template) return false;

        $eventId = $template['event_id'];
        $baseImagePath = __DIR__ . '/../../public' . $template['template_path'];

        if (!file_exists($baseImagePath)) {
            Logger::log(1, 'SYSTEM_ERROR', "Certificate template file missing: $baseImagePath");
            return false;
        }

        // 2. Fetch Attendees
        $attendees = Coordinator::getRegistrationsForAttendance($eventId);

        // 3. Setup Vault Dir
        $vaultDir = __DIR__ . '/../../public/vault/';
        if (!is_dir($vaultDir)) {
            mkdir($vaultDir, 0777, true);
        }

        // 4. Determine Font
        $fontPath = 'C:\Windows\Fonts\consola.ttf';
        if (!file_exists($fontPath)) {
            $fontPath = 'C:\Windows\Fonts\arial.ttf'; // fallback
        }

        // 5. Generate loop
        foreach ($attendees as $user) {
            if ($user['attended']) {
                $ext = strtolower(pathinfo($baseImagePath, PATHINFO_EXTENSION));
                
                if ($ext === 'png') {
                    $image = imagecreatefrompng($baseImagePath);
                } elseif (in_array($ext, ['jpg', 'jpeg'])) {
                    $image = imagecreatefromjpeg($baseImagePath);
                } else {
                    continue; // Unsupported
                }

                $config = isset($template['mapping_config']) && $template['mapping_config'] ? json_decode($template['mapping_config'], true) : null;
                
                // Defaults if unmapped
                $config = $config ?: [
                    'name_y' => 55, 'name_size' => 32, 'name_color' => '#FFFFFF',
                    'event_y' => 70, 'event_size' => 20, 'event_color' => '#FFFFFF',
                    'rank_y' => 80, 'rank_size' => 24, 'rank_color' => '#FF003C'
                ];

                $hexColorAllocate = function($img, $hex) {
                    $hex = ltrim($hex, '#');
                    $r = hexdec(substr($hex, 0, 2));
                    $g = hexdec(substr($hex, 2, 2));
                    $b = hexdec(substr($hex, 4, 2));
                    return imagecolorallocate($img, $r, $g, $b);
                };

                $colorName = $hexColorAllocate($image, $config['name_color']);
                $colorEvent = $hexColorAllocate($image, $config['event_color']);
                $colorRank = $hexColorAllocate($image, $config['rank_color']);

                $width = imagesx($image);
                $height = imagesy($image);

                $textTitle = strtoupper($template['event_title']);
                $textName = strtoupper($user['username']);
                $textRank = strtoupper($user['rank'] ?? 'PARTICIPANT');

                // Function to center text
                $drawCentered = function($img, $size, $y, $color, $font, $text) use ($width) {
                    $y = (int)$y;
                    if (file_exists($font)) {
                        $bbox = imagettfbbox($size, 0, $font, $text);
                        $tw = $bbox[2] - $bbox[0];
                        $x = (int)(($width - $tw) / 2);
                        imagettftext($img, $size, 0, $x, $y, $color, $font, $text);
                    } else {
                        // Fallback if no font file exists (extremely rare on Windows)
                        $tw = imagefontwidth(5) * strlen($text);
                        $x = (int)(($width - $tw) / 2);
                        imagestring($img, 5, $x, $y, $text, $color);
                    }
                };

                // Draw Text based on percentage offsets
                $drawCentered($image, $config['name_size'], $height * ($config['name_y'] / 100), $colorName, $fontPath, $textName);
                $drawCentered($image, $config['event_size'], $height * ($config['event_y'] / 100), $colorEvent, $fontPath, "FOR: " . $textTitle);
                $drawCentered($image, $config['rank_size'], $height * ($config['rank_y'] / 100), $colorRank, $fontPath, "RANK: " . $textRank);

                $outFilename = 'cert_ev' . $eventId . '_u' . $user['user_id'] . '_' . uniqid() . '.png';
                $outPath = $vaultDir . $outFilename;

                imagepng($image, $outPath);
                imagedestroy($image);

                // Save to Vault DB
                Certificate::saveGeneratedCertificate($eventId, $user['user_id'], '/vault/' . $outFilename);
            }
        }

        // 6. Finalize template status
        Certificate::updateStatus($certificateId, 'generated');
        return true;
    }
}
