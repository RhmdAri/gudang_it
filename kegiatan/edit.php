<?php
$id = $_GET['id'];
$result = mysqli_query($con, "SELECT * FROM kegiatan WHERE id=$id");
while ($data = mysqli_fetch_array($result)) {
    $waktu = $data['waktu'];
    $petugas = $data['petugas'];
    $tempat = $data['tempat'];
    $kegiatan = $data['kegiatan'];
    $foto = $data['foto'];
}

if (isset($_POST['submit'])) {
    $petugas = $_POST['petugas'];
    $tempat = $_POST['tempat'];
    $kegiatan = $_POST['kegiatan'];
    $fotoNew = $foto;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $fotoName = time() . '_' . $_FILES['foto']['name'];
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($fotoName);
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
            $fotoNew = $fotoName;
        }
    }

    $result = mysqli_query($con, "UPDATE kegiatan SET petugas='$petugas', tempat='$tempat', kegiatan='$kegiatan', foto='$fotoNew' WHERE id=$id");

    if ($result) {
        echo "<script>window.location.href ='?page=kegiatan';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10"><?php echo $title ?></h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!"><?php echo $title ?></a></li>
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
                                <h5><?php echo $title ?></h5>
                            </div>
                            <div class="card-block">
                                <form class="form-material" method="POST" enctype="multipart/form-data">
                                    <div class="form-group form-default">
                                        <label>Nama Petugas</label>
                                        <select name="petugas" class="form-control" required>
                                            <option value="" selected>- PILIH PETUGAS -</option>
                                            <?php
                                            $query = mysqli_query($con, "SELECT * FROM petugas");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $namaPetugas = $data['nama'];
                                            ?>
                                                <option value="<?= $namaPetugas ?>" <?php echo ($namaPetugas == $petugas) ? "selected" : "" ?>>
                                                    <?= $namaPetugas ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="tempat" value="<?php echo $tempat ?>" required>
                                        <span class="form-bar"></span>
                                        <label for="tempat" class="float-label">Tempat</label>
                                    </div>

                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="kegiatan" value="<?php echo $kegiatan ?>" required>
                                        <span class="form-bar"></span>
                                        <label for="kegiatan" class="float-label">Kegiatan</label>
                                    </div>

                                    <div class="form-group form-default">
                                        <label>Foto (Biarkan kosong jika tidak ingin mengganti)</label>
                                        <input type="file" name="foto" class="form-control" accept="image/*">
                                        <?php if ($foto) { ?>
                                            <div class="mt-2">
                                                <img src="uploads/<?php echo $foto ?>" alt="Foto" style="width: 100px; height: auto;">
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row">
                                        <div class="col offset">
                                            <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                            <a href="?page=kegiatan" class="btn btn-warning"><i class="fa fa-chevron-left"></i> Kembali</a>
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