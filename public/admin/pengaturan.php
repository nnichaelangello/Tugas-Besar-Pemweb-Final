<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengaturan - Warung Bakso Pak Farrel</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .navbar-active { background: rgba(255,255,255,0.25) !important; font-weight: bold; border-radius: 8px; }
    #toast { position: fixed; bottom: 30px; right: 30px; background: #10b981; color: white; padding: 16px 32px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 9999; font-weight: bold; opacity: 0; transform: translateY(20px); transition: all 0.4s ease; }
    #toast.show { opacity: 1; transform: translateY(0); }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">

  <!-- NAVIGASI — SAMA PERSIS DENGAN SEMUA HALAMAN SEBELUMNYA -->
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
          <a href="laporan.php"     class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Laporan</a>
          <a href="pengaturan.php"  class="px-5 py-3 rounded-lg transition navbar-active">Pengaturan</a>
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
        <a href="laporan.php"     class="block px-5 py-3 rounded-lg hover:bg-blue-600">Laporan</a>
        <a href="pengaturan.php"  class="block px-5 py-3 rounded-lg bg-blue-800 navbar-active">Pengaturan</a>
        <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 px-5 py-3 rounded-lg font-bold">Keluar</button>
      </div>
    </div>
  </nav>

  <div class="container mx-auto px-4 py-8 max-w-5xl">
    <h2 class="text-4xl font-bold text-gray-800 mb-10 text-center">Pengaturan Sistem</h2>

    <!-- PENGATURAN UTAMA -->
    <!-- <div class="bg-white p-10 rounded-2xl shadow-xl mb-10">
      <h3 class="text-2xl font-bold mb-8 text-gray-700">Pengaturan Umum</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
          <label class="block text-lg font-semibold mb-3 text-gray-700">Nama Warung</label>
          <input type="text" id="shop-name" class="w-full px-5 py-4 border-2 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition" placeholder="Warung Bakso Pak Farrel">
        </div> -->
        <!-- <div>
          <label class="block text-lg font-semibold mb-3 text-gray-700">Pajak Penjualan (%)</label>
          <input type="number" id="tax-rate" min="0" max="100" step="0.1" class="w-full px-5 py-4 border-2 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition" placeholder="11">
        </div> -->
        <!-- <div>
          <label class="block text-lg font-semibold mb-3 text-gray-700">Mata Uang</label>
          <select id="currency" class="w-full px-5 py-4 border-2 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition">
            <option value="IDR">Rupiah (Rp)</option>
            <option value="USD">Dollar ($)</option>
          </select>
        </div>
        <div>
          <label class="block text-lg font-semibold mb-3 text-gray-700">Bahasa Tampilan</label>
          <select id="language" class="w-full px-5 py-4 border-2 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition">
            <option value="id">Bahasa Indonesia</option>
            <option value="en">English</option>
          </select>
        </div>
      </div>

      <div class="mt-10 text-center">
        <button onclick="saveSettings()" class="bg-blue-600 hover:bg-blue-700 text-white px-12 py-5 rounded-xl text-xl font-bold shadow-xl transition transform hover:scale-105">
          Simpan Semua Pengaturan
        </button>
      </div>
    </div> -->

    <!-- BACKUP & RESTORE -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- EKSPOR DATA -->
      <div class="bg-white p-10 rounded-2xl shadow-xl text-center">
        <h3 class="text-2xl font-bold mb-6 text-green-600">Backup Data</h3>
        <p class="text-gray-600 mb-8">Simpan semua data sistem (produk, transaksi, stok, pengguna, dll) dalam format JSON</p>
        <button onclick="exportAllData()" class="bg-green-600 hover:bg-green-700 text-white px-10 py-5 rounded-xl text-lg font-bold shadow-lg transition transform hover:scale-105">
          Ekspor Semua Data ke JSON
        </button>
      </div>

      <!-- IMPOR DATA -->
      <div class="bg-white p-10 rounded-2xl shadow-xl text-center">
        <h3 class="text-2xl font-bold mb-6 text-yellow-600">Restore Data</h3>
        <p class="text-gray-600 mb-8">Pulihkan data dari file backup JSON (hati-hati! ini akan menimpa data saat ini)</p>
        <label class="bg-yellow-600 hover:bg-yellow-700 text-white px-10 py-5 rounded-xl text-lg font-bold shadow-lg cursor-pointer inline-block transition transform hover:scale-105">
          >
          Pilih File JSON untuk Direstore
          <input type="file" id="restore-file" accept=".json" class="hidden" onchange="restoreData(event)">
        </label>
      </div>
    </div>

    <!-- CLEAR DATA (HANYA UNTUK DARURAT) -->
    <div class="mt-12 text-center">
      <details class="bg-red-50 border-2 border-red-300 rounded-2xl p-6 inline-block">
        <summary class="text-xl font-bold text-red-700 cursor-pointer">Hapus Semua Data (Danger Zone)</summary>
        <p class="mt-4 text-gray-700">Tindakan ini akan menghapus SEMUA data secara permanen dan tidak bisa dikembalikan!</p>
        <button onclick="clearAllData()" class="mt-6 bg-red-600 hover:bg-red-700 text-white px-10 py-4 rounded-xl font-bold text-lg shadow-lg transition">
          HAPUS SEMUA DATA SEKARANG
        </button>
      </details>
    </div>
  </div>

  <div id="toast">Pengaturan disimpan!</div>

  <!-- Firebase Init -->
  <script type="module" src="../assets/js/firebase-init.js"></script>

  <!-- LOGIC PENGATURAN — 100% FIREBASE -->
  <script type="module">
    import { ref, get, set, remove } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-database.js";

    const wait = setInterval(() => {
      if (window.db && window.addActivityLog) {
        clearInterval(wait);
        initPengaturan();
      }
    }, 50);

    function initPengaturan() {
      const user = JSON.parse(localStorage.getItem('currentUser') || 'null');
      if (!user || user.role !== 'admin') location.href = '../index.php';

      // Active navbar
      document.querySelectorAll('nav a').forEach(a => {
        a.classList.toggle('navbar-active', a.href.includes('pengaturan.php'));
      });

      loadSettings();
    }

    async function load() {
      const snap = await get(ref(window.db, 'settings'));
      const settings = snap.val() || {
        shopName: 'Warung Bakso Pak Farrel',
        taxRate: 11,
        currency: 'IDR',
        language: 'id'
      };

      document.getElementById('shop-name').value = settings.shopName || '';
      document.getElementById('tax-rate').value = settings.taxRate || 11;
      document.getElementById('currency').value = settings.currency || 'IDR';
      document.getElementById('language').value = settings.language || 'id';
    }

    window.saveSettings = async () => {
      const shopName = document.getElementById('shop-name').value.trim() || 'Warung Bakso Pak Farrel';
      const taxRate = parseFloat(document.getElementById('tax-rate').value) || 0;
      const currency = document.getElementById('currency').value;
      const language = document.getElementById('language').value;

      if (taxRate < 0 || taxRate > 100) {
        showToast('Pajak harus antara 0-100%!', 'error');
        return;
      }

      if (!confirm('Yakin ingin menyimpan semua pengaturan?')) return;

      await set(ref(window.db, 'settings'), {
        shopName,
        taxRate,
        currency,
        language,
        updatedAt: Date.now(),
        updatedBy: JSON.parse(localStorage.getItem('currentUser')).username
      });

      await window.addActivityLog({
        activity: `Pengaturan disimpan: Pajak ${taxRate}%, Nama: ${shopName}`
      });

      showToast('Pengaturan berhasil disimpan!');
    };

    window.exportAllData = async () => {
      if (!confirm('Ekspor semua data sistem sekarang? File akan diunduh otomatis.')) return;

      const paths = ['products', 'transactions', 'users', 'stockHistory', 'activityLog', 'settings'];
      const data = {};

      for (const path of paths) {
        const snap = await get(ref(window.db, path));
        data[path] = snap.val() || {};
      }

      const json = JSON.stringify(data, null, 2);
      const blob = new Blob([json], { type: 'application/json' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `backup-warung-bakso-${new Date().toISOString().slice(0,10)}.json`;
      a.click();
      URL.revokeObjectURL(url);

      await window.addActivityLog({ activity: 'Backup data lengkap diekspor' });
      showToast('Backup berhasil diunduh!');
    };

    window.restoreData = async (event) => {
      const file = event.target.files[0];
      if (!file) return;

      if (!confirm('RESTORE DATA AKAN MENGHAPUS SEMUA DATA SAAT INI dan menggantinya dengan file ini. Yakin lanjut?')) {
        event.target.value = '';
        return;
      }

      const text = await file.text();
      let backup;
      try {
        backup = JSON.parse(text);
      } catch (e) {
        showToast('File JSON tidak valid!', 'error');
        return;
      }

      // Hapus semua data dulu
      const paths = ['products', 'transactions', 'users', 'stockHistory', 'activityLog', 'settings'];
      await Promise.all(paths.map(p => remove(ref(window.db, p))));

      // Upload ulang
      const promises = Object.entries(backup).map(([path, value]) => {
        if (value && typeof value === 'object') {
          return set(ref(window.db, path), value);
        }
      });

      await Promise.all(promises);

      await window.addActivityLog({ activity: 'Data direstore dari backup JSON' });
      showToast('Data berhasil direstore! Halaman akan reload...');
      setTimeout(() => location.reload(), 2000);
    };

    window.clearAllData = async () => {
      if (!confirm('KETIK "HAPUS SEMUA" untuk konfirmasi penghapusan TOTAL data.')) return;
      const confirmText = prompt('Ketik tepat: HAPUS SEMUA');
      if (confirmText !== 'HAPUS SEMUA') {
        showToast('Penghapusan dibatalkan');
        return;
      }

      const paths = ['products', 'transactions', 'users', 'stockHistory', 'activityLog', 'settings'];
      await Promise.all(paths.map(p => remove(ref(window.db, p))));

      await window.addActivityLog({ activity: 'SEMUA DATA DIHAPUS OLEH ADMIN' });
      showToast('Semua data telah dihapus permanen!');
      setTimeout(() => location.href = 'dashboard.php', 2000);
    };

    function showToast(msg, type = 'success') {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.style.background = type === 'error' ? '#ef4444' : '#10b981';
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 4000);
    }

    window.logout = async () => {
      await window.addActivityLog({ activity: 'Logout dari Pengaturan' });
      localStorage.removeItem('currentUser');
      location.href = '../index.php';
    };
  </script>
</body>
</html>