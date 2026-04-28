<?php
use App\Core\View;
/** @var array $threads */
/** @var array $directMessages */
/** @var string $csrf */
?>

<style>
.worker-messages {
  --primary: #2eaf7d;
  --primary-dark: #16805a;
  --primary-soft: #e8f7f0;
  --bg-soft: #f7fdf9;
  --text-dark: #1f2d3d;
  --text-muted: #546e7a;
  --border: #dcefe6;
  --white: #ffffff;
  --shadow-sm: 0 8px 24px rgba(31,45,61,0.08);
  --shadow-md: 0 16px 40px rgba(31,45,61,0.12);

  max-width: 1180px;
  margin: 0 auto;
  padding: 24px 16px 60px;
  color: var(--text-dark);
}

.worker-messages * {
  box-sizing: border-box;
}

.worker-messages .home-hero {
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

.worker-messages .home-hero::after {
  content: "";
  position: absolute;
  right: -80px;
  bottom: -80px;
  width: 220px;
  height: 220px;
  border-radius: 50%;
  background: rgba(46,175,125,0.13);
}

.worker-messages .home-kicker {
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

.worker-messages .home-hero h1 {
  position: relative;
  margin: 0 0 12px;
  color: var(--text-dark);
  font-size: clamp(32px, 5vw, 52px);
  line-height: 1.1;
  font-weight: 900;
  letter-spacing: -0.04em;
}

.worker-messages .home-hero p {
  position: relative;
  margin: 0;
  color: var(--text-muted);
  font-size: 17px;
}

.messages-list {
  display: grid;
  gap: 22px;
  margin-top: 40px;
}

.thread-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 26px;
  padding: 28px;
  box-shadow: var(--shadow-sm);
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.thread-card:hover {
  transform: translateY(-3px);
  border-color: rgba(46,175,125,0.45);
  box-shadow: var(--shadow-md);
}

.thread-empty {
  padding: 34px 20px;
  text-align: center;
  color: var(--text-muted);
  background: var(--bg-soft);
  border: 1px dashed #cfe3d8;
  border-radius: 22px;
  margin: 0;
}

.thread-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 18px;
  margin-bottom: 18px;
  padding-bottom: 18px;
  border-bottom: 1px solid var(--border);
}

.thread-title {
  margin: 0;
  color: var(--text-dark);
  font-size: 20px;
  font-weight: 900;
  line-height: 1.4;
}

.thread-customer {
  display: inline-flex;
  margin-top: 8px;
  padding: 7px 12px;
  border-radius: 999px;
  background: var(--primary-soft);
  color: var(--primary-dark);
  font-size: 13px;
  font-weight: 800;
}

.thread-badge {
  padding: 7px 13px;
  border-radius: 999px;
  background: var(--bg-soft);
  color: var(--text-muted);
  border: 1px solid var(--border);
  font-size: 13px;
  font-weight: 900;
  white-space: nowrap;
}

.thread-messages {
  display: grid;
  gap: 12px;
}

.thread-message {
  padding: 16px;
  border: 1px solid var(--border);
  border-radius: 18px;
  background: #fcfffd;
}

.thread-message .meta {
  margin: 0 0 8px;
  color: var(--text-muted);
  font-size: 13px;
  font-weight: 700;
  line-height: 1.5;
}

.thread-message .sender {
  color: var(--text-dark);
  font-weight: 900;
}

.thread-message .role {
  color: var(--primary);
  font-weight: 800;
}

.thread-message .content {
  margin: 0;
  color: var(--text-dark);
  line-height: 1.6;
}

.thread-form {
  margin-top: 18px;
  display: grid;
  gap: 12px;
}

.thread-form textarea {
  width: 100%;
  min-height: 110px;
  padding: 14px 16px;
  border: 1px solid var(--border);
  border-radius: 18px;
  background: #fcfffd;
  color: var(--text-dark);
  font-family: inherit;
  font-size: 15px;
  resize: vertical;
  transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.thread-form textarea:focus {
  outline: none;
  background: white;
  border-color: var(--primary);
  box-shadow: 0 0 0 4px rgba(46,175,125,0.12);
}

.worker-btn {
  min-height: 46px;
  padding: 12px 24px;
  border-radius: 999px;
  border: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  justify-self: flex-start;
  text-decoration: none;
  font-size: 15px;
  font-weight: 900;
  cursor: pointer;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: white;
  box-shadow: 0 10px 22px rgba(46,175,125,0.22);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.worker-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 14px 30px rgba(46,175,125,0.28);
}

@media (max-width: 768px) {
  .worker-messages {
    padding: 16px 12px 44px;
  }

  .worker-messages .home-hero {
    padding: 42px 18px;
    border-radius: 22px;
  }

  .thread-card {
    padding: 22px;
    border-radius: 20px;
  }

  .thread-header {
    flex-direction: column;
  }

  .worker-btn {
    width: 100%;
  }
}
</style>

<section class="home-container worker-messages">
  <header class="home-hero">
    <p class="home-kicker">WORKER • TIN NHẮN</p>
    <h1>Trao đổi với Admin</h1>
    <p>Xem và phản hồi các tin nhắn từ admin theo từng đơn.</p>
  </header>

  <div class="messages-list">
    <article class="thread-card" id="direct-admin-chat">
      <div class="thread-header">
        <div>
          <h2 class="thread-title">Trao đổi trực tiếp với Admin</h2>
          <span class="thread-customer">Kênh liên hệ nhanh</span>
        </div>
        <span class="thread-badge">
          <?= count($directMessages ?? []) ?> tin nhắn
        </span>
      </div>

      <div class="thread-messages">
        <?php if (empty($directMessages)): ?>
          <p class="thread-empty">Chưa có tin nhắn trực tiếp nào.</p>
        <?php else: ?>
          <?php foreach ($directMessages as $message): ?>
            <div class="thread-message">
              <p class="meta">
                <span class="sender"><?= View::e((string)($message['sender_name'] ?? '')) ?></span>
                <span class="role">(<?= View::e((string)($message['sender_role'] ?? '')) ?>)</span>
                • <?= View::e((string)($message['created_at'] ?? '')) ?>
              </p>
              <p class="content"><?= View::e((string)($message['content'] ?? '')) ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <form method="post" action="/worker/messages/direct" class="thread-form">
        <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
        <textarea name="content" rows="3" required placeholder="Nhập tin nhắn gửi admin..."></textarea>
        <button class="worker-btn" type="submit">Gửi tin nhắn</button>
      </form>
    </article>

    <?php if (empty($threads)): ?>
      <div class="thread-card">
        <p class="thread-empty">Chưa có tin nhắn nào từ admin.</p>
      </div>
    <?php endif; ?>

    <?php foreach ($threads as $thread): ?>
      <article class="thread-card" id="booking-<?= (int)($thread['booking_id'] ?? 0) ?>">
        <div class="thread-header">
          <div>
            <h2 class="thread-title">
              Booking #<?= (int)($thread['booking_id'] ?? 0) ?> - <?= View::e((string)($thread['service_name'] ?? '')) ?>
            </h2>

            <?php if (!empty($thread['customer_name'])): ?>
              <span class="thread-customer">
                Khách hàng: <?= View::e((string)$thread['customer_name']) ?>
              </span>
            <?php endif; ?>
          </div>

          <span class="thread-badge">
            <?= count($thread['messages'] ?? []) ?> tin nhắn
          </span>
        </div>

        <div class="thread-messages">
          <?php foreach ($thread['messages'] as $message): ?>
            <div class="thread-message">
              <p class="meta">
                <span class="sender"><?= View::e((string)($message['sender_name'] ?? '')) ?></span>
                <span class="role">(<?= View::e((string)($message['sender_role'] ?? '')) ?>)</span>
                • <?= View::e((string)($message['created_at'] ?? '')) ?>
              </p>
              <p class="content"><?= View::e((string)($message['content'] ?? '')) ?></p>
            </div>
          <?php endforeach; ?>
        </div>

        <form method="post" action="/worker/messages/<?= (int)($thread['booking_id'] ?? 0) ?>" class="thread-form">
          <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
          <textarea name="content" rows="3" required placeholder="Nhập tin nhắn gửi admin..."></textarea>
          <button class="worker-btn" type="submit">Gửi tin nhắn</button>
        </form>
      </article>
    <?php endforeach; ?>
  </div>
</section>