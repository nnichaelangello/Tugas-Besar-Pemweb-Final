@echo off
REM Pindah ke direktori tempat file .bat berada
cd /d %~dp0

REM Masuk ke folder public
cd public

REM Jalankan PHP server di background
start "" php -S localhost:8000

REM Tunggu sebentar agar server siap
timeout /t 2 /nobreak > nul

REM Buka browser default
start http://localhost:8000
