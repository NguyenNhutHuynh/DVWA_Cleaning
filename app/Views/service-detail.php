<?php
use App\Core\View;
/** @var array $service */
/** @var array $reviews */
/** @var int $totalReviews */
/** @var float|null $averageRating */
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

    <section class="home-feature" style="margin-top: 20px;">
        <h2 style="margin: 0 0 10px; color: #1f2d3d;">Đánh giá dịch vụ</h2>
        <div class="review-box" style="border: 1px solid #dff1e8; background: #fff;">
            <p style="margin: 0 0 12px; color: #415462;">
                <?php if ($averageRating !== null): ?>
                    <strong><?= View::e((string)$service['name']) ?></strong>
                    • Trung bình: <strong><?= View::e((string)$averageRating) ?>/5</strong>
                    • <?= (int)$totalReviews ?> đánh giá
                <?php else: ?>
                    <strong><?= View::e((string)$service['name']) ?></strong> • Chưa có đánh giá nào.
                <?php endif; ?>
            </p>

            <?php if (empty($reviews)): ?>
                <p style="margin: 0; color: #607d8b;">Dịch vụ này chưa có phản hồi từ khách hàng.</p>
            <?php else: ?>
                <div style="display:grid; gap: 12px;">
                    <?php foreach ($reviews as $review): ?>
                        <article style="border: 1px solid #e0f2e9; border-radius: 10px; padding: 12px; background: #f9fefb;">
                            <p style="margin: 0 0 8px; color: #1f2d3d;">
                                <strong><?= View::e((string)($review['customer_name'] ?? 'Khách hàng')) ?></strong>
                                • Dịch vụ: <strong><?= View::e((string)($review['service_name'] ?? $service['name'])) ?></strong>
                            </p>
                            <p style="margin: 0 0 8px; color: #415462;">
                                <?= str_repeat('⭐', (int)($review['rating'] ?? 0)) ?>
                                <span style="color:#546e7a;">(<?= (int)($review['rating'] ?? 0) ?>/5)</span>
                            </p>
                            <?php if (!empty($review['comment'])): ?>
                                <p style="margin: 0 0 6px; color: #546e7a; font-style: italic;">"<?= View::e((string)$review['comment']) ?>"</p>
                            <?php endif; ?>
                            <small style="color: #78909c;">Thời gian: <?= View::e((string)($review['created_at'] ?? '')) ?></small>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</section>
