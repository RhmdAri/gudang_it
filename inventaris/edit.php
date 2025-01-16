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
$query = mysqli_query($con, "SELECT * FROM inventaris WHERE id = '$id'");
$data = mysqli_fetch_array($query);
$nama = $data['nama'];
$stok = $data['stok'];
if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama']);
    $stok = intval($_POST['stok']);

    if (empty($nama) || $stok <= 0) {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Input Tidak Valid',
                text: 'Semua kolom wajib diisi dengan benar!'
            });
        </script>";
    } else {
        $update = mysqli_query($con, "UPDATE inventaris SET nama = '$nama', stok = '$stok' WHERE id = '$id'");
        if ($update) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil diperbarui!'
                }).then(() => {
                    window.location.href = '?page=inventaris';
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
                        <h5 class="m-b-10">Edit Inventaris</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Edit Inventaris</a></li>
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
                                <h5>Edit Inventaris</h5>
                            </div>
                            <div class="card-block">
                                <form class="form-material" method="POST" novalidate>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($nama); ?>" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nama Inventaris</label>
                                    </div>

                                    <div class="form-group form-default">
                                        <input type="number" class="form-control" name="stok" min="1" value="<?php echo htmlspecialchars($stok); ?>" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Stok</label>
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
