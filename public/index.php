<?php
declare(strict_types=1);

$config = require __DIR__ . '/../config/app.php';
session_name($config['app']['session_name']);
session_start();

// Tự tải đơn giản cho namespace App\
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
use App\Controllers\AccountController;
use App\Core\Auth;
use App\Core\View;
use App\Models\User;

$router = new Router();

// Trang chủ
$router->get('/', function() {
  $uid = Auth::id();
  $user = $uid ? User::findById((int)$uid) : null;
  View::render('home', ['uid' => $uid, 'name' => $user['name'] ?? null]);
});

// Xác thực
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Các dịch vụ
$router->get('/services', [ServicesController::class, 'index']);
// Chi tiết dịch vụ qua truy vấn: /service?id=123
$router->get('/service', [ServicesController::class, 'show']);

// Bảng giá
$router->get('/pricing', [PricingController::class, 'index']);

// Liên hệ
$router->get('/contact', [ContactController::class, 'index']);
$router->post('/contact', [ContactController::class, 'store']);

// Đặt lịch
$router->get('/book', [BookingController::class, 'create']);
$router->post('/book', [BookingController::class, 'store']);
$router->get('/bookings', [BookingController::class, 'index']);

// Bảng điều khiển theo vai trò
$router->get('/admin', function() { header('Location: /admin/dashboard'); exit; });
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
$router->get('/cleaner/dashboard', [CleanerController::class, 'dashboard']);
$router->get('/worker/dashboard', [WorkerController::class, 'dashboard']);
$router->get('/worker/pending', [WorkerController::class, 'pending']);
$router->get('/customer/dashboard', [CustomerController::class, 'dashboard']);

// Trang quản lý Admin
$router->get('/admin/services', [AdminController::class, 'services']);
$router->post('/admin/services/update', [AdminController::class, 'updateService']);
$router->post('/admin/services/toggle', [AdminController::class, 'toggleService']);
$router->post('/admin/services/delete', [AdminController::class, 'deleteService']);
$router->post('/admin/services/create', [AdminController::class, 'createService']);
$router->get('/admin/bookings', [AdminController::class, 'bookings']);
// Các hành động đặt lịch Admin
$router->post('/admin/bookings/confirm', [AdminController::class, 'confirmBooking']);
$router->post('/admin/bookings/cancel', [AdminController::class, 'cancelBooking']);
$router->post('/admin/bookings/assign', [AdminController::class, 'assignBooking']);
$router->get('/admin/moderation', [AdminController::class, 'moderation']);
$router->get('/admin/users', [AdminController::class, 'users']);
$router->get('/admin/user', [AdminController::class, 'userDetail']);
$router->get('/admin/user/json', [AdminController::class, 'userDetailJson']);
$router->get('/admin/stats', [AdminController::class, 'stats']);
// Phê duyệt người dùng Admin
$router->post('/admin/users/approve', [AdminController::class, 'approveWorker']);
$router->post('/admin/users/reject', [AdminController::class, 'rejectWorker']);
// Các hành động quản lý người dùng Admin
$router->post('/admin/user/update', [AdminController::class, 'userUpdate']);
$router->post('/admin/user/lock', [AdminController::class, 'userLock']);
$router->post('/admin/user/unlock', [AdminController::class, 'userUnlock']);
$router->post('/admin/user/delete', [AdminController::class, 'userDelete']);

// Trang người dọn dẹp
$router->get('/cleaner/jobs', [CleanerController::class, 'jobs']);
$router->get('/cleaner/progress', [CleanerController::class, 'progress']);
$router->get('/cleaner/schedule', [CleanerController::class, 'schedule']);

// Trang công nhân (tên vai trò mới)
$router->get('/worker/jobs', [WorkerController::class, 'jobs']);
$router->get('/worker/progress', [WorkerController::class, 'progress']);
$router->get('/worker/schedule', [WorkerController::class, 'schedule']);

// Tài khoản (hồ sơ & cài đặt)
$router->get('/account', [AccountController::class, 'profile']);
$router->get('/account/edit', [AccountController::class, 'edit']);
$router->post('/account/edit', [AccountController::class, 'update']);
$router->get('/account/change-password', [AccountController::class, 'changePassword']);
$router->post('/account/update-password', [AccountController::class, 'updatePassword']);

$router->dispatch();
