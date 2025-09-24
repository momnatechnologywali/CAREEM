<?php
// book_ride.php
session_start();
if (!isset($_SESSION['user_id'])) { redirect('login.php'); }
include 'db.php';
 
$user_id = $_SESSION['user_id'];
$success = '';
 
if ($_POST) {
    $pickup = trim($_POST['pickup']);
    $dropoff = trim($_POST['dropoff']);
    // Dummy distance and fare calculation (in real, use API)
    $distance = rand(2, 20);  // km
    $fare = $distance * 5;  // Dummy $5 per km
 
    $stmt = $pdo->prepare("INSERT INTO rides (user_id, pickup_address, dropoff_address, distance_km, fare) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $pickup, $dropoff, $distance, $fare])) {
        $success = "Ride booked! Fare: $" . $fare . " for " . $distance . "km. Driver will be assigned soon.";
        // Dummy: Assign a driver after 5s simulation
        echo "<script>setTimeout(function(){ alert('Driver assigned! Tracking started.'); window.location.href='track.php?type=ride&id=" . $pdo->lastInsertId() . "'; }, 5000);</script>";
    }
}
 
function redirect($page) {
    echo "<script>window.location.href='$page';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ride - Careem</title>
    <style>
        /* Internal CSS - Interactive booking form */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 2rem; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 1.5rem; color: #333; text-align: center; }
        .input-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; color: #555; }
        input, textarea { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; resize: vertical; }
        input:focus, textarea:focus { outline: none; border-color: #ff6b6b; }
        .btn { width: 100%; padding: 1rem; background: linear-gradient(45deg, #ff6b6b, #ee5a52); color: white; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; transition: opacity 0.3s; }
        .btn:hover { opacity: 0.9; }
        .alert-success { padding: 1rem; background: #efe; color: #3c3; border-radius: 8px; margin-bottom: 1rem; text-align: center; }
        .back-btn { background: #667eea; margin-top: 1rem; display: block; text-align: center; text-decoration: none; color: white; padding: 0.8rem; border-radius: 8px; }
        @media (max-width: 480px) { body { padding: 1rem; } .container { padding: 1.5rem; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Book a Ride</h2>
        <?php if ($success): ?><div class="alert-success"><?php echo $success; ?></div><?php endif; ?>
        <form method="POST">
            <div class="input-group">
                <label>Pickup Location</label>
                <input type="text" name="pickup" placeholder="Enter pickup address" required>
            </div>
            <div class="input-group">
                <label>Drop-off Location</label>
                <input type="text" name="dropoff" placeholder="Enter drop-off address" required>
            </div>
            <button type="submit" class="btn">Calculate Fare & Book</button>
        </form>
        <a href="#" class="back-btn" onclick="redirect('index.php')">Back to Home</a>
    </div>
    <script>
        function redirect(page) { window.location.href = page; }
    </script>
</body>
</html>
