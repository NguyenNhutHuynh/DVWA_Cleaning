<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Booking;
use App\Models\User;

/**
 * WorkerController xử lý bảng điều khiển và nghiệp vụ công việc cho nhân viên.
 * Bao gồm xem đơn được phân công, lịch làm việc,
 * theo dõi tiến độ và trang trạng thái duyệt tài khoản.
 * Hầu hết thao tác yêu cầu vai trò worker và trạng thái đã duyệt.
 */
final class WorkerController
{
    /**
     * Hiển thị bảng điều khiển chính của nhân viên.
     * Yêu cầu vai trò worker và trạng thái duyệt đang hoạt động.
     *
     * @return void
     */
    public function dashboard(): void
    {
        $this->requireApprovedWorkerRole();
        $uid = Auth::id();
        $user = User::findById($uid);
        View::render('worker/dashboard', [
            'uid' => $uid,
            'role' => User::ROLE_WORKER,
            'name' => $user['name'] ?? 'Worker',
        ]);
    }

    /**
     * Hiển thị các công việc đang chờ để nhân viên nhận.
     * Yêu cầu vai trò worker và trạng thái duyệt đang hoạt động.
     *
     * @return void
     */
    public function jobs(): void
    {
        $this->requireApprovedWorkerRole();
        $allBookings = Booking::getAll();
        $pendingJobs = array_filter(
            $allBookings,
            static fn(array $booking): bool => ($booking['status'] ?? '') === 'pending'
        );
        View::render('worker/jobs', ['jobs' => $pendingJobs]);
    }

    /**
     * Hiển thị tiến độ công việc của các đơn đã phân công.
     * Yêu cầu vai trò worker và trạng thái duyệt đang hoạt động.
     *
     * @return void
     */
    public function progress(): void
    {
        $this->requireApprovedWorkerRole();
        $progress = [
            ['booking_id' => 2, 'step' => 'Đang di chuyển', 'time' => '2026-01-24 13:30'],
            ['booking_id' => 2, 'step' => 'Bắt đầu công việc', 'time' => '2026-01-24 14:05'],
        ];
        View::render('worker/progress', ['progress' => $progress]);
    }

    /**
     * Hiển thị lịch làm việc từ các đơn đã phân công cho nhân viên.
     * Yêu cầu vai trò worker và trạng thái duyệt đang hoạt động.
     * Bao gồm các đơn còn hiệu lực theo mốc thời gian.
     *
     * @return void
     */
    public function schedule(): void
    {
        $this->requireApprovedWorkerRole();
        $uid = Auth::id();
        $allBookings = Booking::getAll();

        $workerBookings = array_filter(
            $allBookings,
            static fn(array $b): bool => (int)($b['assigned_worker_id'] ?? 0) === $uid && ($b['status'] ?? '') !== 'cancelled'
        );

        $schedule = array_map(
            static function(array $booking): array {
                return [
                    'time' => ($booking['date'] ?? '') . ' ' . ($booking['time'] ?? ''),
                    'location' => $booking['location'] ?? '',
                    'task' => ($booking['service_name'] ?? 'Công việc') . ' • Trạng thái: ' . ($booking['status'] ?? ''),
                ];
            },
            $workerBookings
        );

        View::render('worker/schedule', ['schedule' => $schedule]);
    }

    /**
     * Hiển thị trang trạng thái duyệt cho worker đang chờ hoặc bị từ chối.
     * Worker đã đăng nhập đều có thể truy cập bất kể trạng thái duyệt.
     * Hiển thị trạng thái chờ xử lý hoặc lý do từ chối.
     *
     * @return void
     */
    public function pending(): void
    {
        $this->requireWorkerRole();
        $uid = Auth::id();
        $user = User::findById($uid);
        View::render('worker/pending', [
            'status' => $user['approval_status'] ?? 'pending',
            'reason' => $user['reject_reason'] ?? null,
            'name' => $user['name'] ?? 'Worker',
        ]);
    }

    /**
     * Bắt buộc người dùng đã xác thực với vai trò worker.
     * Chuyển về trang đăng nhập nếu chưa xác thực hoặc sai vai trò.
     *
     * @return void
     */
    private function requireWorkerRole(): void
    {
        if (!Auth::isAuthenticated() || !Auth::hasRole(User::ROLE_WORKER)) {
            $this->redirect('/login');
        }
    }

    /**
     * Bắt buộc người dùng đã xác thực vai trò worker và có trạng thái duyệt active.
     * Chuyển về đăng nhập nếu chưa xác thực, chuyển trang pending nếu chưa được duyệt.
     *
     * @return void
     */
    private function requireApprovedWorkerRole(): void
    {
        $this->requireWorkerRole();

        $uid = Auth::id();
        $user = User::findById($uid);
        $approvalStatus = $user['approval_status'] ?? '';

        if ($approvalStatus !== 'active') {
            $this->redirect('/worker/pending');
        }
    }

    /**
     * Chuyển hướng tới URL chỉ định và kết thúc xử lý.
     *
     * @param string $path Đường dẫn URL cần chuyển hướng
     * @return void
     */
    private function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit(0);
    }
}