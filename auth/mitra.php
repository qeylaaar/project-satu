<?php
session_start();
require '../database/koneksi.php';

// url get
$response = (isset($_GET['response'])) ? $_GET['response'] : null;

// flash msg
if ($response === "success") {
    $response = "Pendaftaran berhasil, menunggu verifikasi admin";
} elseif ($response === "fail") {
    $response = "Pendaftaran gagal, coba lagi";
}

// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["daftar"])) {
    $nama_toko = htmlspecialchars($_POST["nama_toko"]);
    $bidang = htmlspecialchars($_POST["bidang"]);
    $nama_pemilik = htmlspecialchars($_POST["nama_pemilik"]);
    $username = htmlspecialchars($_POST["username"]);
    $pass = htmlspecialchars($_POST["pass"]);
    $alamat = htmlspecialchars($_POST["alamat"]);
    $email = htmlspecialchars($_POST["email"]);
    $izin_usaha = upload();

    if (!$izin_usaha) {
        echo "<script>
                window.location.href = './daftar-mitra?response=fail';
              </script>";
        exit;
    }

    // Membuat hash dari password
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    $query = "INSERT INTO tb_mitra (nama_toko, bidang, nama_pemilik, username, pass, alamat, email, izin_usaha, mitra_verified) VALUES ('$nama_toko', '$bidang', '$nama_pemilik', '$username', '$hashed_password', '$alamat', '$email', '$izin_usaha', null)";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Pendaftaran berhasil, menunggu verifikasi admin');
                window.location.href = '../index'; // Ganti 'halaman-utama' dengan URL halaman utama Anda
              </script>";
    } else {
        echo "<script>
                alert('Pendaftaran gagal, silahkan coba lagi');
                window.location.href = './auth/mitra'; // Mengarahkan kembali ke halaman pendaftaran mitra
              </script>";
    }
}

function upload()
{
    $nama_file = $_FILES['izin_usaha']['name'];
    $ukuran_file = $_FILES['izin_usaha']['size'];
    $error = $_FILES['izin_usaha']['error'];
    $tmp_name = $_FILES['izin_usaha']['tmp_name'];

    if ($error === 4) {
        return false;
    }

    $ekstensi_valid = ['jpg', 'jpeg', 'png', 'pdf'];
    $ekstensi = explode('.', $nama_file);
    $ekstensi = strtolower(end($ekstensi));
    if (!in_array($ekstensi, $ekstensi_valid)) {
        return false;
    }

    if ($ukuran_file > 1000000) {
        return false;
    }

    $nama_file_baru = uniqid();
    $nama_file_baru .= '.' . $ekstensi;
    move_uploaded_file($tmp_name, '../img/' . $nama_file_baru);

    return $nama_file_baru;
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <title>Twee Coffee | Daftar Kemitraan</title>
</head>

<body>

    <div class="global-container">
        <div class="card login-form">
            <div class="card-body">
                <h3 class="card-title text-center">Twee Coffee - Daftar Kemitraan</h3>
                <div class="card-text">
                    <?php if ($response) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong><?= $response ?>!</strong>
                            <button type="button" class="close" id="close-alert">
                                <a href="./daftar-mitra"><i class="fas fa-times"></i></a>
                            </button>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nama_toko">Nama Toko</label>
                            <input type="text" name="nama_toko" class="form-control form-control-sm" id="nama_toko" required>
                        </div>
                        <div class="form-group">
                            <label for="bidang">Bidang</label>
                            <input type="text" name="bidang" class="form-control form-control-sm" id="bidang" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_pemilik">Nama Pemilik</label>
                            <input type="text" name="nama_pemilik" class="form-control form-control-sm" id="nama_pemilik" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_pemilik">Username (Untuk Login di Website)</label>
                            <input type="text" name="username" class="form-control form-control-sm" id="username" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_pemilik">Password (Untuk Login di Website)</label>
                            <input type="password" name="pass" class="form-control form-control-sm" id="pass" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" name="alamat" class="form-control form-control-sm" id="alamat" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Email</label>
                            <input type="text" name="email" class="form-control form-control-sm" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="izin_usaha">Keterangan Izin Usaha</label>
                            <input type="file" name="izin_usaha" class="form-control form-control-sm" id="izin_usaha" required>
                        </div>
                        <button type="submit" name="daftar" class="btn btn-primary btn-block">Daftar Kemitraan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>

</html>
