<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['id']) || $_SESSION['level'] != 'administrator') {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Akses Ditolak',
            text: 'Hanya Administrator yang dapat mengonfirmasi perubahan.'
        }).then(() => {
            window.location.href = '../';
        });
    </script>";
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM petugas WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "UPDATE petugas SET jabatan = ?, status = 'disetujui' WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "si", $_POST['jabatan'], $id);
    mysqli_stmt_execute($stmt);

    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Perubahan Disetujui',
            text: 'Perubahan petugas telah disetujui dan diterapkan.'
        }).then(() => {
            window.location.href = '?page=petugasList';
        });
    </script>";
    exit();
}
?>

<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Persetujuan Perubahan Petugas</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="?page=petugasList">Daftar Petugas</a></li>
                        <li class="breadcrumb-item"><a href="#!">Persetujuan Perubahan</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        <div class="card-header">
                            <h5>Persetujuan Perubahan Petugas</h5>
                        </div>

                        <div class="card-block">
                            <form method="POST">
                                <div class="form-group">
                                    <label for="jabatan">Jabatan Baru</label>
                                    <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?php echo htmlspecialchars($data['jabatan']); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-success">Setujui Perubahan</button>
                                <a href="?page=petugasList" class="btn btn-danger">Tolak Perubahan</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
