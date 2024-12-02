<?php
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $keterangan = $_POST['keterangan'];

    $result = mysqli_query($con, "INSERT INTO kategori(nama, keterangan) VALUES('$nama', '$keterangan')");

    echo "<script>window.location.href = '?page=kategori';</script>";
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
                            <a href="?page=dashboard"> <i class="fa fa-home"></i> </a>
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
                                <h5><?php echo $title ?>i</h5>
                            </div>
                            <div class="card-block">
                                <form class="form-material" method="POST">
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="nama" required="">
                                        <span class="form-bar"></span>
                                        <label for="namaKategori" class="float-label">Kategori</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="keterangan" required="">
                                        <span class="form-bar"></span>
                                        <label for="keterangan" class="float-label">Keterangan</label>
                                    </div>
                                    <div class="row">
                                        <div class="col offset">
                                            <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                            <a href="?page=kategori" class="btn btn-warning"><i class="fa fa-chevron-left"></i> Kembali</a>
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
