<?php
require 'mitra_function.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (verifikasiMitra($id) > 0) {
        header('Location: ./?response=Berhasil memverifikasi mitra');
    } else {
        header('Location: ./?response=Gagal memverifikasi mitra');
    }
} else {
    header('Location: ./');
}
?>
