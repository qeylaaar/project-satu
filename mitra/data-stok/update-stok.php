<?php

session_start();
require './stok-function.php';
if (!isset($_SESSION['login'])) {
    header('Location: ../');
    exit;
}

// UPDATE STOCK LOGIC
if (isset($_POST['update-stok'])) {
    if (update($_POST) > 0) {
        echo "
            <script>
                document.location.href = './?response=successupdate';
            </script>
        ";
    } else {
        echo "
            <script>
                document.location.href = './?response=failupdate';
            </script>
        ";
    }
}

// Get product data for editing
$id = $_GET['id'];
$product = query("SELECT * FROM tb_stok WHERE id_stok = $id")[0];

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
    <title>Twee Coffee | Mitra Dashboard</title>
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
                    <li><a href="./update-stok" class="active"><span>Edit Stok</span></a></li>
                    <li><a href="./"><span>Kembali</span></a></li>
                    <li><a href="../../auth/logout"><span>Logout</span></a></li>
                </ul>
            </div>
            <div class="page-content">
                <div class="form-grup" style="margin-top: 1rem;">
                    <form action="" method="POST" enctype="multipart/form-data" style="height: 100%;">
                        <input type="hidden" name="id" value="<?= $product['id_stok']; ?>">
                        <input type="hidden" name="gambar_lama" value="<?= $product['thumbnail_stok']; ?>">
                        
                        <input type="text" id="nama_produk" name="nama_produk" placeholder="Nama Produk" class="input" value="<?= $product['nama_produk']; ?>" required>
                        <input type="text" id="harga_produk" name="harga_produk" placeholder="Harga Produk" class="input" value="<?= $product['harga_produk']; ?>" required>
                        <label class="custom-file-upload">
                            <input type="file" name="gambar" id="gambar" />
                            <span style="float: right;">Gambar Produk</span>
                        </label>
                        <input type="text" id="desc_produk" name="desc_produk" placeholder="Deskripsi Produk" class="input" value="<?= $product['desc_stok']; ?>" required>
                        <input type="number" id="stok_produk" name="stok_produk" placeholder="Stok Produk" class="input" value="<?= $product['stok_produk']; ?>" required>
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
