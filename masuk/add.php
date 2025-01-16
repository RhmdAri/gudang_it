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
            window.location.href = 'login.php';
        });
    </script>";
    exit();
}

$divisi = $_SESSION['divisi'];
if (isset($_POST['add_temp'])) {
    $idPetugas = mysqli_real_escape_string($con, $_POST['petugas']);
    $idBarang = mysqli_real_escape_string($con, $_POST['barang']);
    $jumlah = mysqli_real_escape_string($con, $_POST['jumlah']);
    if ($jumlah > 0) {
        $insertTemp = mysqli_query($con, "INSERT INTO temp_masuk (idPetugas, idBarang, jumlah, devisi) 
                                          VALUES ('$idPetugas', '$idBarang', '$jumlah', '$divisi')");
        if (!$insertTemp) {
            echo "<script>Swal.fire({icon: 'error', title: 'Gagal', text: 'Gagal menambahkan ke tabel sementara!'});</script>";
        }
    } else {
        echo "<script>Swal.fire({icon: 'warning', title: 'Input Tidak Valid', text: 'Jumlah harus lebih dari 0!'});</script>";
    }
}
if (isset($_POST['submit_all'])) {
    $tempData = mysqli_query($con, "SELECT * FROM temp_masuk WHERE devisi = '$divisi'");

    while ($row = mysqli_fetch_assoc($tempData)) {
        $idPetugas = $row['idPetugas'];
        $idBarang = $row['idBarang'];
        $jumlah = $row['jumlah'];

        $insert = mysqli_query($con, "INSERT INTO masuk (idPetugas, idBarang, jumlah, devisi) 
                                      VALUES ('$idPetugas', '$idBarang', '$jumlah', '$divisi')");
        $update = mysqli_query($con, "UPDATE barang SET stok = stok + $jumlah WHERE id = '$idBarang'");

        if (!$insert || !$update) {
            echo "<script>Swal.fire({icon: 'error', title: 'Gagal', text: 'Gagal memproses data!'});</script>";
            exit();
        }
    }
    mysqli_query($con, "DELETE FROM temp_masuk WHERE devisi = '$divisi'");
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Semua data berhasil disimpan!'
        }).then(() => {
            window.location.href = '?page=masuk';
        });
    </script>";
}
?>

<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Tambah Barang Masuk</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="?page=masuk">Barang Masuk</a></li>
                        <li class="breadcrumb-item"><a href="#!">Tambah Barang Masuk</a></li>
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
                                <h5>Form Input Barang Masuk</h5>
                            </div>
                            <div class="card-block">
                                <form method="POST" class="form-material" novalidate>
                                <div class="form-group form-default">
                                        <label>Petugas</label>
                                        <select name="petugas" class="form-control" required style="border: none; border-bottom: 2px solid #ccc; background: transparent; outline: none;">
                                            <option value="">- Pilih Petugas -</option>
                                            <?php
                                            $divisi = $_SESSION['divisi'];
                                            $query = "SELECT id, nama FROM petugas WHERE status = 'disetujui' AND divisi = ?";
                                            $stmt = mysqli_prepare($con, $query);
                                            mysqli_stmt_bind_param($stmt, 's', $divisi);
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);
                                            while ($data = mysqli_fetch_assoc($result)) {
                                                echo "<option value='{$data['id']}'>{$data['nama']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group form-default">
                                        <label>Barang</label>
                                        <select name="barang" class="form-control" required style="border: none; border-bottom: 2px solid #ccc; background: transparent; outline: none;">
                                            <option value="" selected>- Pilih Barang -</option>
                                            <?php
                                            $query = mysqli_query($con, "SELECT id, nama FROM barang WHERE devisi = '$divisi'");
                                            while ($row = mysqli_fetch_assoc($query)) {
                                                echo "<option value='{$row['id']}'>{$row['nama']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group form-default">
                                        <input type="number" min="1" class="form-control" name="jumlah" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Jumlah</label>
                                    </div>

                                    <button type="submit" name="add_temp" class="btn btn-outline-primary">
                                        <i class="fa fa-plus"></i> Tambahkan ke Tabel
                                    </button>
                                    <a href="?page=masuk" class="btn btn-outline-warning">
                                        <i class="fa fa-chevron-left"></i> Kembali
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Tabel Sementara</h5>
                            </div>
                            <div class="card-block">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Petugas</th>
                                            <th>Barang</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $tempData = mysqli_query($con, "SELECT temp_masuk.*, petugas.nama AS petugas, barang.nama AS barang 
                                                                        FROM temp_masuk 
                                                                        JOIN petugas ON temp_masuk.idPetugas = petugas.id 
                                                                        JOIN barang ON temp_masuk.idBarang = barang.id 
                                                                        WHERE temp_masuk.devisi = '$divisi'");
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($tempData)) {
                                            echo "<tr>
                                                    <td>{$no}</td>
                                                    <td>{$row['petugas']}</td>
                                                    <td>{$row['barang']}</td>
                                                    <td>{$row['jumlah']}</td>
                                                    <td>
                                                        <a href='?page=hapusSementara&id={$row['id']}' class='btn btn-outline-danger btn-sm'>Hapus</a>
                                                    </td>
                                                </tr>";
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>

                                <form method="POST">
                                    <button type="submit" name="submit_all" class="btn btn-outline-success">Simpan Semua</button>
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
            if (!this.value) {
                this.parentNode.querySelector('.float-label').classList.remove('active');
            }
        });
    });
</script>
