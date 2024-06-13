<?php
session_start();

if (!isset($_SESSION['login'])) {
    header('Location: ../');
    exit;
}
require '../../mitra/data-stok/stok-function.php';

$respon = (isset($_GET['response'])) ? $_GET['response'] : null;

// Handle stock request logic
if (isset($_POST['request-stock'])) {
    $id_stok = $_POST['id_stok'];
    $jumlah = $_POST['jumlah'];

    // Log the stock request for mitra
    $query = "INSERT INTO tb_permintaan (id_stok, jumlah, tanggal, status) VALUES ('$id_stok', '$jumlah', NOW(), 'pending')";
    mysqli_query($conn, $query);

    $respon = "Permintaan stok berhasil diajukan!";
}

// Fetching stock data sorted by the least amount of stock
$stok = query("SELECT * FROM tb_stok ORDER BY stok_produk ASC");

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
    <title>Twee Coffee | Admin Dashboard</title>
    <style>
        .form-container {
            display: none;
            margin-top: 1rem;
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 5px;
        }
        .show-form .form-container {
            display: block;
        }
        .button-ajukan-stok {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            text-align: center;
            font-size: 16px;
            cursor: pointer;
            margin-top: 1rem;
            display: block;
        }
        .button-ajukan-stok:hover {
            background-color: #0056b3;
        }
    </style>
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
                    <li><a href="../"><span>Dashboard</span></a></li>
                    <li><a href="../data-produk"><span>Data Produk</span></a></li>
                    <li><a href="../data-mitra"><span>Data Mitra</span></a></li>
                    <li><a href="../data-stok"><span>Data Stok</span></a></li>
                    <li><a href="./pengajuan-stok" class="active"><span>Pengajuan Stok</span></a></li>
                    <li><a href="../data-laporan/"><span>Laporan</span></a></li>
                    <li><a href="../../auth/logout"><span>Logout</span></a></li>
                </ul>
            </div>
            <div class="page-content">
                <?php if ($respon) : ?>
                    <div class="alert" style="margin-bottom: 1rem;">
                        <a style="float: right;" href="./pengajuan-stok"><i class="fas fa-times"></i></a>
                        <strong><?= $respon ?></strong>
                    </div>
                <?php endif; ?>
                <h2>Pengajuan Stok</h2>
                <button id="show-form-button" class="button-ajukan-stok">Ajukan Stok</button>
                <div class="form-container" id="form-container">
                    <form action="" method="POST">
                        <select name="id_stok" required>
                            <option value="">Pilih Produk</option>
                            <?php foreach ($stok as $stock) : ?>
                                <option value="<?= $stock['id_stok'] ?>"><?= $stock['nama_produk'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="jumlah" placeholder="Jumlah" class="input" style="margin-top: 1rem;" required>
                        <input type="submit" name="request-stock" value="Ajukan Stok" class="button-ajukan-stok" style="margin-top: 1rem;">
                    </form>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Deskripsi Produk</th>
                            <th>Gambar Produk</th>
                            <th>Harga Produk</th>
                            <th>Stok Produk</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stok as $stock) : ?>
                            <tr>
                                <td><?= $stock['nama_produk'] ?></td>
                                <td><?= $stock['desc_stok'] ?></td>
                                <td><img src="../../img/<?= $stock['thumbnail_stok'] ?>" style="width: 100px;"></td>
                                <td><?= number_format($stock['harga_produk'], 0, ',', '.') ?></td>
                                <td><?= $stock['stok_produk'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/6d2ea823d0.js"></script>
    <script src="../main.js"></script>
    <script>
        document.getElementById('show-form-button').addEventListener('click', function() {
            var formContainer = document.getElementById('form-container');
            if (formContainer.style.display === "none" || formContainer.style.display === "") {
                formContainer.style.display = "block";
            } else {
                formContainer.style.display = "none";
            }
        });
    </script>
</body>

</html>
