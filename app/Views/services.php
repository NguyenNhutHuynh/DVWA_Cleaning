<?php
use App\Core\View;
?>

<section class="home-container services-page">
    <header class="home-hero services-page-hero">
        <h1>Dịch vụ của chúng tôi</h1>
        <p>Danh sách đầy đủ dịch vụ vệ sinh chuyên nghiệp, minh bạch giá và đặt lịch nhanh</p>
    </header>

    <section class="services-search-wrap">
        <div class="services-search-head">
            <span class="services-count"><?= count($services) ?> dịch vụ</span>
            <span class="services-hint">Cập nhật mới nhất</span>
        </div>

        <form method="GET" action="/services" class="services-search-form">
            <div class="services-search-field">
                <label for="searchInput">Tìm kiếm dịch vụ</label>
                <input 
                    type="text" 
                    id="searchInput" 
                    name="q" 
                    placeholder="Nhập tên dịch vụ hoặc từ khóa..." 
                    value="<?= View::e($searchQuery ?? '') ?>"
                    class="services-search-input"
                    minlength="2"
                />
            </div>

            <div class="services-search-actions">
                <button type="submit" class="services-btn services-btn-primary">Tìm kiếm</button>
                <?php if (!empty($searchQuery)): ?>
                    <a href="/services" class="services-btn services-btn-outline">Xóa</a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <?php if (!empty($searchQuery)): ?>
        <section class="services-search-result">
            <p>
                Kết quả tìm kiếm cho: <strong><?= View::e($searchQuery) ?></strong>
                <?php if (empty($services)): ?>
                    - Không tìm thấy dịch vụ nào
                <?php else: ?>
                    - Tìm thấy <strong><?= count($services) ?></strong> dịch vụ
                <?php endif; ?>
            </p>
        </section>
    <?php endif; ?>

    <section class="services-grid services-page-grid">
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $s): ?>
                <article class="service-card services-page-card">
                    <a class="services-thumb" href="/service?id=<?= (int)$s['id'] ?>" aria-label="Xem chi tiết <?= View::e($s['name']) ?>">
                        <?php if (!empty($s['image_path'])): ?>
                            <img src="<?= View::e($s['image_path']) ?>" alt="<?= View::e($s['name']) ?>">
                        <?php else: ?>
                            <div class="services-thumb-fallback">
                                <?= View::e($s['icon'] ?: '🧹') ?>
                            </div>
                        <?php endif; ?>
                    </a>

                    <div class="services-content">
                        <div class="services-title-row">
                            <h3>
                                <a href="/service?id=<?= (int)$s['id'] ?>">
                                    <?= View::e($s['name']) ?>
                                </a>
                            </h3>

                            <span class="services-price-badge">
                                <?= number_format((int)$s['price'], 0, ',', '.') ?><?= $s['unit'] ? 'đ/' . View::e($s['unit']) : 'đ' ?>
                            </span>
                        </div>

                        <?php if (!empty($s['description'])): ?>
                            <p class="services-desc"><?= View::e($s['description']) ?></p>
                        <?php else: ?>
                            <p class="services-desc">Dịch vụ tiêu chuẩn với quy trình làm sạch bài bản, trang thiết bị hiện đại và đội ngũ chuyên nghiệp.</p>
                        <?php endif; ?>

                        <div class="services-card-actions">
                            <a href="/service?id=<?= (int)$s['id'] ?>" class="services-btn services-btn-outline">Xem chi tiết</a>
                            <a href="/book?service=<?= (int)$s['id'] ?>" class="services-btn services-btn-primary">Đặt lịch</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="services-empty-state">
                <?php if (!empty($searchQuery)): ?>
                    Không tìm thấy dịch vụ nào khớp với "<strong><?= View::e($searchQuery) ?></strong>". Hãy thử tìm kiếm với từ khóa khác.
                <?php else: ?>
                    Chưa có dịch vụ nào được kích hoạt.
                <?php endif; ?>
            </p>
        <?php endif; ?>
    </section>

    <section class="services-combo-section">
        <h2>Gói combo được yêu thích</h2>

        <div class="services-combo-grid">
            <div class="services-combo-card">
                <h3>Combo Hàng tuần</h3>
                <p>Tổng vệ sinh 2 lần/tuần</p>
                <div class="services-combo-price">
                    <strong>1.299.000đ</strong>
                    <small>/tháng</small>
                </div>
            </div>

            <div class="services-combo-card">
                <h3>Combo Văn phòng</h3>
                <p>Làm sạch 5 ngày/tuần</p>
                <div class="services-combo-price">
                    <strong>3.900.000đ</strong>
                    <small>/tháng</small>
                </div>
            </div>

            <div class="services-combo-card">
                <h3>Combo Sâu</h3>
                <p>Lên kế hoạch tùy chỉnh</p>
                <div class="services-combo-price">
                    <strong>Liên hệ</strong>
                    <small>báo giá</small>
                </div>
            </div>
        </div>
    </section>

    <section class="services-final-cta">
        <h2>Sẵn sàng đặt lịch?</h2>

        <div class="services-final-actions">
            <a href="/book" class="services-btn services-btn-light">Đặt lịch ngay</a>
            <a href="/contact" class="services-btn services-btn-glass">Liên hệ tư vấn</a>
        </div>
    </section>
</section>

<style>
.services-page {
    --primary: #2eaf7d;
    --primary-dark: #16805a;
    --primary-soft: #e8f7f0;
    --bg-soft: #f7fdf9;
    --text-dark: #1f2d3d;
    --text-muted: #546e7a;
    --border: #dcefe6;
    --white: #ffffff;
    --shadow-sm: 0 8px 24px rgba(31,45,61,0.08);
    --shadow-md: 0 16px 40px rgba(31,45,61,0.12);

    max-width: 1180px;
    margin: 0 auto;
    padding: 24px 16px 60px;
    color: var(--text-dark);
    position: relative;
}

.services-page * {
    box-sizing: border-box;
}

.services-page::before {
    content: '';
    position: absolute;
    inset: 180px 0 auto;
    height: 320px;
    background: radial-gradient(ellipse at center, rgba(46,175,125,0.14) 0%, rgba(46,175,125,0) 70%);
    pointer-events: none;
    z-index: 0;
}

.services-page > * {
    position: relative;
    z-index: 1;
}

.services-page-hero {
    position: relative;
    overflow: hidden;
    padding: 56px 28px;
    border-radius: 28px;
    text-align: center;
    background:
        radial-gradient(circle at top left, rgba(46,175,125,0.20), transparent 34%),
        linear-gradient(135deg, #f7fdf9 0%, #ffffff 48%, #e8f7f0 100%);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
}

.services-page-hero::after {
    content: "";
    position: absolute;
    right: -80px;
    bottom: -80px;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    background: rgba(46,175,125,0.13);
}

.services-page-hero h1 {
    position: relative;
    margin: 0 0 12px;
    font-size: clamp(32px, 5vw, 52px);
    line-height: 1.1;
    font-weight: 800;
    letter-spacing: -0.04em;
    color: var(--text-dark);
}

.services-page-hero p {
    position: relative;
    margin: 0;
    font-size: 17px;
    color: var(--text-muted);
}

.services-search-wrap {
    margin-top: 34px;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 24px;
    box-shadow: var(--shadow-sm);
}

.services-search-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    margin-bottom: 16px;
}

.services-count {
    display: inline-flex;
    align-items: center;
    padding: 8px 14px;
    border-radius: 999px;
    background: var(--primary-soft);
    color: var(--primary-dark);
    font-weight: 800;
    font-size: 14px;
}

.services-hint {
    color: var(--text-muted);
    font-size: 14px;
}

.services-search-form {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    align-items: flex-end;
}

.services-search-field {
    flex: 1;
    min-width: 250px;
}

.services-search-field label {
    display: block;
    margin-bottom: 8px;
    color: var(--text-dark);
    font-weight: 800;
    font-size: 14px;
}

.services-search-input {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid var(--border);
    border-radius: 16px;
    font-size: 15px;
    font-family: inherit;
    background: #fcfffd;
    color: var(--text-dark);
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.services-search-input:focus {
    outline: none;
    background: white;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(46,175,125,0.12);
}

.services-search-actions,
.services-card-actions,
.services-final-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.services-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 46px;
    padding: 12px 24px;
    border-radius: 999px;
    border: none;
    text-decoration: none;
    font-size: 15px;
    font-weight: 800;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.services-btn:hover {
    transform: translateY(-2px);
}

.services-btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    box-shadow: 0 10px 22px rgba(46,175,125,0.22);
}

.services-btn-outline {
    background: white;
    color: var(--primary);
    border: 1.5px solid var(--primary);
}

.services-btn-outline:hover {
    background: var(--primary-soft);
}

.services-btn-light {
    background: white;
    color: var(--primary-dark);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

.services-btn-glass {
    color: white;
    border: 1.5px solid rgba(255,255,255,0.75);
    background: rgba(255,255,255,0.08);
}

.services-search-result {
    margin-top: 18px;
    padding: 16px 18px;
    background: var(--primary-soft);
    border-left: 4px solid var(--primary);
    border-radius: 16px;
}

.services-search-result p {
    margin: 0;
    color: var(--primary-dark);
}

.services-page-grid {
    margin-top: 26px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
    gap: 22px;
}

.services-page-card {
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 0;
    background: var(--white);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.services-page-card:hover {
    transform: translateY(-6px);
    border-color: rgba(46,175,125,0.45);
    box-shadow: var(--shadow-md);
}

.services-thumb {
    display: block;
    width: 100%;
    height: 220px;
    overflow: hidden;
    background: #f4f9f6;
}

.services-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.35s ease;
}

.services-page-card:hover .services-thumb img {
    transform: scale(1.06);
}

.services-thumb-fallback {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #eef6f2 0%, #dff1e7 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 64px;
}

.services-content {
    padding: 22px;
}

.services-title-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
}

.services-content h3 {
    margin: 0;
    color: var(--text-dark);
    font-size: 21px;
    font-weight: 800;
    letter-spacing: -0.02em;
}

.services-content h3 a {
    text-decoration: none;
    color: inherit;
}

.services-price-badge {
    background: var(--primary-soft);
    padding: 7px 12px;
    border-radius: 999px;
    font-size: 13px;
    color: var(--primary);
    font-weight: 800;
    white-space: nowrap;
}

.services-desc {
    margin: 12px 0 0;
    color: var(--text-muted);
    line-height: 1.6;
    min-height: 50px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.services-card-actions {
    margin-top: 18px;
}

.services-card-actions .services-btn {
    flex: 1;
    min-width: 120px;
}

.services-empty-state {
    grid-column: 1/-1;
    color: var(--text-muted);
    text-align: center;
    padding: 50px 24px;
    background: var(--bg-soft);
    border: 1px dashed #cfe3d8;
    border-radius: 22px;
}

.services-combo-section {
    margin-top: 44px;
    padding: 34px;
    border-radius: 26px;
    background: linear-gradient(135deg, #f7fdf9, #ffffff);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    text-align: center;
}

.services-combo-section h2,
.services-final-cta h2 {
    margin: 0;
    color: var(--text-dark);
    font-size: clamp(24px, 3vw, 34px);
    font-weight: 800;
    letter-spacing: -0.03em;
}

.services-combo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 18px;
    margin-top: 24px;
}

.services-combo-card {
    background: white;
    padding: 24px;
    border-radius: 20px;
    border: 1px solid var(--border);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.services-combo-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-sm);
}

.services-combo-card h3 {
    color: var(--text-dark);
    margin: 0 0 8px;
    font-size: 20px;
    font-weight: 800;
}

.services-combo-card p {
    margin: 0;
    color: var(--text-muted);
}

.services-combo-price {
    margin-top: 18px;
    padding: 18px;
    border-radius: 16px;
    background: var(--primary-soft);
}

.services-combo-card strong {
    display: block;
    font-size: 24px;
    color: var(--primary);
    font-weight: 900;
}

.services-combo-card small {
    display: block;
    margin-top: 5px;
    color: var(--text-muted);
}

.services-final-cta {
    margin-top: 44px;
    padding: 38px 24px;
    border-radius: 26px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    text-align: center;
    box-shadow: var(--shadow-md);
}

.services-final-cta h2 {
    color: white;
    margin-bottom: 22px;
}

.services-final-actions {
    justify-content: center;
}

@media (max-width: 768px) {
    .services-page {
        padding: 16px 12px 44px;
    }

    .services-page-hero {
        padding: 42px 18px;
        border-radius: 22px;
    }

    .services-search-wrap,
    .services-page-card,
    .services-combo-section,
    .services-final-cta {
        border-radius: 20px;
    }

    .services-search-head,
    .services-title-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .services-search-actions,
    .services-card-actions,
    .services-final-actions,
    .services-btn {
        width: 100%;
    }

    .services-thumb {
        height: 190px;
    }
}
</style>