<?php
use App\Core\View;
?>

<section class="home-container">
    <header class="home-hero">
        <h1>Liên hệ với chúng tôi</h1>
        <p>Chúng tôi sẵn sàng trả lời mọi câu hỏi của bạn</p>
    </header>

    <section style="margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px; max-width: 900px; margin-left: auto; margin-right: auto;">
        <!-- Biểu mẫu liên hệ -->
        <div style="background: white; border: 1px solid #e0f2e9; border-radius: 12px; padding: 30px; box-shadow: 0 6px 20px rgba(44,62,80,0.06);">
            <h2 style="color: #1f2d3d; margin-top: 0;">Gửi tin nhắn</h2>
            <form method="POST" action="/contact" style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #1f2d3d; font-weight: 500;">Họ tên</label>
                    <input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #1f2d3d; font-weight: 500;">Email</label>
                    <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #1f2d3d; font-weight: 500;">Điện thoại</label>
                    <input type="tel" name="phone" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #1f2d3d; font-weight: 500;">Chủ đề</label>
                    <select name="subject" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; box-sizing: border-box;">
                        <option value="">-- Chọn chủ đề --</option>
                        <option value="Hỏi giá">Hỏi giá dịch vụ</option>
                        <option value="Tư vấn">Tư vấn dịch vụ</option>
                        <option value="Khiếu nại">Khiếu nại / Phản hồi</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #1f2d3d; font-weight: 500;">Tin nhắn</label>
                    <textarea name="message" required rows="6" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; box-sizing: border-box; font-family: inherit;"></textarea>
                </div>
                <button type="submit" style="background: #43c59e; color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 16px; transition: background 0.2s;">Gửi tin nhắn</button>
            </form>
        </div>

        <!-- Thông tin liên hệ -->
        <div>
            <div style="background: white; border: 1px solid #e0f2e9; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
                <h3 style="color: #1f2d3d; margin-top: 0;">📞 Điện thoại</h3>
                <p style="margin: 0; color: #2eaf7d; font-weight: 600; font-size: 18px;">1900 123 456</p>
                <p style="margin: 8px 0 0 0; color: #546e7a; font-size: 14px;">Hỗ trợ 24/7</p>
            </div>

            <div style="background: white; border: 1px solid #e0f2e9; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
                <h3 style="color: #1f2d3d; margin-top: 0;">✉️ Email</h3>
                <p style="margin: 0; color: #2eaf7d; font-weight: 600;">support@cleaning.local</p>
                <p style="margin: 8px 0 0 0; color: #546e7a; font-size: 14px;">Trả lời trong 2 giờ</p>
            </div>

            <div style="background: white; border: 1px solid #e0f2e9; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
                <h3 style="color: #1f2d3d; margin-top: 0;">📍 Địa chỉ</h3>
                <p style="margin: 0; color: #1f2d3d; font-weight: 500;">12 Nguyễn Văn Bảo, Quận Gò Vấp</p>
                <p style="margin: 5px 0 0 0; color: #455a64; font-size: 14px;">TP. Hồ Chí Minh, Việt Nam</p>
            </div>

            <div style="background: white; border: 1px solid #e0f2e9; border-radius: 12px; padding: 25px; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
                <h3 style="color: #1f2d3d; margin-top: 0;">💬 Mạng xã hội</h3>
                <div style="display: flex; gap: 10px; margin-top: 12px;">
                    <a href="#" style="display: inline-block; width: 40px; height: 40px; background: #e0f2e9; border-radius: 8px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #2eaf7d; font-weight: bold;">f</a>
                    <a href="#" style="display: inline-block; width: 40px; height: 40px; background: #e0f2e9; border-radius: 8px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #2eaf7d; font-weight: bold;">Z</a>
                    <a href="#" style="display: inline-block; width: 40px; height: 40px; background: #e0f2e9; border-radius: 8px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #2eaf7d; font-weight: bold;">W</a>
                </div>
            </div>
        </div>
    </section>

    <section style="margin-top: 50px; background: #f7fdf9; border: 1px solid #e0f2e9; border-radius: 12px; padding: 30px; text-align: center;">
        <h2 style="color: #1f2d3d; margin-top: 0;">Giờ làm việc</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; margin-top: 20px;">
            <div>
                <strong style="color: #1f2d3d;">Thứ 2 - 6</strong>
                <p style="margin: 8px 0 0 0; color: #455a64;">7:00 - 22:00</p>
            </div>
            <div>
                <strong style="color: #1f2d3d;">Thứ 7</strong>
                <p style="margin: 8px 0 0 0; color: #455a64;">8:00 - 22:00</p>
            </div>
            <div>
                <strong style="color: #1f2d3d;">Chủ nhật</strong>
                <p style="margin: 8px 0 0 0; color: #455a64;">8:00 - 20:00</p>
            </div>
            <div>
                <strong style="color: #1f2d3d;">Lễ tết</strong>
                <p style="margin: 8px 0 0 0; color: #455a64;">Mở cửa thường</p>
            </div>
        </div>
    </section>

    <section style="margin-top: 40px; text-align: center;">
        <h2 style="color: #1f2d3d;">Cần dịch vụ ngay?</h2>
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="/book" class="home-btn" style="background: #43c59e; color: white; padding: 10px 24px; border-radius: 10px; text-decoration: none; font-weight: 600;">Đặt lịch</a>
            <a href="tel:1900123456" class="home-btn home-btn-outline" style="background: #fdfdfd; color: #2eaf7d; border: 1.5px solid #2eaf7d; padding: 10px 24px; border-radius: 10px; text-decoration: none; font-weight: 600;">Gọi ngay</a>
        </div>
    </section>
</section>
