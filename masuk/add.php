<?php
if (isset($_POST['submit'])) {
    $idPetugas      = $_POST['petugas'];
    $idBarang       = $_POST['barang'];
    $jumlah         = $_POST['jumlah'];

    $insert = mysqli_query($con, "INSERT INTO masuk(idPetugas, idBarang, jumlah) VALUES('$idPetugas','$idBarang','$jumlah')");
    
    if ($insert) {
        $update = mysqli_query($con, "UPDATE barang SET stok = stok + $jumlah WHERE id = '$idBarang'");

        if ($update) {
            echo "<script>window.location.href = '?page=masuk';</script>";
        } else {
            echo "Gagal memperbarui stok barang";
        }        
    } else {
        echo "Gagal menambah data masuk";
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
                            <a href="?page=dashboard"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!"><?php echo $title ?>g</a></li>
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
                                        <span class="form-bar"></span>
                                        <label>Petugas</label>
                                        <div class="col-sm-15">
                                            <select name="petugas" class="form-control">
                                                <option value="" selected>- pilih petugas -</option>
                                                <?php
                                                    include "connection.php";
                                                    $query = mysqli_query($con, "SELECT * FROM petugas");
                                                    while ($data = mysqli_fetch_array($query)) {
                                                ?>
                                                <option value="<?php echo $data['id'] ?>"><?php echo $data['nama'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-default">
                                        <span class="form-bar"></span>
                                        <label>Barang</label>
                                        <div class="col-sm-15">
                                            <select name="barang" class="form-control">
                                                <option value="" selected>- pilih barang -</option>
                                                <?php
                                                    include "connection.php";
                                                    $query = mysqli_query($con, "SELECT * FROM barang");
                                                    while ($data = mysqli_fetch_array($query)) {
                                                ?>
                                                <option value="<?php echo $data['id'] ?>"><?php echo $data['nama'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="jumlah" required="">
                                        <span class="form-bar"></span>
                                        <label for="jumlah" class="float-label">Jumlah</label>
                                    </div>
                                    <div class="row">
                                        <div class="col offset">
                                            <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                            <a href="?page=masuk" class="btn btn-warning"><i class="fa fa-chevron-left"></i> Kembali</a>
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
