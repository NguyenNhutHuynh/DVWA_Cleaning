<?php
use App\Core\View;
/** @var array $service */
/** @var array $reviews */
/** @var int $totalReviews */
/** @var float|null $averageRating */
?>

<style>
.service-detail-hero {
    background: #ffffff;
    color: #333;
    padding: 0;
    margin: 0;
    position: relative;
    overflow: hidden;
}

.service-detail-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 40px 20px;
    position: relative;
    z-index: 1;
}

.service-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: flex-start;
    background: white;
    border-radius: 8px;
    padding: 20px;
}

.service-image-wrapper {
    position: relative;
    background: #f5f5f5;
    border-radius: 8px;
    overflow: hidden;
}

.service-image {
    width: 100%;
    height: 480px;
    object-fit: cover;
    border-radius: 8px;
    display: block;
    transition: transform 0.3s ease;
}

.service-image:hover {
    transform: scale(1.05);
}

.service-image-placeholder {
    width: 100%;
    height: 480px;
    background: linear-gradient(135deg, #f5f5f5 0%, #eee 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 120px;
}

.service-info h1 {
    font-size: 2rem;
    margin: 0 0 16px 0;
    font-weight: 600;
    color: #333;
    line-height: 1.3;
}

.service-info p {
    font-size: 0.95rem;
    line-height: 1.5;
    margin: 0 0 24px 0;
    color: #666;
}

.service-meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin: 28px 0 32px 0;
    padding: 20px 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.service-meta-item {
    background: white;
    padding: 16px;
    border-radius: 6px;
    border: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}

.service-meta-item:hover {
    border-color: #43c59e;
    background: #f9fffe;
}

.service-meta-label {
    font-size: 0.8rem;
    color: #999;
    margin-bottom: 6px;
    font-weight: 500;
}

.service-meta-value {
    font-size: 1.4rem;
    font-weight: 700;
    color: #43c59e;
}

.service-actions {
    display: flex;
    gap: 12px;
    margin-top: 32px;
}

.service-actions a {
    flex: 1;
    padding: 14px 24px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    text-align: center;
    transition: all 0.2s ease;
    font-size: 0.95rem;
    cursor: pointer;
    border: none;
}

.service-actions .primary-btn {
    background: #43c59e;
    color: white;
    box-shadow: 0 2px 4px rgba(67, 197, 158, 0.2);
}

.service-actions .primary-btn:hover {
    background: #2eaf7d;
    box-shadow: 0 4px 12px rgba(67, 197, 158, 0.3);
    transform: translateY(-2px);
}

.service-actions .primary-btn:active {
    transform: translateY(0);
}

.service-actions .secondary-btn {
    background: white;
    color: #43c59e;
    border: 2px solid #43c59e;
}

.service-actions .secondary-btn:hover {
    background: #f9fffe;
    box-shadow: 0 2px 8px rgba(67, 197, 158, 0.15);
}

.service-details {
    background: white;
    border-radius: 8px;
    padding: 40px;
    margin-top: 30px;
    position: relative;
    z-index: 2;
    box-shadow: 0 1px 2px rgba(0,0,0,0.08);
}

.service-details h2 {
    font-size: 1.5rem;
    color: #333;
    margin: 0 0 28px 0;
    padding-bottom: 0;
    border-bottom: none;
    font-weight: 600;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    margin-bottom: 48px;
}

.detail-card {
    padding: 20px;
    background: #fafafa;
    border-radius: 6px;
    border-left: 4px solid #43c59e;
    transition: all 0.2s ease;
    cursor: pointer;
}

.detail-card:hover {
    background: #f0f8f6;
    box-shadow: 0 2px 8px rgba(67, 197, 158, 0.1);
}

.detail-card h3 {
    color: #999;
    margin: 0 0 10px 0;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

.detail-card p {
    color: #43c59e;
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
}

.reviews-section {
    margin-top: 48px;
}

.reviews-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
}

.reviews-header h2 {
    font-size: 1.5rem;
    color: #333;
    margin: 0;
    font-weight: 600;
}

.review-stats {
    display: flex;
    align-items: center;
    gap: 24px;
}

.rating-display {
    text-align: center;
    background: #f9fffe;
    padding: 16px 24px;
    border-radius: 6px;
    border: 1px solid #e8f5f0;
}

.rating-stars {
    font-size: 1.8rem;
    margin-bottom: 6px;
}

.rating-number {
    font-size: 1.3rem;
    font-weight: 700;
    color: #43c59e;
}

.rating-count {
    font-size: 0.8rem;
    color: #999;
    margin-top: 4px;
}

.reviews-list {
    display: grid;
    gap: 12px;
}

.review-item {
    background: white;
    border: 1px solid #f0f0f0;
    border-radius: 6px;
    padding: 20px;
    transition: all 0.2s ease;
}

.review-item:hover {
    border-color: #43c59e;
    box-shadow: 0 2px 8px rgba(67, 197, 158, 0.08);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 10px;
}

.review-name {
    font-weight: 600;
    color: #333;
    font-size: 0.95rem;
}
.service-detail-hero {
    background: #ffffff;
    color: var(--ui-text);
    padding: 0;
    margin: 0;
    position: relative;
    overflow: hidden;
}

.service-detail-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 44px 20px;
    position: relative;
    z-index: 1;
}

.service-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 44px;
    align-items: flex-start;
    background: #ffffff;
    border-radius: var(--ui-radius-md);
    padding: 0;
}

.service-image-wrapper {
    position: relative;
    background: var(--ui-surface);
    border-radius: var(--ui-radius-md);
    overflow: hidden;
    box-shadow: var(--ui-shadow-md);
}

.service-image {
    width: 100%;
    height: 480px;
    object-fit: cover;
    border-radius: var(--ui-radius-md);
    display: block;
    transition: transform 0.3s ease;
}

.service-image:hover {
    transform: scale(1.05);
}

.service-image-placeholder {
    width: 100%;
    height: 480px;
    background: linear-gradient(135deg, var(--ui-surface) 0%, rgba(217, 236, 226, 0.5) 100%);
    border-radius: var(--ui-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 120px;
}

.service-info h1 {
    font-size: 1.95rem;
    margin: 0 0 14px 0;
    font-weight: 700;
    color: var(--ui-text);
    line-height: 1.3;
    letter-spacing: -0.5px;
}

.service-info p {
    font-size: 15px;
    line-height: 1.6;
    margin: 0 0 24px 0;
    color: #5a6c7a;
}

.service-meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin: 24px 0 28px 0;
    padding: 0;
    border-top: none;
    border-bottom: none;
}

.service-meta-item {
    background: var(--ui-surface);
    padding: 14px 16px;
    border-radius: var(--ui-radius-sm);
    border: 1px solid var(--ui-border);
    transition: all 0.18s ease;
}

.service-meta-item:hover {
    border-color: var(--ui-primary);
    background: #f2fbf6;
    box-shadow: 0 2px 8px rgba(46, 175, 125, 0.12);
}

.service-meta-label {
    font-size: 12px;
    color: #7a8a96;
    margin-bottom: 6px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.service-meta-value {
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--ui-primary);
    letter-spacing: -0.3px;
}

.service-actions {
    display: flex;
    gap: 12px;
    margin-top: 28px;
}

.service-actions a {
    flex: 1;
    padding: 11px 24px;
    border-radius: var(--ui-radius-sm);
    text-decoration: none;
    font-weight: 700;
    text-align: center;
    transition: all 0.18s ease;
    font-size: 15px;
    cursor: pointer;
    border: none;
    min-height: 44px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.service-actions .primary-btn {
    background: var(--ui-primary);
    color: #ffffff;
    box-shadow: 0 3px 12px rgba(46, 175, 125, 0.2);
}

.service-actions .primary-btn:hover {
    background: #268c66;
    box-shadow: 0 4px 16px rgba(46, 175, 125, 0.28);
    transform: translateY(-2px);
}

.service-actions .primary-btn:active {
    transform: translateY(0);
}

.service-actions .secondary-btn {
    background: #ffffff;
    color: var(--ui-primary);
    border: 1.5px solid var(--ui-primary);
    box-shadow: 0 2px 8px rgba(46, 175, 125, 0.1);
}

.service-actions .secondary-btn:hover {
    background: var(--ui-surface);
    box-shadow: 0 3px 12px rgba(46, 175, 125, 0.15);
    transform: translateY(-2px);
}

.service-details {
    background: #ffffff;
    border-radius: var(--ui-radius-md);
    padding: 40px;
    margin-top: 32px;
    position: relative;
    z-index: 2;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    border: 1px solid var(--ui-border);
}

.service-details h2 {
    font-size: 1.45rem;
    color: var(--ui-text);
    margin: 0 0 24px 0;
    padding-bottom: 0;
    border-bottom: none;
    font-weight: 700;
    letter-spacing: -0.3px;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
    margin-bottom: 44px;
}

.detail-card {
    padding: 18px;
    background: var(--ui-surface);
    border-radius: var(--ui-radius-sm);
    border: 1px solid var(--ui-border);
    border-left: 4px solid var(--ui-primary);
    transition: all 0.18s ease;
    cursor: pointer;
}

.detail-card:hover {
    background: #f2fbf6;
    border-color: var(--ui-primary);
    box-shadow: 0 3px 12px rgba(46, 175, 125, 0.1);
    transform: translateY(-2px);
}

.detail-card h3 {
    color: #7a8a96;
    margin: 0 0 8px 0;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
}

.detail-card p {
    color: var(--ui-primary);
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    letter-spacing: -0.3px;
}

.reviews-section {
    margin-top: 44px;
}

.reviews-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    gap: 20px;
}

.reviews-header h2 {
    font-size: 1.45rem;
    color: var(--ui-text);
    margin: 0;
    font-weight: 700;
    letter-spacing: -0.3px;
}

.review-stats {
    display: flex;
    align-items: center;
    gap: 16px;
}

.rating-display {
    text-align: center;
    background: var(--ui-surface);
    padding: 14px 20px;
    border-radius: var(--ui-radius-sm);
    border: 1px solid var(--ui-border);
    white-space: nowrap;
}

.rating-stars {
    font-size: 1.8rem;
    margin-bottom: 4px;
}

.rating-number {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--ui-primary);
    letter-spacing: -0.2px;
}

.rating-count {
    font-size: 13px;
    color: #7a8a96;
    margin-top: 3px;
}

.reviews-list {
    display: grid;
    gap: 12px;
}

.review-item {
    background: #ffffff;
    border: 1px solid var(--ui-border);
    border-radius: var(--ui-radius-sm);
    padding: 18px;
    transition: all 0.18s ease;
}

.review-item:hover {
    border-color: var(--ui-primary);
    box-shadow: 0 2px 8px rgba(46, 175, 125, 0.08);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 10px;
    gap: 12px;
}

.review-name {
    font-weight: 700;
    color: var(--ui-text);
    font-size: 14px;
}
.review-date {
    font-size: 0.8rem;
    color: #999;
}

.review-rating {
    color: #ffc107;
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.review-comment {
    color: #666;
    font-style: italic;
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.4;
}

.no-reviews {
    text-align: center;
    padding: 50px 30px;
    color: #999;
    background: #fafafa;
    border-radius: 6px;
    border: 1px solid #f0f0f0;
}
.review-date {
    font-size: 13px;
    color: #7a8a96;
    flex-shrink: 0;
    .service-detail-cta {
        background: linear-gradient(135deg, var(--ui-primary) 0%, #268c66 100%);
        color: #ffffff;
        padding: 48px 24px;
        text-align: center;
        margin-top: 40px;
        border-radius: var(--ui-radius-md);
        box-shadow: 0 4px 16px rgba(46, 175, 125, 0.2);
    }

    .service-detail-cta h2 {
        margin: 0 0 14px 0;
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .service-detail-cta p {
        margin: 0 0 26px 0;
        font-size: 15px;
        opacity: 0.95;
        line-height: 1.5;
    }

    .service-detail-cta .home-btn {
        background: #ffffff;
        color: var(--ui-primary) !important;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        min-width: 160px;
    }

    .service-detail-cta .home-btn:hover {
        background: #f2fbf6;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.18);
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .service-detail-grid {
            grid-template-columns: 1fr;
            gap: 24px;
            padding: 0;
        }
    
        .service-image {
            height: 360px;
        }
    
        .service-image-placeholder {
            height: 360px;
            font-size: 80px;
        }
    
        .service-info h1 {
            font-size: 1.5rem;
        }
    
        .service-details {
            padding: 24px 18px;
            margin-top: 24px;
        }
    
        .service-meta-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    
        .details-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    
        .reviews-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
    
        .service-actions {
            flex-direction: column;
        }
    
        .service-detail-cta {
            padding: 32px 18px;
            margin-top: 32px;
        }
    
        .service-detail-cta h2 {
            font-size: 1.4rem;
        }
    }
    }
    
    .service-details {
        padding: 20px;
        margin-top: 20px;
    }
    
    .service-meta-grid {
        grid-template-columns: 1fr;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
    
    .reviews-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .service-actions {
        flex-direction: column;
    }
}
@media (max-width: 768px) {
    .service-detail-grid {
        grid-template-columns: 1fr;
        gap: 24px;
        padding: 0;
    }
    
    .service-image {
        height: 360px;
    }
    
    .service-image-placeholder {
        height: 360px;
        font-size: 80px;
    }
    
    .service-info h1 {
        font-size: 1.5rem;
    }
    
    .service-details {
        padding: 24px 18px;
        margin-top: 24px;
    }
    
    .service-meta-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .reviews-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .service-actions {
        flex-direction: column;
    }
}
</style>

<section class="home-container" style="padding: 0;">
    <!-- Hero Section with Image -->
    <section class="service-detail-hero">
        <div class="service-detail-container">
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
                    <h1><?= View::e($service['name']) ?></h1>
                    <p><?= View::e($service['description']) ?></p>
                    
                    <div class="service-meta-grid">
                        <div class="service-meta-item">
                            <div class="service-meta-label">💰 Giá cơ bản</div>
                            <div class="service-meta-value"><?= number_format((int)$service['price'], 0, ',', '.') ?>đ<?= $service['unit'] ? '/' . View::e($service['unit']) : '' ?></div>
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
                        <a href="/book?service=<?= (int)$service['id'] ?>" class="primary-btn">Đặt lịch ngay</a>
                        <a href="/services" class="secondary-btn">← Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Details Section -->
    <section class="service-details">
        <h2>ℹ️ Thông tin chi tiết</h2>
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

        <!-- Reviews Section -->
        <div class="reviews-section">
            <div class="reviews-header">
                <h2>⭐ Đánh giá từ khách hàng</h2>
                <?php if ($averageRating !== null): ?>
                    <div class="review-stats">
                        <div class="rating-display">
                            <div class="rating-number"><?= View::e((string)$averageRating) ?></div>
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
                                <span style="color: #999; margin-left: 8px;">(<?= (int)($review['rating'] ?? 0) ?>/5)</span>
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

    <!-- CTA Section -->
    <!-- CTA Section -->
    <section class="service-detail-cta">
        <h2>Sẵn sàng đặt dịch vụ?</h2>
        <p>Đặt dịch vụ ngay hôm nay và nhận ưu đãi đặc biệt từ chúng tôi</p>
        <a href="/book?service=<?= (int)$service['id'] ?>" class="home-btn">💳 Đặt lịch ngay</a>
    </section>
</section>
