<?php
session_start();
include '../connection.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['id']) || $_SESSION['level'] != 'administrator') {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Akses Ditolak',
            text: 'Anda tidak memiliki izin untuk mengakses halaman ini!'
        }).then(() => {
            window.location.href = '../';
        });
    </script>";
    exit();
}

if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $level = trim($_POST['level']);
    $divisi = trim($_POST['divisi']) ?: NULL;

    if (empty($username) || empty($password) || empty($level)) {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Input Tidak Valid',
                text: 'Semua kolom wajib diisi kecuali Divisi!'
            });
        </script>";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO pengguna (nama, username, password, level, divisi) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "sssss", $nama, $username, $password_hash, $level, $divisi);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Pengguna berhasil ditambahkan!'
                }).then(() => {
                    window.location.href = '?page=akun';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menyimpan data: " . mysqli_error($con) . "'
                });
            </script>";
        }
    }
}
?>

<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Tambah Pengguna</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Tambah Pengguna</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Tambah Pengguna</h5>
                            </div>
                            <div class="card-block">
                                <form class="form-material" method="POST" id="barangForm" novalidate>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="nama" id="nama" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nama</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="username" id="username" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Username</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="password" id="password" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Password</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <label>Level Pengguna</label>
                                        <select name="level" id="level" class="form-control" required style="border-bottom: 2px solid #ccc; background: transparent; outline: none;">
                                            <option value="" selected>-- Pilih Level --</option>
                                            <option value="administrator">Administrator</option>
                                            <option value="kepala">Kepala Devisi</option>
                                            <option value="pegawai">Pegawai</option>
                                        </select>
                                    </div>
                                    <div class="form-group form-default">
                                        <label>Divisi</label>
                                        <select name="divisi" id="divisi" class="form-control" style="border-bottom: 2px solid #ccc; background: transparent; outline: none;">
                                            <option value="" selected>-- Pilih Divisi --</option>
                                            <?php
                                            $divisi_query = "SELECT devisi FROM devisi ORDER BY devisi ASC";
                                            $result = mysqli_query($con, $divisi_query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<option value="' . htmlspecialchars($row['devisi']) . '">' . htmlspecialchars($row['devisi']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col offset">
                                            <button type="submit" name="submit" class="btn btn-outline-primary">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                            <a href="?page=akun" class="btn btn-outline-warning">
                                                <i class="fa fa-chevron-left"></i> Kembali
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
