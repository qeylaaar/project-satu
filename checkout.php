<?php
session_start();

require '.\vendor\midtrans\midtrans-php\Midtrans.php'; // Sertakan konfigurasi Midtrans
require './database/koneksi.php';
require './controller/cartController.php';
require './controller/bankController.php';
require './controller/transaksiController.php';

use Midtrans\Snap;
use Midtrans\Config;

Config::$serverKey = 'SB-Mid-server-0qxpwUd3DzeA06abJvLj3mD7'; // Ganti dengan server key Anda
Config::$isProduction = false; // Ubah ke true jika menggunakan mode produksi
Config::$isSanitized = true;
Config::$is3ds = true;

if (isset($_SESSION['login'])) {
    $user_id = $_SESSION['dataUser']['user_id'];
    $fullname = $_SESSION['dataUser']['fullname'];
}

$myCart = getMyCart($user_id);
$bank = getAllBank();

// url get
$response = (isset($_GET['r'])) ? $_GET['r'] : null;
$cart_id = (isset($_GET['cart-id'])) ? $_GET['cart-id'] : null;

// flash msg
if ($response === "trxsuccess") {
    $response = "Transaksi success";
} elseif ($response === "trxfailed") {
    $response = "Transaksi failed";
} elseif ($response === "bankfalse") {
    $response = "Please choose payment method";
}

// Hitung total harga dari keranjang belanja
$total = 0;
foreach ($myCart as $cart) {
    $total += $cart['product_price'] * $cart['qty'];
}

$snapToken = null;

if (isset($_POST['tambah-transaksi'])) {
    // Buat data transaksi
    $transaction_details = array(
        'order_id' => rand(),
        'gross_amount' => $total, // Total harga dari cart
    );

    $item_details = array();
    foreach ($myCart as $cart) {
        $item_details[] = array(
            'id' => $cart['product_id'],
            'price' => intval($cart['product_price']),
            'quantity' => intval($cart['qty']),
            'name' => $cart['product_name']
        );
    }

    $customer_details = array(
        'first_name' => $fullname,
        'last_name' => '',
        'email' => 'email@example.com',
        'phone' => '08123456789',
        'billing_address' => array(
            'first_name' => $fullname,
            'last_name' => '',
            'address' => $_POST['alamat_pembeli'],
            'city' => 'Jakarta',
            'postal_code' => '12345',
            'phone' => '08123456789',
            'country_code' => 'IDN'
        ),
        'shipping_address' => array(
            'first_name' => $fullname,
            'last_name' => '',
            'address' => $_POST['alamat_pembeli'],
            'city' => 'Jakarta',
            'postal_code' => '12345',
            'phone' => '08123456789',
            'country_code' => 'IDN'
        )
    );

    $transaction = array(
        'transaction_details' => $transaction_details,
        'item_details' => $item_details,
        'customer_details' => $customer_details
    );

    try {
        // Dapatkan token Snap
        $snapToken = Snap::getSnapToken($transaction);
    } catch (Exception $e) {
        // Tangani kesalahan jika terjadi
        echo $e->getMessage();
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- title -->
    <title>Twee Coffee</title>
    <!-- css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.25/datatables.min.css" />
</head>
<body id="home">
    <!-- navbar -->
    <nav class="navbar-container sticky-top">
        <div class="navbar-logo">
            <h3><a href="./">Twee Coffee</a></h3>
        </div>
        <div class="navbar-box">
            <ul class="navbar-list">
                <li><a href="./"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="./shop"><i class="fas fa-shopping-cart"></i> Shop</a></li>
                <?php if (!isset($_SESSION['login'])) { ?>
                    <li><a href="./auth/login"><i class="fas fa-lock"></i> Signin</a></li>
                <?php } else { ?>
                    <li><a href="./my-cart"><i class="fas fa-shopping-cart"></i> My Cart</a></li>
                    <li><a href="./auth/logout"><i class="fas fa-lock"></i> Logout</a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="navbar-toggle">
            <span></span>
        </div>
    </nav>
    <!-- akhir navbar -->

    <!-- mycart -->
    <div class="container mt-5">
        <?php if ($response) : ?>
            <div class="alert alert-costum mt-2 alert-dismissible fade show" id="success" style="background-color: #4a1667; color: white;" role="alert" data-aos="fade-left" data-aos-delay="500">
                <strong><?= $response ?></strong>
                <button type="button" class="close" id="close-alert">
                    <a href="./checkout"><i class="fas fa-times"></i></a>
                </button>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-6">
                <form action="" method="post">
                    <input type="hidden" name="user_id" id="user_id" value="<?= $user_id ?>">
                    <input type="hidden" name="keranjang_id" id="keranjang_id" value="<?= $cart_id ?>">
                    <input type="hidden" name="keranjang_grup" id="keranjang_grup" value="<?= $user_id ?>">
                    <label for="nama_pembeli">Nama lengkap</label>
                    <input type="text" name="nama_pembeli" id="nama_pembeli" class="form-control" value="<?= $fullname ?>">

                    <label for="alamat_pembeli">Alamat lengkap</label>
                    <input type="text" name="alamat_pembeli" id="alamat_pembeli" class="form-control" required>

                    <button type="submit" name="tambah-transaksi" class="btn btn-success mt-2">Bayar Pesanan</button>
                </form>
            </div>
            <div class="col-md-6">
                <table id="tabel-data" class="table table-striped table-bordered text-center" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Product</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        $total = 0;
                        foreach ($myCart as $cart) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $cart['product_name'] ?></td>
                                <td>Rp.<?= number_format($cart['product_price'], 0, ',', '.') ?></td>
                                <td><?= $cart['qty'] ?></td>
                                <td>Rp.<?= number_format($cart['product_price'] * $cart['qty'], 0, ',', '.') ?></td>
                            </tr>
                            <?php $total += $cart['product_price'] * $cart['qty'] ?>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="4" class="font-weight-bold">Total</td>
                            <td>Rp.<?= number_format($total, 0, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- akhir mycart -->

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-OQfbwMkq6VjWhDCd"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJS3+MXmP4Bl4R9jf5sfEczwN6S+FYM4j6+6LZZT7vYGKNtnsW1+6" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.all.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.25/datatables.min.js"></script>
    <script>
        AOS.init();
        $(document).ready(function() {
            $('#tabel-data').DataTable();
        });

        // Midtrans snap payment
        var snapToken = "<?php echo $snapToken; ?>"; // Token Snap dari PHP

        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    alert("Payment success!");
                    console.log(result);
                },
                onPending: function(result) {
                    alert("Waiting for your payment!");
                    console.log(result);
                },
                onError: function(result) {
                    alert("Payment failed!");
                    console.log(result);
                },
                onClose: function() {
                    alert('You closed the popup without finishing the payment');
                }
            });
        });
    </script>
</body>
</html>
