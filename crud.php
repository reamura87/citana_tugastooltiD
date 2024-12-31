<?php
session_start();
require 'db.php';


$message = '';

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) { // Create user
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        if (!empty($username) && !empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute(['username' => $username, 'password' => $hashedPassword])) {
                $message = 'User created successfully!';
            } else {
                $message = 'Failed to create user.';
            }
        } else {
            $message = 'Please fill in all fields.';
        }
    } elseif (isset($_POST['update'])) { // Update user
        $id = $_POST['id'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username = :username, password = :password WHERE id = :id";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute(['id' => $id, 'username' => $username, 'password' => $hashedPassword])) {
            $message = 'User data updated successfully!';
        } else {
            $message = 'Failed to update user data.';
        }
    } elseif (isset($_POST['delete'])) { // Delete user
        $id = $_POST['id'];
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute(['id' => $id])) {
            $message = 'User deleted successfully!';
        } else {
            $message = 'Failed to delete user.';
        }
    }
}

// Fetch all users
$sql = "SELECT * FROM users";
$stmt = $conn->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Operations - Citana</title>
    <link rel="stylesheet" href="/citana/stylecrud.css">
</head>
<body>
    <div class="header">
        <img src="citana.png" alt="Logo Citana" class="logo">
        <h2>CRUD Operations Mode</h2>
        <h3>Welcome, Admin!</h3>
        <form method="POST" action="index.php">
            <button type="submit" name="logout" class="logout-btn">Log out</button>
        </form>
    </div>

    <!-- Notification Message -->
    <?php if (!empty($message)): ?>
        <div class="notification">
            <p><?= htmlspecialchars($message) ?></p>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <h3>Create New User</h3>
        <form method="POST" action="">
            <input type="text" placeholder="Username" name="username" required />
            <input type="password" placeholder="Password" name="password" required />
            <button type="submit" name="create">Create User</button>
        </form>
    </div>

    <div class="user-list">
        <h3>User List</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td>
                            <form method="POST" action="" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" name="delete" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            <button onclick="editUser('<?= $user['id'] ?>', '<?= $user['username'] ?>')">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="form-container" id="edit-form" style="display: none;">
        <h3>Edit User</h3>
        <form method="POST" action="">
            <input type="hidden" name="id" id="edit-id" />
            <input type="text" name="username" id="edit-username" required />
            <input type="password" name="password" placeholder="New password" required />
            <button type="submit" name="update">Save Changes</button>
        </form>
    </div>

    <script>
        function editUser(id, username) {
            document.getElementById('edit-form').style.display = 'block';
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-username').value = username;
        }
    </script>

    <footer>
        <div class="footer-content">
            <img src="satriabagas.jpg" alt="Foto Komang Satria Bagas Bramantara" class="founder-photo">
            <div class="footer-text">
                <strong>Komang Satria Bagas Bramantara</strong>
                <p>NIM: 2405551005</p>
                <p>Founder Citana Company</p>
            </div>
        </div>
    </footer>

</body>
</html>

