<?php
include_once '../connection.php';

// Matikan output buffering
ob_start();

if (isset($_GET['nama'])) {
    $nama = mysqli_real_escape_string($con, $_GET['nama']);

    // Ambil semua data peminjaman berdasarkan nama
    $queryPinjam = mysqli_query($con, "SELECT idInventaris, jumlah FROM pinjam WHERE nama = '$nama' AND status != 'selesai'");

    if (mysqli_num_rows($queryPinjam) > 0) {
        // Proses semua inventaris
        while ($dataPinjam = mysqli_fetch_array($queryPinjam)) {
            $idInventaris = $dataPinjam['idInventaris'];
            $jumlah = $dataPinjam['jumlah'];

            // Update stok inventaris
            $updateStok = mysqli_query($con, "UPDATE inventaris SET stok = stok + $jumlah WHERE id = '$idInventaris'");
            if (!$updateStok) {
                die('Error updating stock: ' . mysqli_error($con));
            }
        }

        // Update status semua peminjaman untuk nama yang sama
        $updateStatus = mysqli_query($con, "UPDATE pinjam SET status = 'selesai' WHERE nama = '$nama' AND status != 'selesai'");
        if (!$updateStatus) {
            die('Error updating status: ' . mysqli_error($con));
        }

        // Redirect kembali ke halaman show.php
        echo "<script>window.location.href = '?page=pinjam';</script>";
        exit;
    } else {
        echo "Tidak ada data peminjaman yang ditemukan untuk peminjam tersebut.";
    }
} else {
    echo "Nama peminjam tidak diberikan.";
}

// Flush output buffer
ob_end_flush();
?>
