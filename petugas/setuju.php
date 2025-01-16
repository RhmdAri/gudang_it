<?php
session_start();
include '../connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "UPDATE petugas SET status = 'disetujui' WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data telah disetujui!'
            }).then(() => {
                window.location.href = '?page=petugas';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat menyetujui data!'
            });
        </script>";
    }
}
?>
