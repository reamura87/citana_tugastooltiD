<?php
session_start();
require 'db.php';

$message = '';
$username = '';
$password = '';

// Handle Create User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute(['username' => $username, 'password' => $hashedPassword])) {
            $_SESSION['username'] = $username;
            $message = "Selamat Datang, $username!";
        } else {
            $message = 'Failed to create user.';
        }
    } else {
        $message = 'Please fill in all fields.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Citana</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <img src="citana.png" alt="Logo Citana" class="logo">
</div>

<div class="form-container">
    <h3>Buatkan dirimu nyaman, Ayo daftar sekarang!</h3>
    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="text" placeholder="Username" name="username" required />
        <input type="password" placeholder="Password" name="password" required />
        <button type="submit" name="create">Create User</button>
    </form>
</div>

<div class="Crudbutton">
    <p><a href="crud.php"><button>Enter CRUD Mode</button></a></p>
</div>

<div class="Aboutbutton">
    <p><a href="about.php"><button>About Us</button></a></p>
</div>

<div class="footer">
    <p>© 2024 Citana. All Rights Reserved.</p>
</div>
<script src="script.js"></script>
</body>
</html>
