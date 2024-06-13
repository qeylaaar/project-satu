<?php
session_start();

if (!isset($_SESSION['login'])) {
    header('Location: ../');
    exit;
}

require 'stok-function.php';

$respon = (isset($_GET['response'])) ? $_GET['response'] : null;

if ($respon === "successadd") {
    $respon = "Data stok berhasil ditambah!";
} elseif ($respon === "failadd") {
    $respon = "Data stok gagal ditambah!";
} elseif ($respon === "deletesuccess") {
    $respon = "Data stok berhasil dihapus!";
} elseif ($respon === "deletefalse") {
    $respon = "Data stok gagal dihapus!";
} elseif ($respon === "updatesuccess") {
    $respon = "Data stok berhasil diubah!";
} elseif ($respon === "updatefalse") {
    $respon = "Data stok gagal diubah!";
}

$stok = query("SELECT * FROM stok");

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if (hapusStok($id) > 0) {
        header("Location: ./?response=deletesuccess");
    } else {
        header("Location: ./?response=deletefalse");
    }
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
    <title>Twee Coffee | Data Stok</title>
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
                    <li><a href="./" class="active"><span>Data Stok</span></a></li>
                    <li><a href="../pengajuan-stok"><span>Pengajuan Stok</span></a></li>
                    <li><a href="../data-laporan"><span>Laporan</span></a></li>
                    <li><a href="../../auth/logout"><span>Logout</span></a></li>
                </ul>
            </div>
            <div class="page-content">
                <?php if ($respon) : ?>
                    <div class="alert" style="margin-bottom: 1rem;">
                        <a style="float: right;" href="./"><i class="fas fa-times"></i></a>
                        <strong><?= $respon ?></strong>
                    </div>
                <?php endif; ?>
                <a href="./tambah-stok"><i class="fas fa-plus"></i> Tambah Stok</a>
                <table>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Sisa Stok</th>
                        <th>Aksi</th>
                    </tr>
                    <?php foreach ($stok as $item) : ?>
                        <tr>
                            <td><?= $item['product'] ?></td>
                            <td><?= $item['sisa_stok'] ?></td>
                            <td>
                                <a href="./edit-stok?id=<?= $item['id'] ?>">Edit</a> |
                                <a href="./?hapus=<?= $item['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php if (empty($stok)) : ?>
                    <h3>Data Kosong</h3>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/6d2ea823d0.js"></script>
    <script src="../main.js"></script>
</body>

</html>
