<?php 
include '../../database/koneksi.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM `tb_product` WHERE `product_name`='$id'");
 
header("location:index.php?pesan=hapus");
?>