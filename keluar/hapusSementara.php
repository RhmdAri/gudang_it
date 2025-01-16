<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['divisi'])) {
    echo "<script>window.location.href = '../';</script>";
    exit();
}

$divisi = $_SESSION['divisi'];

if (isset($_GET['id'])) {
    $idTemp = $_GET['id'];
    $deleteTemp = mysqli_query($con, "DELETE FROM temp_keluar WHERE id = '$idTemp' AND devisi = '$divisi'");

    if ($deleteTemp) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil dihapus!'
            }).then(() => {
                window.location.href = '?page=keluarAdd';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal menghapus data!'
            });
        </script>";
    }
} else {
    echo "<script>window.location.href = '?page=keluarAdd';</script>";
}
?>
