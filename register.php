<?php
include 'connection.php';

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password'];
    $passwordKonfirmasi = $_POST['confirm-password'];
    $level = mysqli_real_escape_string($con, $_POST['level']);
    $passHash = password_hash($password, PASSWORD_DEFAULT);

    if ($password === $passwordKonfirmasi) {
        $query = "INSERT INTO pengguna (username, password, level) VALUES ('$username', '$passHash', '$level')";
        if (mysqli_query($con, $query)) {
            echo "<script>window.location.href = './';</script>";
        } else {
            $dbError = true;
        }
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - Gudang IT</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .auth-box {
            max-width: 800px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
        .logo-container img {
            max-width: 150px;
            margin-bottom: 15px;
        }
        .form-control {
            border-radius: 25px;
            padding: 12px 15px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #2575fc;
            box-shadow: 0 0 5px rgba(37, 117, 252, 0.5);
        }
        .btn-primary {
            border-radius: 25px;
            background: #2575fc;
            padding: 12px 25px;
        }
        .btn-primary:hover {
            background: #6a11cb;
        }
        .alert {
            border-radius: 25px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <section class="login-block">
        <div class="auth-box">
            <div class="logo-container text-center">
                <img src="assets/images/rsi.png" alt="Logo Instansi">
                <h4>Gudang IT</h4>
            </div>
            <form method="POST">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" required placeholder="Choose Username">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" required placeholder="Password">
                </div>
                <div class="form-group">
                    <input type="password" name="confirm-password" class="form-control" required placeholder="Confirm Password">
                </div>
                <div class="form-group">
                    <label for="level">Select Role:</label>
                    <select name="level" class="form-control" required>
                        <option value="administrator">Administrator</option>
                        <option value="kepala">Kepala</option>
                        <option value="pegawai">Pegawai</option>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-primary btn-block">Sign up now</button>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <strong>Registration failed:</strong> Password confirmation does not match.
                    </div>
                <?php elseif (isset($dbError)): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <strong>Error:</strong> Could not save data to the database.
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
