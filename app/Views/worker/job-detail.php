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

  <?php if (in_array($status, ['accepted', 'in_progress'], true)): ?>
    <section class="home-feature">
      <h2>⏱️ Thời gian ước tính đến</h2>
      <div class="review-box">
        <?php if (!empty($job['estimated_arrival_time'])): ?>
          <p><strong>Thời gian dự kiến:</strong> <?= View::e($job['estimated_arrival_time']) ?></p>
          <hr style="margin:12px 0;">
        <?php endif; ?>
        <form method="post" action="/worker/jobs/<?= (int)$job['id'] ?>/update-eta">
          <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
          <label for="eta">Cập nhật thời gian dự kiến đến</label>
          <input type="datetime-local" id="eta" name="estimated_arrival_time" 
                 value="<?= !empty($job['estimated_arrival_time']) ? View::e(str_replace(' ', 'T', substr($job['estimated_arrival_time'], 0, 16))) : '' ?>"
                 required style="width:100%;padding:10px;border-radius:8px;margin:8px 0 12px;border:1px solid #ddd;">
          <button type="submit" class="home-btn">Cập nhật ETA</button>
        </form>
      </div>
    </section>
  <?php endif; ?>

  <?php if ($liveMode || in_array($status, ['in_progress', 'completed'], true)): ?>
    <section class="map-card">
      <h2>📍 Chỉ đường</h2>
      
      <div class="map-info">
        <div class="map-info-item">
          <strong>Từ vị trí</strong>
          <span id="workerAddress"><?= View::e($job['worker_address'] ?? 'Đang định vị...') ?></span>
        </div>
        <div class="map-info-item">
          <strong>Đến khách hàng</strong>
          <span id="customerAddress"><?= View::e($job['location'] ?? 'Không xác định') ?></span>
        </div>
        <!-- <div class="map-info-item">
          <strong>Khoảng cách</strong>
          <span id="distanceResult">Đang tính...</span>
        </div>
        <div class="map-info-item">
          <strong>ETA</strong>
          <span id="etaResult">Đang tính...</span>
        </div> -->
      </div>

      <div style="display: flex; gap: 16px; margin-top: 20px; justify-content: center;">
        <button type="button" onclick="toggleDirections()" style="width: 40px; height: 40px; border: none; border-radius: 50%; background: linear-gradient(135deg, #b5d8b8 0%, #a8c9a8 100%); color: white; font-weight: 600; cursor: pointer; font-size: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(181, 216, 184, 0.3); transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 6px 16px rgba(181, 216, 184, 0.4)'; this.style.transform='scale(1.1)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 12px rgba(181, 216, 184, 0.3)';" title="Chỉ đường">
          📍
        </button>
        <button type="button" onclick="toggleLocation()" style="width: 40px; height: 40px; border: none; border-radius: 50%; background: linear-gradient(135deg, #d4c5e8 0%, #c8b8dc 100%); color: white; font-weight: 600; cursor: pointer; font-size: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(212, 197, 232, 0.3); transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 6px 16px rgba(212, 197, 232, 0.4)'; this.style.transform='scale(1.1)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 12px rgba(212, 197, 232, 0.3)';" title="Vị trí">
          🗺️
        </button>
      </div>

      <div id="worker-map" role="region" aria-label="Bản đồ chỉ đường">
        <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #a8a8a8;">
          <div style="text-align: center;">
            <div style="font-size: 24px; margin-bottom: 8px;">📍</div>
            <div style="font-size: 13px;">Đang tải bản đồ...</div>
          </div>
        </div>
      </div>

      <script>
        var mapMode = 'directions';
        
        function toggleDirections() {
          mapMode = 'directions';
          updateMap();
        }
        
        function toggleLocation() {
          mapMode = 'location';
          updateMap();
        }
        
        function updateMap() {
          var workerAddr = document.getElementById('workerAddress')?.textContent?.trim() || '';
          var customerAddr = document.getElementById('customerAddress')?.textContent?.trim() || '';
          var mapContainer = document.getElementById('worker-map');
          
          if (!workerAddr || !customerAddr) {
            mapContainer.innerHTML = '<p style="padding: 20px; color: #a8a8a8; text-align: center;">Chưa có địa chỉ</p>';
            return;
          }
          
          if (mapMode === 'directions') {
            // Show Google Maps Directions
            var directionsUrl = 'https://maps.google.com/maps?f=d&source=s_d&saddr=' + encodeURIComponent(workerAddr) + 
              '&daddr=' + encodeURIComponent(customerAddr) + '&output=embed&z=14';
            
            mapContainer.innerHTML = '<iframe src="' + directionsUrl + '" ' +
              'width="100%" height="100%" frameborder="0" style="border:none; border-radius: 20px;" ' +
              'allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
          } else {
            // Show Google Maps Location
            var locationUrl = 'https://maps.google.com/maps?q=' + encodeURIComponent(customerAddr) + 
              '&t=m&z=16&ie=UTF8&iwloc=&output=embed';
            
            mapContainer.innerHTML = '<iframe src="' + locationUrl + '" ' +
              'width="100%" height="100%" frameborder="0" style="border:none; border-radius: 20px;" ' +
              'allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
          }
          
          // Calculate distance and ETA
          calculateDistance(workerAddr, customerAddr);
        }
        
        function geocodeAddress(address, callback) {
          fetch('https://nominatim.openstreetmap.org/search?q=' + encodeURIComponent(address) + 
            '&format=json&limit=1', { headers: { 'Accept': 'application/json' } })
          .then(r => r.json())
          .then(data => {
            if (data?.length > 0) {
              callback({ lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lon) });
            } else {
              callback(null);
            }
          })
          .catch(() => callback(null));
        }
        
        function calculateDistance(workerAddr, customerAddr) {
          geocodeAddress(workerAddr, function(w) {
            if (!w) return;
            geocodeAddress(customerAddr, function(c) {
              if (!c) return;
              
              var toRad = deg => deg * Math.PI / 180;
              var R = 6371;
              var dLat = toRad(c.lat - w.lat);
              var dLon = toRad(c.lng - w.lng);
              var a = Math.sin(dLat/2)**2 + Math.cos(toRad(w.lat)) * Math.cos(toRad(c.lat)) * Math.sin(dLon/2)**2;
              var distance = R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
              var eta = Math.max(1, Math.round(distance / 30 * 60));
              
              document.getElementById('distanceResult').textContent = distance.toFixed(2) + ' km';
              document.getElementById('etaResult').textContent = eta + ' phút';
            });
          });
        }
        
        // Initialize on page load
        (function() {
          var workerAddr = document.getElementById('workerAddress')?.textContent?.trim() || '';
          var customerAddr = document.getElementById('customerAddress')?.textContent?.trim() || '';
          
          if (workerAddr && customerAddr) {
            updateMap();
          }
        })();
      </script>
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


