<?php
use App\Core\View;

$isLoggedIn = isset($uid) && $uid;
?>

<style>
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes floatingAnimation {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes countUp {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

@keyframes shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.hero-kicker {
    animation: fadeInDown 0.8s ease-out;
    color: #1a1a1a;
    font-size: 18px;
    letter-spacing: 3px;
    margin: 0 0 10px 0;
    font-weight: 800;
}

.hero-title {
    animation: fadeInDown 1s ease-out 0.2s both;
    color: #1a1a1a;
    font-size: 68px;
    margin: 20px 0;
    font-weight: 900;
}

.hero-title strong {
    font-weight: 900;
}

.hero-subtitle {
    animation: fadeInUp 1s ease-out 0.4s both;
    color: #2a2a2a;
    font-size: 22px;
    margin-bottom: 30px;
    font-weight: 800;
}

.hero-actions {
    animation: fadeInUp 1s ease-out 0.6s both;
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}

.hero-logo {
    animation: scaleIn 0.8s ease-out, floatingAnimation 3s ease-in-out infinite;
    height: 100px;
    margin-bottom: 20px;
    display: inline-block;
}

.home-hero-btn {
    transition: all 0.3s ease;
    padding: 16px 36px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    font-size: 16px;
    position: relative;
    overflow: hidden;
    display: inline-block;
}

.home-hero-btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.1);
    transform: translate(-50%, -50%);
    transition: width 0.5s, height 0.5s;
}

.home-hero-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.home-hero-btn:hover::after {
    width: 250px;
    height: 250px;
}

.hero-btn-primary {
    background: white;
    color: #43c59e;
    box-shadow: 0 8px 24px rgba(67, 197, 158, 0.3);
    font-weight: 700;
}

.hero-btn-secondary {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    border: 2px solid white;
    padding: 14px 34px;
    backdrop-filter: blur(10px);
}

/* Hero Banner */
.home-hero {
    background: linear-gradient(135deg, rgba(67, 197, 158, 0.35) 0%, rgba(30, 102, 86, 0.35) 100%), url('/assets/img/banner.png') center/cover no-repeat;
    background-size: cover;
    background-position: center;
    color: white;
    padding: 100px 20px;
    text-align: center;
    width: 100vw;
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
    margin-bottom: 40px;
    overflow: hidden;
    min-height: 500px;
}

.hero-content-wrapper {
    max-width: 900px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.5);
    padding: 50px 45px;
    border-radius: 20px;
    backdrop-filter: blur(12px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 0 1px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(67, 197, 158, 0.2);
}

/* Stats Section */
.home-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 24px;
    margin: 40px 0;
}

.stat-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    border-radius: 16px;
    padding: 40px 28px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(67, 197, 158, 0.12), 0 1px 3px rgba(0, 0, 0, 0.08);
    border: 1.5px solid #e0f2e9;
    border-top: 5px solid #43c59e;
    animation: slideInUp 0.6s ease-out backwards;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #43c59e, #2eaf7d);
}

.stat-card:nth-child(1) {
    animation-delay: 0.1s;
}

.stat-card:nth-child(2) {
    animation-delay: 0.2s;
}

.stat-card:nth-child(3) {
    animation-delay: 0.3s;
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(67, 197, 158, 0.2), 0 2px 8px rgba(0, 0, 0, 0.12);
    border-top-color: #2eaf7d;
}

.stat-card strong {
    display: block;
    font-size: 3rem;
    background: linear-gradient(135deg, #43c59e, #2eaf7d);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 16px;
    font-weight: 800;
    letter-spacing: -1px;
}

.stat-card span {
    color: #546e7a;
    font-size: 16px;
    display: block;
    font-weight: 600;
    line-height: 1.5;
}

/* Feature Section */
.home-feature {
    padding: 50px 20px;
    background: white;
}

.home-feature h2 {
    text-align: center;
    margin-bottom: 40px;
    font-size: 32px;
    color: #1f2d3d;
    animation: slideInDown 0.6s ease-out;
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 24px;
}

.feature-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(44,62,80,0.12);
    transition: all 0.3s ease;
    animation: slideInUp 0.6s ease-out backwards;
    position: relative;
}

.feature-card:nth-child(1) { animation-delay: 0.1s; }
.feature-card:nth-child(2) { animation-delay: 0.2s; }
.feature-card:nth-child(3) { animation-delay: 0.3s; }
.feature-card:nth-child(4) { animation-delay: 0.4s; }
.feature-card:nth-child(5) { animation-delay: 0.5s; }
.feature-card:nth-child(6) { animation-delay: 0.6s; }

.feature-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 16px 40px rgba(67, 197, 158, 0.25);
}

.feature-card:hover .feature-card-image img {
    transform: scale(1.1);
}

.feature-card-image {
    height: 200px;
    overflow: hidden;
    background: linear-gradient(135deg, #43c59e, #2eaf7d);
}

.feature-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.feature-card-body {
    padding: 20px;
    background: white;
}

.feature-card-title {
    margin: 0 0 12px 0;
    color: #1f2d3d;
    font-size: 18px;
}

.feature-card-desc {
    color: #546e7a;
    font-size: 14px;
    margin: 0 0 16px 0;
    line-height: 1.5;
}

.feature-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.feature-card-price {
    background: #e0f2e9;
    color: #2eaf7d;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.feature-card:hover .feature-card-price {
    background: #43c59e;
    color: white;
    transform: scale(1.05);
}

.feature-card-link {
    color: #43c59e;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
}

/* Combo Section */
.home-combo {
    background: linear-gradient(135deg, #f7fdf9, #e8f5e9);
    padding: 50px 20px;
    border-radius: 16px;
    margin: 50px 0;
}

.home-combo h2 {
    text-align: center;
    margin-bottom: 40px;
    font-size: 32px;
    color: #1f2d3d;
    animation: slideInDown 0.6s ease-out;
}

.combo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.combo-card {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(67,197,158,0.1);
    border: 2px solid #e0f2e9;
    transition: all 0.3s ease;
    animation: slideInUp 0.6s ease-out backwards;
}

.combo-card:nth-child(1) { animation-delay: 0.1s; }
.combo-card:nth-child(2) { animation-delay: 0.2s; }
.combo-card:nth-child(3) { animation-delay: 0.3s; }

.combo-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(67, 197, 158, 0.2);
}

.combo-card:hover .combo-card-image img {
    transform: scale(1.08);
}

.combo-card.vip {
    background: linear-gradient(135deg, #1f2d3d, #2e4a52);
    border: 2px solid #43c59e;
}

.combo-card-image {
    height: 180px;
    background: linear-gradient(135deg, #43c59e, #2eaf7d);
    overflow: hidden;
    position: relative;
}

.combo-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.combo-card.vip .combo-card-image img {
    opacity: 0.8;
}

.combo-badge {
    position: absolute;
    top: 0;
    right: 0;
    background: #ff6b6b;
    color: white;
    padding: 8px 12px;
    border-radius: 0 0 0 8px;
    font-weight: 600;
    font-size: 12px;
}

.combo-card-body {
    padding: 24px;
}

.combo-card.vip .combo-card-body {
    color: white;
}

.combo-card-title {
    margin: 0 0 12px 0;
    color: #1f2d3d;
    font-size: 20px;
}

.combo-card.vip .combo-card-title {
    color: white;
}

.combo-card-desc {
    color: #546e7a;
    font-size: 14px;
    margin: 0 0 16px 0;
    line-height: 1.6;
}

.combo-card.vip .combo-card-desc {
    color: #e0f2e9;
}

.combo-card-price {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-bottom: 16px;
}

.combo-card-price-value {
    font-size: 24px;
    color: #2eaf7d;
    font-weight: 700;
}

.combo-card.vip .combo-card-price-value {
    color: #43c59e;
}

.combo-card-price-unit {
    color: #999;
    font-size: 14px;
}

.combo-btn {
    display: block;
    margin-top: 16px;
    background: #43c59e;
    color: white;
    padding: 10px 14px;
    border-radius: 8px;
    text-decoration: none;
    text-align: center;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.combo-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.5s, height 0.5s;
}

.combo-btn:hover {
    background: #2eaf7d;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(67, 197, 158, 0.3);
}

.combo-btn:hover::before {
    width: 200px;
    height: 200px;
}

/* Process Section */
.home-process {
    padding: 50px 20px;
}

.home-process h2 {
    text-align: center;
    margin-bottom: 40px;
    font-size: 28px;
    color: #1f2d3d;
    animation: slideInDown 0.6s ease-out;
}

.process-steps {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.process-steps li {
    background: linear-gradient(135deg, #f7fdf9, #e8f5e9);
    padding: 24px;
    padding-right: 52px;
    border-radius: 12px;
    border-left: 4px solid #43c59e;
    color: #546e7a;
    line-height: 1.6;
    animation: slideInUp 0.6s ease-out backwards;
    transition: all 0.3s ease;
    position: relative;
}

.process-steps li::after {
    content: '➜';
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #43c59e;
    font-size: 20px;
    font-weight: 700;
    opacity: 0.9;
}

.process-steps li:last-child::after {
    content: '';
}

.process-steps li:nth-child(1) { animation-delay: 0.1s; }
.process-steps li:nth-child(2) { animation-delay: 0.2s; }
.process-steps li:nth-child(3) { animation-delay: 0.3s; }
.process-steps li:nth-child(4) { animation-delay: 0.4s; }

.process-steps li:hover {
    transform: translateX(8px);
    box-shadow: 0 8px 20px rgba(67, 197, 158, 0.15);
}

/* Why Section */
.home-why {
    padding: 50px 20px;
    background: white;
}

.home-why h2 {
    text-align: center;
    margin-bottom: 40px;
    font-size: 28px;
    color: #1f2d3d;
    animation: slideInDown 0.6s ease-out;
}

.why-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.why-list li {
    padding: 20px;
    background: #f7fdf9;
    border-radius: 12px;
    border-left: 4px solid #2eaf7d;
    color: #546e7a;
    line-height: 1.6;
    animation: slideInUp 0.6s ease-out backwards;
    transition: all 0.3s ease;
}

.why-list li:nth-child(1) { animation-delay: 0.1s; }
.why-list li:nth-child(2) { animation-delay: 0.2s; }
.why-list li:nth-child(3) { animation-delay: 0.3s; }
.why-list li:nth-child(4) { animation-delay: 0.4s; }

.why-list li:hover {
    transform: translateX(8px);
    background: white;
    box-shadow: 0 8px 20px rgba(67, 197, 158, 0.12);
}

/* Review Section */
.home-review {
    padding: 50px 20px;
}

.home-review h2 {
    text-align: center;
    margin-bottom: 40px;
    font-size: 28px;
    color: #1f2d3d;
    animation: slideInDown 0.6s ease-out;
}

.review-box {
    max-width: 1200px;
    margin: 0 auto;
    background: #f7fdf9;
    border-radius: 12px;
    border-left: 4px solid #43c59e;
    padding: 30px;
    animation: slideInUp 0.6s ease-out;
    transition: all 0.3s ease;
}

.review-box:hover {
    box-shadow: 0 12px 32px rgba(67, 197, 158, 0.15);
}

.review-box p {
    color: #546e7a;
    font-size: 15px;
    line-height: 1.6;
    margin: 0 0 20px 0;
}

.review-box p:last-child {
    margin-bottom: 0;
}

.review-box i {
    color: #43c59e;
    font-weight: 500;
}

.review-box strong {
    color: #1f2d3d;
}

/* CTA Section */
.home-cta {
    padding: 50px 20px;
    text-align: center;
}

.home-cta h2 {
    font-size: 28px;
    color: #1f2d3d;
    margin: 0 0 20px 0;
    animation: slideInDown 0.6s ease-out;
}

.home-cta p {
    font-size: 16px;
    color: #546e7a;
    margin: 0 0 30px 0;
    animation: slideInUp 0.6s ease-out 0.2s both;
}

.home-btn {
    display: inline-block;
    padding: 12px 32px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    margin: 0 8px;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.home-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
    z-index: -1;
}

.home-btn:hover::before {
    width: 300px;
    height: 300px;
}

.home-btn {
    background: #43c59e;
    color: white;
}

.home-btn:hover {
    background: #2eaf7d;
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(67, 197, 158, 0.3);
}

.home-btn-outline {
    background: transparent;
    color: #43c59e;
    border: 2px solid #43c59e;
}

.home-btn-outline:hover {
    background: #43c59e;
    color: white;
}

/* FAQ Section */
.home-faq {
    padding: 50px 20px;
    background: #f7fdf9;
}

.home-faq h2 {
    text-align: center;
    margin-bottom: 40px;
    font-size: 28px;
    color: #1f2d3d;
    animation: slideInDown 0.6s ease-out;
}

.faq-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.faq-list article {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(67, 197, 158, 0.1);
    animation: slideInUp 0.6s ease-out backwards;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.faq-list article:nth-child(1) { animation-delay: 0.1s; }
.faq-list article:nth-child(2) { animation-delay: 0.2s; }
.faq-list article:nth-child(3) { animation-delay: 0.3s; }

.faq-list article:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 24px rgba(67, 197, 158, 0.2);
    border-left-color: #43c59e;
}

.faq-list h3 {
    margin: 0 0 12px 0;
    color: #1f2d3d;
    font-size: 16px;
    font-weight: 600;
}

.faq-list p {
    margin: 0;
    color: #546e7a;
    font-size: 14px;
    line-height: 1.6;
}

/* Footer */
.home-footer {
    padding: 0;
    background: white;
}

.footer-content {
    max-width: 1080px;
    margin: 0 auto;
    padding: 40px 20px;
}

.footer-bottom {
    border-top: 1px solid #e0f2e9;
    padding-top: 20px;
    text-align: center;
}

.footer-copyright {
    color: #546e7a;
    font-size: 13px;
    margin: 0;
}

.footer-subtext {
    color: #999;
    font-size: 12px;
    margin: 8px 0 0 0;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .process-steps,
    .why-list,
    .faq-list {
        grid-template-columns: 1fr;
    }

    .process-steps li {
        padding-right: 24px;
        padding-bottom: 54px;
    }

    .process-steps li::after {
        content: '↓';
        right: auto;
        left: 50%;
        top: auto;
        bottom: 16px;
        transform: translateX(-50%);
    }
}
</style>

<section class="home-container" style="padding: 0;">
    <!-- Full Width Hero Banner with Enhanced Visuals -->
    <header class="home-hero">
        <?php if ($isLoggedIn): ?>
            <p class="hero-kicker">Xin chào khách hàng</p>
            <h1 class="hero-title">Chào mừng, <strong><?= isset($name) ? View::e($name) : ('User #' . View::e($uid)) ?></strong></h1>
            <p class="hero-subtitle">Dịch vụ dọn dẹp chuyên nghiệp, đặt lịch nhanh, theo dõi tiện lợi</p>
        <?php else: ?>
            <div class="hero-content-wrapper">
                <!-- <img src="/assets/img/logo_nobg.png" alt="Logo" class="hero-logo"> -->
                <p class="hero-kicker">HOME CARE SOLUTIONS</p>
                <h1 class="hero-title">Nhà sạch, sống khỏe<br><strong>đặt lịch trong 1 phút</strong></h1>
                <p class="hero-subtitle"><strong>Đội ngũ chuyên nghiệp</strong> • <strong>Thiết bị an toàn</strong> • <strong>Giá minh bạch</strong></p>
            </div>
        <?php endif; ?>
        <div class="hero-actions">
            <a class="home-hero-btn hero-btn-primary" href="<?= $isLoggedIn ? '/book' : '/register' ?>">
                <?= $isLoggedIn ? 'Đặt lịch ngay' : ' Đăng ký ngay' ?>
            </a>
            <a class="home-hero-btn hero-btn-secondary" href="<?= $isLoggedIn ? '/services' : '/login' ?>">
                <?= $isLoggedIn ? ' Xem dịch vụ' : 'Đăng nhập' ?>
            </a>
        </div>
    </header>

    <section class="home-stats" aria-label="Số liệu nhanh">
        <div class="stat-card"><strong><?= isset($totalBookings) ? number_format($totalBookings) : '0' ?>+</strong><span>✅ Ca dọn thành công</span></div>
        <div class="stat-card"><strong><?= isset($averageRating) ? $averageRating : '4.9' ?>/5</strong><span>⭐ Đánh giá khách hàng</span></div>
        <div class="stat-card"><strong><?= isset($totalWorkers) ? $totalWorkers : '50' ?>+</strong><span>👥 Nhân viên chuyên nghiệp</span></div>
    </section>

    <section class="home-feature" aria-label="Dịch vụ nổi bật">
        <h2>Dịch vụ nổi bật</h2>
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
                        <h3 class="feature-card-title"> Giặt nệm - sofa</h3>
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
        <h2>Gói combo tiết kiệm</h2>
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
                    <a href="/book" class="combo-btn">Chọn gói →</a>
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
                    <a href="/book" class="combo-btn">Chọn gói →</a>
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
        <h2>Quy trình 4 bước</h2>
        <ol class="process-steps">
            <li>Chọn dịch vụ và lịch phù hợp.</li>
            <li>Xác nhận thông tin, thêm ghi chú đặc biệt.</li>
            <li>Nhân viên đến đúng giờ, thực hiện theo checklist.</li>
            <li>Thanh toán, đánh giá, nhận hóa đơn điện tử.</li>
        </ol>
    </section>

    <section class="home-why" aria-label="Lý do chọn">
        <h2>Vì sao khách hàng chọn chúng tôi</h2>
        <ul class="why-list">
            <li>Nhân viên kiểm duyệt lý lịch, đào tạo định kỳ.</li>
            <li>Hóa chất chuẩn, thân thiện vật nuôi và trẻ nhỏ.</li>
            <li>Giá niêm yết, hợp đồng điện tử, xuất hóa đơn.</li>
            <li>Hỗ trợ 24/7, đổi lịch linh hoạt, bảo hiểm tài sản.</li>
        </ul>
    </section>

    <section class="home-review" aria-label="Đánh giá khách hàng">
        <h2>Khách hàng nói gì?</h2>
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
        <h2>FAQ</h2>
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
        <div class="footer-content">
            <!-- Bottom Footer -->
            <div class="footer-bottom">
                <p class="footer-copyright">
                    Cleaning Service &copy; <?= date('Y') ?>. Giữ nhà sạch - sống khỏe. | Tất cả quyền được bảo lưu.
                </p>
                <p class="footer-subtext">
                    Hỗ trợ 24/7 • Đặt lịch online • Thanh toán linh hoạt • Bảo hiểm tài sản
                </p>
            </div>
        </div>
    </footer>
</section>
</section>
