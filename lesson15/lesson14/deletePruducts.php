<?php 

include_once("config.php");

include_once("header.php");

$id = $_GET['id'];

$sql = "DELETE FROM products WHERE id=:id";

$getUsers = $conn->prepare($sql);

$getUsers->bindParam(':id', $id);

$getUsers->execute();

header('Location:productDashboard.php');

include_once("footer.php");
	
?>
