<?php
use App\Core\View;

/** @var array $services */
/** @var int|null $selected */
/** @var string $userAddress */
?>

<section class="home-container">
    <header class="home-hero">
        <h1>Đặt lịch dịch vụ</h1>
        <p>Chọn dịch vụ và thời gian phù hợp với bạn</p>
    </header>

    <section style="margin-top: 30px; background: white; border: 1px solid #e0f2e9; border-radius: 12px; padding: 30px; max-width: 600px; margin-left: auto; margin-right: auto; box-shadow: 0 6px 20px rgba(44,62,80,0.06);">
        <form id="bookingForm" method="POST" action="/book" style="display: flex; flex-direction: column; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #1f2d3d; font-weight: 500;">Chọn dịch vụ</label>
                <select name="service" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; box-sizing: border-box;">
                    <option value="">-- Chọn dịch vụ --</option>
                    <?php if (!empty($services)): ?>
                        <?php foreach ($services as $s): ?>
                            <option
                                value="<?= (int)$s['id'] ?>"
                                data-price="<?= (float)($s['price'] ?? 0) ?>"
                                data-unit="<?= View::e((string)($s['unit'] ?? '')) ?>"
                                <?= (isset($selected) && (int)$selected === (int)$s['id']) ? 'selected' : '' ?>
                            >
                                <?= View::e($s['name']) ?>
                                (<?= number_format((int)$s['price'], 0, ',', '.') ?>đ<?= $s['unit'] ? '/' . View::e($s['unit']) : '' ?><?= !empty($s['minimum']) ? ', tối thiểu ' . number_format((int)$s['minimum'], 0, ',', '.') . 'đ' : '' ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div>
                <label id="quantityLabel" style="display: block; margin-bottom: 8px; color: #1f2d3d; font-weight: 500;">Số lượng</label>
                <input id="quantityInput" type="number" name="quantity" required min="1" step="1" value="1" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; box-sizing: border-box;">
                <small id="pricePreview" style="display:block; margin-top:8px; color:#2eaf7d; font-weight:600;"></small>
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
                <input type="text" name="location" required value="<?= View::e($userAddress ?? '') ?>" placeholder="Nhập địa chỉ hoặc khu vực" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; box-sizing: border-box;">
                <?php if (empty($userAddress)): ?>
                    <small style="color:#b26a00; display:block; margin-top:6px;">Tài khoản của bạn chưa có địa chỉ lưu sẵn. Vui lòng nhập địa chỉ.</small>
                <?php endif; ?>
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

            <button id="submitBtn" type="submit" style="background: #43c59e; color: white; border: none; padding: 14px 24px; border-radius: 10px; font-weight: 600; cursor: not-allowed; opacity: 0.7; font-size: 16px; transition: background 0.2s;" disabled>Xác nhận đặt lịch</button>
        </form>
        <script>
            (function(){
                const form = document.getElementById('bookingForm');
                const submit = document.getElementById('submitBtn');
                const serviceSelect = form.querySelector('[name="service"]');
                const quantityInput = document.getElementById('quantityInput');
                const quantityLabel = document.getElementById('quantityLabel');
                const pricePreview = document.getElementById('pricePreview');
                const requiredFields = ['service','quantity','date','time','location','agree_terms'];

                const normalizeUnit = (unit) => (unit || '').toLowerCase().trim();
                const isIntegerUnit = (unit) => {
                    const normalized = normalizeUnit(unit);
                    return normalized.includes('phòng')
                        || normalized.includes('phong')
                        || normalized.includes('lần')
                        || normalized.includes('lan')
                        || normalized.includes('gói')
                        || normalized.includes('goi');
                };

                const updateQuantityRules = () => {
                    const option = serviceSelect.options[serviceSelect.selectedIndex];
                    const unit = option?.dataset?.unit || '';
                    const price = Number(option?.dataset?.price || 0);

                    quantityLabel.textContent = unit ? ('Số lượng (' + unit + ')') : 'Số lượng';

                    if (normalizeUnit(unit).includes('m2')) {
                        quantityInput.step = '0.5';
                        quantityInput.min = '1';
                    } else if (isIntegerUnit(unit)) {
                        quantityInput.step = '1';
                        quantityInput.min = '1';
                        quantityInput.value = String(Math.max(1, parseInt(quantityInput.value || '1', 10)));
                    } else {
                        quantityInput.step = '1';
                        quantityInput.min = '1';
                    }

                    const quantity = Number(quantityInput.value || 0);
                    if (price > 0 && quantity > 0) {
                        pricePreview.textContent = 'Thành tiền tạm tính: ' + (price * quantity).toLocaleString('vi-VN') + 'đ';
                    } else {
                        pricePreview.textContent = '';
                    }
                };

                const toggle = () => {
                    const valid = requiredFields.every(name => {
                        const el = form.querySelector('[name="'+name+'"]');
                        if (!el) return false;
                        if (el.type === 'checkbox') return el.checked;
                        return !!el.value;
                    });
                    submit.disabled = !valid;
                    submit.style.cursor = valid ? 'pointer' : 'not-allowed';
                    submit.style.opacity = valid ? '1' : '0.7';
                };
                serviceSelect.addEventListener('change', updateQuantityRules);
                quantityInput.addEventListener('input', updateQuantityRules);
                form.addEventListener('input', toggle);
                form.addEventListener('change', toggle);
                updateQuantityRules();
                toggle();
            })();
        </script>
    </section>
</section>
