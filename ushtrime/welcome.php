<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
$flavorsFile = 'flavors.json';

// Lexo shijet ekzistuese
$flavors = [];
if (file_exists($flavorsFile)) {
    $data = file_get_contents($flavorsFile);
    $flavors = json_decode($data, true);
    if (!is_array($flavors)) $flavors = [];
}

// CRUD vetëm për "reinabeadini"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user === 'reinabeadini') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $flavors[] = [
                    'id' => uniqid(),
                    'name' => trim($_POST['name']),
                    'desc' => trim($_POST['desc']),
                    'img' => trim($_POST['img'])
                ];
                break;
            case 'delete':
                $id = $_POST['id'];
                $flavors = array_values(array_filter($flavors, fn($f) => $f['id'] !== $id));
                break;
            case 'edit':
                $id = $_POST['id'];
                foreach ($flavors as &$f) {
                    if ($f['id'] === $id) {
                        $f['name'] = trim($_POST['name']);
                        $f['desc'] = trim($_POST['desc']);
                        $f['img'] = trim($_POST['img']);
                    }
                }
                unset($f);
                break;
        }
        file_put_contents($flavorsFile, json_encode($flavors, JSON_PRETTY_PRINT));
    }
    header("Location: welcome.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Coca-Cola Dashboard</title>
<style>
body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  color: white;
  overflow-x: hidden;
}

/* Background video */
.video-bg {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  object-fit: cover;
  z-index: -1;
  filter: brightness(60%) blur(1px);
}

/* Header */
header {
  text-align: center;
  padding: 40px 20px 10px;
  z-index: 2;
  position: relative;
}
header img {
  width: 130px;
  animation: float 3s ease-in-out infinite;
}
h1 {
  margin: 10px 0;
  font-size: 2rem;
  letter-spacing: 1px;
}

/* Navbar buttons */
.nav-links {
  text-align: center;
  margin-bottom: 25px;
}
.nav-btn {
  background: rgba(255,255,255,0.2);
  border-radius: 30px;
  padding: 10px 25px;
  color: white;
  text-decoration: none;
  font-weight: bold;
  margin: 0 10px;
  transition: 0.3s;
}
.nav-btn:hover {
  background: #ff0000;
  box-shadow: 0 0 15px #ff3c3c;
  transform: translateY(-3px);
}

/* Add Flavor form */
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

/* Search bar */
.search-container {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 20px auto;
}
.search-box {
  position: relative;
  width: 50%;
  max-width: 400px;
}
.search-box input {
  width: 100%;
  padding: 12px 40px;
  border-radius: 25px;
  border: none;
  outline: none;
  text-align: center;
  font-size: 15px;
  box-shadow: 0 0 10px rgba(255,0,0,0.4);
  background: rgba(255,255,255,0.2);
  color: white;
}
.search-box i {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #fff;
}

/* Flavor Cards */
.flavors-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 25px;
  padding: 20px;
}
.flavor-card {
  background: rgba(255,255,255,0.15);
  border-radius: 20px;
  width: 270px;
  overflow: hidden;
  text-align: center;
  box-shadow: 0 10px 25px rgba(0,0,0,0.3);
  transition: 0.4s;
  transform-style: preserve-3d;
  position: relative;
}
.flavor-card:hover {
  transform: translateY(-10px) scale(1.05);
  box-shadow: 0 15px 35px rgba(255,0,0,0.6);
}
.flavor-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  transition: 0.4s;
}
.flavor-info {
  padding: 15px;
  background: rgba(0,0,0,0.3);
}
.flavor-info h3 {
  font-size: 1.3rem;
  margin-bottom: 5px;
  color: #ffcccc;
}
.flavor-info p {
  font-size: 0.95rem;
}

/* CRUD buttons */
.crud-btns {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 15px;
}
.crud-btns form {
  display: flex;
  flex-direction: column;
  gap: 5px;
}
.crud-btns input {
  width: 220px;
  padding: 5px;
  border-radius: 10px;
  border: none;
  text-align: center;
  outline: none;
}
.crud-btns button {
  background: linear-gradient(45deg, #ff0000, #ff5c5c);
  border: none;
  border-radius: 20px;
  padding: 6px 15px;
  color: white;
  cursor: pointer;
  font-size: 13px;
  font-weight: bold;
  transition: 0.3s;
}
.crud-btns button:hover {
  background: linear-gradient(45deg, #cc0000, #ff1a1a);
  transform: scale(1.05);
}

/* Logout button */
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

/* Animations */
@keyframes float {
  0%,100% {transform: translateY(0);}
  50% {transform: translateY(-10px);}
}
@keyframes fadeIn {
  from {opacity:0; transform: translateY(20px);}
  to {opacity:1; transform: translateY(0);}
}

/* Bubbles */
.bubble {
  position: fixed;
  bottom: -100px;
  background: rgba(255,255,255,0.2);
  border-radius: 50%;
  animation: rise 10s infinite ease-in;
  z-index: 0;
}
@keyframes rise {
  0% {transform: translateY(0) scale(0.5); opacity: 0.7;}
  100% {transform: translateY(-110vh) scale(1.2); opacity: 0;}
}
</style>
</head>
<body>

<video autoplay muted loop class="video-bg">
  <source src="5561389-uhd_3840_2160_25fps.mp4" type="video/mp4">
</video>

<?php for($i=0;$i<12;$i++): ?>
  <div class="bubble" style="left:<?=rand(0,100)?>%;width:<?=rand(10,40)?>px;height:<?=rand(10,40)?>px;animation-delay:<?=rand(0,8)?>s;"></div>
<?php endfor; ?>

<header>
  <img src="logo.png" alt="Coca-Cola Logo">
  <h1>Welcome, <?= htmlspecialchars($user) ?>!</h1>
  <p>Explore our refreshing Coca-Cola flavors 🍹</p>
</header>

<div class="nav-links">
  <a href="coca_story.php" class="nav-btn">🍾 Coca-Cola Story</a>
</div>

<?php if ($user === 'reinabeadini'): ?>
<div class="add-form">
  <form method="POST">
    <input type="hidden" name="action" value="add">
    <input type="text" name="name" placeholder="Flavor name" required>
    <input type="text" name="desc" placeholder="Description" required>
    <input type="text" name="img" placeholder="Image URL" required>
    <button type="submit" class="add-btn">➕ Add Flavor</button>
  </form>
</div>
<?php endif; ?>

<div class="search-container">
  <div class="search-box">
    <i>🔍</i>
    <input type="text" id="searchInput" placeholder="Search flavors...">
  </div>
</div>

<section class="flavors-container" id="flavorList">
  <?php if (!empty($flavors)): ?>
    <?php foreach ($flavors as $f): ?>
      <div class="flavor-card" data-name="<?= strtolower($f['name']) ?>">
        <img src="<?= htmlspecialchars($f['img']) ?>" alt="<?= htmlspecialchars($f['name']) ?>">
        <div class="flavor-info">
          <h3><?= htmlspecialchars($f['name']) ?></h3>
          <p><?= htmlspecialchars($f['desc']) ?></p>
        </div>
        <?php if ($user === 'reinabeadini'): ?>
        <div class="crud-btns">
          <form method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?= $f['id'] ?>">
            <input type="text" name="name" value="<?= htmlspecialchars($f['name']) ?>" required>
            <input type="text" name="desc" value="<?= htmlspecialchars($f['desc']) ?>" required>
            <input type="text" name="img" value="<?= htmlspecialchars($f['img']) ?>" required>
            <button type="submit">✏️ Save Edit</button>
          </form>
          <form method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= $f['id'] ?>">
            <button type="submit">🗑️ Delete</button>
          </form>
        </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="text-align:center;">No flavors yet. Add one above!</p>
  <?php endif; ?>
</section>

<div class="logout-container">
  <a href="logout.php" class="logout-btn">🚪 Log Out</a>
</div>

<script>
// Search live filter
document.getElementById('searchInput').addEventListener('keyup', ()=>{
  const term=document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('.flavor-card').forEach(card=>{
    const name=card.dataset.name;
    card.style.display=name.includes(term)?'block':'none';
  });
});
</script>

</body>
</html>
