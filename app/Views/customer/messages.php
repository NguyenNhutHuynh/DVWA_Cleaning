<?php
use App\Core\View;
/** @var array $messages */
/** @var string $csrf */
?>

<style>
.customer-messages {
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

.customer-messages * {
  box-sizing: border-box;
}

.customer-messages .home-hero {
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

.customer-messages .home-hero::after {
  content: "";
  position: absolute;
  right: -80px;
  bottom: -80px;
  width: 220px;
  height: 220px;
  border-radius: 50%;
  background: rgba(46,175,125,0.13);
}

.customer-messages .home-kicker {
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

.customer-messages .home-hero h1 {
  position: relative;
  margin: 0 0 12px;
  color: var(--text-dark);
  font-size: clamp(32px, 5vw, 52px);
  line-height: 1.1;
  font-weight: 900;
  letter-spacing: -0.04em;
}

.customer-messages .home-hero p {
  position: relative;
  margin: 0;
  color: var(--text-muted);
  font-size: 17px;
}

.chat-card {
  margin-top: 40px;
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 26px;
  padding: 28px;
  box-shadow: var(--shadow-sm);
}

.chat-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  padding-bottom: 18px;
  border-bottom: 1px solid var(--border);
}

.chat-title {
  margin: 0;
  font-size: 20px;
  font-weight: 900;
  color: var(--text-dark);
}

.chat-badge {
  padding: 7px 13px;
  border-radius: 999px;
  background: var(--bg-soft);
  color: var(--text-muted);
  border: 1px solid var(--border);
  font-size: 13px;
  font-weight: 900;
  white-space: nowrap;
}

.chat-thread {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 18px;
  min-height: 120px;
}

.chat-empty {
  margin: 0;
  padding: 24px;
  text-align: center;
  color: var(--text-muted);
  background: var(--bg-soft);
  border: 1px dashed #cfe3d8;
  border-radius: 18px;
}

.chat-bubble {
  max-width: 72%;
  padding: 12px 16px;
  border-radius: 18px;
  border: 1px solid var(--border);
  background: #fcfffd;
  color: var(--text-dark);
  box-shadow: 0 6px 18px rgba(31,45,61,0.06);
}

.chat-bubble.is-admin {
  align-self: flex-start;
  background: #ffffff;
}

.chat-bubble.is-user {
  align-self: flex-end;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: #ffffff;
  border-color: transparent;
}

.chat-meta {
  margin: 0 0 6px;
  font-size: 12px;
  font-weight: 700;
  color: rgba(84,110,122,0.8);
}

.chat-bubble.is-user .chat-meta {
  color: rgba(255,255,255,0.85);
}

.chat-content {
  margin: 0;
  line-height: 1.6;
  white-space: pre-wrap;
}

.chat-form {
  margin-top: 18px;
  display: grid;
  gap: 12px;
}

.chat-form textarea {
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

.chat-form textarea:focus {
  outline: none;
  background: white;
  border-color: var(--primary);
  box-shadow: 0 0 0 4px rgba(46,175,125,0.12);
}

.chat-btn {
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

.chat-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 14px 30px rgba(46,175,125,0.28);
}

@media (max-width: 768px) {
  .customer-messages {
    padding: 16px 12px 44px;
  }

  .customer-messages .home-hero {
    padding: 42px 18px;
    border-radius: 22px;
  }

  .chat-card {
    padding: 22px;
    border-radius: 20px;
  }

  .chat-bubble {
    max-width: 100%;
  }

  .chat-btn {
    width: 100%;
  }
}
</style>

<section class="home-container customer-messages">
  <header class="home-hero">
    <p class="home-kicker">CUSTOMER • TIN NHẮN</p>
    <h1>Trao đổi với Admin</h1>
    <p>Gửi và nhận tin nhắn hỗ trợ nhanh chóng.</p>
  </header>

  <article class="chat-card">
    <div class="chat-header">
      <h2 class="chat-title">Hộp thoại hỗ trợ</h2>
      <span class="chat-badge"><?= count($messages ?? []) ?> tin nhắn</span>
    </div>

    <div class="chat-thread">
      <?php if (empty($messages)): ?>
        <p class="chat-empty">Chưa có tin nhắn nào từ admin.</p>
      <?php else: ?>
        <?php foreach ($messages as $message): ?>
          <?php $isAdmin = ($message['sender_role'] ?? '') === 'admin'; ?>
          <div class="chat-bubble <?= $isAdmin ? 'is-admin' : 'is-user' ?>">
            <p class="chat-meta">
              <?= View::e((string)($message['sender_name'] ?? '')) ?>
              • <?= View::e((string)($message['created_at'] ?? '')) ?>
            </p>
            <p class="chat-content"><?= View::e((string)($message['content'] ?? '')) ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <form method="post" action="/customer/messages" class="chat-form">
      <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
      <textarea name="content" rows="3" required placeholder="Nhập tin nhắn..." ></textarea>
      <button type="submit" class="chat-btn">Gửi tin nhắn</button>
    </form>
  </article>
</section>
