<?php
session_start();
require 'stok-function.php';

if (!isset($_SESSION['login'])) {
    header('Location: ../');
    exit;
}

$id = $_GET['id'];
$stok = query("SELECT * FROM stok WHERE id = $id")[0];

if (isset($_POST['update-stok'])) {
    if (updateStok($_POST, $id) > 0) {
        echo "
            <script>
                document.location.href = './?response=updatesuccess';
            </script>
        ";
    } else {
        echo "
            <script>
                document.location.href = './?response=updatefalse';
            </script>
        ";
    }
}

function updateStok($data, $id) {
    global $conn;

    $nama_produk = htmlspecialchars($data['nama_produk']);
    $sisa_stok = htmlspecialchars($data['sisa_stok']);

    $query = "UPDATE stok SET product = '$nama_produk', sisa_stok = '$sisa_stok' WHERE id = $id";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Vadodara:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <title>Twee Coffee | Edit Stok</title>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-logo">
                <span class="site-title">Twee Coffee</span>
            </div>
            <div class="header-search">
                <button class="button-menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        <div class="main">
            <div class="sidebar">
                <ul>
                    <li><a href="./tambah-stok"><span>Edit Stok</span></a></li>
                    <li><a href="./"><span>Kembali</span></a></li>
                    <li><a href="../../auth/logout"><span>Logout</span></a></li>
                </ul>
            </div>
            <div class="page-content">
                <div class="form-group" style="margin-top: 1rem;">
                    <form action="" method="POST" style="height: 100%;">
                        <input type="text" id="nama_produk" name="nama_produk" placeholder="Nama Produk" class="input" required value="<?= $stok['product'] ?>">
                        <input type="number" id="sisa_stok" name="sisa_stok" placeholder="Sisa Stok" class="input" required value="<?= $stok['sisa_stok'] ?>">
                        <input type="submit" name="update-stok" value="Update" class="submit-input">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/6d2ea823d0.js"></script>
    <script src="../main.js"></script>
</body>

</html>
