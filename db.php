<?php
$host = 'localhost'; // Host database
$dbname = 'db_Citana'; // Nama database
$user = 'root'; // Username default MySQL di XAMPP
$password = ''; // Password kosong di XAMPP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>
