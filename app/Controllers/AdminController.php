<?php

class AdminController
{
    public function dashboard()
    {
        // Path absolut ke folder views dari root proyek
        $viewPath = dirname(__DIR__) . '/Views/admin/dashboard.php';

        if (!file_exists($viewPath)) {
            die("Error: View file tidak ditemukan di " . $viewPath);
        }

        require_once $viewPath;
    }

    public function transaksi()
    {
        $viewPath = dirname(__DIR__) . '/Views/admin/transaksi.php';

        if (!file_exists($viewPath)) {
            die("Error: View file tidak ditemukan di " . $viewPath);
        }

        require_once $viewPath;
    }

    public function produk()
    {
        // Path absolut aman ke folder views
        $viewPath = dirname(__DIR__) . '/Views/admin/produk.php';

        if (!file_exists($viewPath)) {
            die("Error: View produk.php tidak ditemukan di " . $viewPath);
        }

        require_once $viewPath;
    }

    public function stok()
    {
        // Path absolut aman ke folder views
        $viewPath = dirname(__DIR__) . '/Views/admin/stok.php';

        if (!file_exists($viewPath)) {
            die("Error: View stok.php tidak ditemukan di " . $viewPath);
        }

        require_once $viewPath;
    }

    public function laporan()
    {
        // Path absolut aman ke folder views
        $viewPath = dirname(__DIR__) . '/Views/admin/laporan.php';

        if (!file_exists($viewPath)) {
            die("Error: View laporan.php tidak ditemukan di " . $viewPath);
        }

        require_once $viewPath;
    }

    public function pengguna()
    {
        // Path absolut aman ke folder views
        $viewPath = dirname(__DIR__) . '/Views/admin/pengguna.php';

        if (!file_exists($viewPath)) {
            die("Error: View pengguna.php tidak ditemukan di " . $viewPath);
        }

        require_once $viewPath;
    }

}

// Helper sementara untuk simulasi localStorage di server (tidak digunakan di logic utama)
function localStorage_get($key) {
    return $_COOKIE[$key] ?? null;
}