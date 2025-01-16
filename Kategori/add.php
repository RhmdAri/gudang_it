<?php
session_start();
include '../connection.php';

$title = "Tambah Kategori";

if (!isset($_SESSION['id'])) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak',
            text: 'Anda harus login terlebih dahulu.'
        }).then(() => {
            window.location.href = '../';
        });
    </script>";
    exit;
}

if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama']);
    $keterangan = trim($_POST['keterangan']);
    $divisi = $_SESSION['divisi'];

    if (empty($nama) || empty($keterangan)) {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Input Tidak Valid',
                text: 'Nama dan Keterangan tidak boleh kosong.'
            });
        </script>";
    } else {
        $query = "INSERT INTO kategori (nama, keterangan, divisi) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "sss", $nama, $keterangan, $divisi);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil ditambahkan.'
                }).then(() => {
                    window.location.href = '?page=kategori';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menyimpan data kategori.'
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
                        <h5 class="m-b-10"><?php echo $title; ?></h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!"><?php echo $title; ?></a></li>
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
                                <h5><?php echo $title; ?></h5>
                            </div>
                            <div class="card-block">
                                <form class="form-material" method="POST" novalidate>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="nama" required>
                                        <span class="form-bar"></span>
                                        <label for="namaKategori" class="float-label">Kategori</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="keterangan" required>
                                        <span class="form-bar"></span>
                                        <label for="keterangan" class="float-label">Keterangan</label>
                                    </div>
                                    <div class="row">
                                        <div class="col offset">
                                            <button type="submit" name="submit" class="btn btn-outline-primary">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                            <a href="?page=kategori" class="btn btn-outline-warning">
                                                <i class="fa fa-chevron-left"></i> Kembali
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="styleSelector"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
