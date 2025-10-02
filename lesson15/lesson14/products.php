<?php
ssesion_start();
require 'config.php';

if(isset($_POST['update']))
{
	$id = $_POST['id'];
	$title = $_POST['title'];
	$description = $_POST['description'];
	$quantity = $_POST['quantity'];
	$price = $_POST['price'];

    if( empty($title) || empty($id))
    {
        echo "Fill all fields!";
        header("refresh:2; url=login.php")
    }else
    {
        $sql = "SELECT title FROM products WHERE title=:title";
        $tempSQL = $conn->prepare($sql);
        $tempSQL->bindParam(':id', $id);
        $tempSQL->bindParam(':title', $title);
        $tempSQL->bindParam(':description', $description);
        $tempSQL->bindParam(':quantity', $quantity);
        $tempSQL->bindParam(':price', $price);

        $tempSQL->exetucte();

        if($tempSQL->rowCount() > 0)
        {
            $data = $insertSql->fetch();

            if($title == $data['title']){
                $_SESSION['title'] = $data["title"];
                header("Location: dashboard.php")
                exit;
            }
        }else
        {
            echo"User not found";
            header("refresh:2; url=login.php");
            exit;
        }
    }
}

?>