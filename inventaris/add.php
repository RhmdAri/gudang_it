<?php
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $stok = $_POST['stok'];

    $result = mysqli_query($con, "INSERT INTO inventaris(nama, stok) VALUES('$nama', '$stok')");

    echo "<script>window.location.href = '?page=inventaris';</script>";
}
?>

<?php
if (isset($_GET['action']) && $_GET['action'] === 'return' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM pinjam WHERE id = $id";
    $result = mysqli_query($con, $query);
    $data = mysqli_fetch_array($result);

    if ($data) {
        $idInventaris = $data['idInventaris'];

        $updateStokQuery = "UPDATE inventaris SET stok = stok + {$data['jumlah']} WHERE id = $idInventaris";
        mysqli_query($con, $updateStokQuery);

        $deleteQuery = "DELETE FROM pinjam WHERE id = $id";
        mysqli_query($con, $deleteQuery);

        header("Location: ?page=show");
        exit();
    } else {
        echo "Data tidak ditemukan.";
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
                                <form class="form-material" method="POST">
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="nama" required="">
                                        <span class="form-bar"></span>
                                        <label for="namainventaris" class="float-label">Nama Barang</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="stok" required="">
                                        <span class="form-bar"></span>
                                        <label for="stok" class="float-label">Stok</label>
                                    </div>
                                    <div class="row">
                                        <div class="col offset">
                                            <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                            <a href="?page=inventaris" class="btn btn-warning"><i class="fa fa-chevron-left"></i> Kembali</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="styleSelector"></div>
    </div>
</div>
