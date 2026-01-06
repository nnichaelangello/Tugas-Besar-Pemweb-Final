<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaksi Kasir - Warung Bakso Pak Farrel</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .navbar-active { background: rgba(255,255,255,0.25) !important; font-weight: bold; border-radius: 8px; }
    #toast { position: fixed; bottom: 30px; right: 30px; background: #10b981; color: white; padding: 16px 32px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 9999; font-weight: bold; opacity: 0; transform: translateY(20px); transition: all 0.4s ease; }
    #toast.show { opacity: 1; transform: translateY(0); }
    #toast.error { background: #ef4444; }
    .product-card { transition: all 0.3s; cursor: pointer; }
    .product-card:hover { transform: translateY(-8px) scale(1.05); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
    .product-card.low-stock { border: 4px solid #f59e0b; }
    .product-card.out-stock { opacity: 0.6; pointer-events: none; background: #fee2e2; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">

  <!-- NAVIGASI -->
  <nav class="bg-blue-700 text-white shadow-xl">
    <div class="container mx-auto px-4 py-4">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Warung Bakso Pak Farrel</h1>
        <div class="hidden md:flex items-center space-x-1">
          <a href="dashboard.php" class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Dasbor</a>
          <a href="transaksi.php" class="px-5 py-3 rounded-lg bg-blue-800 navbar-active">Transaksi</a>
          <a href="produk.php" class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Produk</a>
          <a href="pengguna.php" class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Pengguna</a>
          <a href="stok.php" class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Stok</a>
          <a href="laporan.php" class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Laporan</a>
          <a href="pengaturan.php" class="px-5 py-3 rounded-lg hover:bg-blue-600 transition">Pengaturan</a>
          <button onclick="logout()" class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-lg font-bold ml-4 transition">Keluar</button>
        </div>
        <button class="md:hidden" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
      <div id="mobile-menu" class="md:hidden hidden mt-4 space-y-2">
        <a href="dashboard.php" class="block px-5 py-3 rounded-lg hover:bg-blue-600">Dasbor</a>
        <a href="transaksi.php" class="block px-5 py-3 rounded-lg bg-blue-800 navbar-active">Transaksi</a>
        <a href="produk.php" class="block px-5 py-3 rounded-lg hover:bg-blue-600">Produk</a>
        <a href="pengguna.php" class="block px-5 py-3 rounded-lg hover:bg-blue-600">Pengguna</a>
        <a href="stok.php" class="block px-5 py-3 rounded-lg hover:bg-blue-600">Stok</a>
        <a href="laporan.php" class="block px-5 py-3 rounded-lg hover:bg-blue-600">Laporan</a>
        <a href="pengaturan.php" class="block px-5 py-3 rounded-lg hover:bg-blue-600">Pengaturan</a>
        <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 px-5 py-3 rounded-lg font-bold">Keluar</button>
      </div>
    </div>
  </nav>

  <div class="container mx-auto px-4 py-8 max-w-7xl">
    <h2 class="text-4xl font-bold text-center text-gray-800 mb-10">Sistem Kasir POS Profesional</h2>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
      <!-- KIRI: PRODUK + KERANJANG -->
      <div class="xl:col-span-2 space-y-8">
        <!-- PENCARIAN -->
        <div class="bg-white p-6 rounded-2xl shadow-2xl">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" id="search-product" class="px-5 py-4 border-2 rounded-xl focus:ring-4 focus:ring-blue-300 text-lg" placeholder="Cari nama / kode..." onkeyup="filterProducts()">
            <select id="filter-category" class="px-5 py-4 border-2 rounded-xl focus:ring-4 focus:ring-blue-300 text-lg" onchange="filterProducts()">
              <option value="">Semua Kategori</option>
            </select>
            <button onclick="clearSearch()" class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-4 rounded-xl font-bold">Reset</button>
          </div>
        </div>

        <!-- GRID PRODUK -->
        <div class="bg-white p-8 rounded-2xl shadow-2xl">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Pilih Produk</h3>
            <span id="product-count" class="text-lg text-gray-600">0 produk</span>
          </div>
          <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 max-h-96 overflow-y-auto"></div>
        </div>

        <!-- KERANJANG -->
        <div class="bg-white p-8 rounded-2xl shadow-2xl">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Keranjang Belanja</h3>
            <button onclick="clearCart()" class="text-red-600 hover:underline font-bold">Kosongkan</button>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                <tr>
                  <th class="p-4 text-left">Produk</th>
                  <th class="p-4 text-right">Harga</th>
                  <th class="p-4 text-center">Qty</th>
                  <th class="p-4 text-right">Subtotal</th>
                  <th class="p-4 text-center">Hapus</th>
                </tr>
              </thead>
              <tbody id="cart-items"></tbody>
            </table>
          </div>

          <div class="mt-8 border-t-4 border-green-600 pt-6 bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl">
            <div class="text-3xl font-bold text-right space-y-4">
              <div class="flex justify-between text-2xl"><span>Subtotal</span><span id="subtotal">Rp 0</span></div>
              <div class="flex justify-between text-4xl text-green-600"><span>TOTAL BAYAR</span><span id="total-bayar">Rp 0</span></div>
            </div>
          </div>

          <div class="grid grid-cols-3 gap-4 mt-8">
            <button onclick="checkout()" class="bg-green-600 hover:bg-green-700 text-white py-6 rounded-xl text-3xl font-bold shadow-2xl transition transform hover:scale-105">
              BAYAR
            </button>
            <button onclick="printReceipt()" class="bg-blue-600 hover:bg-blue-700 text-white py-6 rounded-xl text-3xl font-bold shadow-2xl transition">
              STRUK
            </button>
            <button onclick="holdTransaction()" class="bg-yellow-600 hover:bg-yellow-700 text-white py-6 rounded-xl text-3xl font-bold shadow-2xl transition">
              TAHAN
            </button>
          </div>
        </div>
      </div>

      <!-- KANAN -->
      <div class="space-y-8">
        <div class="bg-gradient-to-br from-blue-600 to-purple-700 text-white p-8 rounded-2xl shadow-2xl">
          <h3 class="text-2xl font-bold mb-6">Hari Ini</h3>
          <div class="space-y-6 text-2xl">
            <div class="flex justify-between"><span>Transaksi</span><span id="today-count" class="text-3xl font-extrabold">0</span></div>
            <div class="flex justify-between"><span>Pendapatan</span><span id="today-revenue" class="text-3xl font-extrabold">Rp 0</span></div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-2xl">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Transaksi Ditahan</h3>
            <button onclick="clearAllHeld()" class="text-xs text-red-600 hover:underline">Hapus Semua</button>
          </div>
          <div id="held-list" class="space-y-3 max-h-64 overflow-y-auto"></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-2xl">
          <h3 class="text-xl font-bold mb-4">Riwayat Transaksi</h3>
          <div id="transaction-history" class="space-y-3 max-h-80 overflow-y-auto"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL DETAIL -->
  <div id="detail-modal" class="fixed inset-0 bg-black bg-opacity-70 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-3xl max-w-2xl w-full max-h-screen overflow-y-auto p-8">
      <h3 class="text-3xl font-bold text-center text-blue-800 mb-6">Detail Transaksi</h3>
      <div id="detail-content"></div>
      <div class="grid grid-cols-3 gap-4 mt-8">
        <button onclick="closeModal()" class="bg-gray-600 hover:bg-gray-700 text-white py-4 rounded-xl font-bold text-xl">Tutup</button>
        <button onclick="printTransactionReceipt()" class="bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-bold text-xl">Cetak Struk</button>
        <button onclick="returTransaction()" class="bg-red-600 hover:bg-red-700 text-white py-4 rounded-xl font-bold text-xl">Retur</button>
      </div>
    </div>
  </div>

  <div id="toast">Transaksi berhasil!</div>

  <!-- Firebase Init -->
  <script type="module" src="../../assets/js/firebase-init.js"></script>

  <!-- LOGIC TRANSAKSI — TETAP 100% SAMA PERSIS -->
  <script type="module">
    import { ref, get, set, push, onValue } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-database.js";

    let cart = [];
    let products = {};
    let heldTransactions = JSON.parse(localStorage.getItem('heldTransactions') || '[]');
    let currentUser = null;

    // Tunggu Firebase & user siap
    const init = setInterval(() => {
      if (window.db && window.addActivityLog) {
        clearInterval(init);
        startApp();
      }
    }, 100);

    function startApp() {
      currentUser = JSON.parse(localStorage.getItem('currentUser') || 'null');
      if (!currentUser) return location.href = '../../index.php';

      document.querySelectorAll('nav a').forEach(a => {
        a.classList.toggle('navbar-active', a.href.includes('transaksi.php'));
      });

      loadProducts();
      loadTransactions();
      setupRealtimeListeners();
      renderHeldTransactions();
    }

    // LOAD DATA DARI FIREBASE
    async function loadProducts() {
      const snap = await get(ref(window.db, 'products'));
      products = snap.val() || {};
      renderProducts();
      updateCategoryFilter();
    }

    async function loadTransactions() {
      const snap = await get(ref(window.db, 'transactions'));
      updateTodayStats(snap.val() || {});
      updateRecentTransactions(snap.val() || {});
    }

    // REAL-TIME LISTENER — INI YANG BIKIN TIDAK PERLU REFRESH!
    function setupRealtimeListeners() {
      onValue(ref(window.db, 'products'), (snap) => {
        products = snap.val() || {};
        renderProducts();
        updateCategoryFilter();
      });

      onValue(ref(window.db, 'transactions'), (snap) => {
        const data = snap.val() || {};
        updateTodayStats(data);
        updateRecentTransactions(data);
      });
    }

    // RENDER PRODUK
    function renderProducts() {
      const grid = document.getElementById('product-grid');
      const search = document.getElementById('search-product').value.toLowerCase();
      const category = document.getElementById('filter-category').value;

      const filtered = Object.values(products).filter(p => {
        const matchSearch = p.name.toLowerCase().includes(search) || p.code.toLowerCase().includes(search);
        const matchCat = !category || p.category === category;
        return matchSearch && matchCat;
      });

      document.getElementById('product-count').textContent = `${filtered.length} produk tersedia`;

      grid.innerHTML = filtered.map(p => {
        const low = p.stock < 10 && p.stock > 0;
        const out = p.stock === 0;
        return `
          <div class="product-card bg-white rounded-2xl shadow-xl p-6 text-center ${low ? 'low-stock' : ''} ${out ? 'out-stock' : ''}"
               onclick="${out ? '' : `addToCart('${p.code}')`}">
            <div class="text-5xl mb-3">${p.icon || 'Bakso'}</div>
            <h4 class="font-bold text-xl">${p.name}</h4>
            <p class="text-gray-600 text-sm">${p.code}</p>
            <p class="text-3xl font-extrabold text-green-600 mt-3">Rp ${Number(p.price).toLocaleString('id-ID')}</p>
            <p class="text-lg font-bold mt-2 ${out ? 'text-red-600' : low ? 'text-orange-600' : 'text-green-600'}">
              Stok: ${p.stock}
            </p>
          </div>
        `;
      }).join('') || '<p class="col-span-full text-center text-gray-500 py-20 text-2xl">Tidak ada produk</p>';
    }

    window.filterProducts = renderProducts;
    window.clearSearch = () => {
      document.getElementById('search-product').value = '';
      document.getElementById('filter-category').value = '';
      renderProducts();
    };

    function updateCategoryFilter() {
      const select = document.getElementById('filter-category');
      const cats = [...new Set(Object.values(products).map(p => p.category).filter(Boolean))];
      select.innerHTML = '<option value="">Semua Kategori</option>' + cats.map(c => `<option>${c}</option>`).join('');
    }

    // KERANJANG
    window.addToCart = (code) => {
      const p = products[code];
      if (!p || p.stock <= 0) return showToast('Stok habis!', 'error');

      const existing = cart.find(i => i.code === code);
      if (existing) {
        if (existing.quantity >= p.stock) return showToast('Stok tidak cukup!', 'error');
        existing.quantity++;
      } else {
        cart.push({ ...p, quantity: 1 });
      }
      renderCart();
    };

    function renderCart() {
      const tbody = document.getElementById('cart-items');
      tbody.innerHTML = cart.length ? cart.map((item, i) => `
        <tr class="border-b hover:bg-green-50 transition">
          <td class="p-4 font-bold">${item.name}</td>
          <td class="p-4 text-right">Rp ${Number(item.price).toLocaleString('id-ID')}</td>
          <td class="p-4 text-center">
            <div class="flex items-center justify-center gap-3">
              <button onclick="updateQty(${i}, -1)" class="bg-red-500 hover:bg-red-600 text-white w-10 h-10 rounded-full font-bold text-xl">−</button>
              <span class="text-2xl font-bold w-16 text-center">${item.quantity}</span>
              <button onclick="updateQty(${i}, 1)" class="bg-green-500 hover:bg-green-600 text-white w-10 h-10 rounded-full font-bold text-xl">+</button>
            </div>
          </td>
          <td class="p-4 text-right font-bold text-xl">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</td>
          <td class="p-4 text-center">
            <button onclick="removeFromCart(${i})" class="text-red-600 hover:text-red-800 text-3xl font-bold">×</button>
          </td>
        </tr>
      `).join('') : '<tr><td colspan="5" class="text-center py-20 text-gray-500 text-2xl">Keranjang kosong</td></tr>';

      const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
      document.getElementById('subtotal').textContent = `Rp ${total.toLocaleString('id-ID')}`;
      document.getElementById('total-bayar').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }

    window.updateQty = (i, change) => {
      const item = cart[i];
      const newQty = item.quantity + change;
      if (newQty < 1) return removeFromCart(i);
      if (newQty > products[item.code].stock) return showToast('Stok tidak cukup!', 'error');
      cart[i].quantity = newQty;
      renderCart();
    };

    window.removeFromCart = (i) => { cart.splice(i, 1); renderCart(); };
    window.clearCart = () => { if (confirm('Kosongkan keranjang?')) { cart = []; renderCart(); } };

    // CHECKOUT — TANPA REFRESH!
    window.checkout = async () => {
      if (cart.length === 0) return showToast('Keranjang kosong!', 'error');
      if (!confirm('Selesaikan transaksi ini?')) return;

      const total = cart.reduce((s, i) => s + i.price * i.quantity, 0);
      const trans = {
        id: Date.now().toString().slice(-6),
        date: Date.now(),
        items: cart.map(i => ({ name: i.name, code: i.code, price: i.price, quantity: i.quantity })),
        total,
        userId: currentUser.username
      };

      // Kurangi stok
      for (const item of cart) {
        const newStock = products[item.code].stock - item.quantity;
        await set(ref(window.db, `products/${item.code}/stock`), newStock);
      }

      await push(ref(window.db, 'transactions'), trans);
      await window.addActivityLog({ activity: `Transaksi #${trans.id} - Rp ${total.toLocaleString('id-ID')}` });

      printReceipt(trans);
      cart = [];
      renderCart();
      showToast('Transaksi berhasil disimpan!');
    };

    // STRUK
    window.printReceipt = (trans = null) => {
      const t = trans || { items: cart, total: cart.reduce((s,i)=>s+i.price*i.quantity,0), id: 'PREVIEW', date: Date.now() };
      const win = window.open('', '_blank');
      win.document.write(`
        <html><head><title>Struk ${t.id}</title><style>
          body{font-family: Arial; padding: 30px; text-align: center; font-size: 16px;}
          .header{font-size: 28px; font-weight: bold;}
          table{width: 100%; margin: 20px 0;}
          .total{font-size: 24px; font-weight: bold;}
        </style></head><body>
          <div class="header">Warung Bakso Pak Farrel</div>
          <p>Jl. Contoh No.123 • 0812-3456-7890</p>
          <hr>
          <p>Struk #${t.id} • ${new Date(t.date).toLocaleString('id-ID')}</p>
          <table>
            ${t.items.map(i => `<tr><td align="left">${i.name} x${i.quantity}</td><td align="right">Rp ${(i.price*i.quantity).toLocaleString('id-ID')}</td></tr>`).join('')}
          </table>
          <hr>
          <p class="total">TOTAL: Rp ${t.total.toLocaleString('id-ID')}</p>
          <p>Terima kasih telah berbelanja!</p>
        </body></html>
      `);
      win.print();
    };

    // TAHAN & LOAD
    window.holdTransaction = () => {
      if (cart.length === 0) return showToast('Keranjang kosong!');
      heldTransactions.push({ id: Date.now(), cart: [...cart], time: new Date().toLocaleString('id-ID') });
      localStorage.setItem('heldTransactions', JSON.stringify(heldTransactions));
      showToast('Transaksi ditahan!');
      cart = []; renderCart(); renderHeldTransactions();
    };

    function renderHeldTransactions() {
      const list = document.getElementById('held-list');
      list.innerHTML = heldTransactions.map((h, i) => `
        <div class="bg-gray-50 p-4 rounded-xl border-2 border-blue-300 cursor-pointer hover:bg-blue-50 transition" onclick="loadHeld(${i})">
          <div class="font-bold">#${h.id.toString().slice(-6)}</div>
          <div class="text-sm text-gray-600">${h.time}</div>
          <div>Rp ${h.cart.reduce((s,i)=>s+i.price*i.quantity,0).toLocaleString('id-ID')}</div>
        </div>
      `).join('') || '<p class="text-center text-gray-500 py-10">Tidak ada transaksi ditahan</p>';
    }

    window.loadHeld = (i) => {
      if (confirm('Muat transaksi ini?')) {
        cart = heldTransactions[i].cart;
        heldTransactions.splice(i, 1);
        localStorage.setItem('heldTransactions', JSON.stringify(heldTransactions));
        renderCart();
        renderHeldTransactions();
      }
    };

    window.clearAllHeld = () => {
      if (confirm('Hapus semua transaksi ditahan?')) {
        heldTransactions = [];
        localStorage.setItem('heldTransactions', JSON.stringify([]));
        renderHeldTransactions();
      }
    };

    // UPDATE STATISTIK & RIWAYAT
    function updateTodayStats(data) {
      const today = new Date().toDateString();
      const todayTx = Object.values(data).filter(t => new Date(t.date).toDateString() === today);
      document.getElementById('today-count').textContent = todayTx.length;
      document.getElementById('today-revenue').textContent = `Rp ${todayTx.reduce((s,t)=>s+(t.total||0),0).toLocaleString('id-ID')}`;
    }

    function updateRecentTransactions(data) {
      const list = document.getElementById('transaction-history');
      const recent = Object.values(data).sort((a,b)=>b.date-a.date).slice(0,10);
      list.innerHTML = recent.map(t => `
        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 cursor-pointer transition" onclick="showDetail('${t.id}')">
          <div>
            <div class="font-bold text-lg">#${t.id}</div>
            <div class="text-sm text-gray-600">${new Date(t.date).toLocaleTimeString('id-ID')}</div>
          </div>
          <div class="text-right">
            <div class="font-bold text-2xl text-green-600">Rp ${t.total.toLocaleString('id-ID')}</div>
          </div>
        </div>
      `).join('') || '<p class="text-center text-gray-500 py-10">Belum ada transaksi hari ini</p>';
    }

    window.showDetail = async (id) => {
      const snap = await get(ref(window.db, 'transactions'));
      const trans = Object.values(snap.val() || {}).find(t => t.id === id);
      if (!trans) return;

      document.getElementById('detail-content').innerHTML = `
        <div class="text-center mb-6">
          <h4 class="text-3xl font-bold">#${trans.id}</h4>
          <p class="text-xl">${new Date(trans.date).toLocaleString('id-ID')}</p>
          <p class="text-blue-600 font-bold text-xl">Kasir: ${trans.userId}</p>
        </div>
        <table class="w-full">
          ${trans.items.map(i => `<tr class="border-b"><td class="py-3">${i.name} × ${i.quantity}</td><td class="text-right font-bold">Rp ${(i.price*i.quantity).toLocaleString('id-ID')}</td></tr>`).join('')}
        </table>
        <div class="text-right mt-6 text-4xl font-bold text-green-600">
          TOTAL: Rp ${trans.total.toLocaleString('id-ID')}
        </div>
      `;
      document.getElementById('detail-modal').classList.remove('hidden');
    };

    window.closeModal = () => document.getElementById('detail-modal').classList.add('hidden');
    window.printTransactionReceipt = printReceipt;

    function showToast(msg, type = 'success') {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.className = type === 'error' ? 'error' : '';
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 4000);
    }

    window.logout = async () => {
      await window.addActivityLog({ activity: `Logout - ${currentUser.username}` });
      localStorage.removeItem('currentUser');
      location.href = '../../index.php';
    };
  </script>
</body>
</html>