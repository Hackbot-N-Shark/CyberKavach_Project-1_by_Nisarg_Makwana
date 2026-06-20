<?php
namespace App\Models;

use App\Core\Application;

class User
{
    public static function create($username, $email, $password, $role = 'operative', $status = 'active')
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = Application::$app->db->prepare("INSERT INTO users (username, email, password_hash, role, status) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$username, $email, $hash, $role, $status])) {
            return Application::$app->db->pdo->lastInsertId();
        }
        return false;
    }

    public static function updateStatus($id, $status)
    {
        $stmt = Application::$app->db->prepare("UPDATE users SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public static function updateRoleAndStatus($id, $role, $status)
    {
        $stmt = Application::$app->db->prepare("UPDATE users SET role = ?, status = ? WHERE id = ?");
        return $stmt->execute([$role, $status, $id]);
    }

    public static function updatePassword($id, $password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = Application::$app->db->prepare("UPDATE users SET password_hash = ?, must_change_password = 0 WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    public static function setMustChangePassword($id, $val)
    {
        $stmt = Application::$app->db->prepare("UPDATE users SET must_change_password = ? WHERE id = ?");
        return $stmt->execute([$val, $id]);
    }

    public static function savePasswordResetToken($email, $token)
    {
        // Expire in 1 hour
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Delete old tokens for this email
        $stmt = Application::$app->db->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([$email]);

        $stmt = Application::$app->db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        return $stmt->execute([$email, $token, $expiresAt]);
    }

    public static function getPasswordResetToken($token)
    {
        $stmt = Application::$app->db->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > CURRENT_TIMESTAMP");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public static function deletePasswordResetToken($token)
    {
        $stmt = Application::$app->db->prepare("DELETE FROM password_resets WHERE token = ?");
        return $stmt->execute([$token]);
    }

    public static function getAllResetRequests()
    {
        $stmt = Application::$app->db->prepare("SELECT email FROM password_resets WHERE token = 'REQUESTED'");
        $stmt->execute();
        $results = $stmt->fetchAll();
        $emails = [];
        foreach ($results as $row) {
            $emails[] = $row['email'];
        }
        return $emails;
    }

    public static function countPending($statuses)
    {
        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        $sql = "SELECT COUNT(*) as count FROM users WHERE status IN ($placeholders)";
        $stmt = Application::$app->db->prepare($sql);
        $stmt->execute($statuses);
        return $stmt->fetch()['count'] ?? 0;
    }

    public static function getPendingByRole($role)
    {
        $stmt = Application::$app->db->prepare("SELECT * FROM users WHERE status = 'pending' AND role = ?");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }

    public static function findByEmail($email)
    {
        $stmt = Application::$app->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function findByUsername($username)
    {
        $stmt = Application::$app->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public static function findById($id)
    {
        $stmt = Application::$app->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
