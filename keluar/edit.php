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
$divisi = $_SESSION['divisi'];
$query = "SELECT * FROM keluar WHERE id = ? AND devisi = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'is', $id, $divisi);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Data Tidak Ditemukan',
            text: 'Data yang Anda cari tidak ada atau bukan milik divisi Anda!'
        }).then(() => {
            window.location.href = '?page=keluar';
        });
    </script>";
    exit();
}

$data = mysqli_fetch_assoc($result);
$jumlahAwal = $data['jumlah'];
if (isset($_POST['update'])) {
    $idPetugas = mysqli_real_escape_string($con, $_POST['petugas']);
    $idBarang = mysqli_real_escape_string($con, $_POST['barang']);
    $jumlahBaru = mysqli_real_escape_string($con, $_POST['jumlah']);

    if ($jumlahBaru > 0) {
        $selisih = $jumlahBaru - $jumlahAwal;
        $updateStokQuery = "UPDATE barang SET stok = stok - ? WHERE id = ?";
        $updateStokStmt = mysqli_prepare($con, $updateStokQuery);
        mysqli_stmt_bind_param($updateStokStmt, 'ii', $selisih, $idBarang);
        mysqli_stmt_execute($updateStokStmt);
        $updateQuery = "UPDATE keluar SET idPetugas = ?, idBarang = ?, jumlah = ? WHERE id = ? AND devisi = ?";
        $updateStmt = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, 'iisii', $idPetugas, $idBarang, $jumlahBaru, $id, $divisi);

        if (mysqli_stmt_execute($updateStmt)) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil diperbarui!'
                }).then(() => {
                    window.location.href = '?page=keluar';
                });
            </script>";
        } else {
            echo "<script>Swal.fire({icon: 'error', title: 'Gagal', text: 'Gagal memperbarui data!'});</script>";
        }
    } else {
        echo "<script>Swal.fire({icon: 'warning', title: 'Input Tidak Valid', text: 'Jumlah harus lebih dari 0!'});</script>";
    }
}
?>

<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Edit Barang Keluar</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="?page=keluar">Barang Keluar</a></li>
                        <li class="breadcrumb-item"><a href="#!">Edit Barang Keluar</a></li>
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
                                <h5>Form Edit Barang Keluar</h5>
                            </div>
                            <div class="card-block">
                                <form method="POST" class="form-material" novalidate>
                                    <div class="form-group form-default">
                                        <label>Petugas</label>
                                        <select name="petugas" class="form-control" required>
                                            <option value="">- Pilih Petugas -</option>
                                            <?php
                                            $queryPetugas = "SELECT id, nama FROM petugas WHERE status = 'disetujui' AND divisi = ?";
                                            $stmtPetugas = mysqli_prepare($con, $queryPetugas);
                                            mysqli_stmt_bind_param($stmtPetugas, 's', $divisi);
                                            mysqli_stmt_execute($stmtPetugas);
                                            $resultPetugas = mysqli_stmt_get_result($stmtPetugas);
                                            while ($petugas = mysqli_fetch_assoc($resultPetugas)) {
                                                $selected = ($petugas['id'] == $data['idPetugas']) ? 'selected' : '';
                                                echo "<option value='{$petugas['id']}' $selected>{$petugas['nama']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group form-default">
                                        <label>Barang</label>
                                        <select name="barang" class="form-control" required>
                                            <option value="">- Pilih Barang -</option>
                                            <?php
                                            $queryBarang = "SELECT id, nama FROM barang WHERE devisi = ?";
                                            $stmtBarang = mysqli_prepare($con, $queryBarang);
                                            mysqli_stmt_bind_param($stmtBarang, 's', $divisi);
                                            mysqli_stmt_execute($stmtBarang);
                                            $resultBarang = mysqli_stmt_get_result($stmtBarang);
                                            while ($barang = mysqli_fetch_assoc($resultBarang)) {
                                                $selected = ($barang['id'] == $data['idBarang']) ? 'selected' : '';
                                                echo "<option value='{$barang['id']}' $selected>{$barang['nama']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group form-default">
                                        <input type="number" min="1" class="form-control" name="jumlah" value="<?php echo $data['jumlah']; ?>" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Jumlah</label>
                                    </div>

                                    <button type="submit" name="update" class="btn btn-outline-success">
                                        <i class="fa fa-save"></i> Simpan Perubahan
                                    </button>
                                    <a href="?page=keluar" class="btn btn-outline-warning">
                                        <i class="fa fa-chevron-left"></i> Kembali
                                    </a>
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
