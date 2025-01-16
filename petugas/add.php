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

if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama']);
    $jabatan = trim($_POST['jabatan']);
    $divisi = $_SESSION['divisi'];
    if (empty($nama) || empty($jabatan)) {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Input Tidak Valid',
                text: 'Semua kolom wajib diisi dengan benar!'
            });
        </script>";
    } else {
        $status = ($_SESSION['level'] === 'administrator') ? 'disetujui' : 'menunggu';
        $query = "INSERT INTO petugas (nama, jabatan, divisi, status) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $nama, $jabatan, $divisi, $status);
        if (mysqli_stmt_execute($stmt)) {
            $message = ($_SESSION['level'] === 'administrator') 
                ? 'Data petugas berhasil disimpan dan disetujui!' 
                : 'Data berhasil diajukan dan menunggu persetujuan!';
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '$message'
                }).then(() => {
                    window.location.href = '?page=petugas';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menyimpan data: " . htmlspecialchars(mysqli_error($con)) . "'
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
                        <h5 class="m-b-10">Tambah Petugas</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Tambah Petugas</a></li>
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
                                <h5>Tambah Petugas</h5>
                            </div>
                            <div class="card-block">
                                <form class="form-material" method="POST" id="petugasForm" novalidate>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="nama" id="nama" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nama Petugas</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="jabatan" id="jabatan" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Jabatan</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION['divisi'] ?? ''); ?>" readonly disabled>
                                        <span class="form-bar"></span>
                                        <label class="float-label"></label>
                                    </div>
                                    <div class="row">
                                        <div class="col offset">
                                            <button type="submit" name="submit" class="btn btn-outline-primary">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                            <a href="?page=petugas" class="btn btn-outline-warning">
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
