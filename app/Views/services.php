<?php
use App\Core\View;
?>

<section class="home-container">
    <header class="home-hero">
        <h1>Dịch vụ của chúng tôi</h1>
        <p>Danh sách đầy đủ các dịch vụ vệ sinh chuyên nghiệp</p>
    </header>

    <section class="services-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 30px;">
        <article class="service-card" style="border: 1px solid #e0f2e9; border-radius: 12px; padding: 20px; background: #ffffff; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
            <h3 style="margin-top: 0; color: #1f2d3d;">🧹 Tổng vệ sinh nhà</h3>
            <p style="color: #455a64; margin: 10px 0;">Dọn dẹp toàn bộ nhà phố, căn hộ, biệt thự với dụng cụ và hóa chất an toàn.</p>
            <div style="display: flex; gap: 10px; margin-top: 15px;">
                <span style="background: #e0f2e9; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #2eaf7d;">2-4 giờ</span>
                <span style="background: #e0f2e9; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #2eaf7d;">250k+</span>
            </div>
        </article>

        <article class="service-card" style="border: 1px solid #e0f2e9; border-radius: 12px; padding: 20px; background: #ffffff; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
            <h3 style="margin-top: 0; color: #1f2d3d;">🛏️ Giặt nệm & sofa</h3>
            <p style="color: #455a64; margin: 10px 0;">Thiết bị phun hút hiện đại, diệt khuẩn, khử mùi, khô nhanh chóng.</p>
            <div style="display: flex; gap: 10px; margin-top: 15px;">
                <span style="background: #e0f2e9; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #2eaf7d;">1-2 giờ</span>
                <span style="background: #e0f2e9; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #2eaf7d;">350k+</span>
            </div>
        </article>

        <article class="service-card" style="border: 1px solid #e0f2e9; border-radius: 12px; padding: 20px; background: #ffffff; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
            <h3 style="margin-top: 0; color: #1f2d3d;">🧼 Vệ sinh sau xây dựng</h3>
            <p style="color: #455a64; margin: 10px 0;">Xử lý bụi mịn, sơn, xi; làm sạch kính, sàn, trần nhà hoàn toàn.</p>
            <div style="display: flex; gap: 10px; margin-top: 15px;">
                <span style="background: #e0f2e9; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #2eaf7d;">4-6 giờ</span>
                <span style="background: #e0f2e9; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #2eaf7d;">1.5m+</span>
            </div>
        </article>

        <article class="service-card" style="border: 1px solid #e0f2e9; border-radius: 12px; padding: 20px; background: #ffffff; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
            <h3 style="margin-top: 0; color: #1f2d3d;">🦠 Khử khuẩn & diệt côn trùng</h3>
            <p style="color: #455a64; margin: 10px 0;">Phun khử khuẩn, diệt côn trùng an toàn cho trẻ nhỏ và vật nuôi.</p>
            <div style="display: flex; gap: 10px; margin-top: 15px;">
                <span style="background: #e0f2e9; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #2eaf7d;">1-2 giờ</span>
                <span style="background: #e0f2e9; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #2eaf7d;">300k+</span>
            </div>
        </article>

        <article class="service-card" style="border: 1px solid #e0f2e9; border-radius: 12px; padding: 20px; background: #ffffff; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
            <h3 style="margin-top: 0; color: #1f2d3d;">🌳 Cắt tỉa sân vườn</h3>
            <p style="color: #455a64; margin: 10px 0;">Cắt tỉa cây, chăm cỏ, lắp hệ thống tưới tự động, dọn lá rụng.</p>
            <div style="display: flex; gap: 10px; margin-top: 15px;">
                <span style="background: #e0f2e9; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #2eaf7d;">2-4 giờ</span>
                <span style="background: #e0f2e9; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #2eaf7d;">400k+</span>
            </div>
        </article>

        <article class="service-card" style="border: 1px solid #e0f2e9; border-radius: 12px; padding: 20px; background: #ffffff; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
            <h3 style="margin-top: 0; color: #1f2d3d;">🚚 Chuyển nhà/Văn phòng</h3>
            <p style="color: #455a64; margin: 10px 0;">Trọn gói đóng gói, bốc xếp, vệ sinh trước và sau quá trình chuyển.</p>
            <div style="display: flex; gap: 10px; margin-top: 15px;">
                <span style="background: #e0f2e9; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #2eaf7d;">6-8 giờ</span>
                <span style="background: #e0f2e9; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #2eaf7d;">Liên hệ</span>
            </div>
        </article>
    </section>

    <section style="margin-top: 50px; background: #f7fdf9; border: 1px solid #e0f2e9; border-radius: 12px; padding: 30px; text-align: center;">
        <h2 style="color: #1f2d3d; margin-top: 0;">Gói combo được yêu thích</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
            <div style="background: white; padding: 20px; border-radius: 10px; border: 1px solid #e0f2e9;">
                <h3 style="color: #2eaf7d;">Combo Hàng tuần</h3>
                <p style="color: #455a64;">Tổng vệ sinh 2 lần/tuần</p>
                <strong style="font-size: 18px; color: #1f2d3d;">1.299.000đ</strong><br>
                <small style="color: #546e7a;">/tháng</small>
            </div>
            <div style="background: white; padding: 20px; border-radius: 10px; border: 1px solid #e0f2e9;">
                <h3 style="color: #2eaf7d;">Combo Văn phòng</h3>
                <p style="color: #455a64;">Làm sạch 5 ngày/tuần</p>
                <strong style="font-size: 18px; color: #1f2d3d;">3.900.000đ</strong><br>
                <small style="color: #546e7a;">/tháng</small>
            </div>
            <div style="background: white; padding: 20px; border-radius: 10px; border: 1px solid #e0f2e9;">
                <h3 style="color: #2eaf7d;">Combo Sâu</h3>
                <p style="color: #455a64;">Lên kế hoạch tùy chỉnh</p>
                <strong style="font-size: 18px; color: #1f2d3d;">Liên hệ</strong><br>
                <small style="color: #546e7a;">báo giá</small>
            </div>
        </div>
    </section>

    <section style="margin-top: 50px; text-align: center;">
        <h2 style="color: #1f2d3d;">Sẵn sàng đặt lịch?</h2>
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="/book" class="home-btn" style="background: #43c59e; color: white; padding: 10px 24px; border-radius: 10px; text-decoration: none; font-weight: 600;">Đặt lịch ngay</a>
            <a href="/contact" class="home-btn home-btn-outline" style="background: #fdfdfd; color: #2eaf7d; border: 1.5px solid #2eaf7d; padding: 10px 24px; border-radius: 10px; text-decoration: none; font-weight: 600;">Liên hệ tư vấn</a>
        </div>
    </section>
</section>
