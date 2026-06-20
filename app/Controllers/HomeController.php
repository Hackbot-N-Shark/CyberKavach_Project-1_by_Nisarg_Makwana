<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->render('home', [
            'title' => 'CyberKavach - Defend the Digital Frontier',
            'pageId' => 'home'
        ]);
    }

    public function about()
    {
        return $this->render('about', [
            'title' => 'About Us - CyberKavach',
            'pageId' => 'about'
        ]);
    }

    public function community()
    {
        return $this->render('community', [
            'title' => 'Community - CyberKavach',
            'pageId' => 'community'
        ]);
    }

    public function team()
    {
        $stmt = \App\Core\Application::$app->db->prepare("
            SELECT id, username, role, status 
            FROM users 
            WHERE role IN ('root', 'architect', 'sudo') AND status = 'active'
        ");
        $stmt->execute();
        $users = $stmt->fetchAll();

        // Group by role
        $team = [
            'root' => [],
            'architect' => [],
            'sudo' => []
        ];

        foreach ($users as $u) {
            $team[$u['role']][] = $u;
        }

        return $this->render('team', [
            'title' => 'Command Structure - CyberKavach',
            'pageId' => 'team',
            'team' => $team
        ]);
    }
}
