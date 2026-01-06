<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Stok - Warung Bakso Pak Farrel</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
  <style>
    .navbar-active { background: rgba(255,255,255,0.25) !important; font-weight: bold; border-radius: 8px; }
    #toast { position: fixed; bottom: 30px; right: 30px; background: #10b981; color: white; padding: 16px 32px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 9999; font-weight: bold; opacity: 0; transform: translateY(20px); transition: all 0.4s ease; }
    #toast.show { opacity: 1; transform: translateY(0); }
    #toast.error { background: #ef4444; }
    .change-positive { color: #10b981; font-weight: bold; }
    .change-negative { color: #ef4444; font-weight: bold; }
    .pagination-btn { @apply px-4 py-2 rounded-lg font-bold transition; }
    .pagination-btn.active { @apply bg-blue-600 text-white; }
    .pagination-btn:not(.active) { @apply bg-gray-200 hover:bg-gray-300 text-gray-700; }
    .pagination-btn:disabled { @apply bg-gray-100 text-gray-400 cursor-not-allowed; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">

  <!-- NAVIGASI — SAMA DENGAN SEMUA HALAMAN -->
  <nav class="bg-blue-700 text-white shadow-xl">
    <div class="container mx-auto px-4 py-4">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Warung Bakso Pak Farrel</h1>
        <div class="hidden md:flex items-center space-x-1">
          <a href="dashboard.php"   class="px-5 py-3 rounded-lg transition">Dasbor</a>
          <a href="kasir.php"   class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Kasir</a>
          <a href="stok.php"        class="px-5 py-3 rounded-lg transition navbar-active">Stok</a>
          <button onclick="logout()" class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-lg font-bold ml-4 transition">Keluar</button>
        </div>
        <button class="md:hidden" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
      <div id="mobile-menu" class="md:hidden hidden mt-4 space-y-2">
        <a href="dashboard.php"   class="block px-5 py-3 rounded-lg hover:bg-blue-600">Dasbor</a>
        <a href="kasir.php"   class="block px-5 py-3 rounded-lg hover:bg-blue-600">Kasir</a>
        <a href="stok.php"        class="block px-5 py-3 rounded-lg bg-blue-800 navbar-active">Stok</a>
        <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 px-5 py-3 rounded-lg font-bold">Keluar</button>
      </div>
    </div>
  </nav>

  <div class="container mx-auto px-4 py-8 max-w-7xl">
    <h2 class="text-4xl font-bold text-gray-800 mb-8 text-center">Manajemen Stok</h2>

    <!-- DAFTAR STOK SAAT INI -->
    <div class="bg-white p-8 rounded-2xl shadow-xl mb-8">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold">Daftar Stok Saat Ini</h3>
        <button onclick="exportStockCSV()" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-bold shadow-lg transition">
          Ekspor CSV
        </button>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-gray-50">
            <tr>
              <th class="p-4 font-bold">Kode</th>
              <th class="p-4 font-bold">Nama Produk</th>
              <th class="p-4 font-bold">Kategori</th>
              <th class="p-4 font-bold text-center">Stok Saat Ini</th>
              <th class="p-4 font-bold text-center">Status</th>
              <th class="p-4 font-bold text-center">Quick Adjust</th>
            </tr>
          </thead>
          <tbody id="current-stock"></tbody>
        </table>
      </div>
    </div>

    <!-- PENYESUAIAN STOK -->
    <div class="bg-white p-8 rounded-2xl shadow-xl mb-8">
      <h3 class="text-2xl font-bold mb-6">Penyesuaian Stok Manual</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div>
          <label class="block text-sm font-semibold mb-2">Pilih Produk</label>
          <select id="stock-product" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"></select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2">Jumlah (±)</label>
          <input type="number" id="stock-quantity" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="±10">
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2">Alasan</label>
          <select id="stock-reason" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">Pilih Alasan</option>
            <option value="Restock">Restock</option>
            <option value="Retur Pelanggan">Retur Pelanggan</option>
            <option value="Kerusakan">Kerusakan</option>
            <option value="Koreksi">Koreksi</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>
        <div class="flex items-end">
          <button onclick="adjustStock()" class="bg-blue-600 hover:bg-blue-700 text-white w-full px-8 py-3 rounded-lg font-bold shadow-lg transition">
            Sesuaikan Stok
          </button>
        </div>
      </div>
      <div id="custom-reason" class="mt-6 hidden">
        <input type="text" id="custom-reason-input" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Tulis alasan lainnya...">
      </div>
    </div>

    <!-- RIWAYAT PERUBAHAN STOK DENGAN PAGINATION -->
    <div class="bg-white p-8 rounded-2xl shadow-xl mb-8">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold">Riwayat Perubahan Stok</h3>
        <div class="flex gap-4">
          <button onclick="exportStockHistoryCSV()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-bold">CSV</button>
          <button onclick="exportStockHistoryPDF()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-bold">PDF</button>
        </div>
      </div>

      <!-- FILTER TANGGAL -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <input type="date" id="history-start" class="px-4 py-3 border rounded-lg">
        <input type="date" id="history-end" class="px-4 py-3 border rounded-lg">
        <button onclick="applyFilterAndPaginate()" class="bg-gray-700 hover:bg-gray-800 text-white px-8 py-3 rounded-lg font-bold">Filter</button>
      </div>

      <!-- TABEL RIWAYAT -->
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-gray-50">
            <tr>
              <th class="p-4 font-bold">Waktu</th>
              <th class="p-4 font-bold">Produk</th>
              <th class="p-4 font-bold text-center">Perubahan</th>
              <th class="p-4 font-bold">Alasan</th>
              <th class="p-4 font-bold text-center">Stok Setelah</th>
            </tr>
          </thead>
          <tbody id="stock-history"></tbody>
        </table>
      </div>

      <!-- PAGINATION -->
      <div class="mt-6 flex justify-center items-center gap-3" id="pagination-container">
        <button onclick="changePage(-1)" id="prev-btn" class="pagination-btn px-5 py-2 border rounded-lg hover:bg-gray-100 disabled:opacity-50" disabled>‹ Sebelumnya</button>
        <div id="page-numbers" class="flex gap-2"></div>
        <button onclick="changePage(1)" id="next-btn" class="pagination-btn px-5 py-2 border rounded-lg hover:bg-gray-100 disabled:opacity-50">Berikutnya ›</button>
      </div>
      <div class="text-center mt-4 text-gray-600">
        Menampilkan <span id="showing-start">1</span>-<span id="showing-end">10</span> dari <span id="total-items">0</span> riwayat
      </div>
    </div>

    <!-- GRAFIK STOK -->
    <div class="bg-white p-8 rounded-2xl shadow-xl">
      <h3 class="text-2xl font-bold mb-6">Grafik Pergerakan Stok (10 Produk Teratas)</h3>
      <div class="h-96">
        <canvas id="stock-chart"></canvas>
      </div>
    </div>
  </div>

  <div id="toast">Berhasil!</div>

  <!-- Firebase Init -->
  <script type="module" src="../../assets/js/firebase-init.js"></script>

  <!-- LOGIC STOK + PAGINATION — TETAP 100% SAMA PERSIS -->
  <script type="module">
    import { ref, get, set, push, onValue } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-database.js";

    let chartInstance = null;
    let allHistory = [];
    let filteredHistory = [];
    let currentPage = 1;
    const itemsPerPage = 10;

    const wait = setInterval(() => {
      if (window.db && window.addActivityLog) {
        clearInterval(wait);
        initStok();
      }
    }, 50);

    function initStok() {
      const user = JSON.parse(localStorage.getItem('currentUser') || 'null');
      if (!user) {
        location.href = '../../index.php';
        return;
      }

      // Kasir tetap bisa mengakses semua fitur stok ini
      document.querySelectorAll('nav a').forEach(a => {
        a.classList.toggle('navbar-active', a.href.includes('stok.php'));
      });

      loadProductsAndStock();
      setupRealtime();
      document.getElementById('stock-reason').addEventListener('change', toggleCustomReason);
    }

    async function loadProductsAndStock() {
      const [prodSnap, histSnap] = await Promise.all([
        get(ref(window.db, 'products')),
        get(ref(window.db, 'stockHistory'))
      ]);

      const products = prodSnap.val() || {};
      allHistory = histSnap.val() ? Object.values(histSnap.val()).sort((a,b) => (b.timestamp || 0) - (a.timestamp || 0)) : [];
      filteredHistory = [...allHistory];

      populateProductSelect(products);
      renderCurrentStock(products);
      applyFilterAndPaginate();
      renderStockChart(filteredHistory, products);
    }

    function populateProductSelect(products) {
      const select = document.getElementById('stock-product');
      select.innerHTML = '<option value="">Pilih Produk</option>';
      Object.values(products).forEach(p => {
        const opt = new Option(`${p.name} (${p.code}) - Stok: ${p.stock}`, p.code);
        select.add(opt);
      });
    }

    function renderCurrentStock(products) {
      const tbody = document.getElementById('current-stock');
      tbody.innerHTML = Object.values(products).map(p => {
        const status = p.stock === 0 ? 'Habis' : p.stock < 10 ? 'Rendah' : p.stock < 30 ? 'Sedang' : 'Aman';
        const color = p.stock === 0 ? 'text-red-600' : p.stock < 10 ? 'text-orange-600' : p.stock < 30 ? 'text-yellow-600' : 'text-green-600';

        return `<tr class="border-b hover:bg-gray-50 transition">
          <td class="p-4 font-mono">${p.code}</td>
          <td class="p-4 font-semibold text-lg">${p.name}</td>
          <td class="p-4"><span class="px-3 py-1 rounded-full bg-gray-200 text-sm">${p.category || '-'}</span></td>
          <td class="p-4 text-center text-2xl font-bold">${p.stock}</td>
          <td class="p-4 text-center"><span class="font-bold ${color}">${status}</span></td>
          <td class="p-4 text-center space-x-3">
            <button onclick="quickAdjust('${p.code}', 10)" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-bold">+</button>
            <button onclick="quickAdjust('${p.code}', -10)" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-bold">−</button>
          </td>
        </tr>`;
      }).join('') || '<tr><td colspan="6" class="text-center py-16 text-gray-500 text-xl">Belum ada produk</td></tr>';
    }

    window.quickAdjust = (code, change) => {
      adjustStockInternal(code, change, change > 0 ? 'Restock Cepat' : 'Koreksi Cepat');
    };

    window.adjustStock = async () => {
      const code = document.getElementById('stock-product').value;
      const qty = parseInt(document.getElementById('stock-quantity').value);
      let reason = document.getElementById('stock-reason').value;

      if (!code || isNaN(qty) || qty === 0 || !reason) return showToast('Lengkapi semua field!', 'error');
      if (reason === 'Lainnya') {
        reason = document.getElementById('custom-reason-input').value.trim();
        if (!reason) return showToast('Tulis alasan!', 'error');
      }

      const prodSnap = await get(ref(window.db, `products/${code}`));
      const product = prodSnap.val();
      if (!product) return showToast('Produk tidak ditemukan!');

      if (product.stock + qty < 0) return showToast('Stok tidak cukup!', 'error');

      if (!confirm(`Yakin ${qty > 0 ? 'menambah' : 'mengurangi'} stok ${product.name} sebanyak ${Math.abs(qty)}?`)) return;

      adjustStockInternal(code, qty, reason);
    };

    async function adjustStockInternal(code, quantity, reason) {
      const prodSnap = await get(ref(window.db, `products/${code}`));
      const product = prodSnap.val();
      if (!product) return;

      const newStock = product.stock + quantity;
      await set(ref(window.db, `products/${code}/stock`), newStock);

      const historyRef = push(ref(window.db, 'stockHistory'));
      await set(historyRef, {
        code, name: product.name, quantity, reason, afterStock: newStock, timestamp: Date.now()
      });

      await window.addActivityLog({ activity: `Stok ${product.name}: ${quantity > 0 ? '+' : ''}${quantity} → ${newStock} (${reason})` });

      showToast(`Stok ${product.name} berhasil disesuaikan!`);
      document.getElementById('stock-quantity').value = '';
      document.getElementById('stock-reason').value = '';
      toggleCustomReason();
    }

    function toggleCustomReason() {
      const reason = document.getElementById('stock-reason').value;
      document.getElementById('custom-reason').classList.toggle('hidden', reason !== 'Lainnya');
    }

    window.applyFilterAndPaginate = () => {
      const start = document.getElementById('history-start').value;
      const end = document.getElementById('history-end').value;
      
      filteredHistory = allHistory.filter(h => {
        const date = new Date(h.timestamp);
        if (start && date < new Date(start)) return false;
        if (end) {
          const endDate = new Date(end);
          endDate.setHours(23, 59, 59);
          if (date > endDate) return false;
        }
        return true;
      });

      currentPage = 1;
      renderPaginatedHistory();
      renderStockChart(filteredHistory);
    };

    function renderPaginatedHistory() {
      const startIdx = (currentPage - 1) * itemsPerPage;
      const endIdx = startIdx + itemsPerPage;
      const pageData = filteredHistory.slice(startIdx, endIdx);

      const tbody = document.getElementById('stock-history');
      tbody.innerHTML = pageData.length ? pageData.map(h => `
        <tr class="border-b hover:bg-gray-50 transition">
          <td class="p-4 text-sm">${new Date(h.timestamp).toLocaleString('id-ID')}</td>
          <td class="p-4 font-medium">${h.name} (${h.code})</td>
          <td class="p-4 text-center text-xl ${h.quantity > 0 ? 'change-positive' : 'change-negative'}">
            ${h.quantity > 0 ? '+' : ''}${h.quantity}
          </td>
          <td class="p-4">${h.reason}</td>
          <td class="p-4 text-center font-bold text-lg">${h.afterStock}</td>
        </tr>
      `).join('') : '<tr><td colspan="5" class="text-center py-16 text-gray-500 text-xl">Tidak ada riwayat</td></tr>';

      updatePagination();
    }

    function updatePagination() {
      const totalItems = filteredHistory.length;
      const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));

      document.getElementById('total-items').textContent = totalItems;
      document.getElementById('showing-start').textContent = totalItems ? (currentPage - 1) * itemsPerPage + 1 : 0;
      document.getElementById('showing-end').textContent = Math.min(currentPage * itemsPerPage, totalItems);

      const pageNumbers = document.getElementById('page-numbers');
      pageNumbers.innerHTML = '';

      for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'pagination-btn';
        if (i === currentPage) btn.classList.add('active');
        btn.onclick = () => { currentPage = i; renderPaginatedHistory(); };
        pageNumbers.appendChild(btn);
      }

      document.getElementById('prev-btn').disabled = currentPage === 1;
      document.getElementById('next-btn').disabled = currentPage === totalPages;
    }

    window.changePage = (delta) => {
      const newPage = currentPage + delta;
      if (newPage >= 1 && newPage <= Math.ceil(filteredHistory.length / itemsPerPage)) {
        currentPage = newPage;
        renderPaginatedHistory();
      }
    };

    function renderStockChart(history, products = {}) {
      const dataByCode = {};
      history.forEach(h => {
        if (!dataByCode[h.code]) dataByCode[h.code] = { name: h.name, data: [] };
        dataByCode[h.code].data.push({ x: new Date(h.timestamp).toLocaleDateString('id-ID'), y: h.afterStock });
      });

      const datasets = Object.values(dataByCode).slice(0, 10).map((item, i) => ({
        label: item.name,
        data: item.data,
        borderColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#6366f1','#14b8a6','#f97316','#06b6d4'][i],
        backgroundColor: 'transparent',
        tension: 0.3,
        pointRadius: 5
      }));

      if (chartInstance) chartInstance.destroy();
      chartInstance = new Chart(document.getElementById('stock-chart'), {
        type: 'line',
        data: { datasets },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { position: 'top' } },
          scales: { y: { beginAtZero: true } }
        }
      });
    }

    // EKSPOR
    window.exportStockCSV = async () => {
      const snap = await get(ref(window.db, 'products'));
      const products = snap.val() || {};
      const rows = Object.values(products).map(p => `${p.code},${p.name},${p.category||''},${p.stock},${p.stock<10?'Rendah':p.stock<30?'Sedang':'Aman'}`);
      const csv = ['Kode,Nama,Kategori,Stok,Status', ...rows].join('\n');
      download(csv, 'stok-saat-ini.csv');
    };

    window.exportStockHistoryCSV = () => {
      const rows = filteredHistory.map(h => `${new Date(h.timestamp).toLocaleString('id-ID')},${h.name},${h.code},${h.quantity},${h.reason},${h.afterStock}`);
      const csv = ['Waktu,Produk,Kode,Perubahan,Alasan,Stok Setelah', ...rows].join('\n');
      download(csv, 'riwayat-stok.csv');
    };

    window.exportStockHistoryPDF = () => {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
      doc.setFontSize(18);
      doc.text('Riwayat Stok - Warung Bakso Pak Farrel', 105, 20, { align: 'center' });
      filteredHistory.slice(0, 100).forEach((h, i) => {
        const y = 40 + i * 8;
        if (y > 270) { doc.addPage(); }
        doc.setFontSize(9);
        doc.text(`${new Date(h.timestamp).toLocaleString('id-ID')} | ${h.name} (${h.code}) → ${h.quantity > 0 ? '+' : ''}${h.quantity} (${h.reason})`, 10, y);
      });
      doc.save('riwayat-stok.pdf');
    };

    function download(content, filename) {
      const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a'); a.href = url; a.download = filename; a.click();
      URL.revokeObjectURL(url);
    }

    function setupRealtime() {
      onValue(ref(window.db, 'products'), () => loadProductsAndStock());
      onValue(ref(window.db, 'stockHistory'), (snap) => {
        allHistory = snap.val() ? Object.values(snap.val()).sort((a,b) => (b.timestamp||0) - (a.timestamp||0)) : [];
        filteredHistory = [...allHistory];
        applyFilterAndPaginate();
      });
    }

    function showToast(msg, type = 'success') {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.className = type === 'error' ? 'error' : '';
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 4000);
    }

    window.logout = async () => {
      await window.addActivityLog({ activity: 'Logout dari halaman Stok' });
      localStorage.removeItem('currentUser');
      location.href = '../../index.php';
    };
  </script>
</body>
</html>