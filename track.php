<?php
// track.php - Real-time tracking simulation
session_start();
if (!isset($_SESSION['user_id'])) { redirect('login.php'); }
include 'db.php';
 
$type = $_GET['type'] ?? 'ride';  // ride or delivery
$id = (int)$_GET['id'];
 
if ($type === 'ride') {
    $stmt = $pdo->prepare("SELECT * FROM rides WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $item = $stmt->fetch();
} else {
    $stmt = $pdo->prepare("SELECT * FROM deliveries WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $item = $stmt->fetch();
}
 
if (!$item) { redirect('index.php'); }
 
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
    <title>Track <?php echo ucfirst($type); ?> - Careem</title>
    <style>
        /* Internal CSS - Map-like tracking interface */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 1rem; }
        .container { max-width: 800px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); overflow: hidden; }
        .header { padding: 1rem; background: #f8f9fa; border-bottom: 1px solid #ddd; }
        .map-container { height: 400px; background: linear-gradient(45deg, #e0f7fa, #b2ebf2); position: relative; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #333; }
        .map-icon { font-size: 3rem; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }
        .details { padding: 1.5rem; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 0.5rem; }
        .status { padding: 0.5rem; background: #4caf50; color: white; border-radius: 20px; text-align: center; font-weight: bold; }
        .btn { padding: 0.8rem 1.5rem; background: #ff6b6b; color: white; border: none; border-radius: 8px; cursor: pointer; margin-top: 1rem; }
        .back-btn { background: #667eea; display: block; text-align: center; text-decoration: none; color: white; padding: 0.8rem; border-radius: 8px; margin-top: 1rem; }
        @media (max-width: 480px) { .container { margin: 0; border-radius: 0; } .map-container { height: 300px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Tracking <?php echo ucfirst($type); ?></h2>
        </div>
        <div class="map-container">
            <div class="map-icon">📍 Driver Location</div>
            <p>Simulating real-time GPS (ETA: <?php echo $item['eta_minutes']; ?> min)</p>
        </div>
        <div class="details">
            <div class="detail-row"><strong>Status:</strong> <span class="status"><?php echo ucfirst($item['status']); ?></span></div>
            <div class="detail-row"><strong>Pickup:</strong> <span><?php echo htmlspecialchars($item['pickup_address']); ?></span></div>
            <div class="detail-row"><strong>Dropoff:</strong> <span><?php echo htmlspecialchars($item['dropoff_address']); ?></span></div>
            <div class="detail-row"><strong>Fare:</strong> $<span><?php echo $item['fare']; ?></span></div>
            <?php if ($type === 'delivery'): ?>
                <div class="detail-row"><strong>Parcel:</strong> <span><?php echo htmlspecialchars($item['parcel_description']); ?> (<?php echo $item['weight_kg']; ?>kg)</span></div>
            <?php endif; ?>
            <button class="btn" onclick="updateStatus(<?php echo $id; ?>, '<?php echo $type; ?>')">Refresh Tracking</button>
        </div>
        <a href="#" class="back-btn" onclick="redirect('index.php')">Back to Home</a>
    </div>
    <script>
        function redirect(page) { window.location.href = page; }
        function updateStatus(id, type) {
            // Simulate status update
            alert('Status updated to In Progress! (Dummy notification sent)');
            // In real, AJAX to PHP endpoint
            setTimeout(() => { window.location.reload(); }, 1000);
        }
        // Real-time simulation: Update every 10s
        setInterval(() => {
            // Dummy driver movement
            document.querySelector('.map-icon').innerHTML = '🚗 Moving...';
        }, 10000);
    </script>
</body>
</html>
