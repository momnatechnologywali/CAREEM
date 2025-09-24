<?php
// profile.php
session_start();
if (!isset($_SESSION['user_id'])) { redirect('login.php'); }
include 'db.php';
 
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
 
if ($_POST) {
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, phone = ? WHERE id = ?");
    $stmt->execute([$full_name, $phone, $user_id]);
    $success = 'Profile updated!';
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
    <title>Profile - Careem</title>
    <style>
        /* Internal CSS - Clean profile page */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 2rem; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 1.5rem; color: #333; }
        .input-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; color: #555; }
        input { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; }
        .btn { padding: 0.8rem 1.5rem; background: linear-gradient(45deg, #ff6b6b, #ee5a52); color: white; border: none; border-radius: 8px; cursor: pointer; }
        .back-btn { background: #667eea; margin-top: 1rem; display: block; text-align: center; text-decoration: none; color: white; }
        .alert-success { padding: 1rem; background: #efe; color: #3c3; border-radius: 8px; margin-bottom: 1rem; }
        @media (max-width: 480px) { body { padding: 1rem; } .container { padding: 1.5rem; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Profile Management</h2>
        <?php if (isset($success)): ?><div class="alert-success"><?php echo $success; ?></div><?php endif; ?>
        <form method="POST">
            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            <div class="input-group">
                <label>Phone</label>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <div class="input-group">
                <label>Email</label>
                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
            </div>
            <div class="input-group">
                <label>Type</label>
                <input type="text" value="<?php echo ucfirst($user['user_type']); ?>" readonly>
            </div>
            <button type="submit" class="btn">Update Profile</button>
        </form>
        <a href="#" class="back-btn" onclick="redirect('index.php')">Back to Home</a>
    </div>
    <script>
        function redirect(page) { window.location.href = page; }
    </script>
</body>
</html>
