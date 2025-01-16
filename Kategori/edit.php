<?php
session_start();
include '../connection.php';
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
$id = $_GET['id'];
$query = mysqli_query($con, "SELECT * FROM kategori WHERE id = '$id'");
$data = mysqli_fetch_array($query);

$nama = $data['nama'];
$keterangan = $data['keterangan'];
if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama']);
    $keterangan = trim($_POST['keterangan']);

    if (empty($nama) || empty($keterangan)) {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Input Tidak Valid',
                text: 'Semua kolom wajib diisi dengan benar!'
            });
        </script>";
    } else {
        $update = mysqli_query($con, "UPDATE kategori SET nama='$nama', keterangan='$keterangan' WHERE id = '$id'");
        if ($update) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil diperbarui!'
                }).then(() => {
                    window.location.href = '?page=kategori';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memperbarui data.'
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
                        <h5 class="m-b-10">Edit Kategori</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Edit Kategori</a></li>
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
                                <h5>Edit Kategori</h5>
                            </div>
                            <div class="card-block">
                                <form class="form-material" method="POST" novalidate>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($nama); ?>" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nama Kategori</label>
                                    </div>

                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="keterangan" value="<?php echo htmlspecialchars($keterangan); ?>" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Keterangan</label>
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
