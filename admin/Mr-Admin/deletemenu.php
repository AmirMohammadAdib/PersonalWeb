<?php 
include "../../database/db.php";
$id = $_GET['id'];

$gt = $conn->prepare('DELETE FROM menus WHERE Id=?');
$gt->bindValue(1, $id);
$gt->execute();

header('location: menu.php');



?>