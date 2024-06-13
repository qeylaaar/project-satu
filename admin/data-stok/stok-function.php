<?php
// stok_function.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_project1";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tambahStok($data) {
    global $conn;

    $nama_produk = htmlspecialchars($data['nama_produk']);
    $sisa_stok = htmlspecialchars($data['sisa_stok']);

    $query = "INSERT INTO stok (product, sisa_stok) VALUES ('$nama_produk', '$sisa_stok')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function hapusStok($id) {
    global $conn;
    $query = "DELETE FROM stok WHERE id = $id";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

?>
