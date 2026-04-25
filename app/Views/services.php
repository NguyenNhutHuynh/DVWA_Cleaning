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
                <button type="submit" class="home-btn services-search-btn">Tìm kiếm</button>
                <?php if (!empty($searchQuery)): ?>
                    <a href="/services" class="home-btn home-btn-outline services-clear-btn">Xóa</a>
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
                            <a href="/service?id=<?= (int)$s['id'] ?>" class="home-btn home-btn-outline">Xem chi tiết</a>
                            <a href="/book" class="home-btn">Đặt lịch</a>
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
                                <strong>1.299.000đ</strong><br>
                                <small>/tháng</small>
            </div>
                        <div class="services-combo-card">
                                <h3>Combo Văn phòng</h3>
                                <p>Làm sạch 5 ngày/tuần</p>
                                <strong>3.900.000đ</strong><br>
                                <small>/tháng</small>
            </div>
                        <div class="services-combo-card">
                                <h3>Combo Sâu</h3>
                                <p>Lên kế hoạch tùy chỉnh</p>
                                <strong>Liên hệ</strong><br>
                                <small>báo giá</small>
            </div>
        </div>
    </section>

        <section class="services-final-cta">
                <h2>Sẵn sàng đặt lịch?</h2>
                <div class="services-final-actions">
                        <a href="/book" class="home-btn">Đặt lịch ngay</a>
                        <a href="/contact" class="home-btn home-btn-outline">Liên hệ tư vấn</a>
        </div>
    </section>
</section>

<style>
.services-page {
    position: relative;
}

.services-page::before {
    content: '';
    position: absolute;
    inset: 180px 0 auto;
    height: 300px;
    background: radial-gradient(ellipse at center, rgba(67, 197, 158, 0.14) 0%, rgba(67, 197, 158, 0) 70%);
    pointer-events: none;
    z-index: 0;
}

.services-page > * {
    position: relative;
    z-index: 1;
}

.services-page-hero {
    margin-bottom: 18px;
}

.services-search-wrap {
    margin-top: 24px;
    background: #f7fdf9;
    border: 1px solid #d9eee3;
    border-radius: 14px;
    padding: 18px;
}

.services-search-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.services-count {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 999px;
    background: #e2f7ec;
    color: #1f8b64;
    font-weight: 700;
    font-size: 0.87rem;
}

.services-hint {
    color: #5f7684;
    font-size: 0.9rem;
}

.services-search-form {
    display: flex;
    gap: 12px;
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
    color: #1f2d3d;
    font-weight: 600;
}

.services-search-input {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #d4eade;
    border-radius: 10px;
    font-size: 14px;
    font-family: inherit;
    box-sizing: border-box;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.services-search-input:focus {
    outline: none;
    border-color: #43c59e;
    box-shadow: 0 0 0 3px rgba(67, 197, 158, 0.16);
}

.services-search-actions {
    display: flex;
    gap: 8px;
}

.services-search-btn,
.services-clear-btn {
    min-height: 44px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.services-search-result {
    margin-top: 16px;
    padding: 14px 16px;
    background: #e8f5e9;
    border-left: 4px solid #43c59e;
    border-radius: 8px;
}

.services-search-result p {
    margin: 0;
    color: #1b5e20;
}

.services-page-grid {
    margin-top: 22px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
    gap: 18px;
}

.services-page-card {
    border: 1px solid #d8eadd;
    border-radius: 14px;
    padding: 0;
    background: #ffffff;
    box-shadow: 0 8px 22px rgba(31, 45, 61, 0.08);
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.services-page-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 30px rgba(31, 45, 61, 0.12);
}

.services-thumb {
    display: block;
    width: 100%;
    height: 210px;
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
    font-size: 62px;
}

.services-content {
    padding: 16px;
}

.services-title-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
}

.services-content h3 {
    margin: 0;
    color: #1f2d3d;
    font-size: 1.28rem;
}

.services-content h3 a {
    text-decoration: none;
    color: inherit;
}

.services-price-badge {
    background: #e0f2e9;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 0.82rem;
    color: #2eaf7d;
    font-weight: 700;
    white-space: nowrap;
}

.services-desc {
    margin: 10px 0 0;
    color: #4e6370;
    line-height: 1.55;
    min-height: 48px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.services-card-actions {
    margin-top: 14px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.services-card-actions .home-btn {
  flex: 1;
  min-width: 110px;
  background: var(--ui-primary) !important;
  color: #ffffff !important;
  border: none !important;
  padding: 11px 16px !important;
  font-size: 15px !important;
  font-weight: 700 !important;
  text-decoration: none;
  text-align: center;
  border-radius: var(--ui-radius-sm) !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
}

.services-card-actions .home-btn-outline {
  flex: 1;
  min-width: 110px;
  background: var(--ui-surface) !important;
  color: var(--ui-primary) !important;
  border: 1.5px solid var(--ui-primary) !important;
  padding: 11px 16px !important;
  font-size: 15px !important;
  font-weight: 700 !important;
  text-decoration: none;
  text-align: center;
  border-radius: var(--ui-radius-sm) !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
}

.services-empty-state {
  grid-column: 1/-1;
  color: #546e7a;
  text-align: center;
  padding: 44px 20px;
  background: #f6fbf8;
  border: 1px dashed #cfe3d8;
  border-radius: 12px;
}

.services-combo-section {
  margin-top: 34px;
  background: #f7fdf9;
  border: 1px solid #d8ece1;
  border-radius: 14px;
  padding: 24px;
  text-align: center;
}

.services-combo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 14px;
    margin-top: 16px;
}

.services-combo-section h2 {
  color: #1f2d3d;
  margin: 0 0 16px;
  font-size: 1.32rem;
  font-weight: 700;
}

.services-combo-card {
    background: #ffffff;
    padding: 18px;
    border-radius: 12px;
    border: 1px solid #deefe6;
}

.services-combo-card h3 {
    color: #2eaf7d;
    margin: 0 0 8px;
}

.services-combo-card p {
    margin: 0 0 8px;
    color: #455a64;
}

.services-combo-card strong {
    font-size: 1.1rem;
    color: #1f2d3d;
}

.services-combo-card small {
    color: #546e7a;
}

.services-final-cta {
    margin-top: 34px;
    border-radius: 14px;
    text-align: center;
    padding: 22px 18px;
    background: linear-gradient(135deg, rgba(67, 197, 158, 0.16), rgba(141, 223, 148, 0.18));
    border: 1px solid #cfe8db;
}

.services-final-cta h2 {
    color: #1f2d3d;
    margin: 0;
}

.services-final-actions {
    display: flex;
  gap: 12px;
  justify-content: center;
  flex-wrap: wrap;
  margin-top: 14px;
}

.services-final-actions .home-btn,
.services-final-actions .home-btn-outline {
  text-decoration: none;
  min-width: 140px;
  padding: 11px 24px;
  font-size: 15px !important;
  font-weight: 700 !important;
  color: #ffffff;
}

.services-final-actions .home-btn-outline {
  color: var(--ui-primary) !important;
}

@media (max-width: 700px) {
    .services-search-wrap {
        padding: 14px;
    }

    .services-title-row {
        flex-direction: column;
    }

    .services-price-badge {
        align-self: flex-start;
    }
}
</style>
