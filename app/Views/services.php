<?php
use App\Core\View;
?>

<section class="home-container">
    <header class="home-hero">
        <h1>Dịch vụ của chúng tôi</h1>
        <p>Danh sách đầy đủ các dịch vụ vệ sinh chuyên nghiệp</p>
    </header>

    <!-- Search Form -->
    <section style="margin-top: 30px; background: #f7fdf9; border: 1px solid #e0f2e9; border-radius: 12px; padding: 25px;">
        <form method="GET" action="/services" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 250px;">
                <label for="searchInput" style="display: block; margin-bottom: 8px; color: #1f2d3d; font-weight: 600;">Tìm kiếm dịch vụ</label>
                <input 
                    type="text" 
                    id="searchInput" 
                    name="q" 
                    placeholder="Nhập tên dịch vụ hoặc từ khóa..." 
                    value="<?= View::e($searchQuery ?? '') ?>"
                    style="width: 100%; padding: 12px 16px; border: 1.5px solid #e0f2e9; border-radius: 8px; font-size: 14px; font-family: inherit; box-sizing: border-box;"
                    minlength="2"
                />
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="submit" style="background: #43c59e; color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px;">Tìm kiếm</button>
                <?php if (!empty($searchQuery)): ?>
                    <a href="/services" style="background: #f0f0f0; color: #1f2d3d; padding: 12px 24px; border: 1.5px solid #e0f2e9; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center;">Xóa</a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <!-- Search Results Info -->
    <?php if (!empty($searchQuery)): ?>
        <section style="margin-top: 20px; padding: 16px; background: #e8f5e9; border-left: 4px solid #43c59e; border-radius: 4px;">
            <p style="margin: 0; color: #1b5e20;">
                Kết quả tìm kiếm cho: <strong><?= View::e($searchQuery) ?></strong>
                <?php if (empty($services)): ?>
                    - Không tìm thấy dịch vụ nào
                <?php else: ?>
                    - Tìm thấy <strong><?= count($services) ?></strong> dịch vụ
                <?php endif; ?>
            </p>
        </section>
    <?php endif; ?>

    <section class="services-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 30px;">
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $s): ?>
                <article class="service-card" style="border: 1px solid #e0f2e9; border-radius: 12px; padding: 0; background: #ffffff; box-shadow: 0 3px 12px rgba(44,62,80,0.06); overflow: hidden;">
                    <?php if (!empty($s['image_path'])): ?>
                        <img src="<?= View::e($s['image_path']) ?>" alt="<?= View::e($s['name']) ?>" style="width: 100%; height: 200px; object-fit: cover; display: block;">
                    <?php else: ?>
                        <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #f5f5f5 0%, #eee 100%); display: flex; align-items: center; justify-content: center; font-size: 60px;">
                            <?= View::e($s['icon'] ?: '🧹') ?>
                        </div>
                    <?php endif; ?>
                    <div style="padding: 20px;">
                        <h3 style="margin-top: 0; color: #1f2d3d;">
                            <a href="/service?id=<?= (int)$s['id'] ?>" style="text-decoration: none; color: inherit;">
                                <?= View::e($s['name']) ?>
                            </a>
                        </h3>
                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <span style="background: #e0f2e9; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #2eaf7d;">
                            <?= number_format((int)$s['price'], 0, ',', '.') ?><?= $s['unit'] ? 'đ/' . View::e($s['unit']) : 'đ' ?>
                        </span>
                    </div>
                    <div style="margin-top: 12px;">
                        <a href="/service?id=<?= (int)$s['id'] ?>" class="home-btn home-btn-outline" style="background: #fdfdfd; color: #2eaf7d; border: 1.5px solid #2eaf7d; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">Xem chi tiết</a>
                    </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="grid-column: 1/-1; color: #546e7a; text-align: center; padding: 40px 20px;">
                <?php if (!empty($searchQuery)): ?>
                    Không tìm thấy dịch vụ nào khớp với "<strong><?= View::e($searchQuery) ?></strong>". Hãy thử tìm kiếm với từ khóa khác.
                <?php else: ?>
                    Chưa có dịch vụ nào được kích hoạt.
                <?php endif; ?>
            </p>
        <?php endif; ?>
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
