<?php 
include "../../database/db.php";
$id = $_GET['id'];

$gt = $conn->prepare('DELETE FROM writers WHERE Id=?');
$gt->bindValue(1, $id);
$gt->execute();

header('location: writers.php');



?>