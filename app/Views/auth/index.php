<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Warung Bakso Pak Farrel - Login & Register</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { font-family: 'Poppins', sans-serif; }
    body { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%); overflow: hidden; }
    .glass { backdrop-filter: blur(12px); background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); }
    .input-glow:focus { box-shadow: 0 0 20px rgba(59,130,246,0.5); }
    .btn-glow { background: linear-gradient(45deg, #3b82f6, #60a5fa); box-shadow: 0 8px 32px rgba(59,130,246,0.4); }
    .btn-glow:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(59,130,246,0.6); }
    @keyframes blob { 0%,100% { transform: translate(0,0) rotate(0deg); } 50% { transform: translate(30px,-30px) rotate(180deg); } }
    .animate-blob { animation: blob 7s infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
    .animation-delay-4000 { animation-delay: 4s; }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen relative">

  <!-- Background Animasi -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute -top-40 -left-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
    <div class="absolute -top-20 -right-20 w-80 h-80 bg-yellow-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-40 left-60 w-80 h-80 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
  </div>

  <!-- CARD UTAMA -->
  <div id="auth-card" class="glass p-8 rounded-2xl shadow-2xl w-full max-w-md z-10 transition-all duration-500">
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mb-4 animate-pulse">
        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5a2 2 0 002-2v-1"></path>
        </svg>
      </div>
      <h1 class="text-3xl font-bold text-white">Warung Bakso Pak Farrel</h1>
      <p class="text-blue-200 mt-1">Sistem Kasir Modern</p>
    </div>

    <!-- LOGIN FORM -->
    <div id="login-form">
      <h2 class="text-2xl font-bold text-white text-center mb-6">Masuk ke Akun</h2>
      <div class="space-y-5">
        <input type="text" id="login-username" class="w-full p-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-blue-200 input-glow focus:outline-none" placeholder="Username">
        <input type="password" id="login-password" class="w-full p-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-blue-200 input-glow focus:outline-none" placeholder="Kata Sandi">
        <button id="login-btn" class="w-full py-4 btn-glow text-white font-bold rounded-xl transition-all duration-300 hover:scale-105">
          <span id="login-text">Masuk</span>
          <span id="login-spinner" class="hidden">Memproses...</span>
        </button>
      </div>
      <p class="text-center text-blue-200 mt-6">
        Belum punya akun? 
        <button type="button" onclick="showRegister()" class="text-yellow-300 font-bold hover:underline">Daftar di sini</button>
      </p>
    </div>

    <!-- REGISTER FORM -->
    <div id="register-form" class="hidden">
      <h2 class="text-2xl font-bold text-white text-center mb-6">Buat Akun Baru</h2>
      <div class="space-y-5">
        <input type="text" id="reg-username" class="w-full p-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-blue-200 input-glow focus:outline-none" placeholder="Username">
        <input type="password" id="reg-password" class="w-full p-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-blue-200 input-glow focus:outline-none" placeholder="Kata Sandi">
        <input type="password" id="reg-confirm" class="w-full p-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white placeholder-blue-200 input-glow focus:outline-none" placeholder="Konfirmasi Kata Sandi">
        <select id="reg-role" class="w-full p-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-xl text-white input-glow focus:outline-none">
          <option value="kasir">Kasir</option>
          <option value="admin">Admin</option>
        </select>
        <button id="register-btn" class="w-full py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-all duration-300 hover:scale-105">
          <span id="reg-text">Daftar Sekarang</span>
          <span id="reg-spinner" class="hidden">Mendaftarkan...</span>
        </button>
      </div>
      <p class="text-center text-blue-200 mt-6">
        Sudah punya akun? 
        <button type="button" onclick="showLogin()" class="text-yellow-300 font-bold hover:underline">Masuk di sini</button>
      </p>
    </div>

    <p id="message" class="text-center mt-4 text-sm font-medium hidden"></p>
  </div>

  <!-- Toast -->
  <div id="toast" class="fixed bottom-6 right-6 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg z-50 hidden"></div>

  <!-- Firebase Init -->
  <script type="module" src="assets/js/firebase-init.js"></script>

  <!-- SEMUA SCRIPT TETAP SAMA PERSIS -->
  <script type="module">
    window.showRegister = () => {
      document.getElementById('login-form').classList.add('hidden');
      document.getElementById('register-form').classList.remove('hidden');
      document.getElementById('message').classList.add('hidden');
    };

    window.showLogin = () => {
      document.getElementById('register-form').classList.add('hidden');
      document.getElementById('login-form').classList.remove('hidden');
      document.getElementById('message').classList.add('hidden');
    };

    function showMessage(text, type = 'error') {
      const msg = document.getElementById('message');
      msg.textContent = text;
      msg.className = `text-center mt-4 text-sm font-medium ${type === 'success' ? 'text-green-300' : 'text-red-300'}`;
      msg.classList.remove('hidden');
    }

    document.getElementById('register-btn').onclick = async () => {
      const username = document.getElementById('reg-username').value.trim();
      const password = document.getElementById('reg-password').value;
      const confirm = document.getElementById('reg-confirm').value;
      const role = document.getElementById('reg-role').value;

      if (!username || !password || !confirm) return showMessage("Isi semua kolom!", "error");
      if (password !== confirm) return showMessage("Konfirmasi password tidak cocok!", "error");
      if (password.length < 6) return showMessage("Password minimal 6 karakter!", "error");

      document.getElementById('reg-text').classList.add('hidden');
      document.getElementById('reg-spinner').classList.remove('hidden');

      try {
        const users = await getUsers();
        if (users.some(u => u.username === username)) {
          showMessage("Username sudah digunakan!", "error");
          document.getElementById('reg-text').classList.remove('hidden');
          document.getElementById('reg-spinner').classList.add('hidden');
          return;
        }

        users.push({ username, password, role });
        await saveUsers(users);
        await addActivityLog({ activity: `Registrasi: ${username} (${role})` });
        showMessage("Registrasi berhasil! Silakan login.", "success");
        setTimeout(showLogin, 1500);
      } catch (e) {
        showMessage("Gagal registrasi!", "error");
        console.error(e);
      } finally {
        document.getElementById('reg-text').classList.remove('hidden');
        document.getElementById('reg-spinner').classList.add('hidden');
      }
    };

    document.getElementById('login-btn').onclick = async () => {
      const username = document.getElementById('login-username').value.trim();
      const password = document.getElementById('login-password').value;

      if (!username || !password) return showMessage("Isi username dan password!", "error");

      document.getElementById('login-text').textContent = "Memeriksa...";

      try {
        const users = await getUsers();
        const user = users.find(u => u.username === username && u.password === password);

        if (user) {
          localStorage.setItem("currentUser", JSON.stringify(user));
          await addActivityLog({ activity: `Login: ${username} (${user.role})` });
          location.href = user.role === "admin" ? "admin/dashboard.php" : "kasir/kasir.php";
        } else {
          showMessage("Username atau password salah!", "error");
          document.getElementById('login-text').textContent = "Masuk";
        }
      } catch (e) {
        showMessage("Gagal login. Pastikan emulator jalan!", "error");
        console.error(e);
      }
    };
  </script>
</body>
</html>