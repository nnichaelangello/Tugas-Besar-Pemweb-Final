<?php

class AuthController
{
    public function index()
    {
        // Hanya load view autentikasi
        // Semua logika JavaScript tetap di view karena berjalan di client-side
        require_once '../app/Views/auth/index.php';
    }
}