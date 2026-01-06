<?php
header('Content-Type: text/html; charset=utf-8');

require_once '../../app/Controllers/AdminController.php';

$controller = new AdminController();
$controller->transaksi();