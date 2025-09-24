<?php
// signup.php
session_start();
include 'db.php';
 
$error = '';
$success = '';
 
if ($_POST) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone = trim($_POST['phone']);
    $user_type = $_POST['user_type'];  // customer or driver
 
    if (empty($username) || empty($email) || empty($password) || empty($phone)) {
        $error = 'All fields are required.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $error = 'User already exists.';
        } else {
            $hashed = hashPassword($password);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, user_type, phone) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed, $user_type, $phone])) {
                $user_id = $pdo->lastInsertId();
                $stmt = $pdo->prepare("INSERT INTO wallets (user_id) VALUES (?)");
                $stmt->execute([$user_id]);
                $success = 'Signup successful! Please login.';
            } else {
                $error = 'Signup failed.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Careem</title>
    <style>
        /* Internal CSS - Elegant signup form */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .form-container { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; margin-bottom: 1.5rem; color: #333; }
        .input-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; color: #555; }
        input, select { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; }
        input:focus, select:focus { outline: none; border-color: #ff6b6b; }
        .btn { width: 100%; padding: 1rem; background: linear-gradient(45deg, #ff6b6b, #ee5a52); color: white; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; transition: opacity 0.3s; }
        .btn:hover { opacity: 0.9; }
        .alert { padding: 1rem; margin-bottom: 1rem; border-radius: 8px; text-align: center; }
        .alert-error { background: #fee; color: #c33; }
        .alert-success { background: #efe; color: #3c3; }
        .link { text-align: center; margin-top: 1rem; }
        .link a { color: #ff6b6b; text-decoration: none; }
        @media (max-width: 480px) { .form-container { margin: 1rem; padding: 1.5rem; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Signup</h2>
        <?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
        <form method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="input-group">
                <label>Phone</label>
                <input type="tel" name="phone" required>
            </div>
            <div class="input-group">
                <label>User Type</label>
                <select name="user_type" required>
                    <option value="customer">Customer</option>
                    <option value="driver">Driver</option>
                </select>
            </div>
            <button type="submit" class="btn">Signup</button>
        </form>
        <div class="link">
            <a href="#" onclick="redirect('login.php')">Already have an account? Login</a>
        </div>
    </div>
    <script>
        function redirect(page) { window.location.href = page; }
    </script>
</body>
</html>
