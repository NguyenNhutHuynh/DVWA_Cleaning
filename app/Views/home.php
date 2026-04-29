<?php
use App\Core\View;

$isLoggedIn = isset($uid) && $uid;
$comboLinks = $comboLinks ?? [
    'tong_ve_sinh' => '/book',
    'gia_dinh' => '/book',
    'chuyen_nha' => '/book',
];
?>

<style>
.home-page {
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
}

.home-page * {
    box-sizing: border-box;
}

.home-page-hero {
    position: relative;
    overflow: hidden;
    padding: 70px 28px;
    border-radius: 28px;
    text-align: center;
    background:
        linear-gradient(135deg, rgba(247,253,249,0.92), rgba(232,247,240,0.92)),
        url('/assets/img/banner.png') center/cover no-repeat;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
}

.home-page-hero::after {
    content: "";
    position: absolute;
    right: -80px;
    bottom: -80px;
    width: 240px;
    height: 240px;
    border-radius: 50%;
    background: rgba(46,175,125,0.14);
}

.hero-content-wrapper,
.home-page-hero > .hero-actions,
.home-page-hero > .hero-kicker,
.home-page-hero > .hero-title,
.home-page-hero > .hero-subtitle {
    position: relative;
    z-index: 1;
}

.hero-kicker {
    display: inline-flex;
    margin: 0 0 14px;
    padding: 7px 14px;
    border-radius: 999px;
    background: var(--primary-soft);
    color: var(--primary-dark);
    font-size: 13px;
    font-weight: 900;
    letter-spacing: 0.08em;
}

.hero-title {
    margin: 0 0 16px;
    color: var(--text-dark);
    font-size: clamp(34px, 6vw, 58px);
    line-height: 1.08;
    font-weight: 900;
    letter-spacing: -0.05em;
}

.hero-subtitle {
    margin: 0 auto 28px;
    max-width: 760px;
    color: var(--text-muted);
    font-size: 17px;
    line-height: 1.6;
}

.hero-actions {
    display: flex;
    gap: 14px;
    justify-content: center;
    flex-wrap: wrap;
}

.home-btn,
.home-hero-btn,
.combo-btn {
    min-height: 46px;
    padding: 12px 26px;
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

.home-btn:hover,
.home-hero-btn:hover,
.combo-btn:hover {
    transform: translateY(-2px);
}

.hero-btn-primary,
.home-btn {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    box-shadow: 0 10px 22px rgba(46,175,125,0.22);
}

.hero-btn-secondary,
.home-btn-outline {
    background: white;
    color: var(--primary);
    border: 1.5px solid var(--primary);
}

.hero-btn-secondary:hover,
.home-btn-outline:hover {
    background: var(--primary-soft);
}

.section-title {
    margin: 0 0 26px;
    text-align: center;
    color: var(--text-dark);
    font-size: clamp(24px, 3vw, 34px);
    font-weight: 900;
    letter-spacing: -0.03em;
}

.home-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 22px;
    margin-top: 40px;
}

.stat-card,
.feature-card,
.combo-card,
.faq-list article,
.why-list li,
.process-steps li {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 24px;
    box-shadow: var(--shadow-sm);
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.stat-card:hover,
.feature-card:hover,
.combo-card:hover,
.faq-list article:hover,
.why-list li:hover,
.process-steps li:hover {
    transform: translateY(-5px);
    border-color: rgba(46,175,125,0.45);
    box-shadow: var(--shadow-md);
}

.stat-card {
    padding: 32px 24px;
    text-align: center;
}

.stat-card strong {
    display: block;
    color: var(--primary);
    font-size: 42px;
    font-weight: 900;
    margin-bottom: 10px;
}

.stat-card span {
    color: var(--text-muted);
    font-weight: 700;
    line-height: 1.5;
}

.home-feature,
.home-process,
.home-why,
.home-review,
.home-faq {
    margin-top: 50px;
}

.feature-grid,
.combo-grid,
.faq-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 22px;
}

.feature-card,
.combo-card {
    overflow: hidden;
}

.feature-card-image,
.combo-card-image {
    height: 210px;
    overflow: hidden;
    background: var(--primary-soft);
}

.feature-card-image img,
.combo-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.35s ease;
}

.feature-card:hover img,
.combo-card:hover img {
    transform: scale(1.06);
}

.feature-card-body,
.combo-card-body {
    padding: 22px;
}

.feature-card-title,
.combo-card-title {
    margin: 0 0 10px;
    color: var(--text-dark);
    font-size: 20px;
    font-weight: 900;
}

.feature-card-desc,
.combo-card-desc,
.faq-list p,
.review-box p,
.why-list li,
.process-steps li {
    color: var(--text-muted);
    line-height: 1.6;
}

.feature-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-top: 16px;
}

.feature-card-price {
    padding: 8px 12px;
    border-radius: 999px;
    background: var(--primary-soft);
    color: var(--primary);
    font-size: 13px;
    font-weight: 900;
    white-space: nowrap;
}

.feature-card-link {
    color: var(--primary);
    text-decoration: none;
    font-weight: 900;
}

.home-combo {
    margin-top: 50px;
    padding: 34px;
    border-radius: 26px;
    background: linear-gradient(135deg, var(--bg-soft), #ffffff);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
}

.combo-card {
    background: white;
}

.combo-card.vip {
    background: linear-gradient(135deg, #1f2d3d, #263f47);
    border-color: rgba(46,175,125,0.65);
}

.combo-card.vip .combo-card-title,
.combo-card.vip .combo-card-price-unit {
    color: white;
}

.combo-card.vip .combo-card-desc {
    color: #dcefe6;
}

.combo-badge {
    position: absolute;
    top: 14px;
    right: 14px;
    padding: 7px 12px;
    border-radius: 999px;
    background: var(--primary);
    color: white;
    font-size: 12px;
    font-weight: 900;
}

.combo-card-image {
    position: relative;
}

.combo-card-price {
    margin: 18px 0;
    padding: 18px;
    border-radius: 18px;
    background: var(--primary-soft);
}

.combo-card-price-value {
    display: block;
    color: var(--primary);
    font-size: 26px;
    font-weight: 900;
}

.combo-card-price-unit {
    color: var(--text-muted);
}

.combo-btn {
    width: 100%;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.process-steps,
.why-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 18px;
}

.process-steps {
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
}

.why-list {
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
}

.process-steps li,
.why-list li {
    padding: 22px;
    position: relative;
}

.process-steps li {
    border-left: 5px solid var(--primary);
}

.why-list li {
    border-left: 5px solid var(--primary-dark);
}

.review-box {
    padding: 30px;
    border-radius: 26px;
    background: var(--white);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
}

.review-box p {
    margin: 0 0 18px;
}

.review-box p:last-child {
    margin-bottom: 0;
}

.review-box i {
    color: var(--primary-dark);
    font-weight: 700;
}

.review-box strong {
    color: var(--text-dark);
}

.home-cta {
    margin-top: 50px;
    padding: 42px 24px;
    border-radius: 26px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    text-align: center;
    box-shadow: var(--shadow-md);
}

.home-cta h2 {
    margin: 0 0 12px;
    color: white;
    font-size: clamp(24px, 3vw, 36px);
    font-weight: 900;
}

.home-cta p {
    margin: 0 0 24px;
    color: rgba(255,255,255,0.86);
}

.home-cta .home-btn {
    background: white;
    color: var(--primary-dark);
}

.home-cta .home-btn-outline {
    background: rgba(255,255,255,0.08);
    color: white;
    border-color: rgba(255,255,255,0.75);
}

.home-faq {
    padding: 34px;
    border-radius: 26px;
    background: linear-gradient(135deg, var(--bg-soft), #ffffff);
    border: 1px solid var(--border);
}

.faq-list article {
    padding: 24px;
}

.faq-list h3 {
    margin: 0 0 10px;
    color: var(--text-dark);
    font-size: 18px;
    font-weight: 900;
}

.home-footer {
    margin-top: 40px;
    padding: 24px;
    border-radius: 24px;
    background: white;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    text-align: center;
}

.footer-copyright {
    margin: 0;
    color: var(--text-muted);
    font-size: 13px;
}

.footer-subtext {
    margin: 8px 0 0;
    color: #78909c;
    font-size: 12px;
}

@media (max-width: 768px) {
    .home-page {
        padding: 16px 12px 44px;
    }

    .home-page-hero {
        padding: 46px 18px;
        border-radius: 22px;
    }

    .home-combo,
    .home-faq {
        padding: 22px;
        border-radius: 20px;
    }

    .feature-card-footer {
        flex-direction: column;
        align-items: flex-start;
    }

    .home-btn,
    .home-hero-btn {
        width: 100%;
    }
}
</style>

<section class="home-container home-page">
    <header class="home-page-hero">
        <?php if ($isLoggedIn): ?>
            <p class="hero-kicker">Xin chào khách hàng</p>
            <h1 class="hero-title">Chào mừng, <strong><?= isset($name) ? View::e($name) : ('User #' . View::e($uid)) ?></strong></h1>
            <p class="hero-subtitle">Dịch vụ dọn dẹp chuyên nghiệp, đặt lịch nhanh, theo dõi tiện lợi</p>
        <?php else: ?>
            <div class="hero-content-wrapper">
                <p class="hero-kicker">HOME CARE SOLUTIONS</p>
                <h1 class="hero-title">Nhà sạch, sống khỏe<br><strong>đặt lịch trong 1 phút</strong></h1>
                <p class="hero-subtitle"><strong>Đội ngũ chuyên nghiệp</strong> • <strong>Thiết bị an toàn</strong> • <strong>Giá minh bạch</strong></p>
            </div>
        <?php endif; ?>

        <div class="hero-actions">
            <a class="home-hero-btn hero-btn-primary" href="<?= $isLoggedIn ? '/book' : '/register' ?>">
                <?= $isLoggedIn ? 'Đặt lịch ngay' : 'Đăng ký ngay' ?>
            </a>
            <a class="home-hero-btn hero-btn-secondary" href="<?= $isLoggedIn ? '/services' : '/login' ?>">
                <?= $isLoggedIn ? 'Xem dịch vụ' : 'Đăng nhập' ?>
            </a>
        </div>
    </header>

    <section class="home-stats" aria-label="Số liệu nhanh">
        <div class="stat-card">
            <strong><?= isset($totalBookings) ? number_format($totalBookings) : '0' ?>+</strong>
            <span>✅ Ca dọn thành công</span>
        </div>
        <div class="stat-card">
            <strong><?= isset($averageRating) ? $averageRating : '4.9' ?>/5</strong>
            <span>⭐ Đánh giá khách hàng</span>
        </div>
        <div class="stat-card">
            <strong><?= isset($totalWorkers) ? $totalWorkers : '50' ?>+</strong>
            <span>👥 Nhân viên chuyên nghiệp</span>
        </div>
    </section>

    <section class="home-feature" aria-label="Dịch vụ nổi bật">
        <h2 class="section-title">Dịch vụ nổi bật</h2>

        <div class="feature-grid">
            <?php 
            $serviceImages = [
                'Combo Tổng vệ sinh' => 'combo-tong-ve-sinh.png',
                'Giặt nệm & sofa' => 'sofa.png',
                'Khử khuẩn' => 'khu-khuan.png',
                'Vệ sinh nhà vệ sinh' => 'nha-ve-sinh.png',
                'Vệ sinh phòng khách' => 'phong-khach.png',
                'Vệ sinh phòng ngủ' => 'phong-ngu.png',
                'Vệ sinh nhà bếp' => 'nha-bep.png',
                'Combo Chuyển Nhà' => 'combo-chuyen-nha.png',
                'Vệ sinh kính' => 'kinh.png',
                'Combo Cơ bản' => 'combo-co-ban.png',
                'Combo Gia đình' => 'combo-gia-dinh.png',
                'Tổng vệ sinh nhà' => 'tong-ve-sinh-nha.png',
            ];
            ?>

            <?php if (!empty($featuredServices)): ?>
                <?php foreach ($featuredServices as $idx => $service): 
                    $imageName = $serviceImages[$service['name']] ?? 'phong-khach.png';
                ?>
                    <article class="feature-card">
                        <div class="feature-card-image">
                            <img src="/assets/img/<?= $imageName ?>" alt="<?= View::e($service['name'] ?? '') ?>">
                        </div>
                        <div class="feature-card-body">
                            <h3 class="feature-card-title">
                                <?= View::e($service['icon'] ?? '🧹') ?> <?= View::e($service['name'] ?? '') ?>
                            </h3>
                            <p class="feature-card-desc">
                                <?= View::e(substr($service['description'] ?? '', 0, 80)) ?><?= strlen($service['description'] ?? '') > 80 ? '...' : '' ?>
                            </p>
                            <div class="feature-card-footer">
                                <span class="feature-card-price">
                                    <?= number_format((int)$service['price'], 0, ',', '.') ?>đ
                                </span>
                                <a href="/service?id=<?= (int)$service['id'] ?>" class="feature-card-link">Xem chi tiết →</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <article class="feature-card">
                    <div class="feature-card-image">
                        <img src="/assets/img/phong-khach.png" alt="Tổng vệ sinh">
                    </div>
                    <div class="feature-card-body">
                        <h3 class="feature-card-title">Tổng vệ sinh nhà</h3>
                        <p class="feature-card-desc">Dọn dẹp tổng thể.</p>
                    </div>
                </article>

                <article class="feature-card">
                    <div class="feature-card-image">
                        <img src="/assets/img/sofa.png" alt="Giặt nệm - sofa">
                    </div>
                    <div class="feature-card-body">
                        <h3 class="feature-card-title">Giặt nệm - sofa</h3>
                        <p class="feature-card-desc">Thiết bị chuyên dụng, diệt khuẩn, khử mùi nhanh.</p>
                    </div>
                </article>

                <article class="feature-card">
                    <div class="feature-card-image">
                        <img src="/assets/img/kinh.png" alt="Vệ sinh kính">
                    </div>
                    <div class="feature-card-body">
                        <h3 class="feature-card-title">Vệ sinh kính</h3>
                        <p class="feature-card-desc">Lau sạch và bóng loáng toàn bộ kính biệt thự, căn hộ cao tầng.</p>
                    </div>
                </article>

                <article class="feature-card">
                    <div class="feature-card-image">
                        <img src="/assets/img/nha-ve-sinh.png" alt="Vệ sinh nhà vệ sinh">
                    </div>
                    <div class="feature-card-body">
                        <h3 class="feature-card-title">Vệ sinh nhà vệ sinh</h3>
                        <p class="feature-card-desc">Vệ sinh toàn bộ WC, diệt khuẩn, khử mùi hiệu quả.</p>
                    </div>
                </article>

                <article class="feature-card">
                    <div class="feature-card-image">
                        <img src="/assets/img/nha-bep.png" alt="Vệ sinh nhà bếp">
                    </div>
                    <div class="feature-card-body">
                        <h3 class="feature-card-title">🍳 Vệ sinh nhà bếp</h3>
                        <p class="feature-card-desc">Làm sạch toàn bộ bếp, công thái học cao, an toàn thực phẩm.</p>
                    </div>
                </article>

                <article class="feature-card">
                    <div class="feature-card-image">
                        <img src="/assets/img/combo-chuyen-nha.png" alt="Combo Chuyển Nhà">
                    </div>
                    <div class="feature-card-body">
                        <h3 class="feature-card-title">🚚 Combo Chuyển Nhà</h3>
                        <p class="feature-card-desc">Trọn gói: bốc xếp, vệ sinh trước và sau chuyển nhà.</p>
                    </div>
                </article>
            <?php endif; ?>
        </div>
    </section>

    <section class="home-combo" aria-label="Gói ưu đãi">
        <h2 class="section-title">Gói combo tiết kiệm</h2>

        <div class="combo-grid">
            <article class="combo-card">
                <div class="combo-card-image">
                    <img src="/assets/img/combo-tong-ve-sinh.png" alt="Combo Tổng vệ sinh">
                </div>
                <div class="combo-card-body">
                    <h3 class="combo-card-title">🏠 Combo Tổng vệ sinh</h3>
                    <p class="combo-card-desc">2 lần/tuần · 3h/lần · Dụng cụ đầy đủ · Tư vấn checklist</p>
                    <div class="combo-card-price">
                        <strong class="combo-card-price-value">1.299.000đ</strong>
                        <span class="combo-card-price-unit">/tháng</span>
                    </div>
                    <a href="<?= View::e($comboLinks['tong_ve_sinh'] ?? '/book') ?>" class="combo-btn">Chọn gói →</a>
                </div>
            </article>

            <article class="combo-card">
                <div class="combo-card-image">
                    <img src="/assets/img/combo-gia-dinh.png" alt="Combo Gia đình">
                </div>
                <div class="combo-card-body">
                    <h3 class="combo-card-title">👨‍👩‍👧 Combo Gia đình</h3>
                    <p class="combo-card-desc">Thứ 2-6 · 2h/ngày · Lau kính, hút bụi, vệ sinh WC</p>
                    <div class="combo-card-price">
                        <strong class="combo-card-price-value">3.900.000đ</strong>
                        <span class="combo-card-price-unit">/tháng</span>
                    </div>
                    <a href="<?= View::e($comboLinks['gia_dinh'] ?? '/book') ?>" class="combo-btn">Chọn gói →</a>
                </div>
            </article>

            <article class="combo-card vip">
                <div class="combo-card-image">
                    <img src="/assets/img/combo-chuyen-nha.png" alt="Combo Chuyển nhà">
                    <div class="combo-badge">VIP</div>
                </div>
                <div class="combo-card-body">
                    <h3 class="combo-card-title">🚚 Combo Chuyển nhà</h3>
                    <p class="combo-card-desc">Đội 4 người · Máy hút công nghiệp · Hoàn thiện trong 1 ngày</p>
                    <div class="combo-card-price">
                        <strong class="combo-card-price-value">Liên hệ</strong>
                        <span class="combo-card-price-unit">báo giá</span>
                    </div>
                    <a href="/contact" class="combo-btn">Liên hệ ngay →</a>
                </div>
            </article>
        </div>
    </section>

    <section class="home-process" aria-label="Quy trình">
        <h2 class="section-title">Quy trình 4 bước</h2>
        <ol class="process-steps">
            <li>Chọn dịch vụ và lịch phù hợp.</li>
            <li>Xác nhận thông tin, thêm ghi chú đặc biệt.</li>
            <li>Nhân viên đến đúng giờ, thực hiện theo checklist.</li>
            <li>Thanh toán, đánh giá, nhận hóa đơn điện tử.</li>
        </ol>
    </section>

    <section class="home-why" aria-label="Lý do chọn">
        <h2 class="section-title">Vì sao khách hàng chọn chúng tôi</h2>
        <ul class="why-list">
            <li>Nhân viên kiểm duyệt lý lịch, đào tạo định kỳ.</li>
            <li>Hóa chất chuẩn, thân thiện vật nuôi và trẻ nhỏ.</li>
            <li>Giá niêm yết, hợp đồng điện tử, xuất hóa đơn.</li>
            <li>Hỗ trợ 24/7, đổi lịch linh hoạt, bảo hiểm tài sản.</li>
        </ul>
    </section>

    <section class="home-review" aria-label="Đánh giá khách hàng">
        <h2 class="section-title">Khách hàng nói gì?</h2>
        <div class="review-box">
            <p><i>"Dịch vụ rất chuyên nghiệp, nhân viên thân thiện, nhà cửa sạch bong!"</i> - <strong>Chị Lan, Q.7</strong></p>
            <p><i>"Đặt lịch nhanh, giá hợp lý, sẽ ủng hộ dài lâu!"</i> - <strong>Anh Minh, Q.1</strong></p>
            <p><i>"Lịch trình linh hoạt, thanh toán dễ, có hóa đơn công ty."</i> - <strong>Chị Hạnh, CEO agency</strong></p>
        </div>
    </section>

    <section class="home-cta" aria-label="Kêu gọi hành động">
        <h2>Sẵn sàng cho không gian sạch?</h2>
        <p>Đặt lịch hôm nay để được ưu đãi 20% cho lần đầu.</p>
        <div class="hero-actions">
            <a class="home-btn" href="<?= $isLoggedIn ? '/book' : '/register' ?>">Bắt đầu ngay</a>
            <a class="home-btn home-btn-outline" href="/contact">Liên hệ tư vấn</a>
        </div>
    </section>

    <section class="home-faq" aria-label="Câu hỏi thường gặp">
        <h2 class="section-title">FAQ</h2>
        <div class="faq-list">
            <article>
                <h3>Mang theo dụng cụ không?</h3>
                <p>Chúng tôi có đầy đủ dụng cụ và hóa chất an toàn. Bạn có thể yêu cầu dùng đồ riêng.</p>
            </article>
            <article>
                <h3>Hủy/đổi lịch thế nào?</h3>
                <p>Đổi lịch miễn phí trước 6 giờ. Hủy gấp vui lòng liên hệ hotline.</p>
            </article>
            <article>
                <h3>Có xuất hóa đơn không?</h3>
                <p>Có, xuất hóa đơn điện tử cho doanh nghiệp và cá nhân.</p>
            </article>
        </div>
    </section>

    <footer class="home-footer" aria-label="Footer">
        <p class="footer-copyright">
            Cleaning Service &copy; <?= date('Y') ?>. Giữ nhà sạch - sống khỏe. | Tất cả quyền được bảo lưu.
        </p>
        <p class="footer-subtext">
            Hỗ trợ 24/7 • Đặt lịch online • Thanh toán linh hoạt • Bảo hiểm tài sản
        </p>
    </footer>
</section>