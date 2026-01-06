<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dasbor - Warung Bakso Pak Farrel</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/luxon@3.4.4/build/global/luxon.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.28/dist/jspdf.plugin.autotable.min.js"></script>
  <style>
    .navbar-active { background: rgba(255,255,255,0.25) !important; font-weight: bold; border-radius: 8px; }
    #toast { position: fixed; bottom: 30px; right: 30px; background: #10b981; color: white; padding: 16px 32px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 9999; font-weight: bold; opacity: 0; transform: translateY(20px); transition: all 0.4s ease; }
    #toast.show { opacity: 1; transform: translateY(0); }
    #toast.error { background: #ef4444; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">

  <!-- NAVIGASI PREMIUM -->
  <nav class="bg-blue-700 text-white shadow-xl">
    <div class="container mx-auto px-4 py-4">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Warung Bakso Pak Farrel</h1>
        <div class="hidden md:flex items-center space-x-1">
          <a href="dashboard.php" class="px-5 py-3 rounded-lg transition navbar-active">Dasbor</a>
          <a href="Kasir.php" class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Kasir</a>
          <a href="stok.php" class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Stok</a>
          <button onclick="logout()" class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-lg font-bold ml-4 transition">Keluar</button>
        </div>
        <button class="md:hidden" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
      <div id="mobile-menu" class="md:hidden hidden mt-4 space-y-2">
        <a href="dashboard.php" class="block px-5 py-3 rounded-lg bg-blue-800 navbar-active">Dasbor</a>
        <a href="Kasir.php" class="block px-5 py-3 rounded-lg hover:bg-blue-600">Kasir</a>
        <a href="stok.php" class="block px-5 py-3 rounded-lg hover:bg-blue-600">Stok</a>
        <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 px-5 py-3 rounded-lg font-bold">Keluar</button>
      </div>
    </div>
  </nav>

  <div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10">
      <div>
        <h2 class="text-4xl font-bold text-gray-800">Selamat Datang, <span id="user-name">Admin</span>!</h2>
        <p class="text-xl text-gray-600 mt-3" id="current-time">Loading...</p>
      </div>
      <div class="flex gap-4 mt-6 md:mt-0">
        <button onclick="refreshDashboard()" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-xl transition transform hover:scale-105">
          Refresh
        </button>
        <button onclick="exportDashboardPDF()" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-xl transition">
          Export PDF
        </button>
      </div>
    </div>

    <!-- KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
      <div class="bg-gradient-to-br from-green-500 to-green-700 text-white p-8 rounded-2xl shadow-2xl transform hover:scale-105 transition">
        <p class="text-xl font-semibold opacity-90">Penjualan Hari Ini</p>
        <p id="daily-sales" class="text-4xl font-extrabold mt-4">Rp 0</p>
        <p id="daily-count" class="text-lg mt-3 opacity-90">0 transaksi</p>
      </div>
      <div class="bg-gradient-to-br from-blue-500 to-blue-700 text-white p-8 rounded-2xl shadow-2xl transform hover:scale-105 transition">
        <p class="text-xl font-semibold opacity-90">Pendapatan Minggu Ini</p>
        <p id="weekly-sales" class="text-4xl font-extrabold mt-4">Rp 0</p>
      </div>
      <div class="bg-gradient-to-br from-purple-500 to-purple-700 text-white p-8 rounded-2xl shadow-2xl transform hover:scale-105 transition">
        <p class="text-xl font-semibold opacity-90">Produk Terjual Bulan Ini</p>
        <p id="monthly-items" class="text-4xl font-extrabold mt-4">0</p>
        <p class="text-lg mt-3 opacity-90">dari <span id="monthly-transactions">0</span> transaksi</p>
      </div>
      <div class="bg-gradient-to-br from-red-500 to-red-700 text-white p-8 rounded-2xl shadow-2xl transform hover:scale-105 transition">
        <p class="text-xl font-semibold opacity-90">Stok Rendah</p>
        <p id="low-stock-count" class="text-6xl font-extrabold mt-4">0</p>
        <p class="text-lg mt-3 opacity-90">dari <span id="total-products">0</span> produk</p>
      </div>
    </div>

    <!-- GRAFIK & TOP PRODUK -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
      <div class="lg:col-span-2 bg-white p-8 rounded-2xl shadow-2xl">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-3xl font-bold text-gray-800">Tren Penjualan</h3>
          <select id="chart-period" onchange="updateSalesChart()" class="px-6 py-3 border-2 border-gray-300 rounded-xl text-lg focus:ring-4 focus:ring-blue-300">
            <option value="7">7 Hari Terakhir</option>
            <option value="30">30 Hari Terakhir</option>
            <option value="90">90 Hari Terakhir</option>
          </select>
        </div>
        <div class="h-96">
          <canvas id="sales-chart"></canvas>
        </div>
      </div>
      <div class="bg-white p-8 rounded-2xl shadow-2xl">
        <h3 class="text-3xl font-bold text-gray-800 mb-8">Top 5 Produk Terlaris</h3>
        <div id="top-products" class="space-y-6"></div>
      </div>
    </div>

    <!-- LOG AKTIVITAS -->
    <!-- <div class="bg-white p-8 rounded-2xl shadow-2xl">
      <h3 class="text-3xl font-bold text-gray-800 mb-8">Aktivitas Terbaru</h3>
      <div class="flex flex-wrap gap-4 mb-8">
        <button onclick="showLogTab('all')" id="tab-all" class="px-8 py-4 rounded-xl font-bold bg-blue-600 text-white shadow-lg">Semua</button>
        <button onclick="showLogTab('login')" id="tab-login" class="px-8 py-4 rounded-xl font-bold bg-gray-200 hover:bg-gray-300 transition">Login/Logout</button>
        <button onclick="showLogTab('sales')" id="tab-sales" class="px-8 py-4 rounded-xl font-bold bg-gray-200 hover:bg-gray-300 transition">Penjualan</button>
        <button onclick="showLogTab('stock')" id="tab-stock" class="px-8 py-4 rounded-xl font-bold bg-gray-200 hover:bg-gray-300 transition">Stok</button>
        <button onclick="showLogTab('system')" id="tab-system" class="px-8 py-4 rounded-xl font-bold bg-gray-200 hover:bg-gray-300 transition">Sistem</button>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <tr>
              <th class="p-6 text-lg font-bold rounded-tl-xl">Waktu</th>
              <th class="p-6 text-lg font-bold">Pengguna</th>
              <th class="p-6 text-lg font-bold rounded-tr-xl">Aktivitas</th>
            </tr>
          </thead>
          <tbody id="activity-table" class="text-gray-800"></tbody>
        </table>
      </div>
      <div class="flex justify-between items-center mt-8">
        <p class="text-lg text-gray-600">Menampilkan <span id="log-range">1-10</span> dari <span id="log-total">0</span> aktivitas</p>
        <div class="flex gap-4">
          <button id="log-prev" onclick="changeLogPage(-1)" class="px-8 py-4 border-2 border-gray-300 rounded-xl font-bold hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition" disabled>← Sebelumnya</button>
          <button id="log-next" onclick="changeLogPage(1)" class="px-8 py-4 border-2 border-gray-300 rounded-xl font-bold hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition" disabled>Berikutnya →</button>
        </div>
      </div>
    </div> -->
  </div>

  <div id="toast">Dashboard berhasil diperbarui!</div>

  <!-- Firebase Init -->
  <script type="module" src="../../assets/js/firebase-init.js"></script>

  <!-- LOGIC DASHBOARD — TETAP 100% SAMA PERSIS -->
  <script type="module">
    import { ref, get, onValue } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-database.js";
    const { DateTime } = luxon;

    let salesChart = null;
    const LOGS_PER_PAGE = 10;
    let currentLogPage = 1;
    let currentLogFilter = 'all';
    let allTransactions = [];
    let allProducts = [];
    let allLogs = [];

    // Tunggu Firebase siap
    const wait = setInterval(() => {
      if (window.db && window.addActivityLog) {
        clearInterval(wait);
        initDashboard();
      }
    }, 100);

    function initDashboard() {
      const user = JSON.parse(localStorage.getItem('currentUser') || 'null');
      if (!user) {
        location.href = '../../index.php';
        return;
      }

      // Kasir tetap bisa melihat dashboard (tidak dibatasi)
      document.getElementById('user-name').textContent = user.username || 'Kasir';
      updateClock();
      setInterval(updateClock, 1000);

      loadAllData();
      setupRealtimeListeners();
    }

    function updateClock() {
      const now = DateTime.now().setLocale('id');
      document.getElementById('current-time').textContent = 
        now.toFormat('cccc, d LLLL yyyy • HH:mm:ss');
    }

    async function loadAllData() {
      try {
        const [transSnap, prodSnap, logSnap] = await Promise.all([
          get(ref(window.db, 'transactions')),
          get(ref(window.db, 'products')),
          get(ref(window.db, 'activityLog'))
        ]);

        allTransactions = transSnap.val() ? Object.values(transSnap.val()) : [];
        allProducts = prodSnap.val() ? Object.values(prodSnap.val()) : [];
        allLogs = logSnap.val() 
          ? Object.values(logSnap.val())
              .filter(log => log.timestamp)
              .sort((a, b) => b.timestamp - a.timestamp)
          : [];

        updateAllDashboard();
        showToast("Dashboard diperbarui otomatis!");
      } catch (err) {
        console.error("Error loading data:", err);
        // showToast("Gagal memuat data!", "error");
      }
    }

    function updateAllDashboard() {
      updateKPI();
      updateSalesChart();
      updateTopProducts();
      updateActivityLog();
    }

    function updateKPI() {
      const now = DateTime.now();
      const todayStart = now.startOf('day');
      const weekStart = now.startOf('week');
      const monthStart = now.startOf('month');

      const todayTx = allTransactions.filter(t => {
        const txDate = DateTime.fromMillis(t.date || t.timestamp || 0);
        return txDate >= todayStart;
      });

      const weekTx = allTransactions.filter(t => {
        const txDate = DateTime.fromMillis(t.date || t.timestamp || 0);
        return txDate >= weekStart;
      });

      const monthTx = allTransactions.filter(t => {
        const txDate = DateTime.fromMillis(t.date || t.timestamp || 0);
        return txDate >= monthStart;
      });

      const totalToday = todayTx.reduce((sum, t) => sum + (t.total || 0), 0);
      const totalWeek = weekTx.reduce((sum, t) => sum + (t.total || 0), 0);

      const monthlyItemsSold = monthTx.reduce((sum, t) => {
        return sum + (t.items || []).reduce((s, i) => s + (i.quantity || i.qty || 0), 0);
      }, 0);

      const lowStock = allProducts.filter(p => (p.stock || 0) < 10).length;

      document.getElementById('daily-sales').textContent = `Rp ${totalToday.toLocaleString('id-ID')}`;
      document.getElementById('daily-count').textContent = `${todayTx.length} transaksi`;
      document.getElementById('weekly-sales').textContent = `Rp ${totalWeek.toLocaleString('id-ID')}`;
      document.getElementById('monthly-items').textContent = monthlyItemsSold.toLocaleString('id-ID');
      document.getElementById('monthly-transactions').textContent = monthTx.length;
      document.getElementById('low-stock-count').textContent = lowStock;
      document.getElementById('total-products').textContent = allProducts.length;
    }

    function updateSalesChart() {
      const days = parseInt(document.getElementById('chart-period').value) || 7;
      const labels = [];
      const data = [];

      for (let i = days - 1; i >= 0; i--) {
        const date = DateTime.now().minus({ days: i }).startOf('day');
        labels.push(date.toFormat('dd MMM'));

        const dayTotal = allTransactions
          .filter(t => {
            const txDate = DateTime.fromMillis(t.date || t.timestamp || 0);
            return txDate.hasSame(date, 'day');
          })
          .reduce((sum, t) => sum + (t.total || 0), 0);

        data.push(dayTotal);
      }

      const ctx = document.getElementById('sales-chart').getContext('2d');
      if (salesChart) salesChart.destroy();

      salesChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels,
          datasets: [{
            label: 'Penjualan (Rp)',
            data,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 6,
            pointBackgroundColor: '#10b981'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: ctx => `Rp ${ctx.parsed.y.toLocaleString('id-ID')}`
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') }
            }
          }
        }
      });
    }

    function updateTopProducts() {
      const salesMap = {};

      allTransactions.forEach(t => {
        (t.items || []).forEach(item => {
          const code = item.code || item.name;
          salesMap[code] = (salesMap[code] || 0) + (item.quantity || item.qty || 0);
        });
      });

      const top5 = Object.entries(salesMap)
        .sort((a, b) => b[1] - a[1])
        .slice(0, 5);

      const container = document.getElementById('top-products');
      if (top5.length === 0) {
        container.innerHTML = '<p class="text-center text-gray-500 text-xl py-10">Belum ada penjualan</p>';
        return;
      }

      container.innerHTML = top5.map(([code, qty], index) => {
        const product = allProducts.find(p => (p.code || p.name) === code) || { name: code, price: 0 };
        const revenue = qty * (product.price || 0);
        return `
          <div class="flex items-center justify-between p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl shadow-lg">
            <div class="flex items-center gap-5">
              <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-white text-2xl font-bold shadow-xl">
                ${index + 1}
              </div>
              <div>
                <p class="text-2xl font-bold text-gray-800">${product.name || code}</p>
                <p class="text-lg text-gray-600">${qty.toLocaleString('id-ID')} terjual</p>
              </div>
            </div>
            <p class="text-1xl font-extrabold text-green-600">Rp ${revenue.toLocaleString('id-ID')}</p>
          </div>
        `;
      }).join('');
    }

    function updateActivityLog() {
      let filtered = allLogs;

      if (currentLogFilter !== 'all') {
        const filters = {
          login: /Login|Logout/i,
          sales: /Transaksi|Bayar|Struk|Retur/i,
          stock: /Stok|ditambah|dikurang|update stok/i,
          system: /Produk|Pengguna|Pengaturan|Tambah|Edit|Hapus/i
        };
        filtered = allLogs.filter(log => filters[currentLogFilter].test(log.activity));
      }

      const start = (currentLogPage - 1) * LOGS_PER_PAGE;
      const end = start + LOGS_PER_PAGE;
      const pageLogs = filtered.slice(start, end);

      const tbody = document.getElementById('activity-table');
      tbody.innerHTML = pageLogs.length ? pageLogs.map(log => {
        const dt = DateTime.fromMillis(log.timestamp);
        const time = dt.toFormat('dd/MM/yyyy HH:mm:ss');
        const [activity, user] = log.activity.split(' - ');
        return `
          <tr class="border-b hover:bg-gray-50 transition">
            <td class="p-6 text-lg">${time}</td>
            <td class="p-6 font-bold text-blue-600">${user || 'Sistem'}</td>
            <td class="p-6 text-lg">${activity || log.activity}</td>
          </tr>
        `;
      }).join('') : '<tr><td colspan="3" class="text-center py-20 text-gray-500 text-2xl">Tidak ada aktivitas</td></tr>';

      document.getElementById('log-total').textContent = filtered.length;
      document.getElementById('log-range').textContent = filtered.length ? `${start + 1}-${Math.min(end, filtered.length)}` : '0';
      document.getElementById('log-prev').disabled = currentLogPage === 1;
      document.getElementById('log-next').disabled = end >= filtered.length;
    }

    window.showLogTab = (tab) => {
      document.querySelectorAll('[id^="tab-"]').forEach(btn => {
        btn.className = "px-8 py-4 rounded-xl font-bold bg-gray-200 hover:bg-gray-300 transition";
      });
      document.getElementById(`tab-${tab}`).className = "px-8 py-4 rounded-xl font-bold bg-blue-600 text-white shadow-lg";
      currentLogFilter = tab;
      currentLogPage = 1;
      updateActivityLog();
    };

    window.changeLogPage = (delta) => {
      currentLogPage += delta;
      updateActivityLog();
    };

    function setupRealtimeListeners() {
      onValue(ref(window.db, 'transactions'), () => loadAllData());
      onValue(ref(window.db, 'products'), () => loadAllData());
      onValue(ref(window.db, 'activityLog'), () => loadAllData());
    }

    window.refreshDashboard = loadAllData;
    window.updateSalesChart = updateSalesChart;

    window.exportDashboardPDF = () => {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
      doc.setFontSize(20);
      doc.text('Dashboard Warung Bakso Pak Farrel', 105, 20, { align: 'center' });
      doc.setFontSize(12);
      doc.text(`Dicetak: ${DateTime.now().toFormat('dd LLLL yyyy HH:mm')}`, 105, 30, { align: 'center' });
      doc.save('dashboard-warung-bakso.pdf');
      showToast("PDF berhasil diekspor!");
    };

    window.logout = async () => {
      const user = JSON.parse(localStorage.getItem('currentUser') || '{}');
      await window.addActivityLog({ 
        activity: `Logout - ${user.username || 'Kasir'}` 
      });
      localStorage.removeItem('currentUser');
      location.href = '../../index.php';
    };

    function showToast(msg, type = 'success') {
      const toast = document.getElementById('toast');
      toast.textContent = msg;
      toast.className = type === 'error' ? 'error' : '';
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 4000);
    }
  </script>
</body>
</html>