<?php

class KasirController
{
    public function index()
    {
        $viewPath = dirname(__DIR__) . '/Views/kasir/kasir.php';

        if (!file_exists($viewPath)) {
            die("Error: View kasir.php tidak ditemukan di " . $viewPath);
        }

        require_once $viewPath;
    }

    public function dashboard()
    {
        $viewPath = dirname(__DIR__) . '/Views/kasir/dashboard.php';

        if (!file_exists($viewPath)) {
            die("Error: View kasir/dashboard.php tidak ditemukan di " . $viewPath);
        }

        require_once $viewPath;
    }

    public function stok()
    {
        $viewPath = dirname(__DIR__) . '/Views/kasir/stok.php';

        if (!file_exists($viewPath)) {
            die("Error: View kasir/stok.php tidak ditemukan di " . $viewPath);
        }

        require_once $viewPath;
    }
}