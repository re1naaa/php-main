<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$flavorsFile = 'flavors.json';
$notifFile = 'notifications.json';

// Ngarko shijet
$flavors = [];
if (file_exists($flavorsFile)) {
    $data = file_get_contents($flavorsFile);
    $flavors = json_decode($data, true);
    if (!is_array($flavors)) $flavors = [];
}

// CRUD për admin
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
                $flavors = array_values(array_filter($flavors, fn($f) => $f['id'] !== $_POST['id']));
                break;
            case 'edit':
                foreach ($flavors as &$f) {
                    if ($f['id'] === $_POST['id']) {
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
.video-bg {
  position: fixed; top:0; left:0; width:100%; height:100%;
  object-fit: cover; z-index: -1; filter: brightness(60%) blur(1px);
}
header { text-align: center; padding: 40px 20px 10px; z-index:1; position: relative;}
header img { width:130px; animation: float 3s ease-in-out infinite;}
h1 { margin:10px 0; font-size:2rem; letter-spacing:1px;}
.nav-links { text-align:center; margin-bottom:25px;}
.nav-btn { background: rgba(255,255,255,0.2); border-radius:30px; padding:10px 25px; color:white; text-decoration:none; font-weight:bold; margin:0 10px; transition:0.3s;}
.nav-btn:hover { background:#ff0000; box-shadow:0 0 15px #ff3c3c; transform:translateY(-3px);}
.add-form { text-align:center; margin:30px auto; background: rgba(255,255,255,0.15); padding:20px; border-radius:15px; width:80%; max-width:600px; box-shadow:0 8px 25px rgba(0,0,0,0.3);}
.add-form input { padding:10px; margin:5px; border-radius:10px; border:none; outline:none; width:30%; font-size:14px; background: rgba(255,255,255,0.2); color:white;}
.add-btn { background: linear-gradient(45deg, #ff0000, #ff5c5c); border:none; padding:10px 20px; border-radius:30px; color:white; cursor:pointer; font-weight:bold; transition:0.3s;}
.add-btn:hover { background: linear-gradient(45deg, #cc0000, #ff1a1a); transform:translateY(-2px);}
.search-container { display:flex; justify-content:center; margin:20px auto;}
.search-box { position:relative; width:50%; max-width:400px;}
.search-box input { width:100%; padding:12px 40px; border-radius:25px; border:none; outline:none; text-align:center; font-size:15px; box-shadow:0 0 10px rgba(255,0,0,0.4); background: rgba(255,255,255,0.2); color:white;}
.flavors-container { display:grid; grid-template-columns:repeat(3,1fr); justify-items:center; gap:25px; padding:20px 50px;}
@media(max-width:900px){.flavors-container{grid-template-columns:repeat(2,1fr);}}
@media(max-width:600px){.flavors-container{grid-template-columns:1fr;}}
.flavor-card { background: rgba(255,255,255,0.15); border-radius:20px; width:270px; overflow:hidden; text-align:center; box-shadow:0 10px 25px rgba(0,0,0,0.3); transition:0.4s; cursor:pointer;}
.flavor-card:hover { transform:translateY(-10px) scale(1.05); box-shadow:0 15px 35px rgba(255,0,0,0.6);}
.flavor-card img { width:100%; height:180px; object-fit:cover;}
.flavor-info { padding:15px; background: rgba(0,0,0,0.3);}
.flavor-info h3 { font-size:1.3rem; margin-bottom:5px; color:#ffcccc;}
.flavor-info p { font-size:0.95rem;}
.crud-btns { display:flex; flex-direction:column; align-items:center; gap:8px; margin:10px 0;}
.crud-btns form { display:flex; flex-direction:column; align-items:center; gap:6px;}
.crud-btns input { width:90%; padding:7px; border-radius:10px; border:none; text-align:center; outline:none; background: rgba(255,255,255,0.15); color:white;}
.crud-btns button { background: linear-gradient(45deg, #ff0000, #ff5c5c); border:none; border-radius:20px; padding:6px 15px; color:white; cursor:pointer; font-size:13px; font-weight:bold; transition:0.3s;}
.crud-btns button:hover { background: linear-gradient(45deg, #cc0000, #ff1a1a); transform:scale(1.05);}
.logout-container { text-align:center; margin:40px 0;}
.logout-btn { display:inline-block; background: linear-gradient(45deg, #ff0000, #ff5c5c); padding:12px 35px; border-radius:40px; color:white; font-weight:600; text-decoration:none; font-size:16px; box-shadow:0 5px 15px rgba(255,0,0,0.4); transition:all 0.3s ease;}
.logout-btn:hover { background: linear-gradient(45deg, #cc0000, #ff1a1a); transform:translateY(-3px);}
.explore-btn { display:block; margin:20px auto; text-align:center; width:fit-content; background: rgba(255,255,255,0.2); color:white; padding:12px 30px; border-radius:30px; text-decoration:none; font-weight:bold; transition:0.3s;}
.explore-btn:hover { background:#ff0000; box-shadow:0 0 15px #ff3c3c; transform:scale(1.05);}
footer { text-align:center; background: rgba(0,0,0,0.5); padding:30px 10px; color:#ddd; font-size:14px;}
footer a { color:#ff4d4d; text-decoration:none; font-weight:bold;}
footer .socials { margin-top:10px;}
footer .socials a { margin:0 8px; color:white; font-size:20px; transition:0.3s;}
footer .socials a:hover { color:#ff0000;}
.scroll-top { position:fixed; bottom:25px; right:25px; background: rgba(255,0,0,0.8); border:none; color:white; border-radius:50%; width:45px; height:45px; font-size:22px; cursor:pointer; display:none; transition:0.3s;}
.scroll-top:hover { background:red; transform:scale(1.1);}
@keyframes float { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-10px);} }
#notifContainer { position:fixed; top:80px; right:20px; width:300px; max-height:400px; overflow:auto; z-index:10; }
</style>
</head>
<body>

<video autoplay muted loop class="video-bg">
  <source src="4465043-hd_1920_1080_30fps.mp4" type="video/mp4">
</video>

<header>
  <img src="logo.png" alt="Coca-Cola Logo">
  <h1>Welcome, <?= htmlspecialchars($user) ?>!</h1>
  <p>Discover, edit, and enjoy Coca-Cola flavors 🍹</p>
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
    <input type="text" id="searchInput" placeholder="🔍 Search flavors...">
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
            <input type="text" name="name" value="<?= htmlspecialchars($f['name']) ?>">
            <input type="text" name="desc" value="<?= htmlspecialchars($f['desc']) ?>">
            <input type="text" name="img" value="<?= htmlspecialchars($f['img']) ?>">
            <button type="submit">💾 Save</button>
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

<a href="https://www.coca-cola.com/" target="_blank" class="explore-btn">🌍 Explore More</a>

<div class="logout-container">
  <a href="logout.php" class="logout-btn">🚪 Log Out</a>
</div>

<footer>
  <p>© <?= date("Y") ?> Coca-Cola Company. All Rights Reserved.</p>
  <div class="socials">
    <a href="https://www.facebook.com/cocacola" target="_blank">🌐</a>
    <a href="https://www.instagram.com/cocacola" target="_blank">📸</a>
    <a href="https://twitter.com/CocaCola" target="_blank">🐦</a>
  </div>
</footer>

<button class="scroll-top" id="scrollTopBtn">⬆</button>

<?php if($user==='reinabeadini'): ?>
<div id="notifContainer"></div>
<script>
// Live notifications
function loadNotifications() {
  fetch('get_notifications.php')
    .then(res=>res.json())
    .then(data=>{
      const container = document.getElementById('notifContainer');
      container.innerHTML='';
      data.forEach(n=>{
        const div=document.createElement('div');
        div.style.background='rgba(255,0,0,0.7)';
        div.style.color='white';
        div.style.padding='8px';
        div.style.margin='5px 0';
        div.style.borderRadius='8px';
        div.innerHTML = `<b>${n.user}</b> clicked <i>${n.flavor}</i> at ${n.time} <button onclick="deleteNotif('${n.id}')">x</button>`;
        container.appendChild(div);
      });
    });
}
function deleteNotif(id){
  fetch('delete_notification.php',{method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'id='+id}).then(loadNotifications);
}
setInterval(loadNotifications,2000);
loadNotifications();
</script>
<?php endif; ?>

<script>
// Search live
document.getElementById('searchInput').addEventListener('keyup', ()=>{
  const term=document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('.flavor-card').forEach(card=>{
    const name=card.dataset.name;
    card.style.display=name.includes(term)?'block':'none';
  });
});

// Scroll to top
const scrollBtn=document.getElementById('scrollTopBtn');
window.addEventListener('scroll',()=>{scrollBtn.style.display=(window.scrollY>200)?'block':'none';});
scrollBtn.addEventListener('click',()=>{window.scrollTo({top:0,behavior:'smooth'});});

// Register clicks to notify admin
document.addEventListener('DOMContentLoaded', ()=>{
  document.querySelectorAll('.flavor-card').forEach(card=>{
    card.addEventListener('click', ()=>{
      const flavorName=card.querySelector('h3').innerText;
      fetch('notify_admin.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'flavor='+encodeURIComponent(flavorName)
      });
    });
  });
});
</script>

</body>
</html>
