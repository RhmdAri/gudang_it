<?php
session_start();
include '../connection.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['id'])) {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Akses Ditolak',
            text: 'Silakan login terlebih dahulu!'
        }).then(() => {
            window.location.href = '../';
        });
    </script>";
    exit();
}
$user_id = $_SESSION['id'];
$divisi = $_SESSION['divisi'];
if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama']);
    $stok = (int)trim($_POST['stok']);

    if (empty($nama) || $stok <= 0) {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Input Tidak Valid',
                text: 'Nama barang dan stok harus diisi dengan benar!'
            });
        </script>";
    } else {
        $query = "INSERT INTO inventaris (nama, stok, divisi) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "sis", $nama, $stok, $divisi);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil disimpan!'
                }).then(() => {
                    window.location.href = '?page=inventaris';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menyimpan data!'
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
                        <h5 class="m-b-10">Tambah Inventaris</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Tambah Inventaris</a></li>
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
                                <h5>Tambah Inventaris</h5>
                            </div>
                            <div class="card-block">
                                <form class="form-material" method="POST" novalidate>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="nama" required>
                                        <span class="form-bar"></span>
                                        <label for="namaBarang" class="float-label">Nama Barang</label>
                                    </div>

                                    <div class="form-group form-default">
                                        <input type="number" class="form-control" name="stok" min="1" required>
                                        <span class="form-bar"></span>
                                        <label for="stokBarang" class="float-label">Stok</label>
                                    </div>

                                    <div class="row">
                                        <div class="col offset">
                                            <button type="submit" name="submit" class="btn btn-outline-primary">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                            <a href="?page=inventaris" class="btn btn-outline-warning">
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
        <div id="styleSelector"></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.form-control').forEach(function (input) {
        input.addEventListener('focus', function () {
            this.parentNode.querySelector('.float-label').classList.add('active');
        });

        input.addEventListener('blur', function () {
            if (this.value.trim() === '') {
                this.parentNode.querySelector('.float-label').classList.remove('active');
            }
        });

        if (input.value.trim() !== '') {
            input.parentNode.querySelector('.float-label').classList.add('active');
        }
    });
</script>
