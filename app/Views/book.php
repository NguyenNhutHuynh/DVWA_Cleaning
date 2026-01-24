<?php
use App\Core\View;
?>

<section class="home-container">
    <header class="home-hero">
        <h1>Đặt lịch dịch vụ</h1>
        <p>Chọn dịch vụ và thời gian phù hợp với bạn</p>
    </header>

    <section style="margin-top: 30px; background: white; border: 1px solid #e0f2e9; border-radius: 12px; padding: 30px; max-width: 600px; margin-left: auto; margin-right: auto; box-shadow: 0 6px 20px rgba(44,62,80,0.06);">
        <form method="POST" action="/book" style="display: flex; flex-direction: column; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #1f2d3d; font-weight: 500;">Chọn dịch vụ</label>
                <select name="service" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; box-sizing: border-box;">
                    <option value="">-- Chọn dịch vụ --</option>
                    <option value="1">Tổng vệ sinh nhà (50.000đ/m²)</option>
                    <option value="2">Giặt nệm / Sofa (350.000đ/chiếc)</option>
                    <option value="3">Vệ sinh sau xây dựng (60.000đ/m²)</option>
                    <option value="4">Khử khuẩn / Diệt côn trùng (30.000đ/m²)</option>
                    <option value="5">Cắt tỉa sân vườn (40.000đ/m²)</option>
                    <option value="6">Chuyển nhà / Văn phòng (15.000.000đ+)</option>
                </select>
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #1f2d3d; font-weight: 500;">Ngày dự kiến</label>
                <input type="date" name="date" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #1f2d3d; font-weight: 500;">Giờ bắt đầu</label>
                <select name="time" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; box-sizing: border-box;">
                    <option value="">-- Chọn giờ --</option>
                    <option value="08:00">08:00 - Sáng sớm</option>
                    <option value="10:00">10:00 - Sáng</option>
                    <option value="13:00">13:00 - Chiều</option>
                    <option value="15:00">15:00 - Chiều muộn</option>
                    <option value="18:00">18:00 - Tối</option>
                </select>
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #1f2d3d; font-weight: 500;">Khu vực / Địa chỉ</label>
                <input type="text" name="location" required placeholder="Nhập địa chỉ hoặc khu vực" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #1f2d3d; font-weight: 500;">Mô tả thêm (tùy chọn)</label>
                <textarea name="description" rows="4" placeholder="Ví dụ: Diện tích 100m², có 3 phòng, cần lưu ý gì đặc biệt..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; box-sizing: border-box; font-family: inherit;"></textarea>
            </div>

            <div style="background: #f7fdf9; border: 1px solid #e0f2e9; border-radius: 10px; padding: 15px; margin: 10px 0;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="agree_terms" required style="width: 18px; height: 18px; cursor: pointer; margin-right: 10px;">
                    <span style="color: #455a64; font-size: 14px;">Tôi đồng ý với <a href="#" style="color: #2eaf7d; text-decoration: none;">điều khoản dịch vụ</a> và <a href="#" style="color: #2eaf7d; text-decoration: none;">chính sách bảo mật</a></span>
                </label>
            </div>

            <button type="submit" style="background: #43c59e; color: white; border: none; padding: 14px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 16px; transition: background 0.2s;">Xác nhận đặt lịch</button>
        </form>
    </section>

    <section style="margin-top: 50px; background: #f7fdf9; border: 1px solid #e0f2e9; border-radius: 12px; padding: 30px; text-align: center;">
        <h2 style="color: #1f2d3d; margin-top: 0;">Quy trình đặt lịch</h2>
        <ol style="max-width: 600px; margin: 20px auto 0 auto; text-align: left; color: #455a64;">
            <li style="margin-bottom: 15px;"><strong style="color: #1f2d3d;">Chọn dịch vụ:</strong> Lựa chọn loại dịch vụ phù hợp với nhu cầu của bạn</li>
            <li style="margin-bottom: 15px;"><strong style="color: #1f2d3d;">Xác định lịch:</strong> Chọn ngày và giờ sẽ được phục vụ</li>
            <li style="margin-bottom: 15px;"><strong style="color: #1f2d3d;">Cung cấp thông tin:</strong> Nhập địa chỉ và mô tả chi tiết công việc</li>
            <li style="margin-bottom: 0;"><strong style="color: #1f2d3d;">Xác nhận:</strong> Nhân viên sẽ liên hệ xác nhận trước 2 giờ</li>
        </ol>
    </section>

    <section style="margin-top: 40px; background: white; border: 1px solid #e0f2e9; border-radius: 12px; padding: 30px; text-align: center;">
        <h2 style="color: #1f2d3d; margin-top: 0;">Nên biết</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px; text-align: left;">
            <div style="padding: 15px; background: #f7fdf9; border-radius: 10px;">
                <h4 style="color: #2eaf7d; margin: 0 0 8px 0;">⏱️ Thời gian</h4>
                <p style="margin: 0; font-size: 14px; color: #455a64;">Có thể gọi hủy/đổi lịch miễn phí trước 6 giờ</p>
            </div>
            <div style="padding: 15px; background: #f7fdf9; border-radius: 10px;">
                <h4 style="color: #2eaf7d; margin: 0 0 8px 0;">💰 Thanh toán</h4>
                <p style="margin: 0; font-size: 14px; color: #455a64;">Thanh toán qua chuyển khoản hoặc tiền mặt khi hoàn thành</p>
            </div>
            <div style="padding: 15px; background: #f7fdf9; border-radius: 10px;">
                <h4 style="color: #2eaf7d; margin: 0 0 8px 0;">📱 Liên lạc</h4>
                <p style="margin: 0; font-size: 14px; color: #455a64;">Nhân viên sẽ gọi điện trước khi đến</p>
            </div>
            <div style="padding: 15px; background: #f7fdf9; border-radius: 10px;">
                <h4 style="color: #2eaf7d; margin: 0 0 8px 0;">🔍 Kiểm tra</h4>
                <p style="margin: 0; font-size: 14px; color: #455a64;">Kiểm tra kỹ trước khi ký xác nhận hoàn thành</p>
            </div>
        </div>
    </section>

    <section style="margin-top: 40px; text-align: center;">
        <p style="color: #546e7a; margin: 0 0 15px 0;">Có bất kỳ câu hỏi nào?</p>
        <a href="/contact" class="home-btn" style="background: #43c59e; color: white; padding: 10px 24px; border-radius: 10px; text-decoration: none; font-weight: 600;">Liên hệ hỗ trợ</a>
    </section>
</section>
