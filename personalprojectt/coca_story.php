<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>About Coca-Cola</title>
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
  padding: 50px 20px 20px;
  position: relative;
  z-index: 1;
}
header img {
  width: 130px;
  animation: float 3s ease-in-out infinite;
}
h1 {
  font-size: 2.2rem;
  margin-top: 10px;
}
p {
  font-size: 1.1rem;
  line-height: 1.6;
}

/* Story section */
.story {
  max-width: 900px;
  margin: 40px auto;
  padding: 30px;
  background: rgba(255,255,255,0.1);
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.3);
  animation: fadeIn 1s ease-out;
}
.story h2 {
  text-align: center;
  font-size: 1.8rem;
  color: #ffdddd;
}
.story p {
  text-align: justify;
  font-size: 1rem;
  color: #fff;
}

/* Timeline */
.timeline {
  position: relative;
  margin: 50px auto;
  width: 80%;
  max-width: 700px;
}
.timeline::before {
  content: "";
  position: absolute;
  width: 4px;
  background: #ff0000;
  top: 0; bottom: 0; left: 50%;
  transform: translateX(-50%);
}
.entry {
  position: relative;
  width: 50%;
  padding: 20px;
  box-sizing: border-box;
}
.entry.left {
  left: 0;
  text-align: right;
}
.entry.right {
  left: 50%;
}
.entry-content {
  background: rgba(255,255,255,0.15);
  padding: 15px;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  transition: 0.3s;
}
.entry-content:hover {
  background: rgba(255,255,255,0.25);
  transform: scale(1.05);
}
.entry::after {
  content: "";
  position: absolute;
  width: 20px;
  height: 20px;
  background: #ff0000;
  border-radius: 50%;
  top: 25px;
  right: -10px;
  transform: translateX(50%);
}
.entry.right::after {
  left: -10px;
  right: auto;
}

/* Back button */
.back-btn {
  display: block;
  width: max-content;
  margin: 60px auto;
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
.back-btn:hover {
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

/* Responsive */
@media (max-width: 700px) {
  .entry, .entry.right, .entry.left {
    left: 0;
    width: 100%;
    text-align: center;
  }
  .timeline::before { left: 10px; }
  .entry::after, .entry.right::after { left: 0; }
}
</style>
</head>
<body>

<!-- Background video -->
<video autoplay muted loop class="video-bg">
  <source src="5561389-uhd_3840_2160_25fps.mp4" type="video/mp4">
</video>

<header>
  <img src="logo.png" alt="Coca-Cola Logo">
  <h1>The Story of Coca-Cola</h1>
  <p>Welcome, <?= htmlspecialchars($user) ?> 👋 — Discover the journey of the world’s favorite drink!</p>
</header>

<section class="story">
  <h2>From Pharmacy to Global Icon 🥤</h2>
  <p>
    Coca-Cola was created in 1886 by Dr. John Stith Pemberton in Atlanta, Georgia. Originally intended as a medicinal tonic, 
    the first Coca-Cola was sold at Jacobs' Pharmacy for just five cents a glass.
  </p>
  <p>
    What started as a small local beverage soon became a symbol of refreshment recognized across the world. 
    The unmistakable red logo, the curvy bottle, and the effervescent bubbles all came together to create a timeless brand.
  </p>
</section>

<section class="timeline">
  <div class="entry left">
    <div class="entry-content">
      <h3>1886</h3>
      <p>John Pemberton invents Coca-Cola in Atlanta and serves it at a local pharmacy.</p>
    </div>
  </div>
  <div class="entry right">
    <div class="entry-content">
      <h3>1892</h3>
      <p>Asa Candler founds The Coca-Cola Company and begins large-scale distribution.</p>
    </div>
  </div>
  <div class="entry left">
    <div class="entry-content">
      <h3>1915</h3>
      <p>The iconic contour bottle design is introduced, making Coca-Cola instantly recognizable.</p>
    </div>
  </div>
  <div class="entry right">
    <div class="entry-content">
      <h3>1982</h3>
      <p>Diet Coke is launched, expanding the brand’s portfolio to new audiences.</p>
    </div>
  </div>
  <div class="entry left">
    <div class="entry-content">
      <h3>2025</h3>
      <p>Coca-Cola continues innovating with new flavors and eco-friendly packaging initiatives.</p>
    </div>
  </div>
</section>

<a href="welcome.php" class="back-btn">⬅ Back to Dashboard</a>

</body>
</html>
