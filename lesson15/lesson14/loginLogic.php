<?php
ssesion_start();
require 'config.php';

if(isset($_POST['submit']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if( empty($username) || empty($password))
    {
        echo "Fill all fields!";
        header("refresh:2; url=login.php")
    }else
    {
        $sql = "SELECT username FROM users WHERE username=:username";
        $tempSQL = $conn->prepare($sql);
        $tempSQL->bindParam(':username', $username);

        $tempSQL->exetucte();

        if($tempSQL->rowCount() > 0)
        {
            $data = $insertSql->fetch();

            if($password == $data['password']){
                $_SESSION['username'] = $data["username"];
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