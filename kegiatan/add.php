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
    $petugas = mysqli_real_escape_string($con, $_POST['petugas']);
    $tempat = mysqli_real_escape_string($con, $_POST['tempat']);
    $kegiatan = mysqli_real_escape_string($con, $_POST['kegiatan']);
    $foto = '';
    if (!empty($_FILES['foto']['name'])) {
        $fotoName = time() . '_' . $_FILES['foto']['name'];
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($fotoName);

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
            $foto = $fotoName;
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Upload',
                    text: 'Terjadi kesalahan saat mengunggah foto.'
                });
            </script>";
        }
    }
    $insertQuery = "INSERT INTO kegiatan (devisi, petugas, tempat, kegiatan, foto) 
                    VALUES ('$divisi', '$petugas', '$tempat', '$kegiatan', '$foto')";
    
    if (mysqli_query($con, $insertQuery)) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Kegiatan Ditambahkan',
                text: 'Kegiatan $kegiatan berhasil ditambahkan.',
            }).then(() => {
                window.location.href = '?page=kegiatan';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat menambahkan kegiatan.',
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
                        <h5 class="m-b-10">Tambah Kegiatan</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="?page=kegiatan">Kegiatan</a></li>
                        <li class="breadcrumb-item"><a href="#!">Tambah Kegiatan</a></li>
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
                                <h5>Tambah Kegiatan</h5>
                            </div>
                            <div class="card-block">
                                <form method="POST" class="form-material" id="kegiatanForm" novalidate enctype="multipart/form-data">
                                    <div class="form-group form-default">
                                        <label>Petugas</label>
                                        <select name="petugas" class="form-control" required>
                                            <option value="">- Pilih Petugas -</option>
                                            <?php
                                            $query = "SELECT id, nama FROM petugas WHERE status = 'disetujui' AND divisi = ?";
                                            $stmt = mysqli_prepare($con, $query);
                                            mysqli_stmt_bind_param($stmt, 's', $divisi);
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);
                                            while ($data = mysqli_fetch_assoc($result)) {
                                                echo "<option value='" . htmlspecialchars($data['nama']) . "'>" . htmlspecialchars($data['nama']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="tempat" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Tempat</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="kegiatan" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Kegiatan</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <label>Foto</label>
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

<script type="text/javascript">
document.getElementById("kegiatanForm").onsubmit = function(event) {
    var petugas = document.querySelector("input[name='petugas']").value;
    var tempat = document.querySelector("input[name='tempat']").value;
    var kegiatan = document.querySelector("input[name='kegiatan']").value;
    
    if (petugas === "" || tempat === "" || kegiatan === "") {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Form tidak lengkap',
            text: 'Semua field harus diisi!',
        });
    }
};
</script>
