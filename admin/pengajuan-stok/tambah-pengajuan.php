<?php
session_start();
require './produk_function.php';
if (!isset($_SESSION['login'])) {
    header('Location: ../');
    exit;
}


// ADD PRODUCT LOGIC
if (isset($_POST['tambah-produk'])) {
    if (tambah($_POST) > 0) {
        echo "
			<script>
				document.location.href = './?response=successadd';
			</script>
		";
    } else {
        echo "
			<script>
				document.location.href = './?response=failadd';
			</script>
		";
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
    <title>Twee Coffee | Admin Dashboard</title>
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
                    <li><a href="./tambah-pengajuan" class="active"><span>Tambah Pengajuan</span></a></li>
 
