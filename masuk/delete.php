<?php
include '../connection.php';

$id = $_GET['id'];
$query = "SELECT idBarang, jumlah FROM masuk WHERE id = $id";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    $idBarang = $data['idBarang'];
    $jumlah = $data['jumlah'];
    $updateBarangQuery = "UPDATE barang SET stok = stok - $jumlah WHERE id = $idBarang";
    $updateResult = mysqli_query($con, $updateBarangQuery);

    if ($updateResult) {
        $deleteQuery = "DELETE FROM masuk WHERE id = $id";
        $deleteResult = mysqli_query($con, $deleteQuery);

        if ($deleteResult) {
            echo "
            <script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data masuk berhasil dihapus dan stok diperbarui.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '?page=masuk';
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
                        window.location.href = '?page=masuk';
                    }
                })
            </script>
            ";
        }
    } else {
        echo "
        <script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat memperbarui stok barang.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?page=masuk';
                }
            })
        </script>
        ";
    }
} else {
    echo "
    <script>
        Swal.fire({
            title: 'Gagal!',
            text: 'Data tidak ditemukan.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '?page=masuk';
            }
        })
    </script>
    ";
}
?>
