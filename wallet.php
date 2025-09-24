<?php
// wallet.php
session_start();
if (!isset($_SESSION['user_id'])) { redirect('login.php'); }
include 'db.php';
 
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM wallets WHERE user_id = ?");
$stmt->execute([$user_id]);
$wallet = $stmt->fetch();
 
$transactions = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
$transactions->execute([$user_id]);
$txns = $transactions->fetchAll();
 
if ($_POST) {
    $amount = (float)$_POST['amount'];
    if ($amount > 0) {
        $stmt = $pdo->prepare("UPDATE wallets SET balance = balance + ? WHERE user_id = ?");
        $stmt->execute([$amount, $user_id]);
        $stmt = $pdo->prepare("INSERT INTO transactions (user_id, amount, type) VALUES (?, ?, 'recharge')");
        $stmt->execute([$user_id, $amount]);
        $success = 'Wallet recharged by $' . $amount;
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
    <title>Wallet - Careem</title>
    <style>
        /* Internal CSS - Wallet interface */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 1rem; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .header { padding: 2rem; text-align: center; background: #f8f9fa; }
        .balance { font-size: 2.5rem; color: #4caf50; font-weight: bold; }
        .form-section { padding: 1.5rem; }
        .input-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; }
        input { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; }
        .btn { width: 100%; padding: 1rem; background: linear-gradient(45deg, #ff6b6b, #ee5a52); color: white; border: none; border-radius: 8px; cursor: pointer; }
        .transactions { padding: 1.5rem; border-top: 1px solid #ddd; }
        .txn { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #eee; }
        .alert-success { padding: 1rem; background: #efe; color: #3c3; border-radius: 8px; margin-bottom: 1rem; text-align: center; }
        .back-btn { display: block; text-align: center; padding: 1rem; background: #667eea; color: white; text-decoration: none; border-radius: 8px; margin: 1rem; }
        @media (max-width: 480px) { .container { margin: 0; } .balance { font-size: 2rem; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Wallet</h2>
            <div class="balance">$<?php echo number_format($wallet['balance'], 2); ?></div>
        </div>
        <div class="form-section">
            <?php if (isset($success)): ?><div class="alert-success"><?php echo $success; ?></div><?php endif; ?>
            <form method="POST">
                <div class="input-group">
                    <label>Recharge Amount ($)</label>
                    <input type="number" name="amount" min="1" step="0.01" required>
                </div>
                <button type="submit" class="btn">Recharge</button>
            </form>
        </div>
        <div class="transactions">
            <h3>Recent Transactions</h3>
            <?php foreach ($txns as $txn): ?>
                <div class="txn">
                    <span><?php echo ucfirst($txn['type']); ?> - $<?php echo number_format($txn['amount'], 2); ?></span>
                    <span><?php echo date('Y-m-d H:i', strtotime($txn['created_at'])); ?></span>
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
