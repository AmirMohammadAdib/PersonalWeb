<?php 
include "../../database/db.php";
$id = $_GET['id'];

$gt = $conn->prepare('DELETE FROM course WHERE Id=?');
$gt->bindValue(1, $id);
$gt->execute();

header('location: course.php');



?>