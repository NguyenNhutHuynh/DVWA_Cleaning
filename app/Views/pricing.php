<?php
use App\Core\View;
?>

<section class="home-container">
    <header class="home-hero">
        <h1>Bảng giá dịch vụ</h1>
        <p>Giá công khai, minh bạch, không phụ phí ẩn</p>
    </header>

    <section style="margin-top: 30px;">
        <div style="background: #f7fdf9; border-radius: 12px; padding: 30px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #e0f2e9; border-bottom: 2px solid #2eaf7d;">
                        <th style="padding: 15px; color: #1f2d3d; font-weight: 600;">Dịch vụ</th>
                        <th style="padding: 15px; color: #1f2d3d; font-weight: 600;">Thời gian</th>
                        <th style="padding: 15px; color: #1f2d3d; font-weight: 600;">Giá cơ bản</th>
                        <th style="padding: 15px; color: #1f2d3d; font-weight: 600;">Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($services)): ?>
                        <?php foreach ($services as $index => $s): ?>
                            <tr style="border-bottom: 1px solid #e0f2e9;<?= $index % 2 === 1 ? ' background: #fdfcf8;' : '' ?>">
                                <td style="padding: 15px;">
                                    <?= View::e($s['icon'] ?: '🧹') ?>
                                    <?= View::e($s['name']) ?><?= $s['unit'] ? ' (' . View::e($s['unit']) . ')' : '' ?>
                                </td>
                                <td style="padding: 15px;">
                                    <?= View::e($s['duration'] ?: '') ?>
                                </td>
                                <td style="padding: 15px; color: #2eaf7d; font-weight: 600;">
                                    <?= number_format((int)$s['price'], 0, ',', '.') ?>đ<?= $s['unit'] ? '/' . View::e($s['unit']) : '' ?>
                                </td>
                                <td style="padding: 15px; font-size: 13px; color: #546e7a;">
                                    <?php if (!empty($s['minimum'])): ?>
                                        Tối thiểu <?= number_format((int)$s['minimum'], 0, ',', '.') ?>đ
                                    <?php elseif (!empty($s['description'])): ?>
                                        <?= View::e($s['description']) ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="padding: 15px; color: #546e7a;">Chưa có dịch vụ nào được kích hoạt.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section style="margin-top: 40px; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
        <div style="background: white; border: 1px solid #e0f2e9; border-radius: 12px; padding: 25px; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
            <h3 style="color: #1f2d3d; margin-top: 0;">Combo Nhà gọn</h3>
            <p style="color: #455a64;">Tổng vệ sinh lặp lại hàng tuần</p>
            <div style="background: #e0f2e9; padding: 20px; border-radius: 10px; margin: 15px 0;">
                <strong style="font-size: 24px; color: #2eaf7d;">1.299.000đ</strong>
                <small style="display: block; color: #546e7a; margin-top: 5px;">/tháng (4 lần)</small>
            </div>
            <ul style="margin: 0; padding-left: 20px; color: #455a64; font-size: 14px;">
                <li>Vệ sinh toàn bộ</li>
                <li>2 lần/tuần</li>
                <li>3 giờ/lần</li>
                <li>Hóa chất an toàn</li>
            </ul>
        </div>

        <div style="background: white; border: 1px solid #e0f2e9; border-radius: 12px; padding: 25px; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
            <h3 style="color: #1f2d3d; margin-top: 0;">Combo Văn phòng sạch</h3>
            <p style="color: #455a64;">Vệ sinh hàng ngày cho không gian làm việc</p>
            <div style="background: #e0f2e9; padding: 20px; border-radius: 10px; margin: 15px 0;">
                <strong style="font-size: 24px; color: #2eaf7d;">3.900.000đ</strong>
                <small style="display: block; color: #546e7a; margin-top: 5px;">/tháng (thứ 2-6)</small>
            </div>
            <ul style="margin: 0; padding-left: 20px; color: #455a64; font-size: 14px;">
                <li>2 giờ/ngày</li>
                <li>Lau kính</li>
                <li>Hút bụi & lau sàn</li>
                <li>Vệ sinh WC</li>
            </ul>
        </div>

        <div style="background: white; border: 1px solid #e0f2e9; border-radius: 12px; padding: 25px; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
            <h3 style="color: #1f2d3d; margin-top: 0;">Combo Nâng cao</h3>
            <p style="color: #455a64;">Kế hoạch tùy chỉnh cho doanh nghiệp</p>
            <div style="background: #e0f2e9; padding: 20px; border-radius: 10px; margin: 15px 0;">
                <strong style="font-size: 24px; color: #2eaf7d;">Liên hệ</strong>
                <small style="display: block; color: #546e7a; margin-top: 5px;">báo giá</small>
            </div>
            <ul style="margin: 0; padding-left: 20px; color: #455a64; font-size: 14px;">
                <li>Lên kế hoạch riêng</li>
                <li>Hỗ trợ 24/7</li>
                <li>Hợp đồng dài hạn</li>
                <li>Giảm giá đặc biệt</li>
            </ul>
        </div>
    </section>

    <section style="margin-top: 50px; background: #f7fdf9; border: 1px solid #e0f2e9; border-radius: 12px; padding: 30px; text-align: center;">
        <h2 style="color: #1f2d3d; margin-top: 0;">Các lợi ích thêm</h2>
        <ul style="list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px;">
            <li style="padding: 15px; background: white; border-radius: 8px; border: 1px solid #e0f2e9;">
                <strong style="color: #2eaf7d;">📋 Hóa đơn điện tử</strong><br>
                <small style="color: #546e7a;">Xuất cho doanh nghiệp</small>
            </li>
            <li style="padding: 15px; background: white; border-radius: 8px; border: 1px solid #e0f2e9;">
                <strong style="color: #2eaf7d;">🔄 Linh hoạt đổi lịch</strong><br>
                <small style="color: #546e7a;">Miễn phí trước 6 giờ</small>
            </li>
            <li style="padding: 15px; background: white; border-radius: 8px; border: 1px solid #e0f2e9;">
                <strong style="color: #2eaf7d;">🛡️ Bảo hiểm tài sản</strong><br>
                <small style="color: #546e7a;">Bảo vệ các vật dụng</small>
            </li>
            <li style="padding: 15px; background: white; border-radius: 8px; border: 1px solid #e0f2e9;">
                <strong style="color: #2eaf7d;">💰 Giảm giá lâu dài</strong><br>
                <small style="color: #546e7a;">Lên đến 20% hàng tháng</small>
            </li>
        </ul>
    </section>

    <section style="margin-top: 40px; text-align: center;">
        <h2 style="color: #1f2d3d;">Có câu hỏi về giá?</h2>
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="/contact" class="home-btn" style="background: #43c59e; color: white; padding: 10px 24px; border-radius: 10px; text-decoration: none; font-weight: 600;">Liên hệ ngay</a>
            <a href="/book" class="home-btn home-btn-outline" style="background: #fdfdfd; color: #2eaf7d; border: 1.5px solid #2eaf7d; padding: 10px 24px; border-radius: 10px; text-decoration: none; font-weight: 600;">Đặt lịch tư vấn</a>
        </div>
    </section>
</section>
