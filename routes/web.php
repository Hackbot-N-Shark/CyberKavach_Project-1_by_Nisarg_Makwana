<?php

use App\Core\Application;
use App\Controllers\HomeController;
use App\Controllers\AuthController;

$app = Application::$app;

$app->router->get('/', [HomeController::class, 'index']);
$app->router->get('/about', [HomeController::class, 'about'], [\App\Core\AuthMiddleware::class]);
$app->router->get('/community', [HomeController::class, 'community'], [\App\Core\AuthMiddleware::class]);
$app->router->get('/team', [HomeController::class, 'team'], [\App\Core\AuthMiddleware::class]);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'loginPost']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'registerPost']);
$app->router->get('/logout', [AuthController::class, 'logout']);
$app->router->get('/forgot-password', [AuthController::class, 'forgotPassword']);
$app->router->post('/forgot-password', [AuthController::class, 'forgotPasswordPost']);
$app->router->get('/reset-password', [AuthController::class, 'resetPassword']);
$app->router->post('/reset-password', [AuthController::class, 'resetPasswordPost']);

$app->router->get('/dashboard', [\App\Controllers\DashboardController::class, 'index'], [\App\Core\AuthMiddleware::class]);
$app->router->get('/force-change-password', [AuthController::class, 'forceChangePassword'], [\App\Core\AuthMiddleware::class]);
$app->router->post('/force-change-password', [AuthController::class, 'forceChangePasswordPost'], [\App\Core\AuthMiddleware::class]);
$app->router->post('/dashboard/update-status', [\App\Controllers\DashboardController::class, 'updateStatus'], [\App\Core\AuthMiddleware::class]);
$app->router->post('/dashboard/event-register', [\App\Controllers\DashboardController::class, 'eventRegister'], [\App\Core\AuthMiddleware::class]);

$app->router->post('/coordinator/create-proposal', [\App\Controllers\CoordinatorController::class, 'createProposal'], [\App\Core\AuthMiddleware::class]);
$app->router->post('/coordinator/save-attendance', [\App\Controllers\CoordinatorController::class, 'saveAttendance'], [\App\Core\AuthMiddleware::class]);

$app->router->post('/architect/manage-role', [\App\Controllers\ArchitectController::class, 'manageRoleRequest'], [\App\Core\AuthMiddleware::class]);
$app->router->post('/architect/manage-event', [\App\Controllers\ArchitectController::class, 'manageEvent'], [\App\Core\AuthMiddleware::class]);
$app->router->post('/architect/manage-resource', [\App\Controllers\ArchitectController::class, 'manageResource'], [\App\Core\AuthMiddleware::class]);

$app->router->post('/root/manage-faculty', [\App\Controllers\RootController::class, 'manageFaculty'], [\App\Core\AuthMiddleware::class]);
$app->router->post('/root/override-user', [\App\Controllers\RootController::class, 'overrideUser'], [\App\Core\AuthMiddleware::class]);
$app->router->post('/root/force-reset', [\App\Controllers\DashboardController::class, 'forceResetPassword'], [\App\Core\AuthMiddleware::class]);
$app->router->get('/root/download-backup', [\App\Controllers\RootController::class, 'downloadBackup'], [\App\Core\AuthMiddleware::class]);

$app->router->post('/event/delete', [\App\Controllers\EventManagementController::class, 'deleteEvent'], [\App\Core\AuthMiddleware::class]);
$app->router->post('/event/complete', [\App\Controllers\EventManagementController::class, 'completeEvent'], [\App\Core\AuthMiddleware::class]);
$app->router->get('/event/export-csv', [\App\Controllers\EventManagementController::class, 'exportRegistrations'], [\App\Core\AuthMiddleware::class]);
$app->router->post('/event/upload-gallery', [\App\Controllers\EventManagementController::class, 'uploadGallery'], [\App\Core\AuthMiddleware::class]);

$app->router->post('/cert/upload-template', [\App\Controllers\CertificateController::class, 'uploadTemplate'], [\App\Core\AuthMiddleware::class]);
$app->router->post('/cert/manage-workflow', [\App\Controllers\CertificateController::class, 'manageWorkflow'], [\App\Core\AuthMiddleware::class]);
$app->router->post('/cert/save-mapping', [\App\Controllers\CertificateController::class, 'saveMapping'], [\App\Core\AuthMiddleware::class]);

$app->router->get('/api/notifications', [\App\Controllers\NotificationController::class, 'getNotifications'], [\App\Core\AuthMiddleware::class]);
$app->router->post('/broadcast', [\App\Controllers\NotificationController::class, 'broadcast'], [\App\Core\AuthMiddleware::class]);

$app->router->get('/blog', [\App\Controllers\PublicController::class, 'blogIndex'], [\App\Core\AuthMiddleware::class]);
$app->router->get('/blog/view', [\App\Controllers\PublicController::class, 'blogView'], [\App\Core\AuthMiddleware::class]);
$app->router->get('/gallery', [\App\Controllers\PublicController::class, 'globalGallery'], [\App\Core\AuthMiddleware::class]);

$app->router->get('/contact', [\App\Controllers\PublicController::class, 'contactForm'], [\App\Core\AuthMiddleware::class]);
$app->router->post('/contact', [\App\Controllers\PublicController::class, 'submitContact'], [\App\Core\AuthMiddleware::class]);
$app->router->get('/newsletter', [\App\Controllers\PublicController::class, 'newsletter'], [\App\Core\AuthMiddleware::class]);

$app->router->post('/admin/blog/create', [\App\Controllers\AdminBlogController::class, 'createBlog'], [\App\Core\AuthMiddleware::class]);
