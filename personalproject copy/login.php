<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: welcome.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $usersFile = 'users.json';
    if (file_exists($usersFile)) {
        $users = json_decode(file_get_contents($usersFile), true);
        if (isset($users[$username]) && password_verify($password, $users[$username])) {
            $_SESSION['user'] = $username;
            header("Location: welcome.php");
            exit(); 
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "No users found. Please sign up first!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coca-Cola Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="login-container">
    <div class="logo">
      <img src="logo.png" alt="Coca-Cola Logo">
    </div>
    <h2>Welcome to Coca-Cola</h2>
    <p class="subtitle">Refresh your world. Log in to continue.</p>

    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="input-group">
        <input type="text" name="username" required placeholder="Username">
      </div>
      <div class="input-group">
        <input type="password" name="password" required placeholder="Password">
      </div>
      <button type="submit" class="login-btn">Login</button>
    </form>

    <p style="margin-top:15px;font-size:14px;">
      Don’t have an account? <a href="signup.php" style="color:#fff;text-decoration:underline;">Sign up here</a>
    </p>
  </div>

  <div class="bubbles"></div>

</body>
</html>
