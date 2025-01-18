<?php
session_start();
// Koneksi ke database
include 'koneksi.php';
// Periksa koneksi
if ($db->connect_error) {
    die("Koneksi ke database gagal: " . $db->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $username = $_POST["username"];
    $password = sha1($_POST["password"]); // Menggunakan SHA1 untuk mengenkripsi password

    // Periksa kredensial di database
    $sql = "SELECT * FROM operator WHERE username='$username' AND password='$password'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        // Kredensial benar, set session login dan username
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;


        // Redirect ke halaman utama
        header("Location: index.php");
    } else {
        // Kredensial salah, beri pesan kesalahan
        header("Location: index.php");
    }
}

// Tutup koneksi ke database
$db->close();
