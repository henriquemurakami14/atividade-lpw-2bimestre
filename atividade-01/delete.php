<?php

require_once("util/Connection.php");

$connection = Connection::getConnection();

if (isset($_GET['id'])) {
    $carro = $_GET['id'];
    $sql = "DELETE FROM carros WHERE id = ?";
    $stm = $connection->prepare($sql);
    $stm->execute([$carro]);
    header("location: form.php");
}else{
    header("Location: form.php?erro=1");
}