<?php

	include_once('config.php');

	if(isset($_POST['submit']))
	{

	    $name = $_POST['name'];
		$surname = $_POST['surname'];
		$email = $_POST['email'];


			$sql = "INSERT INTO users(name,surname,email) VALUES ( :name, :surname, :email)";

			$insertSql = $conn->prepare($sql);

			$insertSql->bindParam(':name', $name);
			$insertSql->bindParam(':surname', $surname);
			$insertSql->bindParam(':email', $email);

			$insertSql->execute();

			echo"Data saved succesfully ...<br>";
		}	
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add a user</title>
</head>
<body>
<a href="index.php">Dashboard</a>
    <form action="add.php" method="POST">
        <input type="text" name="name" placeholder="Name"></br>
        <input type="text" name="surname" placeholder="Surname"></br>
        <input type="email" name="email" placeholder="Email"></br>
        <button type="submit" name="submit">Add</button>
    </form>
</body>
</html>

