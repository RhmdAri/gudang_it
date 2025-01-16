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

$divisi = $_SESSION['divisi'];

if (isset($_POST['submit'])) {
    $kode = mysqli_real_escape_string($con, $_POST['kode']);
    $nama = mysqli_real_escape_string($con, $_POST['nama']);
    $kategori = mysqli_real_escape_string($con, $_POST['kategori']);
    $stok = mysqli_real_escape_string($con, $_POST['stok']);
    $kategoriQuery = mysqli_query($con, "SELECT id FROM kategori WHERE nama = '$kategori' AND divisi = '$divisi'");
    if (mysqli_num_rows($kategoriQuery) > 0) {
        $kategoriData = mysqli_fetch_assoc($kategoriQuery);
        $kategoriId = $kategoriData['id'];
        $barangQuery = mysqli_query($con, "SELECT id FROM barang WHERE kode = '$kode'");
        if (mysqli_num_rows($barangQuery) > 0) {
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Barang sudah ada',
                    text: 'Barang dengan kode $kode sudah ada di database.',
                });
            </script>";
        } else {
            $insertQuery = "INSERT INTO barang (kode, nama, idKategori, stok, devisi) 
                            VALUES ('$kode', '$nama', '$kategoriId', '$stok', '$divisi')";
            
            if (mysqli_query($con, $insertQuery)) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Barang Ditambahkan',
                        text: 'Barang $nama berhasil ditambahkan.',
                    }).then(() => {
                        window.location.href = '?page=barang';
                    });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menambahkan barang.',
                    });
                </script>";
            }
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Kategori tidak ditemukan',
                text: 'Kategori yang dipilih tidak valid atau tidak sesuai dengan divisi Anda.',
            });
        </script>";
    }
}
?>

<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Tambah Barang</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="?page=barang">Barang</a></li>
                        <li class="breadcrumb-item"><a href="#!">Tambah Barang</a></li>
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
                                <h5>Tambah Barang</h5>
                            </div>
                            <div class="card-block">
                                <form method="POST" class="form-material" id="barangForm" novalidate>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="kode" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Kode Barang</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="nama" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nama Barang</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <label>Kategori</label>
                                        <div class="col-sm-15">
                                            <select name="kategori" class="form-control" required style="border: none; border-bottom: 2px solid #ccc; background: transparent; outline: none;">
                                                <option value="" selected>-- Pilih Kategori --</option>
                                                <?php
                                                $kategoriQuery = mysqli_query($con, "SELECT nama FROM kategori WHERE divisi = '$divisi'");
                                                while ($kategori = mysqli_fetch_assoc($kategoriQuery)) {
                                                    echo "<option value='{$kategori['nama']}'>{$kategori['nama']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="number" class="form-control" name="stok" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Stok</label>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-outline-primary">
                                        <i class="fa fa-save"></i> Simpan
                                    </button>
                                    <a href="?page=barang" class="btn btn-outline-warning">
                                        <i class="fa fa-chevron-left"></i> Kembali
                                    </a>
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
<script type="text/javascript">
document.getElementById("barangForm").onsubmit = function(event) {
    var kode = document.querySelector("input[name='kode']").value;
    var nama = document.querySelector("input[name='nama']").value;
    var kategori = document.querySelector("select[name='kategori']").value;
    var stok = document.querySelector("input[name='stok']").value;

    if (kode === "" || nama === "" || kategori === "" || stok === "") {
        event.preventDefault();

        Swal.fire({
            icon: 'error',
            title: 'Form tidak lengkap',
            text: 'Semua field harus diisi!',
        });
    }
};
</script>
