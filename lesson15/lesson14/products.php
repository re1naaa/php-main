<?php
session_start();
include_once('config.php');

if(isset($_POST['submit']))
$title = $_POST['title'];
$description = $_POST['description'];
$quanity = $_POST['quantity'];
$price = $_POST['price'];

    if(empty($title) || empty($description) || empty($quantity) || empty($price))
    {
        echo "You need to fill all the fields.";
    }else
    {
        $sql = "SELECT title FROM products WHERE title=:title";
        $tempSQL = $conn->prepare($sql);
        $tempSQL->bindParam(':title', $title);
        $tempSQL->execute();

        if($tempSQL->rowCount() > 0)
        {
            echo"Title exists!";
            header("refresh:2; url=addProducts.php");
        }else
        {
            $sql = "insert into products (title, description, quantity, price)values(:title, :description, :quantity, :price)";
            $insertSql = $conn->prepare($sql);

            $insertSql->bindParam(':name', $name);
            $insertSql->bindParam(':surname', $surname);
            $insertSql->bindParam(':username', $username);
            $insertSql->bindParam(':email', $email);
            $insertSql->bindParam(':name', $password);

            $insertSql->execute();

            echo"Data saved successfully ...";
            header("refresh:2; url=login.php");
        }
}
?>
<?php include("footer.php"); ?>
