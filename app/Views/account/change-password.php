<?php
use App\Core\View;
?>

<style>
.password-page {
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

  max-width: 1080px;
  margin: 24px auto 60px;
  padding: 0 16px;
  color: var(--text-dark);
}

.password-page * {
  box-sizing: border-box;
}

.password-card {
  overflow: hidden;
  border-radius: 28px;
  background: var(--white);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
}

.password-header {
  position: relative;
  overflow: hidden;
  padding: 42px 28px;
  background:
    radial-gradient(circle at top left, rgba(46,175,125,0.20), transparent 34%),
    linear-gradient(135deg, #f7fdf9 0%, #ffffff 48%, #e8f7f0 100%);
  border-bottom: 1px solid var(--border);
}

.password-header::after {
  content: "";
  position: absolute;
  right: -70px;
  bottom: -70px;
  width: 200px;
  height: 200px;
  border-radius: 50%;
  background: rgba(46,175,125,0.13);
}

.password-header h2 {
  position: relative;
  margin: 0;
  font-size: clamp(28px, 4vw, 42px);
  font-weight: 900;
  letter-spacing: -0.04em;
}

.password-header p {
  position: relative;
  margin: 10px 0 0;
  color: var(--text-muted);
}

.password-body {
  padding: 30px;
}

.password-form {
  display: grid;
  gap: 18px;
  max-width: 520px;
  margin: 0 auto;
}

.form-group {
  display: grid;
  gap: 8px;
}

.form-group span {
  font-size: 14px;
  font-weight: 800;
}

.form-group input {
  width: 100%;
  min-height: 52px;
  padding: 14px 16px;
  border-radius: 16px;
  border: 1px solid var(--border);
  background: #fcfffd;
  font-size: 15px;
  transition: 0.2s;
}

.form-group input:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 4px rgba(46,175,125,0.12);
}

.auth-btn {
  min-height: 50px;
  border-radius: 999px;
  border: none;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: white;
  font-weight: 900;
  cursor: pointer;
  box-shadow: 0 10px 22px rgba(46,175,125,0.22);
  transition: 0.2s;
}

.auth-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 14px 30px rgba(46,175,125,0.28);
}

.back-link {
  text-align: center;
  text-decoration: none;
  color: var(--primary);
  font-weight: 700;
}

.back-link:hover {
  text-decoration: underline;
}

@media (max-width:768px){
  .password-body{padding:22px;}
  .auth-btn{width:100%;}
}
</style>

<section class="password-page">
  <div class="password-card">

    <header class="password-header">
      <h2>Đổi mật khẩu</h2>
      <p>Cập nhật mật khẩu để bảo vệ tài khoản của bạn.</p>
    </header>

    <div class="password-body">
      <form method="post" action="/account/update-password" class="password-form">
        <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">

        <label class="form-group">
          <span>Mật khẩu hiện tại</span>
          <input type="password" name="current_password" required placeholder="Nhập mật khẩu hiện tại">
        </label>

        <label class="form-group">
          <span>Mật khẩu mới</span>
          <input type="password" name="new_password" required placeholder="Ít nhất 6 ký tự">
        </label>

        <label class="form-group">
          <span>Xác nhận mật khẩu mới</span>
          <input type="password" name="confirm_password" required placeholder="Nhập lại mật khẩu mới">
        </label>

        <button type="submit" class="auth-btn">Đổi mật khẩu</button>

        <a href="/account/edit" class="back-link">← Quay lại</a>
      </form>
    </div>

  </div>
</section>