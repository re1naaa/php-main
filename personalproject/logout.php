<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome to Coca-Cola</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #ff0000;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      flex-direction: column;
    }
    a {
      color: white;
      background: rgba(255,255,255,0.2);
      padding: 10px 20px;
      border-radius: 25px;
      text-decoration: none;
      margin-top: 20px;
      transition: 0.3s;
    }
    a:hover {
      background: rgba(255,255,255,0.4);
    }
  </style>
</head>
<body>
  <h1>Welcome, <?= htmlspecialchars($_SESSION['user']) ?>!</h1>
  <p>Enjoy the refreshing taste of success 🥤</p>
  <a href="logout.php">Log out</a>
</body>
</html>
