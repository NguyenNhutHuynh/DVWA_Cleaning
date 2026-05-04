<?php
use App\Core\View;

/** @var array $services */
/** @var int|null $selected */
/** @var string $userAddress */
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lịch Dọn Dẹp - DVWA Cleaning</title>

    <style>
        .booking-page {
            --primary: #2eaf7d;
            --primary-dark: #16805a;
            --primary-soft: #e8f7f0;
            --bg-soft: #f7fdf9;
            --text-dark: #1f2d3d;
            --text-muted: #546e7a;
            --border: #dcefe6;
            --white: #ffffff;
            --danger: #e74c3c;
            --warning: #d97706;
            --shadow-sm: 0 8px 24px rgba(31,45,61,0.08);
            --shadow-md: 0 16px 40px rgba(31,45,61,0.12);

            max-width: 1180px;
            margin: 0 auto;
            padding: 24px 16px 60px;
            color: var(--text-dark);
            font-family: inherit;
        }

        .booking-page * {
            box-sizing: border-box;
        }

        .booking-hero {
            position: relative;
            overflow: hidden;
            padding: 56px 28px;
            border-radius: 28px;
            text-align: center;
            background:
                radial-gradient(circle at top left, rgba(46,175,125,0.20), transparent 34%),
                linear-gradient(135deg, #f7fdf9 0%, #ffffff 48%, #e8f7f0 100%);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .booking-hero::after {
            content: "";
            position: absolute;
            right: -80px;
            bottom: -80px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(46,175,125,0.13);
        }

        .booking-hero h1 {
            position: relative;
            margin: 0 0 12px;
            font-size: clamp(32px, 5vw, 52px);
            line-height: 1.1;
            font-weight: 900;
            letter-spacing: -0.04em;
            color: var(--text-dark);
        }

        .booking-hero p {
            position: relative;
            margin: 0;
            font-size: 17px;
            color: var(--text-muted);
        }

        .booking-layout {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 26px;
            align-items: start;
        }

        .booking-card,
        .booking-benefit-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: var(--shadow-sm);
        }

        .booking-card {
            padding: 34px;
        }

        .booking-form {
            display: grid;
            gap: 30px;
        }

        .booking-section {
            padding-bottom: 30px;
            border-bottom: 1px solid var(--border);
        }

        .booking-section:last-child {
            padding-bottom: 0;
            border-bottom: none;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0 0 22px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--primary-soft);
            color: var(--text-dark);
            font-size: 21px;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .section-icon {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: var(--primary-soft);
            color: var(--primary);
            flex-shrink: 0;
        }

        .booking-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .booking-grid-full {
            grid-column: 1 / -1;
        }

        .input-field label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 800;
            font-size: 14px;
        }

        .input-field input,
        .input-field select,
        .input-field textarea {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--border);
            border-radius: 16px;
            font-size: 15px;
            font-family: inherit;
            color: var(--text-dark);
            background: #fcfffd;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, transform 0.2s ease;
        }

        .input-field input,
        .input-field select {
            min-height: 52px;
        }

        .input-field textarea {
            min-height: 130px;
            resize: vertical;
        }

        .input-field input::placeholder,
        .input-field textarea::placeholder {
            color: #8aa79b;
        }

        .input-field input:focus,
        .input-field select:focus,
        .input-field textarea:focus {
            outline: none;
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(46,175,125,0.12);
            transform: translateY(-1px);
        }

        .field-note {
            margin: 8px 0 0;
            color: var(--text-muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .field-warning {
            margin: 8px 0 0;
            color: var(--warning);
            font-size: 13px;
            font-weight: 700;
        }

        .price-preview {
            display: inline-flex;
            align-items: center;
            margin-top: 10px;
            padding: 11px 16px;
            border-radius: 999px;
            background: var(--primary-soft);
            color: var(--primary-dark);
            font-weight: 900;
            font-size: 14px;
        }

        .terms-box {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 20px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--primary-soft), #ffffff);
            border: 1px solid var(--border);
        }

        .terms-box input {
            width: 20px;
            height: 20px;
            margin-top: 2px;
            accent-color: var(--primary);
            cursor: pointer;
            flex-shrink: 0;
        }

        .terms-box label {
            color: var(--text-muted);
            line-height: 1.6;
            cursor: pointer;
        }

        .terms-box a {
            color: var(--primary);
            font-weight: 900;
            text-decoration: none;
        }

        .terms-box a:hover {
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            min-height: 56px;
            border: none;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            font-size: 17px;
            font-weight: 900;
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(46,175,125,0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }

        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(46,175,125,0.28);
        }

        .submit-btn:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            box-shadow: none;
        }

        .submit-note {
            margin: 14px 0 0;
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
        }

        .booking-benefits {
            display: grid;
            gap: 18px;
            position: sticky;
            top: 24px;
        }

        .booking-benefit-card {
            padding: 24px;
            text-align: center;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .booking-benefit-card:hover {
            transform: translateY(-4px);
            border-color: rgba(46,175,125,0.45);
            box-shadow: var(--shadow-md);
        }

        .booking-benefit-icon {
            width: 58px;
            height: 58px;
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: var(--primary-soft);
            font-size: 30px;
        }

        .booking-benefit-card h3 {
            margin: 0 0 8px;
            color: var(--text-dark);
            font-size: 18px;
            font-weight: 900;
        }

        .booking-benefit-card p {
            margin: 0;
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.6;
        }

        @media (max-width: 980px) {
            .booking-layout {
                grid-template-columns: 1fr;
            }

            .booking-benefits {
                position: static;
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .booking-page {
                padding: 16px 12px 44px;
            }

            .booking-hero {
                padding: 42px 18px;
                border-radius: 22px;
            }

            .booking-card {
                padding: 22px;
                border-radius: 20px;
            }

            .booking-grid {
                grid-template-columns: 1fr;
            }

            .booking-benefits {
                grid-template-columns: 1fr;
            }

            .submit-btn {
                border-radius: 18px;
            }
        }
    </style>
</head>

<body>
    <main class="booking-page">
        <header class="booking-hero">
            <h1>Đặt Lịch Dọn Dẹp</h1>
            <p>Chọn dịch vụ, thời gian và địa chỉ phù hợp với nhu cầu của bạn</p>
        </header>

        <div class="booking-layout">
            <section class="booking-card">
                <form id="bookingForm" method="POST" action="/book" class="booking-form">
                    <input type="hidden" name="_csrf" value="<?= View::e($csrf ?? '') ?>">

                    <div class="booking-section">
                        <h2 class="section-title">
                            <span class="section-icon">👤</span>
                            <span>Thông Tin Khách Hàng</span>
                        </h2>

                        <div class="input-field">
                            <label for="location">Khu vực / Địa chỉ *</label>
                            <input 
                                type="text" 
                                id="location"
                                name="location" 
                                required 
                                value="<?= View::e($userAddress ?? '') ?>" 
                                placeholder="Ví dụ: Quận 1, TP.HCM hoặc địa chỉ cụ thể"
                            >
                            <?php if (empty($userAddress)): ?>
                                <p class="field-warning">⚠️ Lưu ý: Tài khoản của bạn chưa có địa chỉ. Vui lòng nhập địa chỉ.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="booking-section">
                        <h2 class="section-title">
                            <span class="section-icon">🧹</span>
                            <span>Chi Tiết Dịch Vụ Dọn Dẹp</span>
                        </h2>

                        <div class="booking-grid">
                            <div class="input-field booking-grid-full">
                                <label for="service">Chọn dịch vụ *</label>
                                <select id="service" name="service" required>
                                    <option value="">-- Vui lòng chọn dịch vụ --</option>
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

                            <div class="input-field booking-grid-full">
                                <label for="quantity"><span id="quantityLabel">Số lượng</span> *</label>
                                <input 
                                    type="number" 
                                    id="quantity"
                                    name="quantity" 
                                    required 
                                    min="1" 
                                    step="1" 
                                    value="1"
                                >
                                <div id="pricePreview" class="price-preview" style="display: none;"></div>
                            </div>

                            <div class="input-field booking-grid-full">
                                <label for="description">Mô tả thêm (tùy chọn)</label>
                                <textarea 
                                    id="description"
                                    name="description" 
                                    rows="4" 
                                    placeholder="Ví dụ: Diện tích 100m², có 3 phòng, có vật nuôi, cần lưu ý gì đặc biệt..."
                                ></textarea>
                                <p class="field-note">Cung cấp thông tin chi tiết giúp chúng tôi chuẩn bị tốt hơn</p>
                            </div>
                        </div>
                    </div>

                    <div class="booking-section">
                        <h2 class="section-title">
                            <span class="section-icon">📅</span>
                            <span>Lịch Hẹn</span>
                        </h2>

                        <div class="booking-grid">
                            <div class="input-field">
                                <label for="date">Ngày dự kiến *</label>
                                <input type="date" id="date" name="date" required>
                                <p class="field-note">Chọn ngày trong tương lai</p>
                            </div>

                            <div class="input-field">
                                <label for="time">Giờ bắt đầu *</label>
                                <select id="time" name="time" required>
                                    <option value="">-- Chọn giờ --</option>
                                    <option value="08:00">🌅 08:00 - Sáng sớm</option>
                                    <option value="10:00">☀️ 10:00 - Sáng</option>
                                    <option value="13:00">🌤️ 13:00 - Chiều</option>
                                    <option value="15:00">🌞 15:00 - Chiều muộn</option>
                                    <option value="18:00">🌆 18:00 - Tối</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="booking-section">
                        <div class="terms-box">
                            <input 
                                type="checkbox" 
                                id="agree_terms"
                                name="agree_terms" 
                                required
                            >
                            <label for="agree_terms">
                                Tôi đồng ý với 
                                <a href="#">điều khoản dịch vụ</a> 
                                và 
                                <a href="#">chính sách bảo mật</a>
                            </label>
                        </div>
                    </div>

                    <div class="booking-section">
                        <button id="submitBtn" type="submit" class="submit-btn" disabled>
                            ✓ Xác Nhận Đặt Lịch
                        </button>
                        <p class="submit-note">
                            Sau khi xác nhận, bạn sẽ được chuyển tới trang thanh toán
                        </p>
                    </div>
                </form>
            </section>

            <aside class="booking-benefits">
                <div class="booking-benefit-card">
                    <div class="booking-benefit-icon">⚡</div>
                    <h3>Nhanh Chóng</h3>
                    <p>Đặt lịch trong vài phút, xác nhận trong 2 giờ</p>
                </div>

                <div class="booking-benefit-card">
                    <div class="booking-benefit-icon">💰</div>
                    <h3>Giá Cạnh Tranh</h3>
                    <p>Giá rõ ràng, không phí ẩn, thanh toán an toàn</p>
                </div>

                <div class="booking-benefit-card">
                    <div class="booking-benefit-icon">✨</div>
                    <h3>Chuyên Nghiệp</h3>
                    <p>Đội ngũ được huấn luyện, cam kết chất lượng</p>
                </div>
            </aside>
        </div>
    </main>

    <script>
        (function() {
            const form = document.getElementById('bookingForm');
            const submit = document.getElementById('submitBtn');
            const serviceSelect = document.getElementById('service');
            const quantityInput = document.getElementById('quantity');
            const quantityLabel = document.getElementById('quantityLabel');
            const pricePreview = document.getElementById('pricePreview');
            const requiredFields = ['service', 'quantity', 'date', 'time', 'location', 'agree_terms'];

            const normalizeUnit = (unit) => (unit || '').toLowerCase().trim();

            const isIntegerUnit = (unit) => {
                const normalized = normalizeUnit(unit);
                return normalized.includes('phòng') || normalized.includes('phong')
                    || normalized.includes('lần') || normalized.includes('lan')
                    || normalized.includes('gói') || normalized.includes('goi');
            };

            const updateQuantityRules = () => {
                const option = serviceSelect.options[serviceSelect.selectedIndex];
                const unit = option?.dataset?.unit || '';
                const price = Number(option?.dataset?.price || 0);

                quantityLabel.textContent = unit ? `Số lượng (${unit})` : 'Số lượng';

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
                    pricePreview.textContent = '💰 Thành tiền tạm tính: ' + (price * quantity).toLocaleString('vi-VN') + 'đ';
                    pricePreview.style.display = 'inline-flex';
                } else {
                    pricePreview.style.display = 'none';
                }
            };

            const toggleSubmit = () => {
                const valid = requiredFields.every(name => {
                    const el = form.querySelector('[name="' + name + '"]');
                    if (!el) return false;
                    if (el.type === 'checkbox') return el.checked;
                    return !!el.value;
                });

                submit.disabled = !valid;
            };

            serviceSelect.addEventListener('change', () => {
                updateQuantityRules();
                toggleSubmit();
            });

            quantityInput.addEventListener('input', () => {
                updateQuantityRules();
                toggleSubmit();
            });

            form.addEventListener('input', toggleSubmit);
            form.addEventListener('change', toggleSubmit);

            updateQuantityRules();
            toggleSubmit();
        })();
    </script>
</body>
</html>