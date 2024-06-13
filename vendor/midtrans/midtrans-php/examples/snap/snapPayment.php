<?php
if (!isset($_GET['token'])) {
    die('Token tidak ditemukan');
}
$snapToken = $_GET['token'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Midtrans</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-OQfbwMkq6VjWhDCd"></script>
</head>
<body>
    <button id="pay-button">Pay!</button>

    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            window.snap.pay('<?= $snapToken ?>', {
                onSuccess: function(result) {
                    window.location.href = './checkout?r=trxsuccess';
                },
                onPending: function(result) {
                    window.location.href = './checkout?r=trxpending';
                },
                onError: function(result) {
                    window.location.href = './checkout?r=trxfailed';
                },
                onClose: function() {
                    alert('You closed the popup without finishing the payment');
                }
            });
        });
    </script>
</body>
</html>
<?php
require_once 'config/midtrans.php';

$notif = new \Midtrans\Notification();

$order_id = $notif->order_id;
$transaction = $notif->transaction_status;
$fraud = $notif->fraud_status;

if ($transaction == 'capture') {
    if ($fraud == 'challenge') {
        // Update status transaksi menjadi 'challenge'
    } else if ($fraud == 'accept') {
        // Update status transaksi menjadi 'success'
    }
} else if ($transaction == 'settlement') {
    // Update status transaksi menjadi 'success'
} else if ($transaction == 'pending') {
    // Update status transaksi menjadi 'pending'
} else if ($transaction == 'deny') {
    // Update status transaksi menjadi 'deny'
} else if ($transaction == 'expire') {
    // Update status transaksi menjadi 'expire'
} else if ($transaction == 'cancel') {
    // Update status transaksi menjadi 'cancel'
}

// Lakukan pembaruan status transaksi di database Anda berdasarkan $order_id dan status yang diterima