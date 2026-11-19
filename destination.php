<?php
require_once 'config.php';
require_once 'functions.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare("SELECT * FROM destinations WHERE destination_id = ?");
$stmt->execute([$id ?: 0]);
$d = $stmt->fetch();
if (!$d) { http_response_code(404); exit('Destination not found.'); }
$hotelsStmt = $pdo->prepare("SELECT * FROM hotels WHERE destination_id = ? ORDER BY hotel_name");
$hotelsStmt->execute([$d['destination_id']]);
$hotels = $hotelsStmt->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($d['destination_name']) ?> | Zimbabwe Tourism</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="style.css" rel="stylesheet"></head>
<body><nav class="navbar navbar-dark" style="background:#173b35"><div class="container"><a class="navbar-brand" href="index.php">Zimbabwe Tourism</a><a class="btn btn-outline-light btn-sm" href="index.php">Back to destinations</a></div></nav>
<main class="container section-space">
 <h1><?= e($d['destination_name']) ?></h1><p class="text-muted"><?= e($d['location']) ?></p>
 <?php if (!empty($d['image']) && is_file(__DIR__ . '/' . $d['image'])): ?><img src="<?= e($d['image']) ?>" class="img-fluid rounded mb-4" style="max-height:420px" alt="<?= e($d['destination_name']) ?>"><?php endif; ?>
 <p class="lead"><?= e($d['description']) ?></p>
 <hr><h2 class="h4 mt-4">Accommodation</h2>
 <?php if (!$hotels): ?><p>No accommodation listings have been added yet.</p><?php else: ?>
 <div class="row g-3"><?php foreach ($hotels as $h): ?><div class="col-md-6"><div class="card p-3 h-100"><h5><?= e($h['hotel_name']) ?></h5><p class="mb-1"><?= e($h['location']) ?></p><p class="text-muted mb-0"><?= e($h['description']) ?></p></div></div><?php endforeach; ?></div>
 <?php endif; ?>
</main><footer><div class="container text-center">Zimbabwe Tourism Information System</div></footer></body></html>
