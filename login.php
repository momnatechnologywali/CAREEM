<?php
// login.php
session_start();
include 'db.php';
 
$error = '';
 
if ($_POST) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
 
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
 
    if ($user && verifyPassword($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        redirect('index.php');  // Use JS redirect, but since PHP, we'll handle in JS
        exit;
    } else {
        $error = 'Invalid credentials.';
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
    <title>Login - Careem</title>
    <style>
        /* Internal CSS - Matching signup style */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .form-container { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; margin-bottom: 1.5rem; color: #333; }
        .input-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; color: #555; }
        input { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; }
        input:focus { outline: none; border-color: #ff6b6b; }
        .btn { width: 100%; padding: 1rem; background: linear-gradient(45deg, #ff6b6b, #ee5a52); color: white; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; transition: opacity 0.3s; }
        .btn:hover { opacity: 0.9; }
        .alert { padding: 1rem; margin-bottom: 1rem; border-radius: 8px; text-align: center; background: #fee; color: #c33; }
        .link { text-align: center; margin-top: 1rem; }
        .link a { color: #ff6b6b; text-decoration: none; }
        @media (max-width: 480px) { .form-container { margin: 1rem; padding: 1.5rem; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <?php if ($error): ?><div class="alert"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="link">
            <a href="#" onclick="redirect('signup.php')">Don't have an account? Signup</a>
        </div>
    </div>
    <script>
        function redirect(page) { window.location.href = page; }
    </script>
</body>
</html>
