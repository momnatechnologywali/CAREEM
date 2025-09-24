<?php
// index.php - Homepage
session_start();
include 'db.php';
 
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careem Clone - Ride & Delivery</title>
    <style>
        /* Internal CSS - Beautiful, modern design inspired by Careem */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #333; line-height: 1.6; }
        header { background: rgba(255,255,255,0.95); padding: 1rem 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: fixed; width: 100%; top: 0; z-index: 1000; }
        nav { display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.8rem; font-weight: bold; color: #ff6b6b; }
        .nav-links { display: flex; list-style: none; gap: 2rem; }
        .nav-links a { text-decoration: none; color: #333; font-weight: 500; transition: color 0.3s; }
        .nav-links a:hover { color: #ff6b6b; }
        .btn { padding: 0.8rem 1.5rem; border: none; border-radius: 25px; cursor: pointer; font-weight: bold; transition: all 0.3s; text-decoration: none; display: inline-block; }
        .btn-primary { background: linear-gradient(45deg, #ff6b6b, #ee5a52); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255,107,107,0.4); }
        .hero { background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 600"><rect fill="%23f0f0f0" width="1000" height="600"/><circle fill="%23ff6b6b" cx="200" cy="200" r="50"/><circle fill="%23667eea" cx="800" cy="400" r="30"/></svg>') center/cover; height: 100vh; display: flex; align-items: center; justify-content: center; text-align: center; color: white; margin-top: 60px; }
        .hero h1 { font-size: 3.5rem; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .hero p { font-size: 1.2rem; margin-bottom: 2rem; }
        .services { padding: 4rem 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; max-width: 1200px; margin: 0 auto; }
        .service-card { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s; }
        .service-card:hover { transform: translateY(-10px); }
        .service-card h3 { color: #ff6b6b; margin-bottom: 1rem; }
        .service-card img { width: 80px; height: 80px; margin-bottom: 1rem; border-radius: 50%; background: #f0f0f0; }
        footer { background: #333; color: white; text-align: center; padding: 2rem; }
        @media (max-width: 768px) { .nav-links { display: none; } .hero h1 { font-size: 2.5rem; } .services { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Careem</div>
            <ul class="nav-links">
                <li><a href="#" onclick="redirect('index.php')">Home</a></li>
                <?php if (isset($user)): ?>
                    <li><a href="#" onclick="redirect('profile.php')">Profile</a></li>
                    <li><a href="#" onclick="redirect('wallet.php')">Wallet</a></li>
                    <li><a href="#" onclick="logout()">Logout</a></li>
                <?php else: ?>
                    <li><a href="#" onclick="redirect('login.php')">Login</a></li>
                    <li><a href="#" onclick="redirect('signup.php')">Signup</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <section class="hero">
        <div>
            <h1>Welcome to Careem</h1>
            <p>Your trusted ride-hailing and delivery partner</p>
            <a href="#" class="btn btn-primary" onclick="redirect('book_ride.php')">Book a Ride</a>
            <a href="#" class="btn btn-primary" style="margin-left:1rem;" onclick="redirect('book_delivery.php')">Schedule Delivery</a>
        </div>
    </section>
    <section class="services">
        <div class="service-card">
            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='40' fill='%23ff6b6b'/></svg>" alt="Ride">
            <h3>Ride-Hailing</h3>
            <p>Book cars, bikes, or taxis instantly.</p>
        </div>
        <div class="service-card">
            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23667eea'/></svg>" alt="Delivery">
            <h3>Parcel Delivery</h3>
            <p>Send packages safely and quickly.</p>
        </div>
        <div class="service-card">
            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><path d='M50 10 L90 90 L10 90 Z' fill='%23ee5a52'/></svg>" alt="Track">
            <h3>Real-Time Tracking</h3>
            <p>Monitor your ride or delivery live.</p>
        </div>
    </section>
    <footer>
        <p>&copy; 2025 Careem Clone. All rights reserved.</p>
    </footer>
    <script>
        // Internal JS
        function redirect(page) {
            window.location.href = page;
        }
        function logout() {
            if (confirm('Are you sure?')) {
                window.location.href = 'logout.php';
            }
        }
        // Simulate real-time elements if needed
    </script>
</body>
</html>
