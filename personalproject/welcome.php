<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$flavorsFile = 'flavors.json';

// Load existing flavors
$flavors = [];
if (file_exists($flavorsFile)) {
    $flavors = json_decode(file_get_contents($flavorsFile), true);
}

// Handle CRUD actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $new = [
                    'id' => uniqid(),
                    'name' => $_POST['name'],
                    'desc' => $_POST['desc'],
                    'img' => $_POST['img']
                ];
                $flavors[] = $new;
                file_put_contents($flavorsFile, json_encode($flavors, JSON_PRETTY_PRINT));
                break;

            case 'delete':
                $id = $_POST['id'];
                $flavors = array_filter($flavors, fn($f) => $f['id'] !== $id);
                file_put_contents($flavorsFile, json_encode(array_values($flavors), JSON_PRETTY_PRINT));
                break;

            case 'edit':
                $id = $_POST['id'];
                foreach ($flavors as &$f) {
                    if ($f['id'] === $id) {
                        $f['name'] = $_POST['name'];
                        $f['desc'] = $_POST['desc'];
                        $f['img'] = $_POST['img'];
                    }
                }
                file_put_contents($flavorsFile, json_encode($flavors, JSON_PRETTY_PRINT));
                break;
        }
    }
    header("Location: welcome.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Coca-Cola Flavors Manager</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: radial-gradient(circle at top, #ff0000, #660000);
      color: white;
      margin: 0;
      overflow-x: hidden;
    }

    header {
      text-align: center;
      padding: 30px 20px 10px;
    }

    header img {
      width: 120px;
      animation: float 3s ease-in-out infinite;
    }

    h1 {
      margin: 10px 0;
    }

    .add-form {
      text-align: center;
      margin: 30px auto;
      background: rgba(255,255,255,0.15);
      padding: 20px;
      border-radius: 15px;
      width: 80%;
      max-width: 600px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      animation: fadeIn 1s ease-out;
    }

    .add-form input {
      padding: 10px;
      margin: 5px;
      border-radius: 10px;
      border: none;
      outline: none;
      width: 30%;
      font-size: 14px;
    }

    .add-btn {
      background: linear-gradient(45deg, #ff0000, #ff5c5c);
      border: none;
      padding: 10px 20px;
      border-radius: 30px;
      color: white;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
    }

    .add-btn:hover {
      background: linear-gradient(45deg, #cc0000, #ff1a1a);
      transform: translateY(-2px);
    }

    .flavors-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      padding: 20px;
    }

    .flavor-card {
      background: rgba(255,255,255,0.15);
      border-radius: 20px;
      width: 250px;
      overflow: hidden;
      text-align: center;
      box-shadow: 0 10px 25px rgba(0,0,0,0.3);
      transition: 0.3s;
      position: relative;
    }

    .flavor-card:hover {
      transform: scale(1.05);
      box-shadow: 0 15px 35px rgba(255,0,0,0.5);
    }

    .flavor-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .flavor-info {
      padding: 15px;
    }

    .flavor-info h3 {
      margin: 5px 0;
    }

    .crud-btns {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 10px;
    }

    .crud-btns form {
      display: inline;
    }

    .crud-btns button {
      background: rgba(255,255,255,0.2);
      border: none;
      border-radius: 20px;
      padding: 6px 15px;
      color: white;
      cursor: pointer;
      font-size: 13px;
      transition: 0.3s;
    }

    .crud-btns button:hover {
      background: rgba(255,255,255,0.4);
    }

    .logout-container {
      text-align: center;
      margin: 40px 0;
    }

    .logout-btn {
      display: inline-block;
      background: linear-gradient(45deg, #ff0000, #ff5c5c);
      padding: 12px 35px;
      border-radius: 40px;
      color: white;
      font-weight: 600;
      text-decoration: none;
      font-size: 16px;
      box-shadow: 0 5px 15px rgba(255,0,0,0.4);
      transition: all 0.3s ease;
    }

    .logout-btn:hover {
      background: linear-gradient(45deg, #cc0000, #ff1a1a);
      transform: translateY(-3px);
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<header>
  <img src="logo.png" alt="Coca-Cola Logo">
  <h1>Welcome, <?= htmlspecialchars($_SESSION['user']) ?>!</h1>
  <p>Manage your Coca-Cola flavor collection 🍹</p>
</header>

<!-- ADD FORM -->
<div class="add-form">
  <form method="POST">
    <input type="hidden" name="action" value="add">
    <input type="text" name="name" placeholder="Flavor name" required>
    <input type="text" name="desc" placeholder="Description" required>
    <input type="text" name="img" placeholder="Image URL" required>
    <button type="submit" class="add-btn">➕ Add Flavor</button>
  </form>
</div>

<!-- DISPLAY FLAVORS -->
<section class="flavors-container">
  <?php if (!empty($flavors)): ?>
    <?php foreach ($flavors as $f): ?>
      <div class="flavor-card">
        <img src="<?= htmlspecialchars($f['img']) ?>" alt="<?= htmlspecialchars($f['name']) ?>">
        <div class="flavor-info">
          <h3><?= htmlspecialchars($f['name']) ?></h3>
          <p><?= htmlspecialchars($f['desc']) ?></p>
        </div>
        <div class="crud-btns">
          <!-- Edit form -->
          <form method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?= $f['id'] ?>">
            <input type="text" name="name" value="<?= htmlspecialchars($f['name']) ?>" required>
            <input type="text" name="desc" value="<?= htmlspecialchars($f['desc']) ?>" required>
            <input type="text" name="img" value="<?= htmlspecialchars($f['img']) ?>" required>
            <button type="submit">✏️ Edit</button>
          </form>
          <!-- Delete form -->
          <form method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= $f['id'] ?>">
            <button type="submit">🗑️ Delete</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="text-align:center;">No flavors yet. Add one above!</p>
  <?php endif; ?>
</section>

<div class="logout-container">
  <a href="logout.php" class="logout-btn">Log Out</a>
</div>

</body>
</html>

