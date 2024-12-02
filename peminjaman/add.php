<?php
if (isset($_POST['submit'])) {
    $nama       = $_POST['nama'];
    $idPetugas  = $_POST['petugas'];
    $tempat     = $_POST['tempat'];
    $keperluan  = $_POST['keperluan'];
    $jumlah     = $_POST['jumlah']; // Mengambil jumlah per inventaris
    $inventaris = $_POST['inventaris']; // Array dari inventaris yang dipilih

    $totalJumlah = 0; // Untuk menghitung total jumlah yang dipinjam

    if (empty($inventaris)) {
        echo "<script>alert('Silakan pilih inventaris terlebih dahulu.');</script>";
        exit;
    }

    foreach ($inventaris as $index => $idInventaris) {
        // Cek stok sebelum melakukan peminjaman
        $cekStok = mysqli_query($con, "SELECT * FROM inventaris WHERE id = '$idInventaris'");
        $stokInventaris = mysqli_fetch_array($cekStok);
        $stokNow = $stokInventaris['stok'];

        // Validasi jumlah peminjaman
        if ($stokNow < $jumlah[$index]) {
            echo "<script>alert('Stok tidak mencukupi untuk inventaris ID: $idInventaris. Stok saat ini: $stokNow');</script>";
            continue; // Lewati iterasi ini jika stok tidak mencukupi
        } else {
            // Update stok
            $hasilStok = $stokNow - $jumlah[$index]; // Mengurangi stok
            $updateStok = mysqli_query($con, "UPDATE inventaris SET stok='$hasilStok' WHERE id='$idInventaris'");

            // Insert ke tabel pinjam
            $insert = mysqli_query($con, "INSERT INTO pinjam(nama, idInventaris, idPetugas, tempat, keperluan, jumlah) VALUES('$nama', '$idInventaris', '$idPetugas', '$tempat', '$keperluan', '{$jumlah[$index]}')");
            if ($insert) {
                $totalJumlah += $jumlah[$index]; // Tambahkan ke total jumlah jika berhasil
            } else {
                echo "<script>alert('Gagal menyimpan data peminjaman untuk inventaris ID: $idInventaris');</script>";
            }
        }
    }

    // Jika ada peminjaman yang berhasil, lakukan redirect
    if ($totalJumlah > 0) {
        echo "<script>window.location.href = '?page=pinjam';</script>";
    } else {
        echo "<script>alert('Tidak ada inventaris yang berhasil dipinjam.');</script>";
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
                                        <label for="nama" class="float-label">Nama Peminjam</label>
                                    </div>

                                    <div class="form-group">
                                        <label>Inventaris</label><br>
                                        <?php
                                        $query = mysqli_query($con, "SELECT * FROM inventaris");
                                        while ($data = mysqli_fetch_array($query)) {
                                        ?>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <input type="checkbox" name="inventaris[]" value="<?php echo $data['id']; ?>">
                                                    <label><?php echo $data['nama']; ?></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" min="1">
                                                </div>
                                            </div>
                                            <hr> <!-- Garis pemisah untuk membedakan antar item inventaris -->
                                        <?php } ?>
                                    </div>

                                    <div class="form-group form-default">
                                        <span class="form-bar"></span>
                                        <label class="">Petugas</label>
                                        <div class="col-sm-15">
                                            <select name="petugas" class="form-control" required>
                                                <option value="" selected>- Pilih Petugas -</option>
                                                <?php
                                                    $query = mysqli_query($con, "SELECT * FROM petugas");
                                                    while ($data = mysqli_fetch_array($query)) {
                                                ?>
                                                    <option value="<?php echo $data['id'] ?>"><?php echo $data['nama'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="tempat" required="">
                                        <span class="form-bar"></span>
                                        <label for="tempat" class="float-label">Tempat</label>
                                    </div>

                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="keperluan" required="">
                                        <span class="form-bar"></span>
                                        <label for="keperluan" class="float-label">Keperluan</label>
                                    </div>

                                    <div class="row">
                                        <div class="col offset">
                                            <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                            <a href="?page=pinjam" class="btn btn-warning"><i class="fa fa-chevron-left"></i> Kembali</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="styleSelector"></div>
        </div>
    </div>
</div>