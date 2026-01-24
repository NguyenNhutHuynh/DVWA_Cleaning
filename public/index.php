<?php
declare(strict_types=1);

$config = require __DIR__ . '/../config/app.php';
session_name($config['app']['session_name']);
session_start();

// Autoload đơn giản cho namespace App\
spl_autoload_register(function($class){
  $prefix = 'App\\';
  if (str_starts_with($class, $prefix)) {
    $path = __DIR__ . '/../app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (file_exists($path)) require $path;
  }
});

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\ServicesController;
use App\Controllers\PricingController;
use App\Controllers\ContactController;
use App\Controllers\BookingController;
use App\Controllers\AdminController;
use App\Controllers\CleanerController;
use App\Controllers\CustomerController;
use App\Controllers\WorkerController;
use App\Core\Auth;
use App\Core\View;
use App\Models\User;

$router = new Router();

// Home
$router->get('/', function() {
  $uid = Auth::id();
  $user = $uid ? User::findById((int)$uid) : null;
  View::render('home', ['uid' => $uid, 'name' => $user['name'] ?? null]);
});

// Auth
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Services
$router->get('/services', [ServicesController::class, 'index']);

// Pricing
$router->get('/pricing', [PricingController::class, 'index']);

// Contact
$router->get('/contact', [ContactController::class, 'index']);
$router->post('/contact', [ContactController::class, 'store']);

// Booking
$router->get('/book', [BookingController::class, 'create']);
$router->post('/book', [BookingController::class, 'store']);
$router->get('/bookings', [BookingController::class, 'index']);

// Dashboards by role
$router->get('/admin', function() { header('Location: /admin/dashboard'); exit; });
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
$router->get('/cleaner/dashboard', [CleanerController::class, 'dashboard']);
$router->get('/worker/dashboard', [WorkerController::class, 'dashboard']);
$router->get('/worker/pending', [WorkerController::class, 'pending']);
$router->get('/customer/dashboard', [CustomerController::class, 'dashboard']);

// Admin management pages
$router->get('/admin/services', [AdminController::class, 'services']);
$router->get('/admin/bookings', [AdminController::class, 'bookings']);
$router->get('/admin/moderation', [AdminController::class, 'moderation']);
$router->get('/admin/users', [AdminController::class, 'users']);
$router->get('/admin/stats', [AdminController::class, 'stats']);
// Admin user approvals
$router->post('/admin/users/approve', [AdminController::class, 'approveWorker']);
$router->post('/admin/users/reject', [AdminController::class, 'rejectWorker']);

// Cleaner pages
$router->get('/cleaner/jobs', [CleanerController::class, 'jobs']);
$router->get('/cleaner/progress', [CleanerController::class, 'progress']);
$router->get('/cleaner/schedule', [CleanerController::class, 'schedule']);

// Worker pages (new role name)
$router->get('/worker/jobs', [WorkerController::class, 'jobs']);
$router->get('/worker/progress', [WorkerController::class, 'progress']);
$router->get('/worker/schedule', [WorkerController::class, 'schedule']);

$router->dispatch();
