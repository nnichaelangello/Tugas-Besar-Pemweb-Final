<?php
header('Content-Type: text/html; charset=utf-8');

require_once '../app/Controllers/AuthController.php';

$controller = new AuthController();
$controller->index();