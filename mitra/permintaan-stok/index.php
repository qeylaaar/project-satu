<?php
session_start();

if (!isset($_SESSION['login'])) {
    header('Location: ../');
    exit;
}
require '../data-stok/stok-function.php';

$respon = (isset($_GET['response'])) ? $_GET['response'] : null;

// Handle request stock logic
if (isset($_POST['approve-stock'])) {
    $id_permintaan = $_POST['id_permintaan'];
    $permintaan = query("SELECT * FROM tb_permintaan WHERE id_permintaan = '$id_permintaan'")[0];
    $id_stok = $permintaan['id_stok'];
    $jumlah = $permintaan['jumlah'];

    $product = query("SELECT * FROM tb_stok WHERE id_stok = '$id_stok'")[0]; // Mengambil data stok dari tabel 'stok'
    if ($product['stok_produk'] >= $jumlah) {
        // Kurangi stok untuk mitra
        $new_stock = $product['stok_produk'] - $jumlah;
        $query = "UPDATE stok SET sisa_stok = '$new_stock' WHERE id = '$id_stok'";
        mysqli_query($conn, $query);

        // Perbarui status permintaan
        $query = "UPDATE tb_permintaan SET status = 'approved' WHERE id_permintaan = '$id_permintaan'";
        mysqli_query($conn, $query);

        // Perbarui stok untuk admin
        $admin_stok = query("SELECT * FROM stok WHERE id = '$id_stok'")[0]; // Ganti 'stok_admin' dengan nama tabel yang sesuai
        $new_admin_stock = $admin_stok['sisa_stok'] + $jumlah;
        $query = "UPDATE stok SET sisa_stok = '$new_admin_stock' WHERE id = '$id_stok'";
        mysqli_query($conn, $query);

        $respon = "Permintaan stok berhasil disetujui!";
    } else {
        $respon = "Stok tidak mencukupi!";
    }
}

$stok = query("SELECT * FROM tb_stok"); // Ganti 'stok' dengan nama tabel yang sesuai
$permintaan = query("SELECT tb_permintaan.*, stok.product FROM tb_permintaan JOIN stok ON tb_permintaan.id_stok = stok.id WHERE tb_permintaan.status = 'pending' ORDER BY tb_permintaan.tanggal DESC"); // Ganti 'stok' dengan nama tabel yang sesuai

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
                    <li><a href="../"><span>Dashboard</span></a></li>
                    <li><a href="../data-stok"><span>Data Stok</span></a></li>
                    <li><a href="./permintaan-stok" class="active"><span>Permintaan Stok</span></a></li>
                    <li><a href="../../auth/logout"><span>Logout</span></a></li>
                </ul>
            </div>
            <div class="page-content">
                <?php if ($respon) : ?>
                    <div class="alert" style="margin-bottom: 1rem;">
                        <a style="float: right;" href="./permintaan-stok"><i class="fas fa-times"></i></a>
                        <strong><?= $respon ?></strong>
                    </div>
                <?php endif; ?>
                <h2>Permintaan Stok</h2>
                <table>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                    <?php foreach ($permintaan as $request) : ?>
                        <tr>
                            <td><?= $request['nama_produk'] ?></td>
                            <td><?= $request['jumlah'] ?></td>
                            <td><?= $request['tanggal'] ?></td>
                            <td>
                                <form action="" method="POST">
                                    <input type="hidden" name="id_permintaan" value="<?= $request['id_permintaan'] ?>">
                                    <input type="submit" name="approve-stock" value="Setujui">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php if ($permintaan === []) : ?>
                    <h3>Data Kosong</h3>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/6d2ea823d0.js"></script>
    <script src="../main.js"></script>
</body>

</html>

