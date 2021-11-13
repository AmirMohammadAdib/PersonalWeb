<?php 
include "../database/db.php";

$delete = $conn->prepare('DELETE FROM `store` WHERE course_id=?');
$delete->bindValue(1, $_GET['id']);
$delete->execute();

header('location: basket.php');

?>