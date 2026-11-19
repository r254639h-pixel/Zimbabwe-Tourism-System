<?php
require_once 'config.php';
require_once 'functions.php';
require_admin();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'add' || $action === 'update') {
            $name = trim($_POST['destination_name'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $description = trim($_POST['description'] ?? '');
            if ($name === '' || $location === '' || $description === '') {
                throw new RuntimeException('Please complete all required destination fields.');
            }
            $newImage = save_destination_image($_FILES['image'] ?? []);
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO destinations (destination_name, location, description, image) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $location, $description, $newImage]);
                $message = 'Destination added successfully.';
            } else {
                $id = (int)($_POST['destination_id'] ?? 0);
                if ($newImage) {
                    $stmt = $pdo->prepare("UPDATE destinations SET destination_name=?, location=?, description=?, image=? WHERE destination_id=?");
                    $stmt->execute([$name, $location, $description, $newImage, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE destinations SET destination_name=?, location=?, description=? WHERE destination_id=?");
                    $stmt->execute([$name, $location, $description, $id]);
                }
                $message = 'Destination updated successfully.';
            }
        } elseif ($action === 'delete') {
            $id = (int)($_POST['destination_id'] ?? 0);
            $stmt = $pdo->prepare("DELETE FROM destinations WHERE destination_id=?");
            $stmt->execute([$id]);
            $message = 'Destination deleted. Its accommodation records were also removed.';
        } elseif ($action === 'add_hotel') {
            $hotel = trim($_POST['hotel_name'] ?? '');
            $destinationId = (int)($_POST['destination_id'] ?? 0);
            $hotelLocation = trim($_POST['hotel_location'] ?? '');
            $hotelDescription = trim($_POST['hotel_description'] ?? '');
            if ($hotel === '' || $hotelLocation === '' || $destinationId < 1) throw new RuntimeException('Complete the hotel fields.');
            $stmt = $pdo->prepare("INSERT INTO hotels (hotel_name,destination_id,location,description) VALUES (?,?,?,?)");
            $stmt->execute([$hotel,$destinationId,$hotelLocation,$hotelDescription]);
            $message = 'Accommodation listing added.';
        } elseif ($action === 'delete_hotel') {
            $stmt = $pdo->prepare("DELETE FROM hotels WHERE hotel_id=?");
            $stmt->execute([(int)($_POST['hotel_id'] ?? 0)]);
            $message = 'Accommodation listing deleted.';
        }
    } catch (Throwable $ex) { $error = $ex->getMessage(); }
}

$destinations = $pdo->query("SELECT * FROM destinations ORDER BY destination_name")->fetchAll();
$hotels = $pdo->query("SELECT h.*, d.destination_name FROM hotels h JOIN destinations d ON h.destination_id=d.destination_id ORDER BY h.hotel_name")->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Tourism Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="style.css" rel="stylesheet"></head>
<body><nav class="navbar navbar-dark" style="background:#173b35"><div class="container"><span class="navbar-brand">Tourism Admin Dashboard</span><a class="btn btn-outline-light btn-sm" href="logout.php">Log out</a></div></nav>
<div class="container admin-wrap">
<p>Welcome, <?= e($_SESSION['admin_username'] ?? 'Administrator') ?>. <a href="index.php">View public website</a></p>
<?php if ($message): ?><div class="alert alert-success"><?= e($message) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<div class="card p-4 mb-4"><h2 class="h4">Add a destination</h2>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="add">
<div class="row g-3"><div class="col-md-6"><label class="form-label">Destination name</label><input class="form-control" name="destination_name" required></div>
<div class="col-md-6"><label class="form-label">Location</label><input class="form-control" name="location" required></div>
<div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3" required></textarea></div>
<div class="col-md-6"><label class="form-label">Image (JPG, PNG or GIF; max 2 MB)</label><input class="form-control" type="file" name="image" accept=".jpg,.jpeg,.png,.gif"></div>
<div class="col-12"><button class="btn btn-success">Add destination</button></div></div></form></div>

<div class="card p-4 mb-4"><h2 class="h4">Manage destinations</h2>
<?php foreach ($destinations as $d): ?><form method="post" enctype="multipart/form-data" class="border rounded p-3 mb-3">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="update"><input type="hidden" name="destination_id" value="<?= (int)$d['destination_id'] ?>">
<div class="row g-2"><div class="col-md-3"><label class="form-label">Name</label><input class="form-control" name="destination_name" value="<?= e($d['destination_name']) ?>" required></div>
<div class="col-md-3"><label class="form-label">Location</label><input class="form-control" name="location" value="<?= e($d['location']) ?>" required></div>
<div class="col-md-6"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="2" required><?= e($d['description']) ?></textarea></div>
<div class="col-md-6"><label class="form-label">Replace image (optional)</label><input class="form-control" type="file" name="image" accept=".jpg,.jpeg,.png,.gif"></div>
<div class="col-md-6 d-flex align-items-end gap-2"><button class="btn btn-primary">Save changes</button></div></div></form>
<form method="post" class="mb-3"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="destination_id" value="<?= (int)$d['destination_id'] ?>"><button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this destination and its accommodation records?')">Delete <?= e($d['destination_name']) ?></button></form><hr><?php endforeach; ?>
</div>

<div class="card p-4 mb-4"><h2 class="h4">Add accommodation</h2>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="add_hotel">
<div class="row g-3"><div class="col-md-4"><label class="form-label">Hotel or lodge name</label><input class="form-control" name="hotel_name" required></div>
<div class="col-md-3"><label class="form-label">Destination</label><select class="form-select" name="destination_id" required><option value="">Choose destination</option><?php foreach ($destinations as $d): ?><option value="<?= (int)$d['destination_id'] ?>"><?= e($d['destination_name']) ?></option><?php endforeach; ?></select></div>
<div class="col-md-3"><label class="form-label">Location</label><input class="form-control" name="hotel_location" required></div>
<div class="col-md-2"><label class="form-label">Description</label><input class="form-control" name="hotel_description"></div>
<div class="col-12"><button class="btn btn-success">Add accommodation</button></div></div></form>
<h3 class="h5 mt-4">Existing accommodation</h3>
<?php foreach ($hotels as $h): ?><div class="d-flex justify-content-between align-items-center border-bottom py-2"><span><strong><?= e($h['hotel_name']) ?></strong> — <?= e($h['destination_name']) ?> (<?= e($h['location']) ?>)</span><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete_hotel"><input type="hidden" name="hotel_id" value="<?= (int)$h['hotel_id'] ?>"><button class="btn btn-sm btn-outline-danger">Delete</button></form></div><?php endforeach; ?>
</div></div></body></html>
