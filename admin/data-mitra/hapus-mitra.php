<?php
require 'mitra_function.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (deleteMitra($id) > 0) {
        header('Location: ./?response=Berhasil menghapus mitra');
    } else {
        header('Location: ./?response=Gagal menghapus mitra');
    }
} else {
    header('Location: ./');
}
?>
