<?php
$host     = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: "localhost";
$username = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: "root";
$password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : (getenv('MYSQLPASSWORD') !== false ? getenv('MYSQLPASSWORD') : "");
$database = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: "diskeb";
$port     = (int)(getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: 3306);

// Koneksi ke MySQL dengan MySQLi
$koneksi = mysqli_connect($host, $username, $password, $database, $port);

// Periksa koneksi
if (!$koneksi) {
    die("Koneksi database gagal. Silakan periksa konfigurasi server Anda.");
}

mysqli_set_charset($koneksi, "utf8mb4");
?>
