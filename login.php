<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: bejelentkezes.html');
    exit;
}

$felhasznalo = trim($_POST['felhasznalo'] ?? '');
$jelszo = trim($_POST['jelszo'] ?? '');

if ($felhasznalo === '' || $jelszo === '') {
    header('Location: bejelentkezes.html?login=hibas');
    exit;
}

try {
    $sqlSelect = "SELECT id, csaladi_nev, uto_nev
                  FROM felhasznalok
                  WHERE bejelentkezes = :bejelentkezes
                    AND jelszo = SHA1(:jelszo)";
    $sth = $pdo->prepare($sqlSelect);
    $sth->execute([
        ':bejelentkezes' => $felhasznalo,
        ':jelszo' => $jelszo
    ]);
    $row = $sth->fetch();

    if ($row) {
        $_SESSION['csn'] = $row['csaladi_nev'];
        $_SESSION['un'] = $row['uto_nev'];
        $_SESSION['login'] = $felhasznalo;

        header('Location: index.html?login=sikeres');
        exit;
    }

    header('Location: bejelentkezes.html?login=hibas');
    exit;
} catch (PDOException $e) {
    header('Location: bejelentkezes.html?login=hiba');
    exit;
}
?>
