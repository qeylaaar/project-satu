<?php
session_start();

if (!isset($_SESSION['login'])) {
    header('Location: ../');
    exit;
}

require '../../database/koneksi.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../../vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function terimaMitra($id)
{
    global $conn;
    $query = "UPDATE tb_mitra SET mitra_verified = 1 WHERE id = $id";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function tolakMitra($id)
{
    global $conn;
    $query = "DELETE FROM tb_mitra WHERE id = $id";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function sendNotificationEmail($recipientEmail)
{
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username   = '2ndqeyla@gmail.com'; // SMTP username
        $mail->Password   = 'pgkv xurx hizr snxz'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        
        // Recipients
        $mail->setFrom('admin-twee@gmail.com', 'Twee Coffee');
        $mail->addAddress($recipientEmail);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Verifikasi Mitra Berhasil';
        $mail->Body    = 'Selamat, pendaftaran mitra Anda telah diterima! silahkan hubungi nomor berikut untuk pembahasan lebih lanjut mengenai kemitraan ini';

        $mail->send();
    } catch (Exception $e) {
        // Handle error
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}
function sendRejectionEmail($recipientEmail)
{
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username   = '2ndqeyla@gmail.com'; // SMTP username
        $mail->Password   = 'pgkv xurx hizr snxz'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        
        // Recipients
        $mail->setFrom('admin-twee@gmail.com', 'Twee Coffee');
        $mail->addAddress($recipientEmail);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Pendaftaran Mitra Ditolak';
        $mail->Body    = 'Maaf, pendaftaran mitra Anda telah ditolak.';
        
        $mail->send();
        return true; // Tambahkan return true agar kita dapat melihat jika email berhasil terkirim
    } catch (Exception $e) {
        // Handle error
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false; // Tambahkan return false agar kita dapat melihat jika ada kesalahan dalam pengiriman email
    }
}
// URL GET
$action = isset($_GET['action']) ? $_GET['action'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($action == 'reject' && $id !== null) {
    if (tolakMitra($id) > 0) {
        // Fetch the email address of the user (mitra) based on the mitra_id
        $mitra = query("SELECT email FROM tb_mitra WHERE id = $id");
        if (!empty($mitra)) {
            $recipientEmail = $mitra[0]['email'];
            sendRejectionEmail($recipientEmail);
        }
        header('Location: ./?response=deletesuccess');
        exit;
    } else {
        header('Location: ./?response=deletefail');
        exit;
    }
}

// URL GET
$respon = (isset($_GET['response'])) ? $_GET['response'] : null;
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    
    if ($action == 'accept') {
        if (terimaMitra($id) > 0) {
            // Fetch the email address of the user (mitra) based on the mitra_id
            $mitra = query("SELECT email FROM tb_mitra WHERE id = $id");
            if (!empty($mitra)) {
                $recipientEmail = $mitra[0]['email'];
                sendNotificationEmail($recipientEmail);
            }
            header('Location: ./?response=verifysuccess');
            exit;
        } else {
            header('Location: ./?response=verifyfail');
            exit;
        }
    } elseif ($action == 'reject') {
        if (tolakMitra($id) > 0) {
            header('Location: ./?response=deletesuccess');
            exit;
        } else {
            header('Location: ./?response=deletefail');
            exit;
        }
    }
}

// Notifikasi respon
if ($respon === "verifysuccess") {
    $respon = "Mitra berhasil diterima!";
} elseif ($respon === "verifyfail") {
    $respon = "Mitra gagal diterima!";
} elseif ($respon === "deletesuccess") {
    $respon = "Mitra berhasil ditolak!";
} elseif ($respon === "deletefail") {
    $respon = "Mitra gagal ditolak!";
}

// Mengambil data mitra
$mitra = query("SELECT id, nama_toko, bidang, nama_pemilik, username, alamat, email, izin_usaha, mitra_verified FROM tb_mitra");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Admin Dashboard | Verifikasi Mitra</title>
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
                    <li><a href="../data-mitra" class="active"><span>Data Mitra</span></a></li>
                    <li><a href="../data-laporan/"><span>Laporan</span></a></li>
                    <li><a href="../../auth/logout"><span>Logout</span></a></li>
                </ul>
            </div>
            <div class="page-content">
                <?php if ($respon) : ?>
                    <div class="alert" style="margin-bottom: 1rem;">
                        <a style="float: right;" href="./"><i class="fas fa-times"></i></a>
                        <strong><?= htmlspecialchars($respon) ?></strong>
                    </div>
                <?php endif; ?>
                <table>
                    <tr>
                        <th>Nama Toko</th>
                        <th>Bidang</th>
                        <th>Nama Pemilik</th>
                        <th>Username</th>
                        <th>Alamat</th>
                        <th>Email</th>
                        <th>Izin Usaha</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    <?php foreach ($mitra as $m) : ?>
                        <tr>
                            <td><?= htmlspecialchars($m['nama_toko']) ?></td>
                            <td><?= htmlspecialchars($m['bidang']) ?></td>
                            <td><?= htmlspecialchars($m['nama_pemilik']) ?></td>
                            <td><?= htmlspecialchars($m['username']) ?></td>
                            <td><?= htmlspecialchars($m['alamat']) ?></td>
                            <td><?= htmlspecialchars($m['email']) ?></td>
                            <td><a href="../../img/<?= htmlspecialchars($m['izin_usaha']) ?>" target="_blank">Lihat Izin Usaha</a></td>
                            <td><?= $m['mitra_verified'] ? 'Terverifikasi' : 'Belum Terverifikasi' ?></td>
                            <td>
                                <?php if (!$m['mitra_verified']) : ?>
                                    <a href="./?action=accept&id=<?= htmlspecialchars($m['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menerima mitra ini?')">Terima</a>
                                <?php endif; ?>
                                <a href="./?action=reject&id=<?= htmlspecialchars($m['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menolak mitra ini?')">Tolak</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php if (empty($mitra)) : ?>
                    <h3>Data Kosong</h3>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/6d2ea823d0.js"></script>
    <script src="../main.js"></script>
</body>

</html>
