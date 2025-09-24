<?php
// driver_dashboard.php - For drivers
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'driver') { redirect('login.php'); }
include 'db.php';
 
$user_id = $_SESSION['user_id'];
// Fetch pending requests
$pending_rides = $pdo->query("SELECT r.*, u.full_name FROM rides r JOIN users u ON r.user_id = u.id WHERE r.status = 'requested' AND r.driver_id IS NULL LIMIT 5")->fetchAll();
$pending_deliveries = $pdo->query("SELECT d.*, u.full_name FROM deliveries d JOIN users u ON d.user_id = u.id WHERE d.status = 'requested' AND d.driver_id IS NULL LIMIT 5")->fetchAll();
 
if ($_POST) {
    $request_id = (int)$_POST['request_id'];
    $type = $_POST['type'];
    if ($type === 'ride') {
        $stmt = $pdo->prepare("UPDATE rides SET driver_id = ?, status = 'accepted' WHERE id = ?");
    } else {
        $stmt = $pdo->prepare("UPDATE deliveries SET driver_id = ?, status = 'accepted' WHERE id = ?");
    }
    $stmt->execute([$user_id, $request_id]);
    $success = 'Request accepted!';
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
    <title>Driver Dashboard - Careem</title>
    <style>
        /* Internal CSS - Dashboard style */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 1rem; }
        .container { max-width: 800px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .header { padding: 1rem; background: #f8f9fa; border-bottom: 1px solid #ddd; text-align: center; }
        .section { padding: 1.5rem; }
        h3 { margin-bottom: 1rem; color: #333; }
        .request-card { border: 1px solid #ddd; padding: 1rem; margin-bottom: 1rem; border-radius: 8px; background: #f9f9f9; }
        .request-card h4 { color: #ff6b6b; }
        form { display: inline; }
        .accept-btn { background: #4caf50; color: white; padding: 0.5rem 1rem; border: none; border-radius: 5px; cursor: pointer; }
        .alert-success { padding: 1rem; background: #efe; color: #3c3; border-radius: 8px; margin-bottom: 1rem; }
        .back-btn { display: block; text-align: center; padding: 1rem; background: #667eea; color: white; text-decoration: none; border-radius: 8px; margin: 1rem; }
        @media (max-width: 480px) { .container { margin: 0; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Driver Dashboard</h2>
        </div>
        <div class="section">
            <?php if (isset($success)): ?><div class="alert-success"><?php echo $success; ?></div><?php endif; ?>
            <h3>Pending Rides</h3>
            <?php foreach ($pending_rides as $ride): ?>
                <div class="request-card">
                    <h4><?php echo htmlspecialchars($ride['full_name']); ?>'s Ride</h4>
                    <p>From: <?php echo htmlspecialchars($ride['pickup_address']); ?></p>
                    <p>To: <?php echo htmlspecialchars($ride['dropoff_address']); ?></p>
                    <p>Fare: $<?php echo $ride['fare']; ?></p>
                    <form method="POST">
                        <input type="hidden" name="request_id" value="<?php echo $ride['id']; ?>">
                        <input type="hidden" name="type" value="ride">
                        <button type="submit" class="accept-btn">Accept Ride</button>
                    </form>
                </div>
            <?php endforeach; ?>
            <h3>Pending Deliveries</h3>
            <?php foreach ($pending_deliveries as $del): ?>
                <div class="request-card">
                    <h4><?php echo htmlspecialchars($del['full_name']); ?>'s Delivery</h4>
                    <p>From: <?php echo htmlspecialchars($del['pickup_address']); ?></p>
                    <p>To: <?php echo htmlspecialchars($del['dropoff_address']); ?></p>
                    <p>Parcel: <?php echo htmlspecialchars($del['parcel_description']); ?> (<?php echo $del['weight_kg']; ?>kg)</p>
                    <p>Fare: $<?php echo $del['fare']; ?></p>
                    <form method="POST">
                        <input type="hidden" name="request_id" value="<?php echo $del['id']; ?>">
                        <input type="hidden" name="type" value="delivery">
                        <button type="submit" class="accept-btn">Accept Delivery</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="#" class="back-btn" onclick="redirect('index.php')">Back to Home</a>
    </div>
    <script>
        function redirect(page) { window.location.href = page; }
    </script>
</body>
</html>
