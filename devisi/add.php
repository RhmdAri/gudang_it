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
    $devisi = trim($_POST['devisi']);
    $keterangan = trim($_POST['keterangan']);

    if (empty($devisi) || empty($keterangan)) {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Input Tidak Valid',
                text: 'Semua kolom wajib diisi!'
            });
        </script>";
    } else {
        $query = "INSERT INTO devisi (devisi, keterangan) VALUES (?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $devisi, $keterangan);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Divisi berhasil ditambahkan!'
                }).then(() => {
                    window.location.href = '?page=devisi';
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
                        <h5 class="m-b-10">Tambah Divisi</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Tambah Divisi</a></li>
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
                                <h5>Tambah Divisi</h5>
                            </div>
                            <div class="card-block">
                                <form class="form-material" method="POST" novalidate>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="devisi" id="devisi" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nama Divisi</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="keterangan" id="keterangan" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Keterangan</label>
                                    </div>
                                    <div class="row">
                                        <div class="col offset">
                                            <button type="submit" name="submit" class="btn btn-outline-primary">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                            <a href="?page=devisi" class="btn btn-outline-warning">
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
