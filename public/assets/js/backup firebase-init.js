// assets/js/firebase-init.js → VERSI FINAL 100% STABIL
console.log("Firebase init dimulai...");

const firebaseConfig = {
  apiKey: "demo",
  authDomain: "localhost",
  databaseURL: "http://127.0.0.1:9000/?ns=demo-no-project",
  projectId: "demo-no-project"
};

import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-app.js";
import { getDatabase, ref, set, get, push, serverTimestamp, onValue } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-database.js";

const app = initializeApp(firebaseConfig);
const db = getDatabase(app);

// EKSPOR KE WINDOW AGAR SEMUA HALAMAN BISA PAKAI
window.db = db;
window.ref = ref;
window.get = get;
window.set = set;
window.push = push;
window.onValue = onValue;
window.serverTimestamp = serverTimestamp;

// FUNGSI GLOBAL YANG WAJIB ADA
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
console.log("Firebase SIAP 100% — window.db, ref, get, onValue, addActivityLog sudah tersedia!");