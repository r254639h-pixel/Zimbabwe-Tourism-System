<?php
require_once 'config.php';
require_once 'functions.php';

$search = trim($_GET['q'] ?? '');
if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM destinations WHERE destination_name LIKE ? ORDER BY destination_name");
    $stmt->execute(['%' . $search . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM destinations ORDER BY destination_name");
}
$destinations = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Zimbabwe Tourism Information</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background:#173b35">
 <div class="container"><a class="navbar-brand" href="index.php">Zimbabwe Tourism</a>
 <div><a class="btn btn-outline-light btn-sm" href="admin-login.php">Administrator</a></div></div>
</nav>
<header class="hero">
 <div class="container">
  <h1 class="display-5 fw-bold">Discover Zimbabwe</h1>
  <p class="lead">Explore amazing destinations, learn about places to visit and find accommodation information in one place.</p>
  <a href="#destinations" class="btn btn-warning">Explore destinations</a>
 </div>
</header>
<main class="container section-space" id="destinations">
 <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
  <div><h2 class="fw-bold mb-1">Tourist destinations</h2><p class="text-muted mb-0">Find a place you would like to visit.</p></div>
  <form class="d-flex" method="get" action="index.php">
   <input class="form-control me-2" type="search" name="q" placeholder="Search by destination" value="<?= e($search) ?>">
   <button class="btn btn-success">Search</button>
  </form>
 </div>
 <div class="row g-4">
 <?php if (!$destinations): ?>
  <div class="col-12"><div class="alert alert-info">No destinations found. Try another search.</div></div>
 <?php endif; ?>
 <?php foreach ($destinations as $d): ?>
  <div class="col-md-6 col-lg-4">
   <div class="card h-100">
    <?php if (!empty($d['image']) && is_file(__DIR__ . '/' . $d['image'])): ?>
      <img class="card-img-top destination-img" src="<?= e($d['image']) ?>" alt="<?= e($d['destination_name']) ?>">
    <?php else: ?>
      <div class="destination-img d-flex align-items-center justify-content-center bg-success-subtle text-success"><span class="fw-semibold">Zimbabwe destination</span></div>
    <?php endif; ?>
    <div class="card-body d-flex flex-column">
     <h5 class="card-title"><?= e($d['destination_name']) ?></h5>
     <p class="text-muted mb-2"><?= e($d['location']) ?></p>
     <p class="card-text"><?= e($d['description']) ?></p>
     <a class="btn btn-outline-success mt-auto" href="destination.php?id=<?= (int)$d['destination_id'] ?>">View details</a>
    </div>
   </div>
  </div>
 <?php endforeach; ?>
 </div>
</main>
<footer><div class="container text-center">Zimbabwe Tourism Information System</div></footer>
</body></html>
