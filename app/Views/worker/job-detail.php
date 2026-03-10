<?php
use App\Core\View;
use App\Models\BookingProgress;
/** @var array $job */
/** @var array $progress */
/** @var array $messages */
/** @var array|null $payment */
/** @var string $csrf */

$liveMode = isset($_GET['live']) && $_GET['live'] === '1';
$status = $job['status'] ?? '';
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">WORKER • JOB DETAIL</p>
    <h1>Job #<?= View::e($job['id'] ?? '') ?></h1>
    <p>Chi tiết công việc và cập nhật tiến độ theo thời gian thực.</p>
  </header>

  <section class="home-feature">
    <h2>Thông tin công việc</h2>
    <div class="review-box">
      <div>
        <p><strong>Khách hàng:</strong> <?= View::e($job['user_name'] ?? '') ?></p>
        <p><strong>Số điện thoại:</strong> <?= View::e($job['user_phone'] ?? '') ?></p>
        <p><strong>Địa chỉ:</strong> <span id="customerAddress"><?= View::e($job['location'] ?? '') ?></span></p>
        <p><strong>Công việc:</strong> <?= View::e($job['service_name'] ?? '') ?></p>
        <p><strong>Chi tiết:</strong> <?= View::e($job['description'] ?? 'Không có') ?></p>
        <p><strong>Tiền thu khách:</strong> <?= number_format((float)($job['service_price'] ?? 0), 0, ',', '.') ?>đ</p>
        <?php if ($payment !== null): ?>
          <p><strong>Lương worker:</strong> <?= number_format((float)($payment['worker_salary'] ?? 0), 0, ',', '.') ?>đ</p>
          <p><strong>Phí công ty:</strong> <?= number_format((float)($payment['company_fee'] ?? 0), 0, ',', '.') ?>đ</p>
          <p><strong>Thuế:</strong> <?= number_format((float)($payment['tax_amount'] ?? 0), 0, ',', '.') ?>đ</p>
        <?php endif; ?>
      </div>
      <?php if (in_array($status, ['accepted', 'confirmed'], true)): ?>
        <form method="post" action="/worker/jobs/<?= (int)$job['id'] ?>/start" style="margin-top:10px;">
          <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
          <button type="submit" class="home-btn">Let's go</button>
        </form>
      <?php endif; ?>
    </div>
  </section>

  <?php if ($liveMode || in_array($status, ['in_progress', 'completed'], true)): ?>
    <section class="home-feature">
      <h2>Bản đồ di chuyển</h2>
      <div class="review-box">
        <p><strong>Địa chỉ worker:</strong> <span id="workerAddress"><?= View::e($job['worker_address'] ?? '') ?></span></p>
        <p><strong>Khoảng cách ước tính:</strong> <span id="distanceResult">Đang tính...</span></p>
        <p><strong>Thời gian đến dự kiến:</strong> <span id="etaResult">Đang tính...</span></p>
        <iframe id="mapFrame" title="Map" style="width:100%;height:360px;border:0;border-radius:10px;"></iframe>
      </div>
    </section>
  <?php endif; ?>

  <section class="home-feature">
    <h2>Cập nhật tiến độ</h2>
    <div class="review-box">
      <form method="post" action="/worker/jobs/<?= (int)$job['id'] ?>/progress" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
        <label>Bước tiến độ</label>
        <select name="step" required style="width:100%;padding:10px;border-radius:8px;margin:8px 0 12px;">
          <option value="on_the_way">Trên đường đến</option>
          <option value="arrived">Đã đến</option>
          <option value="before_photo">Tiến hành chụp ảnh trước dọn dẹp</option>
          <option value="after_photo">Tiến hành chụp ảnh sau dọn dẹp</option>
          <option value="completed">Hoàn thành</option>
        </select>

        <label>Ghi chú</label>
        <textarea name="note" rows="3" style="width:100%;padding:10px;border-radius:8px;margin:8px 0 12px;"></textarea>

        <label>Ảnh cập nhật (có thể chọn nhiều ảnh)</label>
        <input type="file" name="photos[]" multiple accept="image/*" style="display:block;margin:8px 0 12px;">

        <button type="submit" class="home-btn">Cập nhật tiến độ</button>
      </form>

      <?php if (!empty($progress)): ?>
        <hr style="margin:18px 0;">
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
      <?php endif; ?>

      <?php if (($job['status'] ?? '') === 'completed'): ?>
        <a class="home-btn" href="/worker/jobs/<?= (int)$job['id'] ?>/report">Đi tới báo cáo hoàn thành</a>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature">
    <h2>Trao đổi với khách hàng</h2>
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

      <form method="post" action="/worker/jobs/<?= (int)$job['id'] ?>/message">
        <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
        <textarea name="content" rows="2" required style="width:100%;padding:10px;border-radius:8px;margin:8px 0 12px;" placeholder="Nhập tin nhắn..."></textarea>
        <button type="submit" class="home-btn">Gửi tin nhắn</button>
      </form>
    </div>
  </section>
</section>

<?php if ($liveMode || in_array($status, ['in_progress', 'completed'], true)): ?>
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
    const url = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address);
    const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
    const data = await response.json();
    if (!Array.isArray(data) || data.length === 0) return null;
    return { lat: parseFloat(data[0].lat), lon: parseFloat(data[0].lon), name: data[0].display_name };
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
    if (!workerPoint || !customerPoint) {
      distanceEl.textContent = 'Không định vị được địa chỉ.';
      etaEl.textContent = 'Không định vị được địa chỉ.';
      return;
    }

    const distance = haversineKm(workerPoint, customerPoint);
    const averageSpeedKmPerHour = 30;
    const etaMinutes = Math.max(1, Math.round((distance / averageSpeedKmPerHour) * 60));

    distanceEl.textContent = distance.toFixed(2) + ' km';
    etaEl.textContent = etaMinutes + ' phút';

    const centerLat = (workerPoint.lat + customerPoint.lat) / 2;
    const centerLon = (workerPoint.lon + customerPoint.lon) / 2;
    map.src = 'https://www.openstreetmap.org/export/embed.html?bbox=' +
      (centerLon - 0.02) + '%2C' + (centerLat - 0.02) + '%2C' + (centerLon + 0.02) + '%2C' + (centerLat + 0.02) +
      '&layer=mapnik&marker=' + customerPoint.lat + '%2C' + customerPoint.lon;
  } catch (error) {
    distanceEl.textContent = 'Không thể tính khoảng cách lúc này.';
    etaEl.textContent = 'Không thể tính ETA lúc này.';
  }
})();
</script>
<?php endif; ?>
