<?php
use App\Core\View;
?>

<style>
    .pricing-page {
        --primary: #2eaf7d;
        --primary-dark: #16805a;
        --primary-soft: #e8f7f0;
        --bg-soft: #f7fdf9;
        --text-dark: #1f2d3d;
        --text-muted: #546e7a;
        --border: #dcefe6;
        --white: #ffffff;
        --shadow-sm: 0 8px 24px rgba(31, 45, 61, 0.08);
        --shadow-md: 0 16px 40px rgba(31, 45, 61, 0.12);

        max-width: 1180px;
        margin: 0 auto;
        padding: 24px 16px 60px;
        color: var(--text-dark);
    }

    .pricing-page * {
        box-sizing: border-box;
    }

    .pricing-hero {
        position: relative;
        overflow: hidden;
        padding: 56px 28px;
        border-radius: 28px;
        text-align: center;
        background:
            radial-gradient(circle at top left, rgba(46, 175, 125, 0.20), transparent 34%),
            linear-gradient(135deg, #f7fdf9 0%, #ffffff 48%, #e8f7f0 100%);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    .pricing-hero::after {
        content: "";
        position: absolute;
        right: -80px;
        bottom: -80px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(46, 175, 125, 0.13);
    }

    .pricing-hero h1 {
        position: relative;
        margin: 0 0 12px;
        font-size: clamp(32px, 5vw, 52px);
        line-height: 1.1;
        font-weight: 800;
        letter-spacing: -0.04em;
        color: var(--text-dark);
    }

    .pricing-hero p {
        position: relative;
        margin: 0;
        font-size: 17px;
        color: var(--text-muted);
    }

    .pricing-section {
        margin-top: 36px;
    }

    .pricing-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 24px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .pricing-table-wrap {
        overflow-x: auto;
    }

    .pricing-table {
        width: 100%;
        min-width: 760px;
        border-collapse: separate;
        border-spacing: 0;
        text-align: left;
    }

    .pricing-table thead tr {
        background: linear-gradient(135deg, var(--primary-soft), #f7fdf9);
    }

    .pricing-table th {
        padding: 18px 20px;
        color: var(--text-dark);
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 2px solid var(--primary);
        white-space: nowrap;
    }

    .pricing-table td {
        padding: 18px 20px;
        border-bottom: 1px solid var(--border);
        color: #34495e;
        vertical-align: middle;
    }

    .pricing-table tbody tr {
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .pricing-table tbody tr:nth-child(even) {
        background: #fcfffd;
    }

    .pricing-table tbody tr:hover {
        background: #f2fbf7;
    }

    .service-name {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .service-icon {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: var(--primary-soft);
        flex-shrink: 0;
    }

    .price-text {
        color: var(--primary);
        font-size: 17px;
        font-weight: 800;
        white-space: nowrap;
    }

    .note-text {
        font-size: 13px;
        color: var(--text-muted);
    }

    .combo-grid {
        margin-top: 40px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 22px;
    }

    .combo-card {
        position: relative;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 28px;
        box-shadow: var(--shadow-sm);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .combo-card:hover {
        transform: translateY(-6px);
        border-color: rgba(46, 175, 125, 0.45);
        box-shadow: var(--shadow-md);
    }

    .combo-card h3 {
        margin: 0 0 8px;
        color: var(--text-dark);
        font-size: 22px;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .combo-card p {
        margin: 0;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .combo-price {
        margin: 22px 0;
        padding: 22px;
        border-radius: 18px;
        background: linear-gradient(135deg, var(--primary-soft), #f7fdf9);
        border: 1px solid var(--border);
    }

    .combo-price strong {
        display: block;
        font-size: 28px;
        line-height: 1.1;
        color: var(--primary);
        font-weight: 900;
    }

    .combo-price small {
        display: block;
        color: var(--text-muted);
        margin-top: 7px;
    }

    .combo-card ul {
        margin: 0;
        padding: 0;
        list-style: none;
        display: grid;
        gap: 11px;
        color: #455a64;
        font-size: 14px;
    }

    .combo-card li {
        position: relative;
        padding-left: 26px;
    }

    .combo-card li::before {
        content: "✓";
        position: absolute;
        left: 0;
        top: -1px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: var(--primary-soft);
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
    }

    .benefits-box {
        margin-top: 50px;
        padding: 34px;
        border-radius: 26px;
        background:
            linear-gradient(135deg, #f7fdf9, #ffffff);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        text-align: center;
    }

    .benefits-box h2,
    .pricing-cta h2 {
        margin: 0;
        color: var(--text-dark);
        font-size: clamp(24px, 3vw, 34px);
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .benefit-grid {
        list-style: none;
        padding: 0;
        margin: 26px 0 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
    }

    .benefit-item {
        padding: 20px;
        background: var(--white);
        border-radius: 18px;
        border: 1px solid var(--border);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .benefit-item:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-sm);
    }

    .benefit-item strong {
        display: block;
        color: var(--primary);
        margin-bottom: 6px;
        font-size: 15px;
    }

    .benefit-item small {
        color: var(--text-muted);
    }

    .pricing-cta {
        margin-top: 44px;
        padding: 38px 24px;
        border-radius: 26px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        text-align: center;
        box-shadow: var(--shadow-md);
    }

    .pricing-cta h2 {
        color: white;
        margin-bottom: 22px;
    }

    .cta-actions {
        display: flex;
        gap: 14px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .pricing-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 46px;
        padding: 12px 26px;
        border-radius: 999px;
        text-decoration: none;
        font-weight: 800;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .pricing-btn:hover {
        transform: translateY(-2px);
    }

    .pricing-btn-primary {
        background: white;
        color: var(--primary-dark);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .pricing-btn-outline {
        color: white;
        border: 1.5px solid rgba(255, 255, 255, 0.75);
        background: rgba(255, 255, 255, 0.08);
    }

    .pricing-btn-outline:hover {
        background: rgba(255, 255, 255, 0.16);
    }

    @media (max-width: 768px) {
        .pricing-page {
            padding: 16px 12px 44px;
        }

        .pricing-hero {
            padding: 42px 18px;
            border-radius: 22px;
        }

        .pricing-card,
        .combo-card,
        .benefits-box,
        .pricing-cta {
            border-radius: 20px;
        }

        .combo-card,
        .benefits-box {
            padding: 22px;
        }

        .pricing-table th,
        .pricing-table td {
            padding: 15px;
        }
    }
</style>

<section class="home-container pricing-page">
    <header class="home-hero pricing-hero">
        <h1>Bảng giá dịch vụ</h1>
        <p>Giá công khai, minh bạch, không phụ phí ẩn</p>
    </header>

    <section class="pricing-section">
        <div class="pricing-card pricing-table-wrap">
            <table class="pricing-table">
                <thead>
                    <tr>
                        <th>Dịch vụ</th>
                        <th>Thời gian</th>
                        <th>Giá cơ bản</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($services)): ?>
                        <?php foreach ($services as $index => $s): ?>
                            <tr>
                                <td>
                                    <div class="service-name">
                                        <span class="service-icon"><?= View::e($s['icon'] ?: '🧹') ?></span>
                                        <span>
                                            <?= View::e($s['name']) ?><?= $s['unit'] ? ' (' . View::e($s['unit']) . ')' : '' ?>
                                        </span>
                                    </div>
                                </td>
                                <td><?= View::e($s['duration'] ?: '') ?></td>
                                <td>
                                    <span class="price-text">
                                        <?= number_format((int)$s['price'], 0, ',', '.') ?>đ<?= $s['unit'] ? '/' . View::e($s['unit']) : '' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="note-text">
                                        <?php if (!empty($s['minimum'])): ?>
                                            Tối thiểu <?= number_format((int)$s['minimum'], 0, ',', '.') ?>đ
                                        <?php elseif (!empty($s['description'])): ?>
                                            <?= View::e($s['description']) ?>
                                        <?php endif; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="note-text">Chưa có dịch vụ nào được kích hoạt.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="combo-grid">
        <div class="combo-card">
            <h3>Combo Nhà gọn</h3>
            <p>Tổng vệ sinh lặp lại hàng tuần</p>
            <div class="combo-price">
                <strong>1.299.000đ</strong>
                <small>/tháng (4 lần)</small>
            </div>
            <ul>
                <li>Vệ sinh toàn bộ</li>
                <li>2 lần/tuần</li>
                <li>3 giờ/lần</li>
                <li>Hóa chất an toàn</li>
            </ul>
        </div>

        <div class="combo-card">
            <h3>Combo Văn phòng sạch</h3>
            <p>Vệ sinh hàng ngày cho không gian làm việc</p>
            <div class="combo-price">
                <strong>3.900.000đ</strong>
                <small>/tháng (thứ 2-6)</small>
            </div>
            <ul>
                <li>2 giờ/ngày</li>
                <li>Lau kính</li>
                <li>Hút bụi & lau sàn</li>
                <li>Vệ sinh WC</li>
            </ul>
        </div>

        <div class="combo-card">
            <h3>Combo Nâng cao</h3>
            <p>Kế hoạch tùy chỉnh cho doanh nghiệp</p>
            <div class="combo-price">
                <strong>Liên hệ</strong>
                <small>báo giá</small>
            </div>
            <ul>
                <li>Lên kế hoạch riêng</li>
                <li>Hỗ trợ 24/7</li>
                <li>Hợp đồng dài hạn</li>
                <li>Giảm giá đặc biệt</li>
            </ul>
        </div>
    </section>

    <section class="benefits-box">
        <h2>Các lợi ích thêm</h2>
        <ul class="benefit-grid">
            <li class="benefit-item">
                <strong>📋 Hóa đơn điện tử</strong>
                <small>Xuất cho doanh nghiệp</small>
            </li>
            <li class="benefit-item">
                <strong>🔄 Linh hoạt đổi lịch</strong>
                <small>Miễn phí trước 6 giờ</small>
            </li>
            <li class="benefit-item">
                <strong>🛡️ Bảo hiểm tài sản</strong>
                <small>Bảo vệ các vật dụng</small>
            </li>
            <li class="benefit-item">
                <strong>💰 Giảm giá lâu dài</strong>
                <small>Lên đến 20% hàng tháng</small>
            </li>
        </ul>
    </section>

    <section class="pricing-cta">
        <h2>Có câu hỏi về giá?</h2>
        <div class="cta-actions">
            <a href="/contact" class="home-btn pricing-btn pricing-btn-primary">Liên hệ ngay</a>
            <a href="/book" class="home-btn home-btn-outline pricing-btn pricing-btn-outline">Đặt lịch tư vấn</a>
        </div>
    </section>
</section>