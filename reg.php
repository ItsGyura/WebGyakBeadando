<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: regiszt.html');
    exit;
}

$felhasznalo = trim($_POST['felhasznalo'] ?? '');
$jelszo = trim($_POST['jelszo'] ?? '');
$vezeteknev = trim($_POST['vezeteknev'] ?? '');
$utonev = trim($_POST['utonev'] ?? '');

if ($felhasznalo === '' || $jelszo === '' || $vezeteknev === '' || $utonev === '') {
    header('Location: regiszt.html?reg=hibas');
    exit;
}

try {
    $sqlSelect = "SELECT id FROM felhasznalok WHERE bejelentkezes = :bejelentkezes";
    $sth = $pdo->prepare($sqlSelect);
    $sth->execute([':bejelentkezes' => $felhasznalo]);

    if ($sth->fetch()) {
        header('Location: regiszt.html?reg=foglalt');
        exit;
    }

    $sqlInsert = "INSERT INTO felhasznalok (csaladi_nev, uto_nev, bejelentkezes, jelszo)
                  VALUES (:csaladi_nev, :uto_nev, :bejelentkezes, SHA1(:jelszo))";
    $stmt = $pdo->prepare($sqlInsert);
    $stmt->execute([
        ':csaladi_nev' => $vezeteknev,
        ':uto_nev' => $utonev,
        ':bejelentkezes' => $felhasznalo,
        ':jelszo' => $jelszo
    ]);

    header('Location: bejelentkezes.html?reg=sikeres');
    exit;
} catch (PDOException $e) {
    header('Location: regiszt.html?reg=hiba');
    exit;
}
?>
