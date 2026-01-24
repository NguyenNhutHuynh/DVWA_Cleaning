<?php
use App\Core\View;

$isLoggedIn = isset($uid) && $uid;
?>

<section class="home-container">
    <header class="home-hero">
        <?php if ($isLoggedIn): ?>
            <p class="home-kicker">Xin chào, <strong><?= isset($name) ? View::e($name) : ('User #' . View::e($uid)) ?></strong></p>
            <h1>Chào mừng bạn đến với Cleaning Service</h1>
            <p>Dịch vụ dọn dẹp chuyên nghiệp, đặt lịch nhanh, theo dõi tiện lợi.</p>
            <div class="hero-actions">
                <a class="home-btn" href="/book">Đặt lịch ngay</a>
                <a class="home-btn home-btn-outline" href="/services">Xem dịch vụ</a>
            </div>
        <?php else: ?>
            <p class="home-kicker">CARE HOME SOLUTIONS</p>
            <h1>Nhà sạch, sống khỏe, đặt lịch trong 1 phút</h1>
            <p>Đội ngũ chuyên nghiệp, thiết bị an toàn, giá minh bạch.</p>
            <div class="hero-actions">
                <a class="home-btn" href="/register">Đăng ký ngay</a>
                <a class="home-btn home-btn-outline" href="/login">Đăng nhập</a>
            </div>
        <?php endif; ?>
    </header>

    <section class="home-stats" aria-label="Số liệu nhanh">
        <div class="stat-card"><strong>12k+</strong><span>Ca dọn thành công</span></div>
        <div class="stat-card"><strong>4.9/5</strong><span>Đánh giá khách hàng</span></div>
        <div class="stat-card"><strong>50+</strong><span>Nhân viên chuyên nghiệp</span></div>
    </section>

    <section class="home-feature" aria-label="Dịch vụ nổi bật">
        <h2>Dịch vụ nổi bật</h2>
        <div class="feature-grid">
            <article class="feature-card">
                <h3>🧹 Tổng vệ sinh</h3>
                <p>Nhà phố, căn hộ, biệt thự. Dụng cụ và hóa chất an toàn.</p>
            </article>
            <article class="feature-card">
                <h3>🛏️ Giặt nệm - sofa</h3>
                <p>Thiết bị phun hút, diệt khuẩn, khử mùi, khô nhanh.</p>
            </article>
            <article class="feature-card">
                <h3>🧼 Sau xây dựng</h3>
                <p>Xử lý bụi mịn, sơn, xi; làm sạch kính, sàn, trần.</p>
            </article>
            <article class="feature-card">
                <h3>🦠 Khử khuẩn</h3>
                <p>Phun khử khuẩn, diệt côn trùng, an toàn cho trẻ nhỏ.</p>
            </article>
            <article class="feature-card">
                <h3>🌳 Sân vườn</h3>
                <p>Cắt tỉa cây, chăm cỏ, tưới tự động, dọn lá rụng.</p>
            </article>
            <article class="feature-card">
                <h3>🚚 Chuyển nhà/văn phòng</h3>
                <p>Trọn gói đóng gói, bốc xếp, vệ sinh trước và sau.</p>
            </article>
        </div>
    </section>

    <section class="home-combo" aria-label="Gói ưu đãi">
        <h2>Gói combo tiết kiệm</h2>
        <div class="combo-grid">
            <article class="combo-card">
                <h3>Combo Nhà gọn</h3>
                <p>2 lần/tuần · 3h/lần · Dụng cụ đầy đủ · Tư vấn checklist.</p>
                <strong>1.299.000đ/tháng</strong>
            </article>
            <article class="combo-card">
                <h3>Combo Văn phòng sạch</h3>
                <p>Thứ 2-6 · 2h/ngày · Lau kính, hút bụi, vệ sinh WC.</p>
                <strong>3.900.000đ/tháng</strong>
            </article>
            <article class="combo-card">
                <h3>Combo Sau xây dựng</h3>
                <p>Đội 4 người · Máy hút công nghiệp · Hoàn thiện trong 1 ngày.</p>
                <strong>Liên hệ báo giá</strong>
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
            <li> Nhân viên kiểm duyệt lý lịch, đào tạo định kỳ.</li>
            <li> Hóa chất chuẩn, thân thiện vật nuôi và trẻ nhỏ.</li>
            <li> Giá niêm yết, hợp đồng điện tử, xuất hóa đơn.</li>
            <li> Hỗ trợ 24/7, đổi lịch linh hoạt, bảo hiểm tài sản.</li>
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

    <footer class="home-footer" aria-label="Liên hệ nhanh">
        <div>Hotline: 1900 123 456 · Zalo/WhatsApp</div>
        <div>Email: support@cleaning.local</div>
        <div>Địa chỉ: 12 Nguyễn Văn Bảo, Quận Gò Vấp, TP.HCM</div>
    </footer>
</section>
