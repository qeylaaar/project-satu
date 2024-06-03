<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../auth/login');
    exit;
}
$nama_toko = $_SESSION['nama_toko'] ?? 'Mitra'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Vadodara:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Twee Coffee | Mitra Dashboard</title>
</head>
<body>
    <div class="uwucontainer">
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
                    <li><a href="./" class="active"><span>Dashboard</span></a></li>
                    <li><a href="./permintaan-stok"><span>Permintaan Stok</span></a></li>
                    <li><a href="./data-stok"><span>Data Stok</span></a></li>
                    <li><a href="../auth/logout"><span>Logout</span></a></li>
                </ul>
            </div>
            <div class="page-content">
                <h1>hi <?php echo htmlspecialchars($nama_toko); ?></h1> <!-- Menampilkan nama toko -->
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/6d2ea823d0.js"></script>
    <script src="main.js"></script>
</body>
</html>
