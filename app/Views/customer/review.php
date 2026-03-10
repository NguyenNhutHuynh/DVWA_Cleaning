<?php
use App\Core\View;
/** @var array $booking */
/** @var string $csrf */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">KHÁCH HÀNG • ĐÁNH GIÁ</p>
    <h1>Đánh giá đơn #<?= View::e($booking['id'] ?? '') ?></h1>
    <p>Chia sẻ mức độ hài lòng của bạn sau khi hoàn thành dịch vụ.</p>
  </header>

  <section class="home-feature">
    <h2>Thông tin đơn</h2>
    <div class="review-box">
      <p><strong>Dịch vụ:</strong> <?= View::e($booking['service_name'] ?? '') ?></p>
      <p><strong>Worker:</strong> <?= View::e($booking['worker_name'] ?? '') ?></p>
      <p><strong>Địa chỉ:</strong> <?= View::e($booking['location'] ?? '') ?></p>
    </div>
  </section>

  <section class="home-feature">
    <h2>Gửi đánh giá</h2>
    <div class="review-box">
      <form method="post" action="/bookings/<?= (int)$booking['id'] ?>/review">
        <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">

        <label>Số sao</label>
        <select name="rating" required style="width:100%;padding:10px;border-radius:8px;margin:8px 0 12px;">
          <option value="5">5 sao - Rất tốt</option>
          <option value="4">4 sao - Tốt</option>
          <option value="3">3 sao - Ổn</option>
          <option value="2">2 sao - Chưa tốt</option>
          <option value="1">1 sao - Không hài lòng</option>
        </select>

        <label>Bình luận</label>
        <textarea name="comment" rows="4" style="width:100%;padding:10px;border-radius:8px;margin:8px 0 12px;" placeholder="Nhập nhận xét của bạn..."></textarea>

        <button type="submit" class="home-btn">Gửi đánh giá</button>
      </form>
    </div>
  </section>
</section>
