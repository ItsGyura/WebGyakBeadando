<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

$isLoggedIn = isset($_SESSION['login']) && $_SESSION['login'] !== '';
$displayName = '';

if ($isLoggedIn) {
    $csaladiNev = $_SESSION['csn'] ?? '';
    $utoNev = $_SESSION['un'] ?? '';
    $loginNev = $_SESSION['login'] ?? '';
    $displayName = trim($csaladiNev . ' ' . $utoNev) . " ({$loginNev})";
}

echo json_encode([
    'logged_in' => $isLoggedIn,
    'display_name' => $displayName
], JSON_UNESCAPED_UNICODE);
?>
