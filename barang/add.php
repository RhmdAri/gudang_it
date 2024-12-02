<?php
if (isset($_POST['submit'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];

    $insert = mysqli_query($con, "INSERT INTO barang(kode,idKategori,nama,stok) VALUES('$kode','$kategori','$nama','$stok')");
    echo "<script>window.location.href = '?page=barang';</script>";
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
                                <h5><?php echo $title ?></h5>
                            </div>
                            <div class="card-block">
                                <form class="form-material" method="POST">
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="kode" required="">
                                        <span class="form-bar"></span>
                                        <label for="kodeBarang" class="float-label">Kode Barang</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="nama" required="">
                                        <span class="form-bar"></span>
                                        <label for="nama" class="float-label">Nama Barang</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <span class="form-bar"></span>
                                        <label class="">Kategori</label>
                                        <div class="col-sm-15">
                                            <select name="kategori" class="form-control">
                                                <option value="" selected>- PILIH KATEGORI -</option>
                                                <?php
                                                include "../connection.php";
                                                $query = mysqli_query($con, "SELECT * FROM kategori");
                                                while ($data = mysqli_fetch_array($query)) {
                                                ?>
                                                    <option value="<?php echo $data['id'] ?>"><?php echo $data['nama'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="stok" required="">
                                        <span class="form-bar"></span>
                                        <label for="stok" class="float-label">Stok</label>
                                    </div>
                                    <div class="row">
                                        <div class="col offset">
                                            <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                            <a href="?page=barang" class="btn btn-warning"><i class="fa fa-chevron-left"></i> Kembali</a>
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
