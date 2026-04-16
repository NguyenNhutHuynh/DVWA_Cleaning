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
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #d1fae5;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        .btn-submit {
            background: var(--primary);
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }
        
        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2d3d;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--primary-light);
        }
        
        .input-field {
            position: relative;
        }
        
        .input-field label {
            display: block;
            font-weight: 600;
            color: #1f2d3d;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        
        .input-field input,
        .input-field select,
        .input-field textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s ease;
        }
        
        .input-field input::placeholder,
        .input-field textarea::placeholder {
            color: #9ca3af;
        }
        
        .price-preview {
            display: inline-block;
            background: var(--primary-light);
            color: var(--primary-dark);
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 700;
            margin-top: 8px;
            font-size: 0.95rem;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
    
    <div class="container mx-auto px-4 py-12 max-w-4xl">
        
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-slate-900 mb-3">Đặt Lịch Dọn Dẹp</h1>
            <p class="text-lg text-slate-600">Chọn dịch vụ, thời gian và địa chỉ phù hợp với nhu cầu của bạn</p>
        </div>

        <!-- Main Card Container -->
        <div class="card p-10">
            <form id="bookingForm" method="POST" action="/book" class="space-y-8">
                
                <!-- Section 1: Thông tin khách hàng -->
                <div>
                    <div class="section-title">
                        <span>👤</span>
                        <span>Thông Tin Khách Hàng</span>
                    </div>
                    
                    <div class="input-field">
                        <label for="location">Khu vực / Địa chỉ *</label>
                        <input 
                            type="text" 
                            id="location"
                            name="location" 
                            required 
                            value="<?= View::e($userAddress ?? '') ?>" 
                            placeholder="Ví dụ: Quận 1, TP.HCM hoặc địa chỉ cụ thể"
                            class="text-slate-900"
                        >
                        <?php if (empty($userAddress)): ?>
                            <p class="text-sm text-amber-600 mt-2">⚠️ Lưu ý: Tài khoản của bạn chưa có địa chỉ. Vui lòng nhập địa chỉ.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Section 2: Chi tiết dịch vụ -->
                <div class="border-t-2 border-slate-200 pt-8">
                    <div class="section-title">
                        <span>🧹</span>
                        <span>Chi Tiết Dịch Vụ Dọn Dẹp</span>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div class="input-field">
                            <label for="service">Chọn dịch vụ *</label>
                            <select 
                                id="service"
                                name="service" 
                                required 
                                class="text-slate-900"
                            >
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

                        <div class="input-field">
                            <label for="quantity"><span id="quantityLabel">Số lượng</span> *</label>
                            <input 
                                type="number" 
                                id="quantity"
                                name="quantity" 
                                required 
                                min="1" 
                                step="1" 
                                value="1"
                                class="text-slate-900"
                            >
                            <div id="pricePreview" class="price-preview" style="display: none;"></div>
                        </div>

                        <div class="input-field">
                            <label for="description">Mô tả thêm (tùy chọn)</label>
                            <textarea 
                                id="description"
                                name="description" 
                                rows="4" 
                                placeholder="Ví dụ: Diện tích 100m², có 3 phòng, có vật nuôi, cần lưu ý gì đặc biệt..."
                                class="text-slate-900"
                            ></textarea>
                            <p class="text-sm text-slate-500 mt-2">Cung cấp thông tin chi tiết giúp chúng tôi chuẩn bị tốt hơn</p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Lịch hẹn -->
                <div class="border-t-2 border-slate-200 pt-8">
                    <div class="section-title">
                        <span>📅</span>
                        <span>Lịch Hẹn</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="input-field">
                            <label for="date">Ngày dự kiến *</label>
                            <input 
                                type="date" 
                                id="date"
                                name="date" 
                                required
                                class="text-slate-900"
                            >
                            <p class="text-sm text-slate-500 mt-2">Chọn ngày trong tương lai</p>
                        </div>

                        <div class="input-field">
                            <label for="time">Giờ bắt đầu *</label>
                            <select 
                                id="time"
                                name="time" 
                                required
                                class="text-slate-900"
                            >
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

                <!-- Terms & Conditions -->
                <div class="border-t-2 border-slate-200 pt-8">
                    <div class="flex items-start gap-4 bg-green-50 p-6 rounded-12">
                        <input 
                            type="checkbox" 
                            id="agree_terms"
                            name="agree_terms" 
                            required
                            class="mt-1 w-5 h-5 cursor-pointer"
                        >
                        <label for="agree_terms" class="cursor-pointer flex-1">
                            <span class="text-slate-700">Tôi đồng ý với 
                                <a href="#" class="text-green-600 font-semibold hover:text-green-700">điều khoản dịch vụ</a> 
                                và 
                                <a href="#" class="text-green-600 font-semibold hover:text-green-700">chính sách bảo mật</a>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="border-t-2 border-slate-200 pt-8">
                    <button 
                        id="submitBtn"
                        type="submit" 
                        class="btn-submit w-full py-4 px-6 text-white font-bold text-lg rounded-12 transition-all duration-300 disabled:opacity-60"
                        disabled
                    >
                        ✓ Xác Nhận Đặt Lịch
                    </button>
                    <p class="text-center text-slate-600 mt-4 text-sm">
                        Sau khi xác nhận, bạn sẽ được chuyển tới trang thanh toán
                    </p>
                </div>
            </form>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <div class="card p-6 text-center">
                <div class="text-4xl mb-3">⚡</div>
                <h3 class="font-bold text-slate-900 mb-2">Nhanh Chóng</h3>
                <p class="text-slate-600 text-sm">Đặt lịch trong vài phút, xác nhận trong 2 giờ</p>
            </div>
            <div class="card p-6 text-center">
                <div class="text-4xl mb-3">💰</div>
                <h3 class="font-bold text-slate-900 mb-2">Giá Cạnh Tranh</h3>
                <p class="text-slate-600 text-sm">Giá rõ ràng, không phí ẩn, thanh toán an toàn</p>
            </div>
            <div class="card p-6 text-center">
                <div class="text-4xl mb-3">✨</div>
                <h3 class="font-bold text-slate-900 mb-2">Chuyên Nghiệp</h3>
                <p class="text-slate-600 text-sm">Đội ngũ được huấn luyện, cam kết chất lượng</p>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const form = document.getElementById('bookingForm');
            const submit = document.getElementById('submitBtn');
            const serviceSelect = document.getElementById('service');
            const quantityInput = document.getElementById('quantity');
            const quantityLabel = document.getElementById('quantityLabel');
            const pricePreview = document.getElementById('pricePreview');
            const requiredFields = ['service', 'quantity', 'date', 'time', 'location', 'agree_terms'];

            // Normalize unit name
            const normalizeUnit = (unit) => (unit || '').toLowerCase().trim();
            const isIntegerUnit = (unit) => {
                const normalized = normalizeUnit(unit);
                return normalized.includes('phòng') || normalized.includes('phong')
                    || normalized.includes('lần') || normalized.includes('lan')
                    || normalized.includes('gói') || normalized.includes('goi');
            };

            // Update quantity rules based on selected service
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

                // Display price preview
                const quantity = Number(quantityInput.value || 0);
                if (price > 0 && quantity > 0) {
                    pricePreview.textContent = '💰 Thành tiền tạm tính: ' + (price * quantity).toLocaleString('vi-VN') + 'đ';
                    pricePreview.style.display = 'inline-block';
                } else {
                    pricePreview.style.display = 'none';
                }
            };

            // Toggle submit button
            const toggleSubmit = () => {
                const valid = requiredFields.every(name => {
                    const el = form.querySelector('[name="' + name + '"]');
                    if (!el) return false;
                    if (el.type === 'checkbox') return el.checked;
                    return !!el.value;
                });
                submit.disabled = !valid;
            };

            // Event listeners
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

            // Initialize
            updateQuantityRules();
            toggleSubmit();
        })();
    </script>
</body>
</html>

