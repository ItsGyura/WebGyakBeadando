<?php
session_start();

function galeriaKepLista(array $folderek): array {
    $eredmeny = [];
    foreach ($folderek as $folder) {
        $abs = __DIR__ . '/' . trim($folder, '/');
        if (!is_dir($abs)) {
            continue;
        }

        $files = glob($abs . '/*.{jpg,jpeg,png,gif,webp,JPG,JPEG,PNG,GIF,WEBP}', GLOB_BRACE) ?: [];
        foreach ($files as $file) {
            $eredmeny[] = [
                'path' => str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $file),
                'time' => filemtime($file) ?: 0
            ];
        }
    }

    usort($eredmeny, fn($a, $b) => $b['time'] <=> $a['time']);
    return $eredmeny;
}

$login = isset($_SESSION['login']) && $_SESSION['login'] !== '';
$notice = $_GET['upload'] ?? '';
$images = galeriaKepLista(['uploads', 'kepek']);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Képek</title>
    <link rel="stylesheet" href="style.css">
    <script src="menu.js" ></script>
</head>
<body>
<main>
    <section class="tablazat gallery-card">
        <h2>Galéria</h2>
        <?php if ($notice === 'success'): ?>
            <p class="auth-message auth-message--success">A képfeltöltés sikeres.</p>
        <?php elseif ($notice === 'error'): ?>
            <p class="auth-message">A képfeltöltés nem sikerült.</p>
        <?php endif; ?>

        <?php if ($login): ?>
            <form class="upload-form" action="upload.php" method="post" enctype="multipart/form-data">
                <label for="fileToUpload">Új kép feltöltése:</label>
                <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*">
                <button type="submit" name="submit" class="gomb">Feltöltés</button>
            </form>
        <?php else: ?>
            <p class="auth-message">Képfeltöltéshez jelentkezzen be.</p>
        <?php endif; ?>

        <div class="gallery-grid">
            <?php if ($images): ?>
                <?php foreach ($images as $image): ?>
                    <figure class="gallery-item">
                        <img src="<?= htmlspecialchars($image['path'], ENT_QUOTES, 'UTF-8') ?>" alt="Feltöltött kép">
                    </figure>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Még nincs feltöltött kép.</p>
            <?php endif; ?>
        </div>
    </section>
</main>
</body>
</html>
