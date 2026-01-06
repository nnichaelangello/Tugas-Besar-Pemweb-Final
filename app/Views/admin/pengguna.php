<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Pengguna - Warung Bakso Pak Farrel</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .navbar-active { background: rgba(255,255,255,0.25) !important; font-weight: bold; border-radius: 8px; }
    #toast { position: fixed; bottom: 30px; right: 30px; background: #10b981; color: white; padding: 16px 32px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 9999; font-weight: bold; opacity: 0; transform: translateY(20px); transition: all 0.4s ease; }
    #toast.show { opacity: 1; transform: translateY(0); }
    #toast.error { background: #ef4444; }
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
          <a href="pengguna.php"    class="px-5 py-3 rounded-lg transition navbar-active">Pengguna</a>
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
        <a href="produk.php"      class="block px-5 py-3 rounded-lg hover:bg-blue-600">Produk</a>
        <a href="pengguna.php"    class="block px-5 py-3 rounded-lg bg-blue-800 navbar-active">Pengguna</a>
        <a href="stok.php"        class="block px-5 py-3 rounded-lg hover:bg-blue-600">Stok</a>
        <a href="laporan.php"     class="block px-5 py-3 rounded-lg hover:bg-blue-600">Laporan</a>
        <a href="pengaturan.php"  class="block px-5 py-3 rounded-lg hover:bg-blue-600">Pengaturan</a>
        <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 px-5 py-3 rounded-lg font-bold">Keluar</button>
      </div>
    </div>
  </nav>

  <div class="container mx-auto px-4 py-8 max-w-6xl">
    <h2 class="text-4xl font-bold text-gray-800 mb-10 text-center">Manajemen Pengguna & Akses</h2>

    <!-- FORM TAMBAH/EDIT PENGGUNA -->
    <div class="bg-white p-10 rounded-2xl shadow-2xl mb-10">
      <h3 class="text-2xl font-bold mb-8 text-gray-700" id="form-title">Tambah Pengguna Baru</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
          <label class="block text-lg font-semibold mb-3 text-gray-700">Username</label>
          <input type="text" id="user-username" class="w-full px-5 py-4 border-2 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition text-lg" placeholder="masukkan username">
        </div>
        <div>
          <label class="block text-lg font-semibold mb-3 text-gray-700">Password</label>
          <div class="relative">
            <input type="password" id="user-password" class="w-full px-5 py-4 border-2 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition text-lg pr-12" placeholder="minimal 6 karakter">
            <button type="button" onclick="togglePassword()" class="absolute right-4 top-5 text-gray-600">
              <svg id="eye-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
            </button>
          </div>
        </div>
        <div>
          <label class="block text-lg font-semibold mb-3 text-gray-700">Role Akses</label>
          <select id="user-role" class="w-full px-5 py-4 border-2 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition text-lg">
            <option value="kasir">Kasir (Hanya Transaksi)</option>
            <option value="admin">Admin (Akses Penuh)</option>
          </select>
        </div>
      </div>

      <div class="mt-10 flex gap-6 justify-center">
        <button onclick="saveUser()" id="save-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-12 py-5 rounded-xl text-xl font-bold shadow-xl transition transform hover:scale-105">
          Tambah Pengguna
        </button>
        <button onclick="cancelEdit()" id="cancel-btn" class="hidden bg-gray-600 hover:bg-gray-700 text-white px-12 py-5 rounded-xl text-xl font-bold shadow-xl transition">
          Batal Edit
        </button>
      </div>
    </div>

    <!-- DAFTAR PENGGUNA -->
    <div class="bg-white p-10 rounded-2xl shadow-2xl">
      <h3 class="text-2xl font-bold mb-8 text-gray-700">Daftar Pengguna Aktif</h3>
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
            <tr>
              <th class="p-6 font-bold text-lg rounded-tl-xl">No</th>
              <th class="p-6 font-bold text-lg">Username</th>
              <th class="p-6 font-bold text-lg">Role</th>
              <th class="p-6 font-bold text-lg">Status</th>
              <th class="p-6 font-bold text-lg text-center rounded-tr-xl">Aksi</th>
            </tr>
          </thead>
          <tbody id="user-table"></tbody>
        </table>
      </div>
      <div id="empty-state" class="text-center py-20 text-gray-500 text-xl hidden">
        <p>Belum ada pengguna terdaftar</p>
      </div>
    </div>
  </div>

  <div id="toast">Berhasil!</div>

  <!-- Firebase Init -->
  <script type="module" src="../../assets/js/firebase-init.js"></script>

  <!-- LOGIC PENGGUNA — TETAP 100% SAMA PERSIS -->
  <script type="module">
    import { ref, get, set, remove } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-database.js";

    let editMode = false;
    let editUsername = null;

    const wait = setInterval(() => {
      if (window.db && window.addActivityLog) {
        clearInterval(wait);
        initPengguna();
      }
    }, 50);

    function initPengguna() {
      const user = JSON.parse(localStorage.getItem('currentUser') || 'null');
      if (!user || user.role !== 'admin') location.href = '../../index.php';

      document.querySelectorAll('nav a').forEach(a => {
        a.classList.toggle('navbar-active', a.href.includes('pengguna.php'));
      });

      loadUsers();
    }

    async function loadUsers() {
      const snap = await get(ref(window.db, 'users'));
      const users = snap.val() || {};
      const tbody = document.getElementById('user-table');
      const empty = document.getElementById('empty-state');

      const userList = Object.entries(users).map(([key, val]) => ({ id: key, ...val }));

      if (userList.length === 0) {
        tbody.innerHTML = '';
        empty.classList.remove('hidden');
        return;
      }

      empty.classList.add('hidden');
      tbody.innerHTML = userList.map((u, i) => `
        <tr class="border-b hover:bg-gray-50 transition">
          <td class="p-6 text-lg">${i + 1}</td>
          <td class="p-6 font-bold text-xl">${u.username}</td>
          <td class="p-6">
            <span class="px-5 py-2 rounded-full text-white font-bold text-lg ${u.role === 'admin' ? 'bg-purple-600' : 'bg-blue-600'}">
              ${u.role.toUpperCase()}
            </span>
          </td>
          <td class="p-6">
            <span class="px-4 py-2 rounded-full text-white font-bold ${u.username === JSON.parse(localStorage.getItem('currentUser')).username ? 'bg-green-600' : 'bg-gray-500'}">
              ${u.username === JSON.parse(localStorage.getItem('currentUser')).username ? 'Sedang Login' : 'Offline'}
            </span>
          </td>
          <td class="p-6 text-center space-x-4">
            <button onclick="startEdit('${u.id}', '${u.username}', '${u.role}')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition">
              Edit
            </button>
            ${u.username !== JSON.parse(localStorage.getItem('currentUser')).username ? 
              `<button onclick="deleteUser('${u.id}', '${u.username}')" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition">
                Hapus
              </button>` : 
              `<span class="text-gray-400 italic">Tidak bisa hapus diri sendiri</span>`
            }
          </td>
        </tr>
      `).join('');
    }

    window.startEdit = (id, username, role) => {
      editMode = true;
      editUsername = id;
      document.getElementById('form-title').textContent = 'Edit Pengguna';
      document.getElementById('user-username').value = username;
      document.getElementById('user-password').value = '';
      document.getElementById('user-password').placeholder = 'Kosongkan jika tidak ingin ganti password';
      document.getElementById('user-role').value = role;
      document.getElementById('save-btn').textContent = 'Update Pengguna';
      document.getElementById('cancel-btn').classList.remove('hidden');
      document.getElementById('user-username').setAttribute('readonly', true);
      document.getElementById('user-username').classList.add('bg-gray-200');
    };

    window.cancelEdit = () => {
      editMode = false;
      editUsername = null;
      document.getElementById('form-title').textContent = 'Tambah Pengguna Baru';
      document.getElementById('save-btn').textContent = 'Tambah Pengguna';
      document.getElementById('cancel-btn').classList.add('hidden');
      document.getElementById('user-username').removeAttribute('readonly');
      document.getElementById('user-username').classList.remove('bg-gray-200');
      document.getElementById('user-username').value = '';
      document.getElementById('user-password').value = '';
      document.getElementById('user-password').placeholder = 'minimal 6 karakter';
      document.getElementById('user-role').value = 'kasir';
    };

    window.saveUser = async () => {
      const username = document.getElementById('user-username').value.trim();
      const password = document.getElementById('user-password').value;
      const role = document.getElementById('user-role').value;

      if (!username || (!editMode && !password) || password.length < 6) {
        showToast('Username & password minimal 6 karakter!', 'error');
        return;
      }

      const snap = await get(ref(window.db, 'users'));
      const users = snap.val() || {};

      if (!editMode && users[username]) {
        showToast('Username sudah digunakan!', 'error');
        return;
      }

      if (editMode && editUsername !== username && users[username]) {
        showToast('Username baru sudah dipakai!', 'error');
        return;
      }

      const userData = { username, role };
      if (password) userData.password = password;

      await set(ref(window.db, `users/${editMode ? editUsername : username}`), userData);

      await window.addActivityLog({
        activity: editMode ? `Pengguna diupdate: ${username}` : `Pengguna ditambahkan: ${username} (${role})`
      });

      showToast(editMode ? 'Pengguna berhasil diupdate!' : 'Pengguna berhasil ditambahkan!');
      cancelEdit();
      loadUsers();
    };

    window.deleteUser = async (id, username) => {
      if (!confirm(`Yakin ingin menghapus pengguna "${username}"?`)) return;

      await remove(ref(window.db, `users/${id}`));
      await window.addActivityLog({ activity: `Pengguna dihapus: ${username}` });
      showToast('Pengguna dihapus!');
      loadUsers();
    };

    window.togglePassword = () => {
      const input = document.getElementById('user-password');
      const icon = document.getElementById('eye-icon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.974 9.974 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>`;
      } else {
        input.type = 'password';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
      }
    };

    function showToast(msg, type = 'success') {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.className = type === 'error' ? 'error' : '';
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 4000);
    }

    window.logout = async () => {
      await window.addActivityLog({ activity: 'Logout dari halaman Pengguna' });
      localStorage.removeItem('currentUser');
      location.href = '../../index.php';
    };
  </script>
</body>
</html>