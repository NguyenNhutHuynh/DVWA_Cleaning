<?php
use App\Core\View;
?>

<section class="home-container">
    <header class="home-hero">
        <h1><?= View::e(($service['icon'] ?: '🧹') . ' ' . $service['name']) ?></h1>
        <?php if (!empty($service['description'])): ?>
            <p><?= View::e($service['description']) ?></p>
        <?php endif; ?>
    </header>

    <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 20px;">
        <div style="background: #ffffff; border: 1px solid #e0f2e9; border-radius: 12px; padding: 20px; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
            <h3 style="margin-top: 0; color: #1f2d3d;">Thông tin dịch vụ</h3>
            <ul style="list-style: none; padding: 0; margin: 0; color: #455a64;">
                <?php if (!empty($service['duration'])): ?>
                    <li style="margin-bottom: 8px;"><strong>Thời gian:</strong> <?= View::e($service['duration']) ?></li>
                <?php endif; ?>
                <li style="margin-bottom: 8px;"><strong>Giá cơ bản:</strong> <?= number_format((int)$service['price'], 0, ',', '.') ?>đ<?= $service['unit'] ? '/' . View::e($service['unit']) : '' ?></li>
                <?php if (!empty($service['minimum'])): ?>
                    <li style="margin-bottom: 8px;"><strong>Tối thiểu:</strong> <?= number_format((int)$service['minimum'], 0, ',', '.') ?>đ</li>
                <?php endif; ?>
            </ul>
            <div style="margin-top: 16px;">
                <a href="/book?service=<?= (int)$service['id'] ?>" class="home-btn" style="background: #43c59e; color: white; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600;">Đặt lịch ngay</a>
                <a href="/services" class="home-btn home-btn-outline" style="background: #fdfdfd; color: #2eaf7d; border: 1.5px solid #2eaf7d; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600;">Quay lại danh sách</a>
            </div>
        </div>
        <div style="background: #f7fdf9; border: 1px solid #e0f2e9; border-radius: 12px; padding: 20px;">
            <h3 style="margin-top: 0; color: #1f2d3d;">Ghi chú</h3>
            <p style="color: #546e7a;">Giá có thể thay đổi tùy theo diện tích, tình trạng công trình và yêu cầu cụ thể. Vui lòng liên hệ để được tư vấn chi tiết.</p>
        </div>
    </section>
</section>
