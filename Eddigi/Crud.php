<?php
require_once 'connect.php';
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        
        $stmt = $pdo->query("SELECT * FROM F1");
        $gp = $stmt->fetchAll();
        echo json_encode($gp);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->nev) && !empty($data->helyszin) && !empty($data->datum) ) {
            $stmt = $pdo->prepare("INSERT INTO F1 (nev,helyszin,datum) VALUES (?, ?, ?)");
            $stmt->execute([$data->nev, $data->helyszin,$data->datum]);
            echo json_encode(["message" => "Sikeres hozzáadás"]);
        }
        break;

    case 'PUT':
        $data= json_decode(file_get_contents("php://input"));
        $stmt = $pdo->prepare("UPDATE F1 SET  nev=?,helyszin=?,datum=? where azon = ?");
        $stmt->execute([$data->nev, $data->helyszin,$data->datum,$data->azon]);
        echo json_encode(["message" =>"Sikeres módosítás"]);
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->azon)) {
            $stmt = $pdo->prepare("DELETE FROM F1 WHERE azon = ?");
            $stmt->execute([$data->azon]);
            echo json_encode(["message" => "Sikeres törlés"]);
        }
        break;
}
?>