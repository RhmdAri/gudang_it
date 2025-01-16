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

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Data Tidak Ditemukan',
            text: 'ID kegiatan tidak ditemukan.',
        }).then(() => {
            window.location.href = '?page=kegiatan';
        });
    </script>";
    exit();
}

$query = "SELECT * FROM kegiatan WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Data Tidak Ditemukan',
            text: 'Kegiatan dengan ID tersebut tidak ditemukan.',
        }).then(() => {
            window.location.href = '?page=kegiatan';
        });
    </script>";
    exit();
}

if (isset($_POST['submit'])) {
    $petugas = mysqli_real_escape_string($con, $_POST['petugas']);
    $tempat = mysqli_real_escape_string($con, $_POST['tempat']);
    $kegiatan = mysqli_real_escape_string($con, $_POST['kegiatan']);
    $foto = $data['foto'];
    if (!empty($_FILES['foto']['name'])) {
        $fotoName = time() . '_' . $_FILES['foto']['name'];
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($fotoName);

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
            $foto = $fotoName;
            if (!empty($data['foto']) && file_exists("../uploads/" . $data['foto'])) {
                unlink("../uploads/" . $data['foto']);
            }
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Upload',
                    text: 'Terjadi kesalahan saat mengunggah foto.',
                });
            </script>";
        }
    }

    $updateQuery = "UPDATE kegiatan SET petugas = ?, tempat = ?, kegiatan = ?, foto = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'ssssi', $petugas, $tempat, $kegiatan, $foto, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Kegiatan Diperbarui',
                text: 'Data kegiatan berhasil diperbarui.',
            }).then(() => {
                window.location.href = '?page=kegiatan';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat memperbarui data kegiatan.',
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
                        <h5 class="m-b-10">Edit Kegiatan</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="?page=kegiatan">Kegiatan</a></li>
                        <li class="breadcrumb-item"><a href="#!">Edit Kegiatan</a></li>
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
                                <h5>Edit Kegiatan</h5>
                            </div>
                            <div class="card-block">
                                <form method="POST" class="form-material" enctype="multipart/form-data">
                                    <div class="form-group form-default">
                                        <label>Petugas</label>
                                        <select name="petugas" class="form-control" required>
                                            <option value="">- Pilih Petugas -</option>
                                            <?php
                                            $query = "SELECT id, nama FROM petugas WHERE status = 'disetujui' AND divisi = ?";
                                            $stmt = mysqli_prepare($con, $query);
                                            mysqli_stmt_bind_param($stmt, 's', $data['devisi']);
                                            mysqli_stmt_execute($stmt);
                                            $resultPetugas = mysqli_stmt_get_result($stmt);
                                            while ($row = mysqli_fetch_assoc($resultPetugas)) {
                                                $selected = ($row['id'] == $data['petugas']) ? 'selected' : '';
                                                echo "<option value='{$row['id']}' $selected>{$row['nama']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="tempat" value="<?php echo htmlspecialchars($data['tempat']); ?>" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Tempat</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="kegiatan" value="<?php echo htmlspecialchars($data['kegiatan']); ?>" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Kegiatan</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <label>Foto Saat Ini</label>
                                        <?php if (!empty($data['foto'])) { ?>
                                            <div>
                                                <img src="../uploads/<?php echo htmlspecialchars($data['foto']); ?>" 
                                                     alt="Foto Kegiatan" 
                                                     style="width: 150px; height: auto; object-fit: cover; margin-bottom: 10px;">
                                            </div>
                                        <?php } else { ?>
                                            <p>Tidak ada foto</p>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group form-default">
                                        <label>Ganti Foto (Opsional)</label>
                                        <input type="file" name="foto" class="form-control" accept="image/*">
                                    </div>
                                    <div class="row">
                                        <div class="col offset">
                                            <button type="submit" name="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Simpan</button>
                                            <a href="?page=kegiatan" class="btn btn-outline-warning"><i class="fa fa-chevron-left"></i> Kembali</a>
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
