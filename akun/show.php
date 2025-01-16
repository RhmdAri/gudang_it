<?php
session_start();
include '../connection.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['id'])) {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Akses Ditolak',
            text: 'Silakan login terlebih dahulu!'
        }).then(() => {
            window.location.href = '../';
        });
    </script>";
    exit();
}

$title = "Manajemen User"; 
$level = $_SESSION['level'];
$userId = $_SESSION['id'];
$divisi = $_SESSION['divisi'] ?? null;
?>

<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10"><?php echo htmlspecialchars($title); ?></h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!"><?php echo htmlspecialchars($title); ?></a></li>
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
                            <h5><?php echo htmlspecialchars($title); ?></h5>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <?php if ($level == 'administrator') { ?>
                                    <a href="?page=add" class="btn btn-outline-primary">
                                        <i class="icofont icofont-user-alt-3"></i> Tambah
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="card-block table-border-style">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Level</th>
                                        <th class="text-center">Divisi</th>
                                        <?php if ($level == 'administrator') { ?>
                                            <th class="text-center">Aksi</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ($level == 'administrator') {
                                    $query = "SELECT * FROM pengguna ORDER BY level ASC";
                                    $stmt = mysqli_prepare($con, $query);
                                } elseif ($level == 'kepala') {
                                    $query = "SELECT * FROM pengguna WHERE divisi = ? AND level = 'pegawai' ORDER BY username ASC";
                                    $stmt = mysqli_prepare($con, $query);
                                    mysqli_stmt_bind_param($stmt, "s", $divisi);
                                } elseif ($level == 'pegawai') {
                                    $query = "SELECT * FROM pengguna WHERE id = ? AND divisi = ?";
                                    $stmt = mysqli_prepare($con, $query);
                                    mysqli_stmt_bind_param($stmt, "is", $userId, $divisi);
                                }

                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);

                                $no = 1;
                                while ($data = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no++; ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($data['nama']); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($data['username']); ?></td>
                                        <td class="text-center"><?php echo ucfirst(htmlspecialchars($data['level'])); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($data['divisi'] ?? '-'); ?></td>
                                        <?php if ($level == 'administrator') { ?>
                                            <td class="text-center">
                                                <a class="btn btn-outline-success btn-sm mx-1" href="?page=reset&id=<?php echo $data['id']; ?>">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm mx-1" href="#" onclick="confirmDelete(<?php echo $data['id']; ?>)">
                                                    <i class="fa fa-trash"></i> Hapus
                                                </a>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
function confirmDelete(id) {
    Swal.fire({
        title: 'Anda yakin?',
        text: 'Data ini akan dihapus dan tidak dapat dipulihkan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?page=delete&id=' + id;
        }
    });
}
</script>
