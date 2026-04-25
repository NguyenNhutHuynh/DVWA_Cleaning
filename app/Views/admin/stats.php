<?php
use App\Core\View;
/** @var array $stats Dữ liệu thống kê */
?>

<section class="home-container admin-stats">
  <header class="home-hero">
    <p class="home-kicker">ADMIN • THỐNG KÊ</p>
    <h1>Thống kê hệ thống</h1>
    <p>Tổng quan nhanh về dịch vụ, đơn đặt và người dùng.</p>
  </header>
  <div class="hero-actions">
      <button class="home-btn" id="exportPdfBtn">📥 Tải xuống PDF</button>
      <button class="home-btn home-btn-outline" id="refreshBtn">🔄 Làm mới</button>
  </div>
  <section class="stats-group">
    <div class="stats-group-head stats-group-head-overview">
      <h2>Tổng quan hệ thống</h2>
      <!-- <p>Tổng quan nhanh.</p> -->
      <button type="button" class="stats-toggle-btn" data-target="overviewGroup" aria-expanded="true">Thu gọn</button>
    </div>
    <div class="stats-group-content" id="overviewGroup">
    <section class="home-stats" id="statsOverview">
      <div class="stat-card">
        <strong><?= View::e($stats['service_count']) ?></strong>
        <span>Dịch vụ</span>
      </div>
      <div class="stat-card">
        <strong><?= View::e($stats['booking_count']) ?></strong>
        <span>Đơn đặt</span>
      </div>
      <div class="stat-card">
        <strong><?= View::e($stats['user_count']) ?></strong>
        <span>Người dùng</span>
      </div>
      <div class="stat-card">
        <strong><?= View::e($stats['contact_count']) ?></strong>
        <span>Tin nhắn</span>
      </div>
    </section>
    </div>
  </section>

  <section class="stats-group">
    <div class="stats-group-head">
      <h2>Doanh thu và hiệu suất</h2>
      <!-- <p>Doanh thu và tỷ lệ chính.</p> -->
      <button type="button" class="stats-toggle-btn" data-target="revenueGroup" aria-expanded="true">Thu gọn</button>
    </div>
    <div class="stats-group-content" id="revenueGroup">
    <section class="home-stats revenue-stats">
      <div class="stat-card stat-revenue">
        <strong><?= number_format($stats['total_revenue'], 0, ',', '.') ?>đ</strong>
        <span>💰 Tổng doanh thu</span>
      </div>
      <div class="stat-card stat-aov">
        <strong><?= number_format($stats['average_order_value'], 0, ',', '.') ?>đ</strong>
        <span>📊 Giá trị đơn TB</span>
      </div>
      <div class="stat-card stat-conversion">
        <strong><?= View::e($stats['conversion_rate']) ?>%</strong>
        <span>🎯 Tỷ lệ chuyển đổi</span>
      </div>
      <div class="stat-card stat-completion">
        <strong><?= View::e($stats['completion_rate']) ?>%</strong>
        <span>✅ Tỷ lệ hoàn thành</span>
      </div>
    </section>
    </div>
  </section>

  <section class="stats-group">
    <div class="stats-group-head">
      <h2>Biểu đồ phân tích</h2>
      <!-- <p>Xu hướng và phân bố dữ liệu.</p> -->
      <button type="button" class="stats-toggle-btn" data-target="chartsGroup" aria-expanded="true">Thu gọn</button>
    </div>
    <div class="stats-group-content" id="chartsGroup">
    <div class="charts-grid">
      <!-- Biểu đồ Doanh thu theo tháng -->
      <section class="chart-section">
        <div class="chart-header">
          <h2>💰 Doanh thu 6 tháng gần nhất</h2>
          <div class="chart-controls">
            <select id="revenueChartType" class="chart-select">
              <option value="bar">Biểu đồ cột</option>
              <option value="line">Biểu đồ đường</option>
            </select>
          </div>
        </div>
        <div class="chart-container">
          <canvas id="monthlyRevenueChart"></canvas>
        </div>
      </section>

      <!-- Biểu đồ Xu hướng đơn đặt theo tháng -->
      <section class="chart-section">
        <div class="chart-header">
          <h2>📈 Xu hướng đơn đặt 6 tháng gần nhất</h2>
          <div class="chart-controls">
            <select id="lineChartType" class="chart-select">
              <option value="line">Biểu đồ đường</option>
              <option value="bar">Biểu đồ cột</option>
            </select>
          </div>
        </div>
        <div class="chart-container">
          <canvas id="monthlyBookingsChart"></canvas>
        </div>
      </section>

      <!-- Biểu đồ Trạng thái đơn đặt -->
      <section class="chart-section">
        <div class="chart-header">
          <h2>📊 Phân bố trạng thái đơn đặt</h2>
          <div class="chart-controls">
            <select id="statusChartType" class="chart-select">
              <option value="pie">Biểu đồ tròn</option>
              <option value="doughnut">Biểu đồ vành khuyên</option>
              <option value="bar">Biểu đồ cột</option>
            </select>
          </div>
        </div>
        <div class="chart-container">
          <canvas id="bookingStatusChart"></canvas>
        </div>
      </section>

      <!-- Biểu đồ Phân bố vai trò người dùng -->
      <section class="chart-section">
        <div class="chart-header">
          <h2>👥 Phân bố vai trò người dùng</h2>
          <div class="chart-controls">
            <select id="roleChartType" class="chart-select">
              <option value="doughnut">Biểu đồ vành khuyên</option>
              <option value="pie">Biểu đồ tròn</option>
              <option value="bar">Biểu đồ cột</option>
            </select>
          </div>
        </div>
        <div class="chart-container">
          <canvas id="userRoleChart"></canvas>
        </div>
      </section>
    </div>
    </div>
  </section>

  <!-- Bảng chi tiết -->
  <section class="home-feature stats-group">
    <div class="stats-group-head">
      <h2>📋 Chi tiết thống kê</h2>
      <!-- <p>Số lượng và tỷ lệ.</p> -->
      <button type="button" class="stats-toggle-btn" data-target="detailGroup" aria-expanded="true">Thu gọn</button>
    </div>
    <div class="stats-group-content" id="detailGroup">
    <div class="stats-table-container">
      <table class="stats-table">
        <thead>
          <tr>
            <th>Chỉ số</th>
            <th>Giá trị</th>
            <th>Tỷ lệ</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Đơn đã xác nhận</td>
            <td><?= View::e($stats['booking_status_breakdown']['confirmed'] ?? 0) ?></td>
            <td><?= View::e($stats['confirmed_rate']) ?>%</td>
          </tr>
          <tr>
            <td>Đơn đang chờ</td>
            <td><?= View::e($stats['booking_status_breakdown']['pending'] ?? 0) ?></td>
            <td><?= View::e($stats['pending_rate']) ?>%</td>
          </tr>
          <tr>
            <td>Đơn hoàn thành</td>
            <td><?= View::e($stats['booking_status_breakdown']['completed'] ?? 0) ?></td>
            <td><?= $stats['booking_count'] > 0 ? round(($stats['booking_status_breakdown']['completed'] / $stats['booking_count']) * 100, 1) : 0 ?>%</td>
          </tr>
          <tr>
            <td>Đơn đã hủy</td>
            <td><?= View::e($stats['booking_status_breakdown']['cancelled'] ?? 0) ?></td>
            <td><?= $stats['booking_count'] > 0 ? round(($stats['booking_status_breakdown']['cancelled'] / $stats['booking_count']) * 100, 1) : 0 ?>%</td>
          </tr>
          <tr class="divider-row">
            <td colspan="3"></td>
          </tr>
          <tr class="highlight-row">
            <td>💰 Tổng doanh thu</td>
            <td colspan="2"><?= number_format($stats['total_revenue'], 0, ',', '.') ?>đ</td>
          </tr>
          <tr class="highlight-row">
            <td>📊 Giá trị đơn trung bình</td>
            <td colspan="2"><?= number_format($stats['average_order_value'], 0, ',', '.') ?>đ</td>
          </tr>
          <tr class="highlight-row">
            <td>🎯 Tỷ lệ chuyển đổi</td>
            <td colspan="2"><?= View::e($stats['conversion_rate']) ?>%</td>
          </tr>
          <tr class="highlight-row">
            <td>✅ Tỷ lệ hoàn thành</td>
            <td colspan="2"><?= View::e($stats['completion_rate']) ?>%</td>
          </tr>
        </tbody>
      </table>
    </div>
    </div>
  </section>
</section>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<!-- jsPDF + html2canvas for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
(function() {
  'use strict';

  // Dữ liệu từ PHP
  const statsData = <?= json_encode($stats) ?>;

  // Màu sắc theme
  const colors = {
    primary: '#6366f1',
    success: '#10b981',
    warning: '#f59e0b',
    danger: '#ef4444',
    info: '#3b82f6',
    purple: '#8b5cf6',
    pink: '#ec4899',
  };

  // Config chung cho biểu đồ
  const commonOptions = {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
      legend: {
        display: true,
        position: 'bottom',
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        padding: 12,
        titleFont: { size: 14, weight: 'bold' },
        bodyFont: { size: 13 },
        cornerRadius: 8,
      },
    },
  };

  // Format số tiền VNĐ
  function formatCurrency(value) {
    return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
  }

  // 1. Biểu đồ doanh thu theo tháng
  let revenueChart = null;
  const revenueLabels = Object.keys(statsData.monthly_revenue);
  const revenueValues = Object.values(statsData.monthly_revenue);

  function createRevenueChart(type = 'bar') {
    const ctx = document.getElementById('monthlyRevenueChart');
    if (!ctx) return;

    if (revenueChart) revenueChart.destroy();

    revenueChart = new Chart(ctx, {
      type: type,
      data: {
        labels: revenueLabels,
        datasets: [{
          label: 'Doanh thu (VNĐ)',
          data: revenueValues,
          backgroundColor: type === 'bar' 
            ? 'rgba(16, 185, 129, 0.8)' 
            : 'rgba(16, 185, 129, 0.1)',
          borderColor: colors.success,
          borderWidth: 2,
          tension: 0.4,
          fill: type === 'line',
        }]
      },
      options: {
        ...commonOptions,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return formatCurrency(value);
              }
            }
          }
        },
        plugins: {
          ...commonOptions.plugins,
          tooltip: {
            ...commonOptions.plugins.tooltip,
            callbacks: {
              label: function(context) {
                return 'Doanh thu: ' + formatCurrency(context.parsed.y);
              }
            }
          }
        }
      }
    });
  }

  // 2. Biểu đồ xu hướng đơn đặt theo tháng
  let monthlyChart = null;
  const monthlyLabels = Object.keys(statsData.monthly_bookings);
  const monthlyValues = Object.values(statsData.monthly_bookings);

  function createMonthlyChart(type = 'line') {
    const ctx = document.getElementById('monthlyBookingsChart');
    if (!ctx) return;

    if (monthlyChart) monthlyChart.destroy();

    monthlyChart = new Chart(ctx, {
      type: type,
      data: {
        labels: monthlyLabels,
        datasets: [{
          label: 'Số đơn đặt',
          data: monthlyValues,
          backgroundColor: type === 'bar' ? colors.primary : 'rgba(99, 102, 241, 0.1)',
          borderColor: colors.primary,
          borderWidth: 2,
          tension: 0.4,
          fill: type === 'line',
        }]
      },
      options: {
        ...commonOptions,
        scales: {
          y: {
            beginAtZero: true,
            ticks: { stepSize: 1 }
          }
        }
      }
    });
  }

  // 3. Biểu đồ trạng thái đơn đặt
  let statusChart = null;
  const statusLabels = ['Chờ xác nhận', 'Đã xác nhận', 'Hoàn thành', 'Đã hủy'];
  const statusValues = [
    statsData.booking_status_breakdown.pending || 0,
    statsData.booking_status_breakdown.confirmed || 0,
    statsData.booking_status_breakdown.completed || 0,
    statsData.booking_status_breakdown.cancelled || 0,
  ];

  function createStatusChart(type = 'pie') {
    const ctx = document.getElementById('bookingStatusChart');
    if (!ctx) return;

    if (statusChart) statusChart.destroy();

    const isBarChart = type === 'bar';
    
    statusChart = new Chart(ctx, {
      type: type,
      data: {
        labels: statusLabels,
        datasets: [{
          label: 'Số lượng',
          data: statusValues,
          backgroundColor: isBarChart 
            ? [colors.warning, colors.success, colors.info, colors.danger]
            : [colors.warning, colors.success, colors.info, colors.danger],
          borderColor: '#fff',
          borderWidth: 2,
        }]
      },
      options: {
        ...commonOptions,
        ...(isBarChart && {
          scales: {
            y: {
              beginAtZero: true,
              ticks: { stepSize: 1 }
            }
          }
        })
      }
    });
  }

  // 4. Biểu đồ phân bố vai trò người dùng
  let roleChart = null;
  const roleLabels = ['Admin', 'Worker', 'Customer'];
  const roleValues = [
    statsData.user_role_distribution.admin || 0,
    statsData.user_role_distribution.worker || 0,
    statsData.user_role_distribution.customer || 0,
  ];

  function createRoleChart(type = 'doughnut') {
    const ctx = document.getElementById('userRoleChart');
    if (!ctx) return;

    if (roleChart) roleChart.destroy();

    const isBarChart = type === 'bar';

    roleChart = new Chart(ctx, {
      type: type,
      data: {
        labels: roleLabels,
        datasets: [{
          label: 'Số lượng',
          data: roleValues,
          backgroundColor: isBarChart 
            ? [colors.danger, colors.primary, colors.success]
            : [colors.danger, colors.primary, colors.success],
          borderColor: '#fff',
          borderWidth: 2,
        }]
      },
      options: {
        ...commonOptions,
        ...(isBarChart && {
          scales: {
            y: {
              beginAtZero: true,
              ticks: { stepSize: 1 }
            }
          }
        })
      }
    });
  }

  // Khởi tạo biểu đồ
  createRevenueChart('bar');
  createMonthlyChart('line');
  createStatusChart('pie');
  createRoleChart('doughnut');

  // Xử lý thay đổi loại biểu đồ
  document.getElementById('revenueChartType')?.addEventListener('change', (e) => {
    createRevenueChart(e.target.value);
  });

  document.getElementById('lineChartType')?.addEventListener('change', (e) => {
    createMonthlyChart(e.target.value);
  });

  document.getElementById('statusChartType')?.addEventListener('change', (e) => {
    createStatusChart(e.target.value);
  });

  document.getElementById('roleChartType')?.addEventListener('change', (e) => {
    createRoleChart(e.target.value);
  });

  // Xử lý làm mới
  document.getElementById('refreshBtn')?.addEventListener('click', () => {
    location.reload();
  });

  // Thu gọn / xổ ra theo từng mục
  document.querySelectorAll('.stats-toggle-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      const targetId = btn.getAttribute('data-target');
      const target = targetId ? document.getElementById(targetId) : null;
      if (!target) return;

      const isExpanded = btn.getAttribute('aria-expanded') === 'true';

      if (isExpanded) {
        target.classList.add('is-collapsed');
        window.setTimeout(() => {
          if (target.classList.contains('is-collapsed')) {
            target.classList.add('is-hidden');
          }
        }, 360);

        btn.textContent = 'Mở rộng';
        btn.setAttribute('aria-expanded', 'false');
        return;
      }

      target.classList.remove('is-hidden');
      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          target.classList.remove('is-collapsed');
        });
      });

      btn.textContent = 'Thu gọn';
      btn.setAttribute('aria-expanded', 'true');

      if (targetId === 'chartsGroup') {
        setTimeout(() => {
          revenueChart?.resize();
          monthlyChart?.resize();
          statusChart?.resize();
          roleChart?.resize();
        }, 220);
      }
    });
  });

  // Xử lý xuất PDF
  document.getElementById('exportPdfBtn')?.addEventListener('click', async () => {
    const btn = document.getElementById('exportPdfBtn');
    if (!btn) return;

    btn.disabled = true;
    btn.textContent = '⏳ Đang xử lý...';

    try {
      const { jsPDF } = window.jspdf;
      const pdf = new jsPDF('p', 'mm', 'a4');
      const pageWidth = pdf.internal.pageSize.getWidth();
      const pageHeight = pdf.internal.pageSize.getHeight();
      const margin = 15;

      // Tiêu đề
      pdf.setFontSize(20);
      pdf.setTextColor(99, 102, 241);
      pdf.text('BÁO CÁO THỐNG KÊ HỆ THỐNG', pageWidth / 2, margin, { align: 'center' });
      
      pdf.setFontSize(12);
      pdf.setTextColor(100);
      pdf.text('Ngày xuất: ' + new Date().toLocaleDateString('vi-VN'), pageWidth / 2, margin + 7, { align: 'center' });

      let yOffset = margin + 20;

      // Thống kê tổng quan
      pdf.setFontSize(14);
      pdf.setTextColor(0);
      pdf.text('Tổng quan:', margin, yOffset);
      yOffset += 8;

      pdf.setFontSize(11);
      const overview = [
        `Dịch vụ: ${statsData.service_count}`,
        `Đơn đặt: ${statsData.booking_count}`,
        `Người dùng: ${statsData.user_count}`,
        `Tin nhắn: ${statsData.contact_count}`,
      ];
      overview.forEach(text => {
        pdf.text('• ' + text, margin + 5, yOffset);
        yOffset += 6;
      });

      yOffset += 5;

      // Thêm thống kê doanh thu
      pdf.setFontSize(11);
      const revenueStats = [
        `Tổng doanh thu: ${formatCurrency(statsData.total_revenue)}`,
        `Giá trị đơn TB: ${formatCurrency(statsData.average_order_value)}`,
        `Tỷ lệ chuyển đổi: ${statsData.conversion_rate}%`,
        `Tỷ lệ hoàn thành: ${statsData.completion_rate}%`,
      ];
      revenueStats.forEach(text => {
        pdf.text('• ' + text, margin + 5, yOffset);
        yOffset += 6;
      });

      yOffset += 10;

      // Chụp biểu đồ Doanh thu
      const canvasRevenue = document.getElementById('monthlyRevenueChart');
      if (canvasRevenue) {
        if (yOffset + 80 > pageHeight - margin) {
          pdf.addPage();
          yOffset = margin;
        }

        pdf.setFontSize(14);
        pdf.text('Doanh thu theo tháng:', margin, yOffset);
        yOffset += 8;
        
        const imgDataRevenue = canvasRevenue.toDataURL('image/png');
        const imgWidth = pageWidth - 2 * margin;
        const imgHeight = (canvasRevenue.height / canvasRevenue.width) * imgWidth;
        
        if (yOffset + imgHeight > pageHeight - margin) {
          pdf.addPage();
          yOffset = margin;
        }
        
        pdf.addImage(imgDataRevenue, 'PNG', margin, yOffset, imgWidth, imgHeight);
        yOffset += imgHeight + 10;
      }

      // Chụp biểu đồ Monthly Bookings
      const canvas1 = document.getElementById('monthlyBookingsChart');
      if (canvas1) {
        pdf.setFontSize(14);
        pdf.text('Xu hướng đơn đặt:', margin, yOffset);
        yOffset += 8;
        
        const imgData1 = canvas1.toDataURL('image/png');
        const imgWidth = pageWidth - 2 * margin;
        const imgHeight = (canvas1.height / canvas1.width) * imgWidth;
        
        if (yOffset + imgHeight > pageHeight - margin) {
          pdf.addPage();
          yOffset = margin;
        }
        
        pdf.addImage(imgData1, 'PNG', margin, yOffset, imgWidth, imgHeight);
        yOffset += imgHeight + 10;
      }

      // Chụp biểu đồ Status
      const canvas2 = document.getElementById('bookingStatusChart');
      if (canvas2) {
        if (yOffset + 80 > pageHeight - margin) {
          pdf.addPage();
          yOffset = margin;
        }

        pdf.setFontSize(14);
        pdf.text('Trạng thái đơn đặt:', margin, yOffset);
        yOffset += 8;
        
        const imgData2 = canvas2.toDataURL('image/png');
        const imgWidth = (pageWidth - 2 * margin) * 0.6;
        const imgHeight = (canvas2.height / canvas2.width) * imgWidth;
        
        pdf.addImage(imgData2, 'PNG', margin + (pageWidth - 2*margin - imgWidth)/2, yOffset, imgWidth, imgHeight);
        yOffset += imgHeight + 10;
      }

      // Chụp biểu đồ User Role
      const canvas3 = document.getElementById('userRoleChart');
      if (canvas3) {
        if (yOffset + 80 > pageHeight - margin) {
          pdf.addPage();
          yOffset = margin;
        }

        pdf.setFontSize(14);
        pdf.text('Phân bố người dùng:', margin, yOffset);
        yOffset += 8;
        
        const imgData3 = canvas3.toDataURL('image/png');
        const imgWidth = (pageWidth - 2 * margin) * 0.6;
        const imgHeight = (canvas3.height / canvas3.width) * imgWidth;
        
        pdf.addImage(imgData3, 'PNG', margin + (pageWidth - 2*margin - imgWidth)/2, yOffset, imgWidth, imgHeight);
      }

      // Lưu PDF
      const fileName = `Bao-cao-thong-ke-${new Date().toISOString().split('T')[0]}.pdf`;
      pdf.save(fileName);

      btn.textContent = '✅ Tải xuống thành công!';
      setTimeout(() => {
        btn.disabled = false;
        btn.textContent = '📥 Tải xuống PDF';
      }, 2000);
    } catch (error) {
      console.error('Lỗi khi xuất PDF:', error);
      alert('Có lỗi xảy ra khi xuất PDF. Vui lòng thử lại.');
      btn.disabled = false;
      btn.textContent = '📥 Tải xuống PDF';
    }
  });
})();
</script>

<style>
.admin-stats {
  max-width: 1200px;
  margin: 0 auto 70px;
  padding: 0 16px;
  display: grid;
  gap: 16px;
}

.home-hero {
  background: linear-gradient(135deg, #2eaf7d 0%, #43c59e 50%, #8cdf94 100%);
  border-radius: 20px;
  padding: 50px 40px;
  color: #fff;
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(46, 175, 125, 0.3);
  animation: slideInDown 0.6s ease-out;
}

.home-hero::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
  animation: rotate 20s linear infinite;
}

.home-hero .home-kicker {
  position: relative;
  z-index: 1;
  color: #fff;
  animation: fadeIn 0.5s ease-out;
}

.home-hero h1 {
  font-size: 2.5rem;
  margin: 0 0 10px 0;
  font-weight: 700;
  position: relative;
  z-index: 1;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  animation: fadeInDown 0.6s ease-out 0.1s both;
}

.home-hero p {
  font-size: 1.1rem;
  margin: 0;
  opacity: 0.95;
  position: relative;
  z-index: 1;
  animation: fadeInUp 0.6s ease-out 0.2s both;
}

.hero-actions {
  position: relative;
  z-index: 1;
  animation: fadeInUp 0.6s ease-out 0.3s both;
  margin-bottom: 4px;
}

.stats-section-gap {
  margin-top: 20px;
}

.stats-group {
  margin-top: 0;
  background: rgba(255, 255, 255, 0.72);
  border: 1px solid #d8e6ef;
  border-radius: 16px;
  padding: 16px;
  box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
}

.stats-group-head {
  position: relative;
  margin: 0;
  min-height: 40px;
  padding-bottom: 10px;
  border-bottom: 1px dashed #dce8f1;
}

.stats-group-head-overview {
  /* Keep class for backward compatibility with existing markup. */
}

.stats-group-head h2 {
  position: absolute;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  width: max-content;
  max-width: calc(100% - 150px);
  margin: 0;
  font-size: 1.2rem;
  color: #0f172a;
  text-align: center;
}

.stats-group-head p {
  margin: 44px 0 0;
  color: #475569;
  font-size: 0.92rem;
  text-align: center;
}

.stats-toggle-btn {
  position: absolute;
  right: 0;
  top: 0;
  border: 1px solid #cfe3d9;
  background: #ffffff;
  color: #1f3b31;
  border-radius: 10px;
  padding: 8px 14px;
  font-size: 0.88rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.stats-toggle-btn:hover {
  border-color: #8ecfb3;
  background: #f2fbf7;
}

.stats-group-content {
  margin-top: 12px;
  overflow: hidden;
  opacity: 1;
  transform: scaleX(1);
  transform-origin: left center;
  transition: transform 0.35s ease, opacity 0.25s ease;
}

.stats-group-content.is-collapsed {
  transform: scaleX(0);
  opacity: 0;
}

.stats-group-content.is-hidden {
  display: none;
}

.home-stats {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 12px;
}

.charts-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 12px;
}

.home-stats .stat-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  padding: 14px;
  min-height: 88px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 4px;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
  transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
}

.home-stats .stat-card strong {
  font-size: 1.35rem;
  line-height: 1.25;
  color: #0f172a;
}

.home-stats .stat-card span {
  font-size: 0.93rem;
  color: #475569;
  font-weight: 500;
}

#statsOverview .stat-card {
  background: linear-gradient(135deg, #f8fffb 0%, #f3f9ff 100%);
  border-color: #dbe7f3;
}

#statsOverview .stat-card strong {
  color: #1e3a8a;
}

.home-stats .stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
  border-color: #cbd5e1;
}

.chart-section {
  background: #fff;
  border-radius: 12px;
  padding: 16px;
  margin: 0;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  border: 1px solid #e7eef5;
}

.chart-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
  flex-wrap: wrap;
  gap: 12px;
}

.chart-header h2 {
  margin: 0;
  font-size: 1.25rem;
  color: #1f2937;
}

.chart-controls {
  display: flex;
  gap: 8px;
  align-items: center;
}

.chart-select {
  padding: 8px 12px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  font-size: 0.9rem;
  background: #fff;
  cursor: pointer;
  transition: all 0.2s;
}

.chart-select:hover {
  border-color: #6366f1;
}

.chart-select:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.chart-container {
  position: relative;
  height: 320px;
  max-height: 400px;
}

.chart-container canvas {
  max-height: 100%;
}

.stats-table-container {
  overflow-x: auto;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.stats-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
}

.stats-table thead {
  background: #f9fafb;
}

.stats-table th {
  padding: 12px 16px;
  text-align: left;
  font-weight: 600;
  color: #374151;
  border-bottom: 2px solid #e5e7eb;
}

.stats-table td {
  padding: 12px 16px;
  border-bottom: 1px solid #f3f4f6;
  color: #6b7280;
}

.stats-table tbody tr:hover {
  background: #f9fafb;
}

.stats-table tbody tr:last-child td {
  border-bottom: none;
}

.divider-row td {
  padding: 4px !important;
  background: #f3f4f6 !important;
  border-bottom: 2px solid #e5e7eb !important;
}

.highlight-row {
  background: #f0fdf4 !important;
  font-weight: 500;
}

.highlight-row td {
  color: #047857 !important;
}

.highlight-row:hover {
  background: #dcfce7 !important;
}

/* Revenue stats cards */
.revenue-stats .stat-card {
  border: 2px solid transparent;
  transition: all 0.3s ease;
}

.stat-revenue {
  background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
  border-color: #10b981;
}

.stat-revenue strong {
  color: #047857;
}

.stat-aov {
  background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
  border-color: #3b82f6;
}

.stat-aov strong {
  color: #1e40af;
}

.stat-conversion {
  background: linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%);
  border-color: #8b5cf6;
}

.stat-conversion strong {
  color: #6d28d9;
}

.stat-completion {
  background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
  border-color: #f59e0b;
}

.stat-completion strong {
  color: #b45309;
}

.revenue-stats .stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

@keyframes slideInDown {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 768px) {
  .stats-group {
    padding: 14px;
  }

  .home-stats {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .charts-grid {
    grid-template-columns: 1fr;
  }

  .chart-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .chart-container {
    height: 280px;
  }
}

@media (max-width: 520px) {
  .stats-group-head {
    min-height: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
  }

  .stats-group-head h2,
  .stats-group-head p,
  .stats-toggle-btn {
    position: static;
    transform: none;
    max-width: 100%;
  }

  .stats-group-head p {
    margin-top: 0;
  }

  .home-stats {
    grid-template-columns: 1fr;
  }
}
</style>