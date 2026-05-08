<?php


$loggedIn = isset($_SESSION['login']) && $_SESSION['login'] !== '';
$displayName = $loggedIn
    ? trim(($_SESSION['csn'] ?? '') . ' ' . ($_SESSION['un'] ?? '')) . ' (' . $_SESSION['login'] . ')'
    : 'Vendég';

function clean_text(?string $value): string {
    return trim((string) $value);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targy = clean_text($_POST['targy'] ?? '');
    $uzenet = clean_text($_POST['uzenet'] ?? '');

    $errors = [];


    if (strlen($targy) < 3) {
        $errors[] = 'A tárgy legalább 3 karakter legyen.';
    }

    if (strlen($uzenet) < 10) {
        $errors[] = 'Az üzenet legalább 10 karakter legyen.';
    }

    if ($errors) {
        ?>
        <section class="tablazat">
            <h2>Az üzenet nem sikerült elküldeni</h2>
            <p class="auth-message"><?= htmlspecialchars(implode(' ', $errors), ENT_QUOTES, 'UTF-8') ?></p>
            <a class="gomb-link" href="kapcsolat.html">Vissza az űrlaphoz</a>
        </section>
        <?php
       
        exit;
    }


    try {
        $stmt = $dbh->prepare(
            "INSERT INTO uzenetek (nev, targy, uzenet, datum)
             VALUES (:nev, :targy, :uzenet, NOW())"
        );
        $stmt->execute([
            ':nev'=>$displayName,
            ':targy' => $targy,
            ':uzenet' => $uzenet
        ]);

        
        ?>
        <section class="tablazat">
            <h2>Az üzenet elküldve</h2>
            <div class="submission-box">
                <p><strong>Küldő:</strong> <?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?></p>
                <p><strong>Tárgy:</strong> <?= htmlspecialchars($targy, ENT_QUOTES, 'UTF-8') ?></p>
                <p><strong>Üzenet:</strong> <?= nl2br(htmlspecialchars($uzenet, ENT_QUOTES, 'UTF-8')) ?></p>
            </div>
            <a class="gomb-link" href="/kapcsolat">Új üzenet írása</a>
        </section>
        <?php
        
        exit;
    } catch (PDOException $e) {
        echo $e->getMessage();
        ?>
        <section class="tablazat">
            <h2>Hiba történt</h2>
            <p class="auth-message">Az üzenet mentése nem sikerült.</p>
            <a class="gomb-link" href="/kapcsolat">Vissza az űrlaphoz</a>

        </section>
        <?php
        exit;
    }
}

if (!$loggedIn) {
    ?>
    <section class="tablazat">
        <h2>Az üzenetek megtekintéséhez be kell jelentkezni.</h2>
        <a class="gomb-link" href="bejelentkezes.html">Bejelentkezés</a>
    </section>
    <?php
    exit;
}

try {
    $stmt = $dbh->query(
        "SELECT  nev, targy, uzenet, datum
         FROM uzenetek
         ORDER BY datum DESC"
    );
    $rows = $stmt->fetchAll();
} catch (PDOException $e) {
    $rows = [];
}

?>
<section class="tablazat">
    <h2>Elküldött üzenetek</h2>

    <div class="table-wrap">
        <table id="lista">
            <thead>
                <tr>
                    <th>Dátum</th>
                    <th>Küldő</th>
                    <th>Tárgy</th>
                    <th>Üzenet</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rows): ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['datum'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['nev'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
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
