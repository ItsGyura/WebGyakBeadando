<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] === '') {
    header('Location: bejelentkezes.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['fileToUpload'])) {
    header('Location: kepek.php?upload=error');
    exit;
}

$targetDir = __DIR__ . '/uploads/';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$file = $_FILES['fileToUpload'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    header('Location: kepek.php?upload=error');
    exit;
}

$check = @getimagesize($file['tmp_name']);
if ($check === false) {
    header('Location: kepek.php?upload=error');
    exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = $finfo ? finfo_file($finfo, $file['tmp_name']) : '';
if ($finfo) {
    finfo_close($finfo);
}

$allowedMimes = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
];

if (!isset($allowedMimes[$mimeType])) {
    header('Location: kepek.php?upload=error');
    exit;
}

$extension = $allowedMimes[$mimeType];
$baseName = pathinfo($file['name'], PATHINFO_FILENAME);
$baseName = preg_replace('/[^A-Za-z0-9_\-]/u', '_', $baseName);
if ($baseName === '') {
    $baseName = 'kep';
}

$uniqueName = $baseName . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
$targetFile = $targetDir . $uniqueName;

if (move_uploaded_file($file['tmp_name'], $targetFile)) {
    header('Location: kepek.php?upload=success');
    exit;
}

header('Location: kepek.php?upload=error');
exit;
?>
