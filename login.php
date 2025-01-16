<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'connection.php';

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $query = "SELECT * FROM pengguna WHERE username = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $pengguna = mysqli_fetch_assoc($result);
        if (password_verify($password, $pengguna['password'])) {
            $_SESSION['id'] = $pengguna['id']; 
            $_SESSION['nama'] = $pengguna['nama']; 
            $_SESSION['level'] = $pengguna['level']; 
            $_SESSION['divisi'] = $pengguna['divisi']; 
            $_SESSION['status'] = "login"; 
            if ($pengguna['level'] == 'administrator') {
                header('Location: admin/?page=dashboard');
                exit();
            } elseif ($pengguna['level'] == 'kepala') {
                header('Location: admin/?page=dashboard');
                exit();
            } elseif ($pengguna['level'] == 'pegawai') {
                header('Location: admin/?page=dashboard');
                exit();
            }
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Gudang</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            overflow: hidden;
            background: linear-gradient(to right, #8360c3, #2ebf91);
        }

        .login-block {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
        }

        .card {
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <section class="login-block">
        <div class="card shadow-lg" style="width: 400px;">
            <div class="card-body text-center">
                <div class="logo-container mb-4">
                    <img src="assets/images/rsisa.png" alt="Logo Instansi" class="img-fluid" style="width: auto;">
                    <h4 class="mt-3" style="color: #555;">Gudang</h4>
                </div>

                <form method="POST" class="form-container">
                    <p class="text-muted">Login to your account</p>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white"><i class="fa fa-user"></i></span>
                            </div>
                            <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block btn-lg">Log in</button>

                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger text-center mt-3">
                            <strong>Error:</strong> <?= htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</body>
</html>
