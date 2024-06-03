<?php
session_start();
require '../database/koneksi.php';
require '../controller/authController.php';

// url get
$response = (isset($_GET['response'])) ? $_GET['response'] : null;

// flash msg
if ($response === "passfalse") {
    $response = "Wrong password";
} elseif ($response === "false") {
    $response = "Account is not available, please register first";
} elseif ($response === "notverified") {
    $response = "Maaf, kamu belum diverifikasi";
}

// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"]; // Ganti 'pass' dengan 'password' untuk menyamakan dengan input field

    // Cek apakah pengguna adalah mitra
    $result = mysqli_query($conn, "SELECT * FROM tb_mitra WHERE username = '$username'");

    // cek username pada tb_mitra
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // cek status verifikasi
        if ($row['mitra_verified'] == 1) {
            // cek password
            if (password_verify($password, $row["pass"])) { // Ganti 'password' dengan 'pass' sesuai dengan kolom di database
                // set session
                $_SESSION["login"] = true;
                $_SESSION['dataUser'] = $row;
                $_SESSION['nama_toko'] = $row['nama_toko']; 

                // Arahkan ke dashboard mitra jika pengguna adalah mitra
                header('Location: ../mitra');
                exit;
            } else {
                echo "<script>
                window.location.href = './login?response=passfalse'
                </script>";
            }
        } else {
            echo "<script>
            window.location.href = './login?response=notverified'
            </script>";
        }
    } else {
        // Cek apakah pengguna adalah admin
        $result_admin = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$username' AND role = 1");

        // cek username pada tb_user dengan role 1 (admin)
        if (mysqli_num_rows($result_admin) === 1) {
            $row_admin = mysqli_fetch_assoc($result_admin);

            // cek password admin
            if (password_verify($password, $row_admin["password"])) {
                // set session
                $_SESSION["login"] = true;
                $_SESSION['dataUser'] = $row_admin;

                // Arahkan ke dashboard admin jika pengguna adalah admin
                header('Location: ../admin');
                exit;
            } else {
                echo "<script>
                window.location.href = './login?response=passfalse'
                </script>";
            }
        } else {
            // Cek apakah pengguna adalah user dengan role 2
            $result_user = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$username' AND role = 2");

            // cek username pada tb_user dengan role 2 (user)
            if (mysqli_num_rows($result_user) === 1) {
                $row_user = mysqli_fetch_assoc($result_user);

                // cek password user
                if (password_verify($password, $row_user["password"])) {
                    // set session
                    $_SESSION["login"] = true;
                    $_SESSION['dataUser'] = $row_user;

                    // Arahkan ke halaman user jika pengguna adalah user
                    header('Location: ../index');
                    exit;
                } else {
                    echo "<script>
                    window.location.href = './login?response=passfalse'
                    </script>";
                }
            } else {
                echo "<script>
                    window.location.href = './login?response=false'
                    </script>";
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css">

    <link rel="stylesheet" href="style.css">

    <title>Twee Coffee | Sign In</title>
</head>

<body>

    <div class="global-container">
        <div class="card login-form">
            <div class="card-body">
                <h3 class="card-title text-center">Twee Coffee</h3>
                <div class="card-text">
                    <?php if ($response) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><?= $response ?>!</strong>
                            <button type="button" class="close" id="close-alert">
                                <a href="./login"><i class="fas fa-times"></i></a>
                            </button>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control form-control-sm" id="username">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control form-control-sm" id="password">
                        </div>
                        <button type="submit" name="login" class="btn btn-primary btn-block">Sign in</button>

                        <div class="sign-up">
                            Don't have an account? <a href="./register">Create One</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>