<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Produk - Warung Bakso Pak Farrel</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
  <style>
    .navbar-active { background: rgba(255,255,255,0.25) !important; font-weight: bold; border-radius: 8px; }
    #toast { position: fixed; bottom: 30px; right: 30px; background: #10b981; color: white; padding: 16px 32px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 9999; font-weight: bold; opacity: 0; transform: translateY(20px); transition: all 0.4s ease; }
    #toast.show { opacity: 1; transform: translateY(0); }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">

  <!-- NAVIGASI — SAMA PERSIS DENGAN DASHBOARD -->
  <nav class="bg-blue-700 text-white shadow-xl">
    <div class="container mx-auto px-4 py-4">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Warung Bakso Pak Farrel</h1>
        <div class="hidden md:flex items-center space-x-1">
          <a href="dashboard.php"   class="px-5 py-3 rounded-lg transition">Dasbor</a>
          <a href="transaksi.php"   class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Transaksi</a>
          <a href="produk.php"      class="px-5 py-3 rounded-lg transition navbar-active">Produk</a>
          <a href="pengguna.php"    class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Pengguna</a>
          <a href="stok.php"        class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Stok</a>
          <a href="laporan.php"     class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Laporan</a>
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
        <a href="produk.php"      class="block px-5 py-3 rounded-lg bg-blue-800 navbar-active">Produk</a>
        <a href="pengguna.php"    class="block px-5 py-3 rounded-lg hover:bg-blue-600">Pengguna</a>
        <a href="stok.php"        class="block px-5 py-3 rounded-lg hover:bg-blue-600">Stok</a>
        <a href="laporan.php"     class="block px-5 py-3 rounded-lg hover:bg-blue-600">Laporan</a>
        <a href="pengaturan.php"  class="block px-5 py-3 rounded-lg hover:bg-blue-600">Pengaturan</a>
        <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 px-5 py-3 rounded-lg font-bold">Keluar</button>
      </div>
    </div>
  </nav>

  <div class="container mx-auto px-4 py-8 max-w-7xl">
    <h2 class="text-4xl font-bold text-gray-800 mb-8">Manajemen Produk</h2>

    <!-- FORM TAMBAH/EDIT -->
    <div class="bg-white p-8 rounded-2xl shadow-xl mb-8">
      <h3 class="text-2xl font-bold mb-6" id="form-title">Tambah Produk Baru</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div>
          <label class="block text-sm font-semibold mb-2">Kode Produk</label>
          <input type="text" id="product-code" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="BK001" maxlength="10">
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2">Nama Produk</label>
          <input type="text" id="product-name" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Bakso Sapi Spesial">
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2">Harga (Rp)</label>
          <input type="text" id="product-price" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 formatted" placeholder="15.000">
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2">Stok Awal</label>
          <input type="number" id="product-stock" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="50" min="0">
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2">Kategori</label>
          <select id="product-category" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">Pilih Kategori</option>
            <option value="Makanan">Makanan</option>
            <option value="Minuman">Minuman</option>
            <option value="Tambahan">Tambahan</option>
            <option value="Paket">Paket</option>
          </select>
        </div>
        <div class="flex items-end gap-4">
          <button id="save-btn" onclick="saveProduct()" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-bold shadow-lg transition">Simpan</button>
          <button onclick="cancelEdit()" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-bold hidden" id="cancel-btn">Batal</button>
        </div>
      </div>
    </div>

    <!-- FILTER & EKSPOR -->
    <div class="bg-white p-6 rounded-2xl shadow-xl mb-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="flex flex-wrap gap-4">
          <select id="filter-category" class="px-4 py-3 border rounded-lg">
            <option value="">Semua Kategori</option>
            <option value="Makanan">Makanan</option>
            <option value="Minuman">Minuman</option>
            <option value="Tambahan">Tambahan</option>
            <option value="Paket">Paket</option>
          </select>
          <select id="filter-stock" class="px-4 py-3 border rounded-lg">
            <option value="">Semua Stok</option>
            <option value="low">Stok Rendah (&lt;10)</option>
            <option value="medium">Stok Sedang (10-30)</option>
            <option value="high">Stok Aman (&gt;30)</option>
          </select>
          <input type="text" id="search-name" class="px-4 py-3 border rounded-lg flex-1" placeholder="Cari nama produk...">
        </div>
        <div class="flex justify-end gap-4">
          <select id="sort-by" class="px-4 py-3 border rounded-lg">
            <option value="name-asc">Nama (A-Z)</option>
            <option value="name-desc">Nama (Z-A)</option>
            <option value="price-asc">Harga (Rendah)</option>
            <option value="price-desc">Harga (Tinggi)</option>
            <option value="stock-asc">Stok (Rendah)</option>
            <option value="stock-desc">Stok (Tinggi)</option>
          </select>
          <button onclick="exportProductsCSV()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-bold">CSV</button>
          <button onclick="exportProductsPDF()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-bold">PDF</button>
        </div>
      </div>
      <button onclick="applyFilters()" class="bg-gray-700 hover:bg-gray-800 text-white px-8 py-3 rounded-lg font-bold">Terapkan Filter</button>
    </div>

    <!-- TABEL PRODUK -->
    <div class="bg-white p-8 rounded-2xl shadow-xl mb-8">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold">Daftar Produk</h3>
        <p class="text-lg text-gray-600">Total: <span id="total-products" class="font-bold">0</span> produk</p>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-gray-50">
            <tr>
              <th class="p-4 font-bold">Kode</th>
              <th class="p-4 font-bold">Nama</th>
              <th class="p-4 font-bold">Harga</th>
              <th class="p-4 font-bold text-center">Stok</th>
              <th class="p-4 font-bold">Kategori</th>
              <th class="p-4 font-bold text-center">Status</th>
              <th class="p-4 font-bold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody id="product-table"></tbody>
        </table>
      </div>
    </div>

    <!-- GRAFIK -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <div class="bg-white p-8 rounded-2xl shadow-xl">
        <h3 class="text-2xl font-bold mb-6">Distribusi Kategori</h3>
        <div class="h-80"><canvas id="category-chart"></canvas></div>
      </div>
      <div class="bg-white p-8 rounded-2xl shadow-xl">
        <h3 class="text-2xl font-bold mb-6">Total Stok per Kategori</h3>
        <div class="h-80"><canvas id="stock-chart"></canvas></div>
      </div>
    </div>
  </div>

  <div id="toast">Berhasil!</div>

  <!-- Firebase Init -->
  <script type="module" src="../../assets/js/firebase-init.js"></script>

  <!-- LOGIC PRODUK — TETAP 100% SAMA PERSIS -->
  <script type="module">
    import { ref, get, set, push } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-database.js";

    let chartCategory = null;
    let chartStock = null;
    let editMode = false;
    let editCode = null;

    const wait = setInterval(() => {
      if (window.db && window.addActivityLog) {
        clearInterval(wait);
        initProduk();
      }
    }, 50);

    function initProduk() {
      const user = JSON.parse(localStorage.getItem('currentUser') || 'null');
      if (!user || user.role !== 'admin') location.href = '../../index.php';

      document.querySelectorAll('nav a').forEach(a => {
        a.classList.toggle('navbar-active', a.href.includes('produk.php'));
      });

      loadProducts();
      document.getElementById('product-price').addEventListener('input', formatPriceInput);
    }

    async function loadProducts() {
      const snap = await get(ref(window.db, 'products'));
      const products = snap.val() ? Object.values(snap.val()) : [];
      renderTable(products);
      renderCharts(products);
      document.getElementById('total-products').textContent = products.length;
    }

    window.applyFilters = async () => {
      const snap = await get(ref(window.db, 'products'));
      let products = snap.val() ? Object.values(snap.val()) : [];

      const cat = document.getElementById('filter-category').value;
      const stockFilter = document.getElementById('filter-stock').value;
      const search = document.getElementById('search-name').value.toLowerCase();
      const sort = document.getElementById('sort-by').value;

      if (cat) products = products.filter(p => p.category === cat);
      if (stockFilter === 'low') products = products.filter(p => p.stock < 10);
      if (stockFilter === 'medium') products = products.filter(p => p.stock >= 10 && p.stock <= 30);
      if (stockFilter === 'high') products = products.filter(p => p.stock > 30);
      if (search) products = products.filter(p => p.name.toLowerCase().includes(search));

      // Sorting
      products.sort((a, b) => {
        if (sort === 'name-asc') return a.name.localeCompare(b.name);
        if (sort === 'name-desc') return b.name.localeCompare(a.name);
        if (sort === 'price-asc') return a.price - b.price;
        if (sort === 'price-desc') return b.price - a.price;
        if (sort === 'stock-asc') return a.stock - b.stock;
        if (sort === 'stock-desc') return b.stock - a.stock;
        return 0;
      });

      renderTable(products);
    };

    function renderTable(products) {
      const tbody = document.getElementById('product-table');
      tbody.innerHTML = products.map(p => {
        const status = p.stock === 0 ? 'Habis' : p.stock < 10 ? 'Rendah' : p.stock < 30 ? 'Sedang' : 'Aman';
        const color = p.stock === 0 ? 'bg-red-100 text-red-700' : p.stock < 10 ? 'bg-orange-100 text-orange-700' : p.stock < 30 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700';

        return `<tr class="border-b hover:bg-gray-50 transition">
          <td class="p-4 font-mono">${p.code}</td>
          <td class="p-4 font-semibold">${p.name}</td>
          <td class="p-4">Rp ${p.price.toLocaleString('id-ID')}</td>
          <td class="p-4 text-center font-bold">${p.stock}</td>
          <td class="p-4"><span class="px-3 py-1 rounded-full bg-gray-200 text-sm">${p.category}</span></td>
          <td class="p-4 text-center"><span class="px-3 py-1 rounded-full ${color} text-sm font-bold">${status}</span></td>
          <td class="p-4 text-center space-x-3">
            <button onclick="startEdit('${p.code}')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Edit</button>
            <button onclick="deleteProduct('${p.code}')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Hapus</button>
          </td>
        </tr>`;
      }).join('') || '<tr><td colspan="7" class="text-center py-16 text-gray-500 text-xl">Tidak ada produk</td></tr>';
    }

    window.startEdit = (code) => {
      get(ref(window.db, 'products')).then(snap => {
        const products = snap.val() ? Object.values(snap.val()) : [];
        const p = products.find(x => x.code === code);
        if (!p) return;

        editMode = true;
        editCode = code;
        document.getElementById('form-title').textContent = 'Edit Produk';
        document.getElementById('product-code').value = p.code;
        document.getElementById('product-name').value = p.name;
        document.getElementById('product-price').value = p.price.toLocaleString('id-ID');
        document.getElementById('product-stock').value = p.stock;
        document.getElementById('product-category').value = p.category;
        document.getElementById('save-btn').textContent = 'Update';
        document.getElementById('cancel-btn').classList.remove('hidden');
        document.getElementById('product-code').setAttribute('readonly', true);
        document.getElementById('product-code').classList.add('bg-gray-100');
      });
    };

    window.cancelEdit = () => {
      editMode = false; editCode = null;
      document.getElementById('form-title').textContent = 'Tambah Produk Baru';
      document.getElementById('save-btn').textContent = 'Simpan';
      document.getElementById('cancel-btn').classList.add('hidden');
      document.getElementById('product-code').removeAttribute('readonly');
      document.getElementById('product-code').classList.remove('bg-gray-100');
      document.querySelectorAll('#product-code, #product-name, #product-price, #product-stock, #product-category').forEach(el => el.value = '');
    };

    window.saveProduct = async () => {
      const code = document.getElementById('product-code').value.trim();
      const name = document.getElementById('product-name').value.trim();
      const price = parseInt(document.getElementById('product-price').value.replace(/\D/g, ''));
      const stock = parseInt(document.getElementById('product-stock').value);
      const category = document.getElementById('product-category').value;

      if (!code || !name || !price || !category || stock < 0) {
        showToast('Isi semua field dengan benar!', 'error');
        return;
      }

      const snap = await get(ref(window.db, 'products'));
      const products = snap.val() || {};

      if (editMode) {
        if (!products[editCode]) return showToast('Produk tidak ditemukan!');
        delete products[editCode];
      } else if (products[code]) {
        return showToast('Kode produk sudah ada!');
      }

      products[code] = { code, name, price, stock, category };
      await set(ref(window.db, 'products'), products);
      await window.addActivityLog({ activity: editMode ? `Produk diupdate: ${name}` : `Produk ditambahkan: ${name}` });
      showToast(editMode ? 'Berhasil diupdate!' : 'Berhasil ditambahkan!');
      cancelEdit();
      loadProducts();
    };

    window.deleteProduct = async (code) => {
      if (!confirm('Yakin ingin menghapus produk ini?')) return;
      const snap = await get(ref(window.db, 'products'));
      const products = snap.val() || {};
      const name = products[code]?.name || code;
      delete products[code];
      await set(ref(window.db, 'products'), products);
      await window.addActivityLog({ activity: `Produk dihapus: ${name}` });
      showToast('Produk dihapus!');
      loadProducts();
    };

    function renderCharts(products) {
      const catCount = {}, catStock = {};
      products.forEach(p => {
        catCount[p.category] = (catCount[p.category] || 0) + 1;
        catStock[p.category] = (catStock[p.category] || 0) + p.stock;
      });

      const labels = Object.keys(catCount);
      const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'];

      if (chartCategory) chartCategory.destroy();
      chartCategory = new Chart(document.getElementById('category-chart'), {
        type: 'doughnut',
        data: { labels, datasets: [{ data: Object.values(catCount), backgroundColor: colors }] },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
      });

      if (chartStock) chartStock.destroy();
      chartStock = new Chart(document.getElementById('stock-chart'), {
        type: 'bar',
        data: { labels, datasets: [{ label: 'Total Stok', data: Object.values(catStock), backgroundColor: colors.map(c => c+'90') }] },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
      });
    }

    window.exportProductsCSV = async () => {
      const snap = await get(ref(window.db, 'products'));
      const products = snap.val() ? Object.values(snap.val()) : [];
      const csv = ['Kode,Nama,Harga,Stok,Kategori', ...products.map(p => `${p.code},${p.name},${p.price},${p.stock},${p.category}`)].join('\n');
      const blob = new Blob([csv], { type: 'text/csv' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a'); a.href = url; a.download = 'produk.csv'; a.click();
    };

    window.exportProductsPDF = async () => {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
      doc.setFontSize(18); doc.text('Daftar Produk - Warung Bakso Pak Farrel', 105, 20, { align: 'center' });
      const snap = await get(ref(window.db, 'products'));
      const products = snap.val() ? Object.values(snap.val()) : [];
      products.forEach((p, i) => {
        doc.setFontSize(10);
        doc.text(`${p.code} | ${p.name} | Rp ${p.price.toLocaleString('id-ID')} | Stok: ${p.stock} | ${p.category}`, 10, 40 + i*10);
      });
      doc.save('daftar-produk.pdf');
    };

    function formatPriceInput(e) {
      let val = e.target.value.replace(/\D/g, '');
      e.target.value = val ? parseInt(val).toLocaleString('id-ID') : '';
    }

    function showToast(msg) {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 3000);
    }

    window.logout = async () => {
      await window.addActivityLog({ activity: 'Logout dari halaman Produk' });
      localStorage.removeItem('currentUser');
      location.href = '../../index.php';
    };
  </script>
</body>
</html>