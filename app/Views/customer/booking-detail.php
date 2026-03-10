<?php
use App\Core\View;
use App\Models\BookingProgress;
/** @var array $booking */
/** @var array $progress */
/** @var array $messages */
/** @var array|null $payment */
/** @var bool $hasReview */
/** @var string $csrf */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">KHÁCH HÀNG • THEO DÕI ĐƠN</p>
    <h1>Đơn #<?= View::e($booking['id'] ?? '') ?></h1>
    <p>Theo dõi tiến độ làm việc, ảnh cập nhật và trao đổi trực tiếp với worker.</p>
  </header>

  <section class="home-feature">
    <h2>Thông tin đơn</h2>
    <div class="review-box">
      <p><strong>Dịch vụ:</strong> <?= View::e($booking['service_name'] ?? '') ?></p>
      <?php if (isset($booking['quantity']) && (float)$booking['quantity'] > 0): ?>
        <p><strong>Khối lượng:</strong> <?= View::e((string)$booking['quantity']) ?> <?= View::e((string)($booking['measure_unit'] ?? '')) ?></p>
      <?php endif; ?>
      <?php if (isset($booking['unit_price']) && (float)$booking['unit_price'] > 0): ?>
        <p><strong>Đơn giá:</strong> <?= number_format((float)$booking['unit_price'], 0, ',', '.') ?>đ/<?= View::e((string)($booking['measure_unit'] ?? '')) ?></p>
      <?php endif; ?>
      <p><strong>Địa chỉ:</strong> <span id="customerAddress"><?= View::e($booking['location'] ?? '') ?></span></p>
      <p><strong>Thời gian:</strong> <?= View::e(($booking['date'] ?? '') . ' ' . ($booking['time'] ?? '')) ?></p>
      <p><strong>Worker:</strong> <?= View::e($booking['worker_name'] ?? 'Chưa gán') ?></p>
      <p><strong>SĐT worker:</strong> <?= View::e($booking['worker_phone'] ?? '') ?></p>
      <p><strong>Trạng thái:</strong> <span style="color:#2eaf7d;font-weight:700;"><?= View::e($booking['status'] ?? '') ?></span></p>

      <?php if ($payment !== null): ?>
        <hr style="margin:12px 0;">
        <p><strong>Khách thanh toán:</strong> <?= number_format((float)($payment['service_price'] ?? 0), 0, ',', '.') ?>đ</p>
      <?php else: ?>
        <hr style="margin:12px 0;">
        <p><strong>Khách thanh toán:</strong> <?= number_format((float)($booking['service_price'] ?? 0), 0, ',', '.') ?>đ</p>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature">
    <h2>Tiến độ công việc & ảnh cập nhật</h2>
    <div class="review-box">
      <?php if (empty($progress)): ?>
        <p>Worker chưa cập nhật tiến độ.</p>
      <?php endif; ?>
      <?php foreach ($progress as $item): ?>
        <div style="margin-bottom:14px;padding:10px;border:1px solid #e9ecef;border-radius:8px;">
          <p><strong><?= View::e(BookingProgress::stepLabel((string)($item['step'] ?? ''))) ?></strong> • <?= View::e($item['created_at'] ?? '') ?></p>
          <?php if (!empty($item['note'])): ?>
            <p><?= View::e($item['note']) ?></p>
          <?php endif; ?>
          <?php if (!empty($item['photos'])): ?>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
              <?php foreach ($item['photos'] as $photo): ?>
                <a href="<?= View::e($photo) ?>" target="_blank" rel="noopener">
                  <img src="<?= View::e($photo) ?>" alt="progress" style="width:110px;height:110px;object-fit:cover;border-radius:8px;">
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <?php if (!empty($booking['assigned_worker_id']) && in_array($booking['status'] ?? '', ['accepted', 'confirmed', 'in_progress'], true)): ?>
    <section class="home-feature">
      <h2>Bản đồ di chuyển</h2>
      <div class="review-box">
        <p><strong>Địa chỉ worker:</strong> <span id="workerAddress"><?= View::e($booking['worker_address'] ?? '') ?></span></p>
        <p><strong>Khoảng cách ước tính:</strong> <span id="distanceResult">Đang tính...</span></p>
        <p><strong>Thời gian đến dự kiến:</strong> <span id="etaResult">Đang tính...</span></p>
        <iframe id="mapFrame" title="Map" style="width:100%;height:360px;border:0;border-radius:10px;"></iframe>
      </div>
    </section>
  <?php endif; ?>

  <section class="home-feature">
    <h2>Nhắn tin với worker</h2>
    <div class="review-box">
      <?php if (empty($messages)): ?>
        <p>Chưa có tin nhắn.</p>
      <?php endif; ?>
      <?php foreach ($messages as $message): ?>
        <div style="margin-bottom:10px;padding:8px;border:1px solid #e9ecef;border-radius:8px;">
          <p style="margin:0 0 4px;"><strong><?= View::e($message['sender_name'] ?? '') ?></strong> (<?= View::e($message['sender_role'] ?? '') ?>)</p>
          <p style="margin:0 0 4px;"><?= View::e($message['content'] ?? '') ?></p>
          <small><?= View::e($message['created_at'] ?? '') ?></small>
        </div>
      <?php endforeach; ?>

      <form method="post" action="/bookings/<?= (int)$booking['id'] ?>/message">
        <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
        <textarea name="content" rows="2" required style="width:100%;padding:10px;border-radius:8px;margin:8px 0 12px;" placeholder="Nhập tin nhắn..."></textarea>
        <button type="submit" class="home-btn">Gửi tin nhắn</button>
      </form>
    </div>
  </section>

  <?php if (($booking['status'] ?? '') === 'completed' && !$hasReview): ?>
    <section class="home-feature">
      <h2>Đánh giá sau khi hoàn thành</h2>
      <div class="review-box">
        <p>Đơn đã hoàn thành. Vui lòng đánh giá worker và để lại bình luận.</p>
        <a class="home-btn" href="/bookings/<?= (int)$booking['id'] ?>/review">Đi tới trang đánh giá</a>
      </div>
    </section>
    <script>
      setTimeout(function () {
        window.location.href = '/bookings/<?= (int)$booking['id'] ?>/review';
      }, 2200);
    </script>
  <?php endif; ?>
</section>

<script>
(async function () {
  const workerAddress = (document.getElementById('workerAddress')?.textContent || '').trim();
  const customerAddress = (document.getElementById('customerAddress')?.textContent || '').trim();
  const distanceEl = document.getElementById('distanceResult');
  const etaEl = document.getElementById('etaResult');
  const map = document.getElementById('mapFrame');

  if (!workerAddress || !customerAddress) {
    distanceEl.textContent = 'Thiếu địa chỉ để tính.';
    etaEl.textContent = 'Thiếu địa chỉ để tính.';
    return;
  }

  const geocode = async (address) => {
    try {
      const url = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address) + '&limit=1';
      const response = await fetch(url, { 
        headers: { 'Accept': 'application/json' }
      });
      if (!response.ok) throw new Error('API error');
      const data = await response.json();
      if (!Array.isArray(data) || data.length === 0) return null;
      return { lat: parseFloat(data[0].lat), lon: parseFloat(data[0].lon), name: data[0].display_name };
    } catch (e) {
      console.error('Geocode error for:', address, e);
      try {
        const simplified = address.split(',').slice(0, 2).join(',').trim();
        if (simplified && simplified !== address) {
          const retryUrl = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(simplified) + '&limit=1&countrycodes=vn';
          const retryResponse = await fetch(retryUrl, { 
            headers: { 'Accept': 'application/json' }
          });
          if (retryResponse.ok) {
            const retryData = await retryResponse.json();
            if (Array.isArray(retryData) && retryData.length > 0) {
              return { lat: parseFloat(retryData[0].lat), lon: parseFloat(retryData[0].lon), name: retryData[0].display_name };
            }
          }
        }
      } catch (retryErr) {
        console.error('Retry geocode error:', retryErr);
      }
      return null;
    }
  };

  const haversineKm = (a, b) => {
    const toRad = (d) => d * Math.PI / 180;
    const R = 6371;
    const dLat = toRad(b.lat - a.lat);
    const dLon = toRad(b.lon - a.lon);
    const lat1 = toRad(a.lat);
    const lat2 = toRad(b.lat);
    const h = Math.sin(dLat / 2) ** 2 + Math.cos(lat1) * Math.cos(lat2) * Math.sin(dLon / 2) ** 2;
    return 2 * R * Math.asin(Math.sqrt(h));
  };

  try {
    const [workerPoint, customerPoint] = await Promise.all([geocode(workerAddress), geocode(customerAddress)]);
    
    if (!workerPoint) {
      console.warn('Worker address not geocoded:', workerAddress);
      distanceEl.textContent = 'Không thể xác định vị trí worker.';
      etaEl.textContent = 'Không thể xác định vị trí worker.';
      return;
    }
    
    if (!customerPoint) {
      console.warn('Customer address not geocoded:', customerAddress);
      distanceEl.textContent = 'Không thể xác định vị trí khách hàng.';
      etaEl.textContent = 'Không thể xác định vị trí khách hàng.';
      return;
    }

    const distance = haversineKm(workerPoint, customerPoint);
    const averageSpeedKmPerHour = 30;
    const etaMinutes = Math.max(1, Math.round((distance / averageSpeedKmPerHour) * 60));

    distanceEl.textContent = distance.toFixed(2) + ' km';
    etaEl.textContent = etaMinutes + ' phút';

    const south = Math.min(customerPoint.lat, workerPoint.lat) - 0.03;
    const north = Math.max(customerPoint.lat, workerPoint.lat) + 0.03;
    const west = Math.min(customerPoint.lon, workerPoint.lon) - 0.03;
    const east = Math.max(customerPoint.lon, workerPoint.lon) + 0.03;

    map.src = 'https://www.openstreetmap.org/export/embed.html?bbox=' +
      west + '%2C' + south + '%2C' + east + '%2C' + north +
      '&layer=mapnik&marker=' + customerPoint.lat + '%2C' + customerPoint.lon + 
      '&marker=' + workerPoint.lat + '%2C' + workerPoint.lon;
  } catch (error) {
    console.error('Map error:', error);
    distanceEl.textContent = 'Lỗi khi tính toán bản đồ.';
    etaEl.textContent = 'Lỗi khi tính toán bản đồ.';
  }
})();
</script>
