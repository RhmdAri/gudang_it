<?php
include_once '../connection.php';
ob_start();

if (isset($_GET['nama'])) {
    $nama = mysqli_real_escape_string($con, $_GET['nama']);
    $queryPinjam = mysqli_query($con, "SELECT idInventaris, jumlah FROM pinjam WHERE nama = '$nama' AND status != 'selesai'");

    if (mysqli_num_rows($queryPinjam) > 0) {
        while ($dataPinjam = mysqli_fetch_array($queryPinjam)) {
            $idInventaris = $dataPinjam['idInventaris'];
            $jumlah = $dataPinjam['jumlah'];
            $updateStok = mysqli_query($con, "UPDATE inventaris SET stok = stok + $jumlah WHERE id = '$idInventaris'");
            if (!$updateStok) {
                die('Error updating stock: ' . mysqli_error($con));
            }
        }

        $updateStatus = mysqli_query($con, "UPDATE pinjam SET status = 'selesai' WHERE nama = '$nama' AND status != 'selesai'");
        if (!$updateStatus) {
            die('Error updating status: ' . mysqli_error($con));
        }

        echo "<script>window.location.href = '?page=pinjam';</script>";
        exit;
    } else {
        echo "Tidak ada data peminjaman yang ditemukan untuk peminjam tersebut.";
    }
} else {
    echo "Nama peminjam tidak diberikan.";
}
ob_end_flush();
?>
