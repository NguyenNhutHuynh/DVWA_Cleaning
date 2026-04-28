<?php
use App\Core\View;
/** @var array $contacts Danh sách liên hệ */
/** @var array $reviews Danh sách đánh giá từ booking_reviews */
/** @var array $bookingMessages Danh sách tin nhắn khiếu nại từ booking_messages */
/** @var array $reports Danh sách báo cáo hoàn thành từ booking_reports */
/** @var string $csrf CSRF token */

$comments = [];
$ratings = [];
$complaints = [];

foreach ($contacts as $contact) {
  $subject = mb_strtolower((string)($contact['subject'] ?? ''));
  $message = mb_strtolower((string)($contact['message'] ?? ''));
  $content = $subject . ' ' . $message;

  $isComplaint = str_contains($content, 'khiếu nại')
    || str_contains($content, 'khieu nai')
    || str_contains($content, 'phản hồi')
    || str_contains($content, 'phan hoi')
    || str_contains($content, 'complaint');

  $isRating = str_contains($content, 'đánh giá')
    || str_contains($content, 'danh gia')
    || str_contains($content, 'rating')
    || str_contains($content, 'review')
    || str_contains($content, ' sao')
    || preg_match('/\b[1-5]\s*\/\s*5\b/u', $content) === 1;

  if ($isComplaint) {
    $complaints[] = $contact;
    continue;
  }

  if ($isRating) {
    $ratings[] = $contact;
    continue;
  }

  $comments[] = $contact;
}

$renderModerationList = static function (array $items, string $emptyText): void {
  if ($items === []) {
    echo '<p class="moderation-empty">' . View::e($emptyText) . '</p>';
    return;
  }

  foreach ($items as $c) {
    echo '<article class="moderation-item">';
    echo '<p class="moderation-meta"><strong>' . View::e((string)($c['subject'] ?? '')) . '</strong> • '
      . View::e((string)($c['name'] ?? '')) . ' (' . View::e((string)($c['email'] ?? '')) . ')</p>';
    echo '<p class="moderation-message">"' . View::e((string)($c['message'] ?? '')) . '"</p>';
    echo '<p class="moderation-status">Trạng thái: <span>'
      . View::e((string)($c['status'] ?? 'pending')) . '</span></p>';
    echo '<div class="hero-actions moderation-actions">';
    echo '<a class="home-btn" href="#">Duyệt</a>';
    echo '<a class="home-btn home-btn-outline" href="#">Ẩn</a>';
    echo '<a class="home-btn home-btn-outline" href="#">Gắn cờ</a>';
    echo '</div>';
    echo '</article>';
  }
};
?>

<meta name="csrf-token" content="<?= View::e($csrf ?? '') ?>">

<style>
.moderation-page {
  --primary: #2eaf7d;
  --primary-dark: #16805a;
  --primary-soft: #e8f7f0;
  --bg-soft: #f7fdf9;
  --text-dark: #1f2d3d;
  --text-muted: #546e7a;
  --border: #dcefe6;
  --white: #ffffff;
  --danger: #dc2626;
  --danger-soft: #fff1f1;
  --warning: #d97706;
  --warning-soft: #fff7ed;
  --shadow-sm: 0 8px 24px rgba(31,45,61,0.08);
  --shadow-md: 0 16px 40px rgba(31,45,61,0.12);

  max-width: 1180px;
  margin: 0 auto;
  padding: 24px 16px 60px;
  color: var(--text-dark);
  display: grid;
  gap: 24px;
}

.moderation-page * {
  box-sizing: border-box;
}

.moderation-page .home-hero {
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

.moderation-page .home-hero::after {
  content: "";
  position: absolute;
  right: -80px;
  bottom: -80px;
  width: 220px;
  height: 220px;
  border-radius: 50%;
  background: rgba(46,175,125,0.13);
}

.moderation-page .home-kicker {
  position: relative;
  display: inline-flex;
  margin: 0 0 14px;
  padding: 7px 14px;
  border-radius: 999px;
  background: var(--primary-soft);
  color: var(--primary-dark);
  font-size: 13px;
  font-weight: 900;
  letter-spacing: 0.08em;
}

.moderation-page .home-hero h1 {
  position: relative;
  margin: 0 0 12px;
  color: var(--text-dark);
  font-size: clamp(32px, 5vw, 52px);
  line-height: 1.1;
  font-weight: 900;
  letter-spacing: -0.04em;
}

.moderation-page .home-hero p {
  position: relative;
  margin: 0;
  color: var(--text-muted);
  font-size: 17px;
  line-height: 1.6;
}

.moderation-tabs {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 12px;
  padding: 14px;
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 24px;
  box-shadow: var(--shadow-sm);
}

.moderation-tab-btn {
  min-height: 48px;
  border: 1.5px solid var(--border);
  background: #fcfffd;
  color: var(--text-dark);
  border-radius: 999px;
  padding: 12px 16px;
  font-weight: 900;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, border-color 0.2s ease;
}

.moderation-tab-btn:hover {
  transform: translateY(-2px);
  background: var(--primary-soft);
  border-color: rgba(46,175,125,0.45);
  box-shadow: var(--shadow-sm);
}

.moderation-tab-btn.is-active {
  color: #fff;
  border-color: transparent;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  box-shadow: 0 10px 22px rgba(46,175,125,0.22);
}

.moderation-panel {
  display: none;
}

.moderation-panel.is-active {
  display: block;
}

.moderation-page .home-feature {
  padding: 30px;
  border-radius: 26px;
  background: var(--white);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
}

.moderation-page .home-feature h2 {
  margin: 0 0 22px;
  color: var(--text-dark);
  font-size: clamp(22px, 3vw, 30px);
  font-weight: 900;
  letter-spacing: -0.03em;
}

.moderation-page .review-box {
  display: grid;
  gap: 16px;
}

.moderation-item {
  padding: 22px;
  border: 1px solid var(--border);
  border-radius: 22px;
  background: linear-gradient(135deg, #ffffff, var(--bg-soft));
  box-shadow: 0 5px 16px rgba(31,45,61,0.05);
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.moderation-item:hover {
  transform: translateY(-4px);
  border-color: rgba(46,175,125,0.45);
  box-shadow: var(--shadow-md);
}

.moderation-meta {
  margin: 0 0 10px;
  color: var(--text-dark);
  font-weight: 800;
  line-height: 1.6;
}

.moderation-meta strong {
  color: var(--text-dark);
  font-weight: 900;
}

.moderation-message {
  margin: 0 0 12px;
  color: var(--text-muted);
  line-height: 1.6;
}

.moderation-status {
  margin: 12px 0 0;
  color: var(--text-muted);
  font-size: 14px;
  line-height: 1.6;
}

.moderation-status span {
  color: var(--primary);
  font-weight: 900;
  text-transform: capitalize;
}

.moderation-status .status-hidden {
  color: var(--danger);
}

.moderation-status .status-visible {
  color: var(--primary);
}

.moderation-actions {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
  margin-top: 16px;
}

.moderation-actions form {
  display: inline-flex;
  margin: 0;
}

.home-btn {
  min-height: 44px;
  padding: 11px 22px;
  border-radius: 999px;
  border: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  font-size: 14px;
  font-weight: 900;
  cursor: pointer;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: white;
  box-shadow: 0 10px 22px rgba(46,175,125,0.22);
  transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.home-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 14px 30px rgba(46,175,125,0.28);
}

.home-btn-outline {
  background: white;
  color: var(--primary);
  border: 1.5px solid var(--primary);
  box-shadow: none;
}

.home-btn-outline:hover {
  background: var(--primary-soft);
  box-shadow: var(--shadow-sm);
}

.moderation-empty {
  margin: 0;
  padding: 28px 20px;
  border-radius: 20px;
  background: var(--bg-soft);
  border: 1px dashed #cfe3d8;
  color: var(--text-muted);
  text-align: center;
  font-weight: 700;
}

.contact-reply-form {
  display: grid;
  gap: 10px;
  margin: 16px 0;
  padding: 16px;
  border-radius: 18px;
  background: #fcfffd;
  border: 1px solid var(--border);
}

.contact-reply-form textarea {
  width: 100%;
  min-height: 96px;
  padding: 14px 16px;
  border: 1px solid var(--border);
  border-radius: 16px;
  background: white;
  color: var(--text-dark);
  font-family: inherit;
  font-size: 15px;
  resize: vertical;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.contact-reply-form textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 4px rgba(46,175,125,0.12);
}

.admin-reply-box,
.report-warning-box {
  margin: 14px 0;
  padding: 16px;
  border-radius: 18px;
  line-height: 1.6;
}

.admin-reply-box {
  background: var(--primary-soft);
  border-left: 4px solid var(--primary);
}

.admin-reply-box p {
  margin: 0;
}

.admin-reply-title {
  color: var(--primary-dark);
  font-weight: 900;
}

.admin-reply-content {
  margin-top: 8px !important;
  color: #1b5e20;
  white-space: pre-wrap;
}

.admin-reply-time {
  margin-top: 8px !important;
  font-size: 12px;
  color: #558b2f;
}

.report-warning-box {
  background: var(--warning-soft);
  border-left: 4px solid var(--warning);
}

@media (max-width: 900px) {
  .moderation-tabs {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 768px) {
  .moderation-page {
    padding: 16px 12px 44px;
  }

  .moderation-page .home-hero {
    padding: 42px 18px;
    border-radius: 22px;
  }

  .moderation-page .home-feature {
    padding: 22px;
    border-radius: 20px;
  }

  .moderation-item {
    padding: 20px;
    border-radius: 18px;
  }

  .moderation-actions,
  .moderation-actions form,
  .home-btn {
    width: 100%;
  }
}

@media (max-width: 520px) {
  .moderation-tabs {
    grid-template-columns: 1fr;
  }
}
</style>

<section class="home-container moderation-page">
  <header class="home-hero">
    <p class="home-kicker">ADMIN • KIỂM DUYỆT</p>
    <h1>Kiểm duyệt nội dung</h1>
    <p>Duyệt phản hồi, khiếu nại, nội dung gửi lên.</p>
  </header>

  <nav class="moderation-tabs" aria-label="Danh mục kiểm duyệt">
    <button type="button" class="moderation-tab-btn is-active" data-tab-target="contacts">📩 Tin nhắn liên hệ</button>
    <button type="button" class="moderation-tab-btn" data-tab-target="reviews">⭐ Đánh giá từ khách</button>
    <button type="button" class="moderation-tab-btn" data-tab-target="complaints">⚠️ Khiếu nại từ khách</button>
    <button type="button" class="moderation-tab-btn" data-tab-target="reports">📋 Báo cáo của nhân viên</button>
  </nav>

  <section class="home-feature moderation-panel is-active" data-tab-panel="contacts">
    <h2>📩 Tin nhắn từ form liên hệ</h2>
    <div class="review-box">
      <?php if (empty($contacts)): ?>
        <p class="moderation-empty">Chưa có tin nhắn nào từ form liên hệ.</p>
      <?php else: ?>
        <?php foreach ($contacts as $contact): ?>
          <article class="moderation-item" id="contact-<?= (int)($contact['id'] ?? 0) ?>">
            <p class="moderation-meta">
              <strong><?= View::e((string)($contact['subject'] ?? 'Không có tiêu đề')) ?></strong> •
              <?= View::e((string)($contact['name'] ?? 'Ẩn danh')) ?>
            </p>

            <p class="moderation-message">
              <strong>Email:</strong> <?= View::e((string)($contact['email'] ?? '')) ?>
              <?php if (!empty($contact['phone'])): ?>
                • <strong>Điện thoại:</strong> <?= View::e((string)$contact['phone']) ?>
              <?php endif; ?>
            </p>

            <p class="moderation-message">"<?= View::e((string)($contact['message'] ?? '')) ?>"</p>

            <?php if (!empty($contact['reply'])): ?>
              <div class="admin-reply-box">
                <p class="admin-reply-title">✅ Phản hồi từ Admin:</p>
                <p class="admin-reply-content"><?= View::e((string)$contact['reply']) ?></p>
                <p class="admin-reply-time">Trả lời lúc: <?= View::e((string)($contact['replied_at'] ?? '')) ?></p>
              </div>
            <?php else: ?>
              <form class="contact-reply-form" data-contact-id="<?= (int)($contact['id'] ?? 0) ?>">
                <textarea name="reply" placeholder="Nhập phản hồi cho khách..." required></textarea>
                <button type="submit" class="home-btn">Gửi phản hồi</button>
              </form>
            <?php endif; ?>

            <p class="moderation-status">
              Trạng thái: <span><?= View::e((string)($contact['status'] ?? 'pending')) ?></span>
              <?php if (!empty($contact['created_at'])): ?>
                • Gửi: <span><?= View::e((string)$contact['created_at']) ?></span>
              <?php endif; ?>
            </p>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature moderation-panel" data-tab-panel="reviews">
    <h2>Đánh giá từ khách hàng</h2>
    <div class="review-box">
      <?php if (empty($reviews)): ?>
        <p class="moderation-empty">Chưa có đánh giá nào.</p>
      <?php else: ?>
        <?php foreach ($reviews as $r): ?>
          <?php $isHidden = ((int)($r['is_hidden'] ?? 0)) === 1; ?>
          <article class="moderation-item">
            <p class="moderation-meta">
              <strong><?= str_repeat('⭐', (int)($r['rating'] ?? 0)) ?> - <?= View::e((string)($r['service_name'] ?? 'N/A')) ?></strong> •
              Booking #<?= (int)($r['booking_id'] ?? 0) ?>
            </p>

            <p class="moderation-message">
              <strong>Khách hàng:</strong> <?= View::e((string)($r['customer_name'] ?? '')) ?> (<?= View::e((string)($r['customer_email'] ?? '')) ?>)
              <?php if (!empty($r['worker_name'])): ?>
                → <strong>Worker:</strong> <?= View::e((string)$r['worker_name']) ?>
              <?php endif; ?>
            </p>

            <?php if (!empty($r['comment'])): ?>
              <p class="moderation-message">"<?= View::e((string)$r['comment']) ?>"</p>
            <?php endif; ?>

            <p class="moderation-status">
              Đánh giá vào: <span><?= View::e((string)($r['created_at'] ?? '')) ?></span>
              • Trạng thái:
              <span class="<?= $isHidden ? 'status-hidden' : 'status-visible' ?>">
                <?= $isHidden ? 'Đã ẩn' : 'Đang hiển thị' ?>
              </span>
            </p>

            <div class="hero-actions moderation-actions">
              <a class="home-btn" href="/admin/bookings/<?= (int)($r['booking_id'] ?? 0) ?>">Xem đơn</a>

              <?php if ($isHidden): ?>
                <form method="post" action="/admin/reviews/<?= (int)($r['id'] ?? 0) ?>/show">
                  <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                  <button type="submit" class="home-btn home-btn-outline" onclick="return confirm('Bạn có chắc muốn mở lại đánh giá này không?');">
                    Mở lại
                  </button>
                </form>
              <?php else: ?>
                <form method="post" action="/admin/reviews/<?= (int)($r['id'] ?? 0) ?>/hide">
                  <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                  <button type="submit" class="home-btn home-btn-outline" onclick="return confirm('Bạn có chắc muốn ẩn đánh giá này không?');">
                    Ẩn đánh giá
                  </button>
                </form>
              <?php endif; ?>

              <a class="home-btn home-btn-outline" href="#">Gắn cờ</a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature moderation-panel" data-tab-panel="complaints">
    <h2>Tin nhắn khiếu nại trong đơn hàng</h2>
    <div class="review-box">
      <?php if (empty($bookingMessages)): ?>
        <p class="moderation-empty">Không có khiếu nại nào.</p>
      <?php else: ?>
        <?php foreach ($bookingMessages as $msg): ?>
          <article class="moderation-item">
            <p class="moderation-meta">
              <strong>Booking #<?= (int)($msg['booking_id'] ?? 0) ?></strong> •
              <?= View::e((string)($msg['service_name'] ?? 'N/A')) ?>
            </p>

            <p class="moderation-message">
              <strong>Người gửi:</strong> <?= View::e((string)($msg['sender_name'] ?? '')) ?>
              (<span style="text-transform:uppercase;color:#2eaf7d;font-weight:900;"><?= View::e((string)($msg['sender_role'] ?? '')) ?></span>) -
              <?= View::e((string)($msg['sender_email'] ?? '')) ?>
            </p>

            <p class="moderation-message">"<?= View::e((string)($msg['content'] ?? '')) ?>"</p>

            <p class="moderation-status">
              Gửi lúc: <span><?= View::e((string)($msg['created_at'] ?? '')) ?></span>
            </p>

            <div class="hero-actions moderation-actions">
              <a class="home-btn" href="/admin/bookings/<?= (int)($msg['booking_id'] ?? 0) ?>">Xem đơn hàng</a>
              <?php if (($msg['sender_role'] ?? '') === 'worker'): ?>
                <a class="home-btn home-btn-outline" href="/admin/bookings/<?= (int)($msg['booking_id'] ?? 0) ?>#admin-worker-messages">Liên hệ Worker</a>
              <?php else: ?>
                <a class="home-btn home-btn-outline" href="/admin/bookings/<?= (int)($msg['booking_id'] ?? 0) ?>#booking-messages">Xem hội thoại khách</a>
              <?php endif; ?>
              <a class="home-btn home-btn-outline" href="#">Xử lý</a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature moderation-panel" data-tab-panel="reports">
    <h2>📋 Báo cáo hoàn thành từ Worker</h2>
    <div class="review-box">
      <?php if (empty($reports)): ?>
        <p class="moderation-empty">Chưa có báo cáo nào.</p>
      <?php else: ?>
        <?php foreach ($reports as $rep): ?>
          <article class="moderation-item">
            <p class="moderation-meta">
              <strong>Booking #<?= (int)($rep['booking_id'] ?? 0) ?> - <?= View::e((string)($rep['service_name'] ?? 'N/A')) ?></strong>
            </p>

            <p class="moderation-message">
              <strong>Worker:</strong> <?= View::e((string)($rep['worker_name'] ?? '')) ?> (<?= View::e((string)($rep['worker_email'] ?? '')) ?>)
              <?php if (!empty($rep['customer_name'])): ?>
                • <strong>Khách hàng:</strong> <?= View::e((string)$rep['customer_name']) ?>
              <?php endif; ?>
            </p>

            <?php if (!empty($rep['difficulties'])): ?>
              <div class="report-warning-box">
                <p class="moderation-message" style="margin:0;white-space:pre-wrap;"><?= View::e((string)$rep['difficulties']) ?></p>
              </div>
            <?php endif; ?>

            <?php if (!empty($rep['summary'])): ?>
              <p class="moderation-message"><strong>Tóm tắt:</strong> <?= View::e((string)$rep['summary']) ?></p>
            <?php endif; ?>

            <p class="moderation-status">
              Báo cáo vào: <span><?= View::e((string)($rep['created_at'] ?? '')) ?></span>
            </p>

            <div class="hero-actions moderation-actions">
              <a class="home-btn" href="/admin/bookings/<?= (int)($rep['booking_id'] ?? 0) ?>">Xem đơn hàng</a>
              <a class="home-btn home-btn-outline" href="/admin/bookings/<?= (int)($rep['booking_id'] ?? 0) ?>#admin-worker-messages">Liên hệ Worker</a>
              <a class="home-btn home-btn-outline" href="#">Gắn cờ</a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</section>

<script>
function setActiveModerationTab(tabName) {
  document.querySelectorAll('.moderation-tab-btn').forEach(function(btn) {
    btn.classList.toggle('is-active', btn.dataset.tabTarget === tabName);
  });

  document.querySelectorAll('.moderation-panel').forEach(function(panel) {
    panel.classList.toggle('is-active', panel.dataset.tabPanel === tabName);
  });
}

document.addEventListener('click', function(event) {
  const tabBtn = event.target.closest('.moderation-tab-btn');
  if (!tabBtn) return;

  const target = tabBtn.dataset.tabTarget || '';
  if (!target) return;

  setActiveModerationTab(target);
});

document.addEventListener('submit', function(event) {
  const form = event.target;
  if (!form.classList.contains('contact-reply-form')) return;

  event.preventDefault();

  const contactId = form.dataset.contactId;
  const reply = form.querySelector('textarea[name="reply"]').value.trim();

  if (!reply) {
    alert('Vui lòng nhập phản hồi');
    return;
  }

  const formData = new FormData();
  formData.append('id', contactId);
  formData.append('reply', reply);
  formData.append('_csrf', getCsrfToken());

  fetch('/admin/contact/reply', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return response.json();
  })
  .then(data => {
    if (data.success) {
      location.reload();
    } else {
      alert('Lỗi: ' + (data.error || 'Không thể gửi phản hồi'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Lỗi kết nối: ' + error.message);
  });
});

function getCsrfToken() {
  let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  if (!token) {
    token = document.querySelector('input[name="_csrf"]')?.value;
  }
  return token || '';
}
</script>