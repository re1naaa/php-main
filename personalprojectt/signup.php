<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm']);

    if ($password !== $confirm) {
        $error = "Passwords do not match!";
    } elseif (strlen($username) < 3 || strlen($password) < 4) {
        $error = "Username or password too short!";
    } else {
        $usersFile = 'users.json';
        $users = [];

        if (file_exists($usersFile)) {
            $users = json_decode(file_get_contents($usersFile), true);
        }

        if (isset($users[$username])) {
            $error = "Username already exists!";
        } else {
            $users[$username] = password_hash($password, PASSWORD_DEFAULT);
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

            $_SESSION['user'] = $username;
            header("Location: welcome.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Coca-Cola</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="login-container">
    <div class="logo">
      <img src="logo.png" alt="Coca-Cola Logo">
    </div>
    <h2>Create Your Account</h2>
    <p class="subtitle">Join the Coca-Cola family 🍾</p>

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
      <div class="input-group">
        <input type="password" name="confirm" required placeholder="Confirm Password">
      </div>
      <button type="submit" class="login-btn">Sign Up</button>
    </form>

    <p style="margin-top:15px;font-size:14px;">
      Already have an account? <a href="login.php" style="color:#fff;text-decoration:underline;">Login here</a>
    </p>
  </div>

  <div class="bubbles"></div>

</body>
</html>

