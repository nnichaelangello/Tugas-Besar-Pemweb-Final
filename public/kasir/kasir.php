<?php
header('Content-Type: text/html; charset=utf-8');

require_once '../../app/Controllers/KasirController.php';

$controller = new KasirController();
$controller->index();