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
$id = (int)$_GET['id'];
$query = mysqli_query($con, "SELECT * FROM barang WHERE id='$id'");

if (mysqli_num_rows($query) == 0) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan',
            text: 'Barang tidak ditemukan!'
        }).then(() => {
            window.location.href = '?page=barang';
        });
    </script>";
    exit();
}

$data = mysqli_fetch_assoc($query);
$kode = $data['kode'];
$nama = $data['nama'];
$idkategoriBarang = $data['idKategori'];
$stok = $data['stok'];

if (isset($_POST['submit'])) {
    $kode = trim($_POST['kode']);
    $nama = trim($_POST['nama']);
    $kategori = (int)$_POST['kategori'];
    $stok = (int)$_POST['stok'];

    if (empty($kode) || empty($nama) || empty($kategori) || $stok < 0) {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Input Tidak Valid',
                text: 'Semua kolom wajib diisi dengan benar!'
            });
        </script>";
    } else {
        $update = mysqli_query($con, "UPDATE barang 
                                      SET kode='$kode', nama='$nama', idKategori='$kategori', stok='$stok' 
                                      WHERE id='$id'");

        if ($update) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil diperbarui!'
                }).then(() => {
                    window.location.href = '?page=barang';
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
                        <h5 class="m-b-10">Edit Barang</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Edit Barang</a></li>
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
                                <h5>Edit Barang</h5>
                            </div>
                            <div class="card-block">
                                <form class="form-material" method="POST" novalidate>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="kode" value="<?php echo htmlspecialchars($kode); ?>" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Kode Barang</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($nama); ?>" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nama Barang</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <label>Kategori</label>
                                        <div class="col-sm-15">
                                            <select id="mySelect" name="kategori" class="form-control" required style="border: none; border-bottom: 2px solid #ccc; background: transparent; outline: none;">
                                                <option value="" selected>- Pilih Kategori -</option>
                                                <?php
                                                $level = $_SESSION['level'];
                                                $user_id = $_SESSION['id'];
                                                $divisi = $_SESSION['divisi'];
                                                if ($level === 'admin') {
                                                    $query = "SELECT * FROM kategori";
                                                    $stmt = mysqli_query($con, $query);
                                                } else {
                                                    $query = "SELECT * FROM kategori WHERE divisi = ?";
                                                    $stmt = mysqli_prepare($con, $query);
                                                    mysqli_stmt_bind_param($stmt, "s", $divisi);
                                                    mysqli_stmt_execute($stmt);
                                                    $stmt = mysqli_stmt_get_result($stmt);
                                                }

                                                while ($row = mysqli_fetch_array($stmt)) {
                                                    $selected = ($row['id'] == $idkategoriBarang) ? "selected" : "";
                                                    echo "<option value='{$row['id']}' $selected>{$row['nama']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="number" min="0" class="form-control" name="stok" value="<?php echo htmlspecialchars($stok); ?>" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Stok</label>
                                    </div>
                                    <div class="row">
                                        <div class="col offset">
                                            <button type="submit" name="submit" class="btn btn-outline-primary">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                            <a href="?page=barang" class="btn btn-outline-warning">
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
