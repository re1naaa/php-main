<?php

include_once('config.php');

include_once('header.php');

if(isset($_POST['update']))
{
	$id = $_POST['id'];
	$title = $_POST['title'];
	$description = $_POST['description'];
	$quantity = $_POST['quantity'];
	$price = $_POST['price'];

	$sql = "UPDATE products SET username=:username, name=:name, surname=:surname, email=:email WHERE id=:id";
	$prep = $conn->prepare($sql);
	$prep->bindParam(':id', $id);
	$prep->bindParam(':username', $title);
	$prep->bindParam(':name', $description);
	$prep->bindParam(':surname', $quantity);
	$prep->bindParam(':email', $price);

	$prep->execute();

	header("Location:dashboard.php");
}
include_once('footer.php');
?>

