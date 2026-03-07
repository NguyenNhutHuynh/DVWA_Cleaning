<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Controller tương thích ngược cho các route cleaner cũ.
 * Chuyển tiếp toàn bộ thao tác sang WorkerController.
 */
final class CleanerController
{
    public function dashboard(): void
    {
        (new WorkerController())->dashboard();
    }

    public function jobs(): void
    {
        (new WorkerController())->jobs();
    }

    public function progress(): void
    {
        (new WorkerController())->progress();
    }

    public function schedule(): void
    {
        (new WorkerController())->schedule();
    }
}
