<?php
use App\Core\View;
/** @var array $service */
/** @var array $reviews */
/** @var int $totalReviews */
/** @var float|null $averageRating */
?>

<style>
.service-detail-page {
    --primary: #2eaf7d;
    --primary-dark: #16805a;
    --primary-soft: #e8f7f0;
    --bg-soft: #f7fdf9;
    --text-dark: #1f2d3d;
    --text-muted: #546e7a;
    --border: #dcefe6;
    --white: #ffffff;
    --warning: #f59e0b;
    --shadow-sm: 0 8px 24px rgba(31,45,61,0.08);
    --shadow-md: 0 16px 40px rgba(31,45,61,0.12);

    max-width: 1180px;
    margin: 0 auto;
    padding: 24px 16px 60px;
    color: var(--text-dark);
}

.service-detail-page * {
    box-sizing: border-box;
}

.service-detail-hero {
    position: relative;
    overflow: hidden;
    border-radius: 30px;
    background:
        radial-gradient(circle at top left, rgba(46,175,125,0.18), transparent 34%),
        linear-gradient(135deg, #f7fdf9 0%, #ffffff 48%, #e8f7f0 100%);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
}

.service-detail-hero::after {
    content: "";
    position: absolute;
    right: -90px;
    bottom: -90px;
    width: 240px;
    height: 240px;
    border-radius: 50%;
    background: rgba(46,175,125,0.12);
}

.service-detail-grid {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
    gap: 34px;
    align-items: stretch;
    padding: 34px;
}

.service-image-wrapper {
    position: relative;
    overflow: hidden;
    min-height: 480px;
    border-radius: 26px;
    background: var(--primary-soft);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
}

.service-image {
    width: 100%;
    height: 100%;
    min-height: 480px;
    object-fit: cover;
    display: block;
    transition: transform 0.45s ease;
}

.service-image-wrapper:hover .service-image {
    transform: scale(1.045);
}

.service-image-placeholder {
    width: 100%;
    min-height: 480px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 112px;
    background:
        radial-gradient(circle at center, rgba(46,175,125,0.18), transparent 45%),
        linear-gradient(135deg, #ffffff, var(--primary-soft));
}

.service-info {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 12px 4px;
}

.service-kicker {
    width: fit-content;
    margin: 0 0 14px;
    padding: 7px 14px;
    border-radius: 999px;
    background: var(--primary-soft);
    color: var(--primary-dark);
    font-size: 13px;
    font-weight: 900;
    letter-spacing: 0.08em;
}

.service-info h1 {
    margin: 0 0 16px;
    color: var(--text-dark);
    font-size: clamp(32px, 5vw, 52px);
    line-height: 1.08;
    font-weight: 900;
    letter-spacing: -0.05em;
}

.service-description {
    margin: 0;
    color: var(--text-muted);
    font-size: 16px;
    line-height: 1.7;
}

.service-meta-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
    margin-top: 28px;
}

.service-meta-item {
    padding: 18px;
    border-radius: 20px;
    background: var(--white);
    border: 1px solid var(--border);
    box-shadow: 0 6px 18px rgba(31,45,61,0.05);
    transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
}

.service-meta-item:hover {
    transform: translateY(-3px);
    border-color: rgba(46,175,125,0.45);
    box-shadow: var(--shadow-sm);
}

.service-meta-label {
    margin-bottom: 7px;
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.service-meta-value {
    color: var(--primary);
    font-size: clamp(20px, 2.5vw, 28px);
    font-weight: 900;
    line-height: 1.2;
    letter-spacing: -0.03em;
}

.service-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 30px;
}

.service-btn,
.home-btn {
    min-height: 46px;
    padding: 12px 24px;
    border-radius: 999px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 15px;
    font-weight: 900;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.service-btn:hover,
.home-btn:hover {
    transform: translateY(-2px);
}

.primary-btn,
.home-btn {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #ffffff;
    box-shadow: 0 10px 22px rgba(46,175,125,0.22);
}

.secondary-btn {
    background: #ffffff;
    color: var(--primary);
    border: 1.5px solid var(--primary);
}

.secondary-btn:hover {
    background: var(--primary-soft);
    box-shadow: var(--shadow-sm);
}

.service-content-card {
    margin-top: 34px;
    padding: 34px;
    border-radius: 28px;
    background: var(--white);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
}

.section-heading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 18px;
    margin-bottom: 24px;
    text-align: center;
}

.section-heading > div {
    width: 100%;
}

.section-heading h2 {
    margin: 0;
    color: var(--text-dark);
    font-size: clamp(24px, 3vw, 34px);
    font-weight: 900;
    letter-spacing: -0.03em;
    text-align: center;
}

.section-subtitle {
    margin: 8px auto 0;
    color: var(--text-muted);
    line-height: 1.6;
    text-align: center;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
}

.detail-card {
    position: relative;
    overflow: hidden;
    padding: 22px;
    border-radius: 22px;
    background: linear-gradient(135deg, #ffffff, var(--bg-soft));
    border: 1px solid var(--border);
    box-shadow: 0 6px 18px rgba(31,45,61,0.05);
    transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
    text-align: center;
}

.detail-card::after {
    content: "";
    position: absolute;
    right: -34px;
    bottom: -34px;
    width: 92px;
    height: 92px;
    border-radius: 50%;
    background: rgba(46,175,125,0.09);
}

.detail-card:hover {
    transform: translateY(-4px);
    border-color: rgba(46,175,125,0.45);
    box-shadow: var(--shadow-md);
}

.detail-card h3,
.detail-card p {
    position: relative;
    z-index: 1;
    text-align: center;
}

.detail-card h3 {
    margin: 0 0 9px;
    color: var(--text-muted);
    font-size: 13px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.detail-card p {
    margin: 0;
    color: var(--primary);
    font-size: 22px;
    font-weight: 900;
    line-height: 1.35;
}

.reviews-section {
    margin-top: 34px;
}

.review-stats {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 16px;
}

.rating-display {
    min-width: 130px;
    padding: 14px 18px;
    border-radius: 18px;
    background: var(--primary-soft);
    border: 1px solid #ccefe0;
    text-align: center;
}

.rating-number {
    color: var(--primary);
    font-size: 26px;
    font-weight: 900;
    line-height: 1;
}

.rating-count {
    margin-top: 5px;
    color: var(--text-muted);
    font-size: 13px;
    font-weight: 800;
}

.reviews-list {
    display: grid;
    gap: 14px;
}

.review-item {
    padding: 20px;
    border-radius: 20px;
    background: #fcfffd;
    border: 1px solid var(--border);
    transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
}

.review-item:hover {
    transform: translateY(-3px);
    border-color: rgba(46,175,125,0.45);
    box-shadow: var(--shadow-sm);
}

.review-header {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: flex-start;
    margin-bottom: 10px;
}

.review-name {
    color: var(--text-dark);
    font-size: 15px;
    font-weight: 900;
}

.review-date {
    color: #78909c;
    font-size: 13px;
    white-space: nowrap;
}

.review-rating {
    margin-bottom: 10px;
    color: var(--warning);
    font-size: 15px;
}

.review-rating span {
    color: var(--text-muted);
    margin-left: 8px;
}

.review-comment {
    margin: 0;
    color: var(--text-muted);
    font-style: italic;
    line-height: 1.65;
}

.no-reviews {
    padding: 34px 22px;
    border-radius: 22px;
    background: var(--bg-soft);
    border: 1px dashed #cfe3d8;
    color: var(--text-muted);
    text-align: center;
}

.no-reviews p {
    margin: 0;
    line-height: 1.65;
}

.service-detail-cta {
    margin-top: 34px;
    padding: 46px 24px;
    border-radius: 28px;
    background:
        radial-gradient(circle at top left, rgba(255,255,255,0.18), transparent 34%),
        linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #ffffff;
    text-align: center;
    box-shadow: var(--shadow-md);
}

.service-detail-cta h2 {
    margin: 0 0 12px;
    color: #ffffff;
    font-size: clamp(26px, 4vw, 38px);
    font-weight: 900;
    letter-spacing: -0.04em;
}

.service-detail-cta p {
    margin: 0 0 24px;
    color: rgba(255,255,255,0.88);
    line-height: 1.6;
}

.service-detail-cta .home-btn {
    background: #ffffff;
    color: var(--primary-dark);
    box-shadow: 0 10px 24px rgba(31,45,61,0.18);
}

@media (max-width: 900px) {
    .service-detail-grid {
        grid-template-columns: 1fr;
        gap: 24px;
        padding: 24px;
    }

    .service-image-wrapper,
    .service-image,
    .service-image-placeholder {
        min-height: 380px;
    }

    .service-info {
        padding: 0;
    }
}

@media (max-width: 768px) {
    .service-detail-page {
        padding: 16px 12px 44px;
    }

    .service-detail-hero {
        border-radius: 22px;
    }

    .service-detail-grid {
        padding: 18px;
    }

    .service-image-wrapper,
    .service-image,
    .service-image-placeholder {
        min-height: 300px;
    }

    .service-image-placeholder {
        font-size: 76px;
    }

    .service-meta-grid {
        grid-template-columns: 1fr;
    }

    .service-content-card {
        padding: 22px;
        border-radius: 22px;
    }

    .section-heading {
        flex-direction: column;
        align-items: center;
    }

    .review-header {
        flex-direction: column;
        gap: 6px;
    }

    .review-date {
        white-space: normal;
    }

    .service-actions,
    .service-btn,
    .home-btn {
        width: 100%;
    }
}
</style>

<section class="home-container service-detail-page">
    <section class="service-detail-hero">
        <div class="service-detail-grid">
            <div class="service-image-wrapper">
                <?php if (!empty($service['image_path'])): ?>
                    <img
                        src="<?= View::e($service['image_path']) ?>"
                        alt="<?= View::e($service['name']) ?>"
                        class="service-image"
                        onerror="this.style.display='none'; this.parentElement.querySelector('.service-image-placeholder')?.style.display='flex';"
                    >
                <?php endif; ?>

                <div class="service-image-placeholder" <?php if (!empty($service['image_path'])) echo 'style="display:none;"'; ?>>
                    <?= View::e($service['icon'] ?: '🧹') ?>
                </div>
            </div>

            <div class="service-info">
                <p class="service-kicker">CHI TIẾT DỊCH VỤ</p>
                <h1><?= View::e($service['name']) ?></h1>
                <p class="service-description"><?= View::e($service['description']) ?></p>

                <div class="service-meta-grid">
                    <div class="service-meta-item">
                        <div class="service-meta-label">💰 Giá cơ bản</div>
                        <div class="service-meta-value">
                            <?= number_format((int)$service['price'], 0, ',', '.') ?>đ<?= $service['unit'] ? '/' . View::e($service['unit']) : '' ?>
                        </div>
                    </div>

                    <?php if (!empty($service['duration'])): ?>
                        <div class="service-meta-item">
                            <div class="service-meta-label">⏱️ Thời gian</div>
                            <div class="service-meta-value"><?= View::e($service['duration']) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($service['minimum'])): ?>
                        <div class="service-meta-item">
                            <div class="service-meta-label">📋 Tối thiểu</div>
                            <div class="service-meta-value"><?= number_format((int)$service['minimum'], 0, ',', '.') ?>đ</div>
                        </div>
                    <?php endif; ?>

                    <?php if ($averageRating !== null): ?>
                        <div class="service-meta-item">
                            <div class="service-meta-label">⭐ Đánh giá</div>
                            <div class="service-meta-value"><?= View::e((string)$averageRating) ?>/5</div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="service-actions">
                    <a href="/book?service=<?= (int)$service['id'] ?>" class="service-btn primary-btn">Đặt lịch ngay</a>
                    <a href="/services" class="service-btn secondary-btn">← Quay lại dịch vụ</a>
                </div>
            </div>
        </div>
    </section>

    <section class="service-content-card">
        <div class="section-heading">
            <div>
                <h2>Thông tin chi tiết</h2>
                <p class="section-subtitle">Các thông tin quan trọng giúp bạn chọn dịch vụ phù hợp.</p>
            </div>
        </div>

        <div class="details-grid">
            <div class="detail-card">
                <h3>💰 Mức giá</h3>
                <p><?= number_format((int)$service['price'], 0, ',', '.') ?>đ<?= $service['unit'] ? '/' . View::e($service['unit']) : '' ?></p>
            </div>

            <?php if (!empty($service['duration'])): ?>
                <div class="detail-card">
                    <h3>⏱️ Khoảng thời gian</h3>
                    <p><?= View::e($service['duration']) ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($service['minimum'])): ?>
                <div class="detail-card">
                    <h3>📋 Chi phí tối thiểu</h3>
                    <p><?= number_format((int)$service['minimum'], 0, ',', '.') ?>đ</p>
                </div>
            <?php endif; ?>

            <div class="detail-card">
                <h3>📱 Hỗ trợ</h3>
                <p>24/7</p>
            </div>
        </div>

        <div class="reviews-section">
            <div class="section-heading">
                <div>
                    <h2>Đánh giá từ khách hàng</h2>
                    <p class="section-subtitle">Phản hồi thực tế sau khi khách hàng sử dụng dịch vụ.</p>
                </div>

                <?php if ($averageRating !== null): ?>
                    <div class="review-stats">
                        <div class="rating-display">
                            <div class="rating-number"><?= View::e((string)$averageRating) ?>/5</div>
                            <div class="rating-count"><?= (int)$totalReviews ?> đánh giá</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (empty($reviews)): ?>
                <div class="no-reviews">
                    <p>Dịch vụ này chưa có đánh giá từ khách hàng. <strong><?= View::e($service['name']) ?></strong> sẽ được đánh giá sau các đơn đặt đầu tiên.</p>
                </div>
            <?php else: ?>
                <div class="reviews-list">
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <span class="review-name"><?= View::e((string)($review['customer_name'] ?? 'Khách hàng')) ?></span>
                                <span class="review-date"><?= View::e((string)($review['created_at'] ?? '')) ?></span>
                            </div>

                            <div class="review-rating">
                                <?= str_repeat('⭐', (int)($review['rating'] ?? 0)) ?>
                                <span>(<?= (int)($review['rating'] ?? 0) ?>/5)</span>
                            </div>

                            <?php if (!empty($review['comment'])): ?>
                                <p class="review-comment">"<?= View::e((string)$review['comment']) ?>"</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="service-detail-cta">
        <h2>Sẵn sàng đặt dịch vụ?</h2>
        <p>Đặt dịch vụ ngay hôm nay và nhận ưu đãi đặc biệt từ chúng tôi.</p>
        <a href="/book?service=<?= (int)$service['id'] ?>" class="home-btn">💳 Đặt lịch ngay</a>
    </section>
</section>