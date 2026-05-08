<?php
require_once('includes/connect.php');

if ($_SERVER['REQUEST_METHOD']=='POST') {

if(!empty($_POST['azon'])) {
            $stmt = $dbh->prepare("DELETE FROM F1 WHERE azon = ?");
            $stmt->execute([$_POST['azon']]);
        }

        }
header('Location: /crud');
?>