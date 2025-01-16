<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<?php
$id = $_GET['id'];

$result = mysqli_query($con, "DELETE FROM petugas WHERE id=$id");

if ($result) {
    echo "
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data petugas berhasil dihapus.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '?page=petugas'; // Arahkan kembali ke halaman daftar petugas
            }
        })
    </script>
    ";
} else {
    echo "
    <script>
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menghapus data.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '?page=petugas'; // Arahkan kembali ke halaman daftar petugas
            }
        })
    </script>
    ";
}
?>
