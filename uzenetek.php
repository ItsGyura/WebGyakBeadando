<?php
require_once 'connect.php';


$loggedIn = isset($_SESSION['login']) && $_SESSION['login'] !== '';
$displayName = $loggedIn
    ? trim(($_SESSION['csn'] ?? '') . ' ' . ($_SESSION['un'] ?? '')) . ' (' . $_SESSION['login'] . ')'
    : 'Vendég';

function clean_text(?string $value): string {
    return trim((string) $value);
}

function render_page_start(string $title): void {
    ?>
    <!DOCTYPE html>
    <html lang="hu">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
        <link rel="stylesheet" href="style.css">
        <script src="menu.js" defer></script>
    </head>
    <body>
    <main>
    <?php
}

function render_page_end(): void {
    echo "</main></body></html>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nev = clean_text($_POST['nev'] ?? '');
    $email = clean_text($_POST['email'] ?? '');
    $targy = clean_text($_POST['targy'] ?? '');
    $uzenet = clean_text($_POST['uzenet'] ?? '');

    $errors = [];

    if (strlen($nev) < 2) {
        $errors[] = 'A név legalább 2 karakter legyen.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Érvénytelen e-mail cím.';
    }

    if (strlen($targy) < 3) {
        $errors[] = 'A tárgy legalább 3 karakter legyen.';
    }

    if (strlen($uzenet) < 10) {
        $errors[] = 'Az üzenet legalább 10 karakter legyen.';
    }

    if ($errors) {
        render_page_start('Kapcsolat - hiba');
        ?>
        <section class="tablazat">
            <h2>Az üzenet nem sikerült elküldeni</h2>
            <p class="auth-message"><?= htmlspecialchars(implode(' ', $errors), ENT_QUOTES, 'UTF-8') ?></p>
            <a class="gomb-link" href="kapcsolat.html">Vissza az űrlaphoz</a>
        </section>
        <?php
        render_page_end();
        exit;
    }

    $senderName = $loggedIn ? $displayName : 'Vendég';

    try {
        $stmt = $pdo->prepare(
            "INSERT INTO uzenetek (nev, email, targy, uzenet, datum)
             VALUES (:nev, :email, :targy, :uzenet, NOW())"
        );
        $stmt->execute([
            ':nev' => $senderName,
            ':email' => $email,
            ':targy' => $targy,
            ':uzenet' => $uzenet
        ]);

        render_page_start('Üzenet elküldve');
        ?>
        <section class="tablazat">
            <h2>Az üzenet elküldve</h2>
            <div class="submission-box">
                <p><strong>Név:</strong> <?= htmlspecialchars($nev, ENT_QUOTES, 'UTF-8') ?></p>
                <p><strong>E-mail:</strong> <?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></p>
                <p><strong>Tárgy:</strong> <?= htmlspecialchars($targy, ENT_QUOTES, 'UTF-8') ?></p>
                <p><strong>Küldő:</strong> <?= htmlspecialchars($senderName, ENT_QUOTES, 'UTF-8') ?></p>
                <p><strong>Üzenet:</strong> <?= nl2br(htmlspecialchars($uzenet, ENT_QUOTES, 'UTF-8')) ?></p>
            </div>
            <a class="gomb-link" href="kapcsolat.html">Új üzenet írása</a>
        </section>
        <?php
        render_page_end();
        exit;
    } catch (PDOException $e) {
        render_page_start('Kapcsolat - hiba');
        ?>
        <section class="tablazat">
            <h2>Hiba történt</h2>
            <p class="auth-message">Az üzenet mentése nem sikerült.</p>
            <a class="gomb-link" href="kapcsolat.html">Vissza az űrlaphoz</a>
        </section>
        <?php
        render_page_end();
        exit;
    }
}

if (!$loggedIn) {
    render_page_start('Üzenetek');
    ?>
    <section class="tablazat">
        <h2>Az üzenetek megtekintéséhez be kell jelentkezni.</h2>
        <a class="gomb-link" href="bejelentkezes.html">Bejelentkezés</a>
    </section>
    <?php
    render_page_end();
    exit;
}

try {
    $stmt = $pdo->query(
        "SELECT id, nev, email, targy, uzenet, datum
         FROM uzenetek
         ORDER BY datum DESC, id DESC"
    );
    $rows = $stmt->fetchAll();
} catch (PDOException $e) {
    $rows = [];
}

render_page_start('Üzenetek');
?>
<section class="tablazat">
    <h2>Elküldött üzenetek</h2>
    <p class="auth-message auth-message--success">Bejelentkezett felhasználó: <?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?></p>

    <div class="table-wrap">
        <table id="lista">
            <thead>
                <tr>
                    <th>Dátum</th>
                    <th>Küldő</th>
                    <th>E-mail</th>
                    <th>Tárgy</th>
                    <th>Üzenet</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rows): ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['datum'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['nev'] ?? 'Vendég', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['targy'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['uzenet'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Még nincs mentett üzenet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php
render_page_end();
?>
