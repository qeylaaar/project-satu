<?php

session_start();
require './stok-function.php';
if (!isset($_SESSION['login'])) {
    header('Location: ../');
    exit;
}

// DELETE STOCK LOGIC
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "DELETE FROM tb_stok WHERE id_stok = $id";
    mysqli_query($conn, $query);
    
    if (mysqli_affected_rows($conn) > 0) {
        echo "
            <script>
                document.location.href = './?response=successdelete';
            </script>
        ";
    } else {
        echo "
            <script>
                document.location.href = './?response=faildelete';
            </script>
        ";
    }
}
?>
