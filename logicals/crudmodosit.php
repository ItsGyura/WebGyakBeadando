<?php
require_once('includes/connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
$stmt = $dbh->prepare("UPDATE F1 SET  nev=?,helyszin=?,datum=? where azon = ?");
$stmt->execute([$_POST['palya'], $_POST['helyszin'],$_POST['datum'],$_POST['azon']]);
header('Location: /crud');

}



?>