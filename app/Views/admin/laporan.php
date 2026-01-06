<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Penjualan - Warung Bakso Pak Farrel</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.28/dist/jspdf.plugin.autotable.min.js"></script>
  <style>
    .navbar-active { background: rgba(255,255,255,0.25) !important; font-weight: bold; border-radius: 8px; }
    #toast { position: fixed; bottom: 30px; right: 30px; background: #10b981; color: white; padding: 16px 32px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 9999; font-weight: bold; opacity: 0; transform: translateY(20px); transition: all 0.4s ease; }
    #toast.show { opacity: 1; transform: translateY(0); }
    #toast.error { background: #ef4444; }
    .pagination-btn { @apply px-5 py-3 rounded-xl font-bold transition transform hover:scale-105; }
    .pagination-btn.active { @apply bg-blue-600 text-white; }
    .pagination-btn:not(.active) { @apply bg-gray-200 hover:bg-gray-300 text-gray-700; }
    .pagination-btn:disabled { @apply bg-gray-100 text-gray-400 cursor-not-allowed; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">

  <!-- NAVIGASI — SAMA PERSIS DENGAN SEMUA HALAMAN -->
  <nav class="bg-blue-700 text-white shadow-xl">
    <div class="container mx-auto px-4 py-4">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Warung Bakso Pak Farrel</h1>
        <div class="hidden md:flex items-center space-x-1">
          <a href="dashboard.php"   class="px-5 py-3 rounded-lg transition">Dasbor</a>
          <a href="transaksi.php"   class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Transaksi</a>
          <a href="produk.php"      class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Produk</a>
          <a href="pengguna.php"    class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Pengguna</a>
          <a href="stok.php"        class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Stok</a>
          <a href="laporan.php"     class="px-5 py-3 rounded-lg transition navbar-active">Laporan</a>
          <a href="pengaturan.php"  class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Pengaturan</a>
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
        <a href="transaksi.php"   class="block px-5 py-3 rounded-lg hover:bg-blue-600">Transaksi</a>
        <a href="produk.php"      class="block px-5 py-3 rounded-lg hover:bg-blue-600">Produk</a>
        <a href="pengguna.php"    class="block px-5 py-3 rounded-lg hover:bg-blue-600">Pengguna</a>
        <a href="stok.php"        class="block px-5 py-3 rounded-lg hover:bg-blue-600">Stok</a>
        <a href="laporan.php"     class="block px-5 py-3 rounded-lg bg-blue-800 navbar-active">Laporan</a>
        <a href="pengaturan.php"  class="block px-5 py-3 rounded-lg hover:bg-blue-600">Pengaturan</a>
        <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 px-5 py-3 rounded-lg font-bold">Keluar</button>
      </div>
    </div>
  </nav>

  <div class="container mx-auto px-4 py-8 max-w-7xl">
    <h2 class="text-4xl font-bold text-gray-800 mb-10 text-center">Laporan Penjualan</h2>

    <!-- FILTER & KONTROL -->
    <div class="bg-white p-8 rounded-2xl shadow-xl mb-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div>
          <label class="block text-lg font-semibold mb-3 text-gray-700">Tipe Laporan</label>
          <select id="report-type" class="w-full px-5 py-4 border-2 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition text-lg">
            <option value="daily">Harian (Hari Ini)</option>
            <option value="weekly">Mingguan</option>
            <option value="monthly">Bulanan</option>
            <option value="yearly">Tahunan</option>
            <option value="custom">Rentang Tanggal</option>
          </select>
        </div>
        <div id="custom-range" class="hidden lg:col-span-2">
          <label class="block text-lg font-semibold mb-3 text-gray-700">Rentang Tanggal</label>
          <div class="grid grid-cols-2 gap-4">
            <input type="date" id="date-start" class="px-5 py-4 border-2 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition text-lg">
            <input type="date" id="date-end" class="px-5 py-4 border-2 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition text-lg">
          </div>
        </div>
        <div>
          <label class="block text-lg font-semibold mb-3 text-gray-700">Urutkan Berdasarkan</label>
          <select id="sort-by" class="w-full px-5 py-4 border-2 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition text-lg">
            <option value="date-desc">Tanggal (Terbaru)</option>
            <option value="date-asc">Tanggal (Terlama)</option>
            <option value="total-desc">Total (Terbesar)</option>
            <option value="total-asc">Total (Terkecil)</option>
          </select>
        </div>
        <div class="flex flex-col justify-end gap-3">
          <button onclick="generateReport()" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-5 rounded-xl text-xl font-bold shadow-xl transition transform hover:scale-105">
            Buat Laporan
          </button>
          <div class="grid grid-cols-2 gap-3">
            <button onclick="exportCSV()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg transition">
              CSV
            </button>
            <button onclick="exportPDF()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg transition">
              PDF
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- RINGKASAN METRIK -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
      <div class="bg-gradient-to-br from-blue-500 to-blue-700 text-white p-8 rounded-2xl shadow-2xl transform hover:scale-105 transition">
        <h3 class="text-xl font-bold mb-3">Total Transaksi</h3>
        <p id="summary-transactions" class="text-4xl font-extrabold">0</p>
      </div>
      <div class="bg-gradient-to-br from-green-500 to-green-700 text-white p-8 rounded-2xl shadow-2xl transform hover:scale-105 transition">
        <h3 class="text-xl font-bold mb-3">Total Pendapatan</h3>
        <p id="summary-revenue" class="text-4xl font-extrabold">Rp 0</p>
      </div>
      <div class="bg-gradient-to-br from-purple-500 to-purple-700 text-white p-8 rounded-2xl shadow-2xl transform hover:scale-105 transition">
        <h3 class="text-xl font-bold mb-3">Rata-rata Transaksi</h3>
        <p id="summary-average" class="text-4xl font-extrabold">Rp 0</p>
      </div>
      <div class="bg-gradient-to-br from-yellow-500 to-red-600 text-white p-8 rounded-2xl shadow-2xl transform hover:scale-105 transition">
        <h3 class="text-xl font-bold mb-3">Produk Terlaris</h3>
        <p id="summary-top-product" class="text-2xl font-extrabold">-</p>
      </div>
    </div>

    <!-- GRAFIK PENJUALAN -->
    <div class="bg-white p-8 rounded-2xl shadow-2xl mb-10">
      <h3 class="text-2xl font-bold mb-6 text-gray-800">Grafik Pendapatan Harian</h3>
      <div class="h-96">
        <canvas id="sales-chart"></canvas>
      </div>
    </div>

    <!-- TABEL DETAIL DENGAN PAGINATION -->
    <div class="bg-white p-8 rounded-2xl shadow-2xl">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-gray-800">Detail Transaksi</h3>
        <button onclick="toggleAllDetails()" class="text-blue-600 hover:underline font-bold">Tampilkan/Sembunyikan Semua Detail</button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
            <tr>
              <th class="p-6 font-bold text-lg rounded-tl-xl">ID</th>
              <th class="p-6 font-bold text-lg">Tanggal & Waktu</th>
              <th class="p-6 font-bold text-lg">Kasir</th>
              <!-- <th class="p-6 font-bold text-lg text-right">Subtotal</th> -->
              <th class="p-6 font-bold text-lg text-center">Total</th>
              <th class="p-6 font-bold text-lg text-center">Aksi</th>
            </tr>
          </thead>
          <tbody id="report-table"></tbody>
        </table>
      </div>

      <!-- PAGINATION -->
      <div class="mt-8 flex justify-center items-center gap-4" id="pagination-container">
        <button onclick="changePage(-1)" id="prev-btn" class="pagination-btn" disabled>Sebelumnya</button>
        <div id="page-numbers" class="flex gap-2"></div>
        <button onclick="changePage(1)" id="next-btn" class="pagination-btn">Berikutnya</button>
      </div>
      <div class="text-center mt-4 text-gray-600 font-semibold">
        Menampilkan <span id="showing-start">1</span>-<span id="showing-end">10</span> dari <span id="total-items">0</span> transaksi
      </div>
    </div>
  </div>

  <div id="toast">Laporan berhasil dibuat!</div>

  <!-- Firebase Init -->
  <script type="module" src="../../assets/js/firebase-init.js"></script>

  <!-- LOGIC LAPORAN CANGGIH + PAGINATION — TETAP 100% SAMA PERSIS -->
  <script type="module">
    import { ref, get, onValue } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-database.js";

    let chartInstance = null;
    let allTransactions = [];
    let filteredTransactions = [];
    let currentPage = 1;
    const itemsPerPage = 10;

    const wait = setInterval(() => {
      if (window.db && window.addActivityLog) {
        clearInterval(wait);
        initLaporan();
      }
    }, 50);

    function initLaporan() {
      const user = JSON.parse(localStorage.getItem('currentUser') || 'null');
      if (!user || user.role !== 'admin') location.href = '../../index.php';

      document.querySelectorAll('nav a').forEach(a => {
        a.classList.toggle('navbar-active', a.href.includes('laporan.php'));
      });

      document.getElementById('report-type').addEventListener('change', toggleCustomRange);
      document.getElementById('sort-by').addEventListener('change', generateReport);
      document.getElementById('date-start').addEventListener('change', generateReport);
      document.getElementById('date-end').addEventListener('change', generateReport);

      loadAllData();
      setupRealtime();
    }

    async function loadAllData() {
      const [transSnap, userSnap, settingsSnap] = await Promise.all([
        get(ref(window.db, 'transactions')),
        get(ref(window.db, 'users')),
        get(ref(window.db, 'settings'))
      ]);

      allTransactions = transSnap.val() ? Object.values(transSnap.val()) : [];
      window.allUsers = userSnap.val() || {};
      window.settings = settingsSnap.val() || { taxRate: 11, currency: 'IDR' };

      toggleCustomRange();
      generateReport();
    }

    function setupRealtime() {
      onValue(ref(window.db, 'transactions'), () => loadAllData());
    }

    function toggleCustomRange() {
      const type = document.getElementById('report-type').value;
      const customDiv = document.getElementById('custom-range');
      customDiv.classList.toggle('hidden', type !== 'custom');
      if (type === 'custom') {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date-start').value = today;
        document.getElementById('date-end').value = today;
      }
      generateReport();
    }

    window.generateReport = () => {
      const type = document.getElementById('report-type').value;
      const sortBy = document.getElementById('sort-by').value;
      let start, end;

      const now = new Date();
      if (type === 'custom') {
        const s = document.getElementById('date-start').value;
        const e = document.getElementById('date-end').value;
        if (!s || !e) return showToast('Pilih tanggal!', 'error');
        start = new Date(s);
        end = new Date(e);
        end.setHours(23, 59, 59);
      } else {
        if (type === 'daily') {
          start = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        } else if (type === 'weekly') {
          start = new Date(now); start.setDate(now.getDate() - now.getDay() + 1);
        } else if (type === 'monthly') {
          start = new Date(now.getFullYear(), now.getMonth(), 1);
        } else if (type === 'yearly') {
          start = new Date(now.getFullYear(), 0, 1);
        }
        end = now;
      }

      filteredTransactions = allTransactions.filter(t => {
        const d = new Date(t.date);
        return d >= start && d <= end;
      });

      filteredTransactions.sort((a, b) => {
        if (sortBy.includes('date')) return sortBy === 'date-desc' ? new Date(b.date) - new Date(a.date) : new Date(a.date) - new Date(b.date);
        return sortBy === 'total-desc' ? b.total - a.total : a.total - b.total;
      });

      currentPage = 1;
      renderSummary();
      renderChart();
      renderPaginatedTable();
    };

    function renderSummary() {
      const totalTrans = filteredTransactions.length;
      const totalRev = filteredTransactions.reduce((sum, t) => sum + t.total, 0);
      const avgTrans = totalTrans > 0 ? totalRev / totalTrans : 0;

      const productCount = {};
      filteredTransactions.forEach(t => {
        t.items.forEach(i => {
          productCount[i.name] = (productCount[i.name] || 0) + i.quantity;
        });
      });
      const topProduct = Object.entries(productCount).sort((a, b) => b[1] - a[1])[0];

      document.getElementById('summary-transactions').textContent = totalTrans.toLocaleString('id-ID');
      document.getElementById('summary-revenue').textContent = `Rp ${totalRev.toLocaleString('id-ID')}`;
      document.getElementById('summary-average').textContent = `Rp ${Math.round(avgTrans).toLocaleString('id-ID')}`;
      document.getElementById('summary-top-product').textContent = topProduct ? `${topProduct[0]} (${topProduct[1]}x)` : '-';
    }

    function renderChart() {
      const dailyTotals = {};
      filteredTransactions.forEach(t => {
        const date = new Date(t.date).toLocaleDateString('id-ID');
        dailyTotals[date] = (dailyTotals[date] || 0) + t.total;
      });

      const labels = Object.keys(dailyTotals).sort((a, b) => new Date(a.split('/').reverse().join('-')) - new Date(b.split('/').reverse().join('-')));
      const data = labels.map(l => dailyTotals[l]);

      if (chartInstance) chartInstance.destroy();
      chartInstance = new Chart(document.getElementById('sales-chart'), {
        type: 'line',
        data: {
          labels,
          datasets: [{
            label: 'Pendapatan Harian (Rp)',
            data,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 6,
            pointBackgroundColor: '#3b82f6'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { position: 'top', labels: { font: { size: 16 } } } },
          scales: {
            y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } }
          }
        }
      });
    }

    function renderPaginatedTable() {
      const startIdx = (currentPage - 1) * itemsPerPage;
      const endIdx = startIdx + itemsPerPage;
      const pageData = filteredTransactions.slice(startIdx, endIdx);

      const tbody = document.getElementById('report-table');
      tbody.innerHTML = pageData.length ? pageData.map((t, i) => {
        const globalIdx = startIdx + i;
        const kasir = window.allUsers[t.userId]?.username || 'Unknown';
        return `
          <tr class="border-b hover:bg-gray-50 transition">
            <td class="p-6 font-mono text-lg font-bold">#${t.id}</td>
            <td class="p-6 text-lg">${new Date(t.date).toLocaleString('id-ID')}</td>
            <td class="p-6 font-semibold text-blue-600">${kasir}</td>

            <td class="p-6 text-right text-2xl font-extrabold text-green-600">Rp ${t.total.toLocaleString('id-ID')}</td>
            <td class="p-6 text-center">
              <button onclick="toggleDetail(${globalIdx})" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition">
                Detail
              </button>
            </td>
          </tr>
          <tr id="detail-${globalIdx}" class="hidden bg-gradient-to-r from-blue-50 to-indigo-50">
            <td colspan="7" class="p-8">
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 text-lg">
                <div>
                  <h4 class="font-bold text-xl mb-4 text-blue-800">Daftar Item:</h4>
                  ${t.items.map(item => `
                    <div class="flex justify-between py-2 border-b">
                      <span>${item.name} × ${item.quantity}</span>
                      <span class="font-semibold">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</span>
                    </div>
                  `).join('')}
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg">
                  <h4 class="font-bold text-xl mb-4 text-green-800">Ringkasan Pembayaran</h4>
                  <div class="space-y-3 text-lg">
                    <div class="flex justify-between text-2xl font-extrabold text-green-600 pt-4 border-t-4 border-green-600">
                      <span>TOTAL</span><span>Rp ${t.total.toLocaleString('id-ID')}</span>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        `;
      }).join('') : '<tr><td colspan="7" class="text-center py-20 text-gray-500 text-2xl">Tidak ada transaksi</td></tr>';

      updatePagination();
    }

    window.toggleDetail = (idx) => {
      const el = document.getElementById(`detail-${idx}`);
      el.classList.toggle('hidden');
    };

    window.toggleAllDetails = () => {
      const details = document.querySelectorAll('[id^="detail-"]');
      const allHidden = Array.from(details).every(d => d.classList.contains('hidden'));
      details.forEach(d => d.classList.toggle('hidden', !allHidden));
    };

    function updatePagination() {
      const totalItems = filteredTransactions.length;
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
        btn.onclick = () => { currentPage = i; renderPaginatedTable(); };
        pageNumbers.appendChild(btn);
      }

      document.getElementById('prev-btn').disabled = currentPage === 1;
      document.getElementById('next-btn').disabled = currentPage === totalPages;
    }

    window.changePage = (delta) => {
      const newPage = currentPage + delta;
      if (newPage >= 1 && newPage <= Math.ceil(filteredTransactions.length / itemsPerPage)) {
        currentPage = newPage;
        renderPaginatedTable();
      }
    };

    window.exportCSV = () => {
      if (!filteredTransactions.length) return showToast('Tidak ada data!', 'error');
      const rows = filteredTransactions.flatMap(t => 
        t.items.map(item => [
          t.id,
          new Date(t.date).toLocaleString('id-ID'),
          window.allUsers[t.userId]?.username || '-',
          item.name,
          item.quantity,
          item.price,
          item.price * item.quantity,
          t.tax || 0,
          t.total
        ])
      );
      const csv = [['ID', 'Tanggal', 'Kasir', 'Produk', 'Qty', 'Harga', 'Subtotal Item', 'Total Transaksi'], ...rows]
        .map(r => r.join(',')).join('\n');
      download(csv, `laporan-penjualan-${new Date().toISOString().slice(0,10)}.csv`);
    };

    window.exportPDF = () => {
      if (!filteredTransactions.length) return showToast('Tidak ada data!', 'error');

      const { jsPDF } = window.jspdf;
      const doc = new jsPDF('p', 'mm', 'a4');

      // Judul
      doc.setFontSize(20);
      doc.text('Laporan Penjualan - Warung Bakso Pak Farrel', 105, 20, { align: 'center' });

      // Periode
      const selectedOption = document.getElementById('report-type').options[document.getElementById('report-type').selectedIndex];
      doc.setFontSize(12);
      doc.text(`Periode: ${selectedOption.text}`, 105, 30, { align: 'center' });

      // Keterangan mata uang
      doc.setFontSize(10);
      doc.text('Semua nilai dalam Rupiah (Rp)', 14, 38);

      // Data tabel: hanya ID, Tanggal, Kasir, Item, dan TOTAL
      const tableData = filteredTransactions.map(t => [
        t.id,
        new Date(t.date).toLocaleDateString('id-ID'),
        window.allUsers[t.userId]?.username || '-',
        t.items.map(i => `${i.name} × ${i.quantity}`).join('\n'),
        t.total.toLocaleString('id-ID')  // Hanya total, tanpa "Rp " di dalam sel
      ]);

      doc.autoTable({
        head: [['ID Transaksi', 'Tanggal', 'Kasir', 'Item', 'Total (Rp)']],
        body: tableData,
        startY: 45,
        theme: 'grid',
        styles: { 
          fontSize: 10, 
          cellPadding: 5,
          lineHeight: 1.4
        },
        headStyles: { 
          fillColor: [37, 99, 235],  // biru tua
          textColor: [255, 255, 255],
          fontStyle: 'bold'
        },
        columnStyles: {
          0: { cellWidth: 25 },         // ID
          1: { cellWidth: 30 },         // Tanggal
          2: { cellWidth: 35 },         // Kasir
          3: { cellWidth: 70 },         // Item (lebar karena bisa banyak baris)
          4: { cellWidth: 40, halign: 'right', fontStyle: 'bold' }  // Total → rata kanan & tebal
        }
      });

      // Simpan file dengan nama yang lebih informatif
      const period = selectedOption.text.toLowerCase().replace(/\s+/g, '-');
      doc.save(`laporan-penjualan-${period}-${new Date().toISOString().slice(0,10)}.pdf`);
    };

    function download(content, filename) {
      const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a'); a.href = url; a.download = filename; a.click();
      URL.revokeObjectURL(url);
    }

    function showToast(msg, type = 'success') {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.className = type === 'error' ? 'error' : '';
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 4000);
    }

    window.logout = async () => {
      await window.addActivityLog({ activity: 'Logout dari Laporan' });
      localStorage.removeItem('currentUser');
      location.href = '../../index.php';
    };
  </script>
</body>
</html>