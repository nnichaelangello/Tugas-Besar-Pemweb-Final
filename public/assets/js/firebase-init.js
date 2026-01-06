// assets/js/firebase-init.js → VERSI PRODUCTION (Firebase Asli)

console.log("Firebase init dimulai (Production)...");

// GANTI SELURUHNYA DENGAN CONFIG DARI FIREBASE CONSOLE PROJECT ASLI KAMU
const firebaseConfig = {
  apiKey: "AIzaSyAronRNCE63cTcrNqshr9D02JMAgWcZ3ls",
  authDomain: "warung-bakso-e4d7f.firebaseapp.com",
  databaseURL: "https://warung-bakso-e4d7f-default-rtdb.asia-southeast1.firebasedatabase.app",
  projectId: "warung-bakso-e4d7f",
  storageBucket: "warung-bakso-e4d7f.firebasestorage.app",
  messagingSenderId: "849802520066",
  appId: "1:849802520066:web:ff04bcf089a4dfdfb0691b"
};

// Import Firebase SDK (versi terbaru direkomendasikan, tapi 10.7.0 masih OK)
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-app.js";
import { 
  getDatabase, 
  ref, 
  set, 
  get, 
  push, 
  serverTimestamp, 
  onValue 
} from "https://www.gstatic.com/firebasejs/10.7.0/firebase-database.js";

// Inisialisasi app
const app = initializeApp(firebaseConfig);
const db = getDatabase(app);

// EKSPOR KE WINDOW AGAR SEMUA HALAMAN BISA PAKAI (sama seperti sebelumnya)
window.db = db;
window.ref = ref;
window.get = get;
window.set = set;
window.push = push;
window.onValue = onValue;
window.serverTimestamp = serverTimestamp;

// FUNGSI GLOBAL YANG WAJIB ADA (tidak berubah)
window.getUsers = async () => {
  const snap = await get(ref(db, 'users'));
  return snap.val() ? Object.values(snap.val()) : [];
};

window.saveUsers = async (data) => {
  await set(ref(db, 'users'), data);
};

window.addActivityLog = async (entry) => {
  await push(ref(db, 'activityLog'), {
    ...entry,
    timestamp: serverTimestamp(),
    timestampDisplay: new Date().toLocaleString('id-ID')
  });
};

// Pastikan siap sebelum dipakai
window.firebaseReady = true;
console.log("Firebase PRODUCTION SIAP 100% — window.db, ref, get, onValue, addActivityLog sudah tersedia!");