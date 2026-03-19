<?php
use App\Core\View;
/** @var array $previousContacts */
?>

<style>
    /* Contact Section Container */
    .contact-form-section {
        margin-top: 40px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Form Box */
    .contact-form-box {
        background: white;
        border: 1px solid #e0f2e9;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 6px 20px rgba(44, 62, 80, 0.06);
    }

    .contact-form-box h2 {
        color: #1f2d3d;
        margin-top: 0;
    }

    /* Form Styling */
    .contact-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-row .form-group {
        margin: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #1f2d3d;
        font-weight: 500;
    }

    .form-group label span.required {
        color: #e74c3c;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        box-sizing: border-box;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #43c59e;
        box-shadow: 0 0 0 3px rgba(67, 197, 158, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    .contact-form button {
        background: #43c59e;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.2s ease;
        align-self: flex-start;
    }

    .contact-form button:hover {
        background: #39a87d;
    }

    .contact-form button:disabled {
        background: #bbb;
        cursor: not-allowed;
    }

    /* Alert Messages */
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Contact Info Section */
    .contact-info-section {
        display: flex;
        flex-direction: column;
    }

    .contact-info-box {
        background: white;
        border: 1px solid #e0f2e9;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 3px 12px rgba(44, 62, 80, 0.06);
    }

    .contact-info-box:last-child {
        margin-bottom: 0;
    }

    .contact-info-box h3 {
        color: #1f2d3d;
        margin-top: 0;
        margin-bottom: 12px;
    }

    .contact-info-box p {
        margin: 0;
        color: #455a64;
        font-size: 14px;
    }

    .contact-info-box p:first-of-type {
        color: #2eaf7d;
        font-weight: 600;
        font-size: 18px;
        margin-bottom: 5px;
    }

    .contact-info-box p.info-detail {
        color: #546e7a;
        font-size: 14px;
        margin-top: 8px;
    }

    .contact-info-box p.info-subtitle {
        color: #1f2d3d;
        font-weight: 500;
        font-size: 16px;
        margin-bottom: 5px;
    }

    /* Social Links */
    .social-links {
        display: grid;
        grid-template-columns: repeat(3, 40px);
        justify-content: center;
        column-gap: 14px;
        margin-top: 12px;
        align-items: center;
    }

    .social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: #e0f2e9;
        border-radius: 8px;
        text-decoration: none;
        color: #2eaf7d;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .social-link:hover {
        background: #d0e8df;
        transform: translateY(-2px);
    }

    .social-link svg {
        width: 20px;
        height: 20px;
        fill: currentColor;
    }

    .social-link-facebook {
        color: #1877f2;
    }

    .social-link-github {
        color: #1f2328;
    }

    .social-link-mail {
        color: #ea4335;
    }

    .contact-links {
        display: grid;
        gap: 10px;
        margin-top: 12px;
    }

    .contact-link-row {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #455a64;
        font-size: 14px;
    }

    .contact-link-row strong {
        min-width: 56px;
        color: #1f2d3d;
        font-size: 13px;
    }

    .contact-link-row a {
        color: #2eaf7d;
        text-decoration: none;
        word-break: break-all;
    }

    .contact-link-row a:hover {
        text-decoration: underline;
    }

    .contact-history-section {
        margin-top: 50px;
        max-width: 900px;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
    }

    .contact-history-card {
        width: 100%;
        box-sizing: border-box;
    }

    /* Working Hours Section */
    .working-hours-section {
        margin-top: 50px;
        background: #f7fdf9;
        border: 1px solid #e0f2e9;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
    }

    .working-hours-section h2 {
        color: #1f2d3d;
        margin-top: 0;
    }

    .hours-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .hours-item strong {
        color: #1f2d3d;
        display: block;
    }

    .hours-item p {
        margin: 8px 0 0 0;
        color: #455a64;
    }

    /* CTA Section */
    .contact-cta-section {
        margin-top: 40px;
        text-align: center;
    }

    .contact-cta-section h2 {
        color: #1f2d3d;
    }

    .cta-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .cta-btn {
        padding: 10px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .cta-btn-primary {
        background: #43c59e;
        color: white;
    }

    .cta-btn-primary:hover {
        background: #39a87d;
    }

    .cta-btn-outline {
        background: #fdfdfd;
        color: #2eaf7d;
        border: 1.5px solid #2eaf7d;
    }

    .cta-btn-outline:hover {
        background: #e0f2e9;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .contact-form-section {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .working-hours-section {
            margin-top: 30px;
            padding: 20px;
        }

        .cta-buttons {
            gap: 10px;
        }

        .cta-btn {
            flex: 1;
            min-width: 120px;
        }
    }
</style>

<section class="home-container">
    <header class="home-hero">
        <h1>Liên hệ với chúng tôi</h1>
        <p>Chúng tôi sẵn sàng trả lời mọi câu hỏi của bạn</p>
    </header>

    <!-- Hiển thị thông báo -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success" style="max-width: 900px; margin: 30px auto 0;">
            <?= View::e($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error" style="max-width: 900px; margin: 30px auto 0;">
            <?= View::e($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <section class="contact-form-section">
        <!-- Biểu mẫu liên hệ -->
        <div class="contact-form-box">
            <h2>Gửi tin nhắn</h2>
            <form method="POST" action="/contact" class="contact-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Họ tên <span class="required">*</span></label>
                        <input type="text" name="name" required placeholder="Nhập họ tên của bạn">
                    </div>
                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email" required placeholder="example@email.com">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Điện thoại <span class="required">*</span></label>
                        <input type="tel" name="phone" required placeholder="0123456789">
                    </div>
                    <div class="form-group">
                        <label>Chủ đề <span class="required">*</span></label>
                        <select name="subject" required>
                            <option value="">-- Chọn chủ đề --</option>
                            <option value="Hỏi giá">Hỏi giá dịch vụ</option>
                            <option value="Tư vấn">Tư vấn dịch vụ</option>
                            <option value="Khiếu nại">Khiếu nại / Phản hồi</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tin nhắn <span class="required">*</span></label>
                    <textarea name="message" required placeholder="Viết tin nhắn của bạn..."></textarea>
                </div>
                <button type="submit">Gửi tin nhắn</button>
            </form>
        </div>

        <!-- Thông tin liên hệ -->
        <div class="contact-info-section">
            <div class="contact-info-box">
                <h3>📞 Điện thoại</h3>
                <p>0382 583 013</p>
                <p class="info-detail">Hỗ trợ 24/7</p>
            </div>

            <div class="contact-info-box">
                <h3>✉️ Email</h3>
                <p><a href="mailto:nhuthuynhforwork@gmail.com">nhuthuynhforwork@gmail.com</a></p>
                <p class="info-detail">Liên hệ công việc và hỗ trợ trực tiếp</p>
            </div>

            <div class="contact-info-box">
                <h3>📍 Địa chỉ</h3>
                <p class="info-subtitle">12 Nguyễn Văn Bảo, Quận Gò Vấp</p>
                <p class="info-detail">TP. Hồ Chí Minh, Việt Nam</p>
            </div>

            <div class="contact-info-box">
                <h3>Mạng xã hội</h3>
                <div class="social-links">
                    <a href="https://www.facebook.com/Minashhh.04" class="social-link social-link-facebook" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 8H16V4.5h-2.9C10.2 4.5 8.5 6.3 8.5 9.4V12H6v3.5h2.5V23h3.7v-7.5H15l.5-3.5h-3.3V9.8c0-1 .3-1.8 1.8-1.8z"/></svg>
                    </a>
                    <a href="https://github.com/NguyenNhutHuynh" class="social-link social-link-github" target="_blank" rel="noopener noreferrer" aria-label="GitHub">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 .5C5.7.5.8 5.5.8 11.7c0 5 3.2 9.2 7.7 10.7.6.1.8-.3.8-.6v-2.2c-3.1.7-3.8-1.3-3.8-1.3-.5-1.3-1.2-1.6-1.2-1.6-1-.7.1-.7.1-.7 1.1.1 1.7 1.1 1.7 1.1 1 .1 2.6 2.1 2.6 2.1.9 1.5 2.4 1.1 3 .9.1-.7.3-1.1.6-1.3-2.5-.3-5.2-1.2-5.2-5.6 0-1.2.4-2.2 1.1-3-.1-.3-.5-1.4.1-2.9 0 0 .9-.3 3 .1.9-.2 1.9-.3 2.9-.3s2 .1 2.9.3c2.1-.4 3-.1 3-.1.6 1.5.2 2.6.1 2.9.7.8 1.1 1.8 1.1 3 0 4.4-2.7 5.3-5.3 5.6.4.3.7 1 .7 2.1v3.1c0 .3.2.7.8.6 4.5-1.5 7.7-5.7 7.7-10.7C23.2 5.5 18.3.5 12 .5z"/></svg>
                    </a>
                    <a href="mailto:nhuthuynhforwork@gmail.com" class="social-link social-link-mail" aria-label="Email">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 5.5h19a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1h-19a1 1 0 0 1-1-1v-11a1 1 0 0 1 1-1zm1.2 2L12 13.2l8.3-5.7H3.7zm17.8 9.8V8.7l-9 6.2a1 1 0 0 1-1.1 0l-9-6.2v8.6h19z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php if (!empty($previousContacts)): ?>
    <section class="contact-history-section">
        <h2 style="color: #1f2d3d; margin-bottom: 30px;">Phản hồi từ Admin</h2>
        <?php foreach ($previousContacts as $contact): ?>
            <div class="contact-history-card" style="background: white; border: 1px solid #e0f2e9; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 3px 12px rgba(44,62,80,0.06);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <h3 style="color: #1f2d3d; margin: 0 0 8px 0;"><?= View::e((string)($contact['subject'] ?? '')) ?></h3>
                        <p style="margin: 0; color: #546e7a; font-size: 14px;">
                            Gửi lúc: <?= View::e((string)($contact['created_at'] ?? '')) ?>
                        </p>
                    </div>
                            <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; 
                                <?= $contact['status'] === 'replied' ? 'background: #e8f5e9; color: #2e7d32;' : 'background: #fff3e0; color: #e65100;' ?>">
                                <?= $contact['status'] === 'replied' ? 'Đã trả lời' : 'Chờ xử lý' ?>
                    </span>
                </div>
                
                <div style="background: #f5f5f5; padding: 12px; border-radius: 8px; margin: 12px 0;">
                    <p style="margin: 0; color: #1f2d3d; line-height: 1.6;">
                        "<?= View::e((string)($contact['message'] ?? '')) ?>"
                    </p>
                </div>

                <?php if (!empty($contact['reply'])): ?>
                    <div style="background: #e8f5e9; padding: 12px; border-radius: 8px; border-left: 4px solid #4caf50; margin: 12px 0;">
                        <p style="margin: 0 0 8px 0; color: #2e7d32; font-weight: 600;">Phản hồi từ Admin:</p>
                        <p style="margin: 0; color: #1b5e20; line-height: 1.6; white-space: pre-wrap;">
                            <?= View::e((string)$contact['reply']) ?>
                        </p>
                        <p style="margin: 8px 0 0 0; font-size: 12px; color: #558b2f;">
                            Trả lời lúc: <?= View::e((string)($contact['replied_at'] ?? '')) ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>

    <section class="working-hours-section">
        <h2>Giờ làm việc</h2>
        <div class="hours-grid">
            <div class="hours-item">
                <strong>Thứ 2 - 6</strong>
                <p>7:00 - 22:00</p>
            </div>
            <div class="hours-item">
                <strong>Thứ 7</strong>
                <p>8:00 - 22:00</p>
            </div>
            <div class="hours-item">
                <strong>Chủ nhật</strong>
                <p>8:00 - 20:00</p>
            </div>
            <div class="hours-item">
                <strong>Lễ tết</strong>
                <p>Mở cửa thường</p>
            </div>
        </div>
    </section>

    <section class="contact-cta-section">
        <h2>Cần dịch vụ ngay?</h2>
        <div class="cta-buttons">
            <a href="/book" class="cta-btn cta-btn-primary">Đặt lịch</a>
            <a href="tel:1900123456" class="cta-btn cta-btn-outline">Gọi ngay</a>
        </div>
    </section>
</section>
</section>
