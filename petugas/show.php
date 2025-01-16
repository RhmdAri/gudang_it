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

$title = "Manajemen Petugas"; 
$level = $_SESSION['level'];
$divisi = $_SESSION['divisi'];
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
                                <?php if ($level == 'administrator' || $level == 'kepala') { ?>
                                    <a href="?page=petugasAdd" class="btn btn-outline-primary">
                                        <i class="fa fa-plus"></i> Tambah
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
                                        <th class="text-center">Nama Petugas</th>
                                        <th class="text-center">Jabatan</th>
                                        <th class="text-center">Status</th>
                                        <?php if ($level == 'administrator') { ?>
                                            <th class="text-center">Divisi</th>
                                        <?php } ?>
                                        <?php if ($level == 'administrator' || $level == 'kepala') { ?>
                                            <th class="text-center">Aksi</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ($level == 'administrator') {
                                    $query = "SELECT * FROM petugas";
                                    $stmt = mysqli_prepare($con, $query);
                                } else {
                                    $query = "SELECT * FROM petugas WHERE divisi = ?";
                                    $stmt = mysqli_prepare($con, $query);
                                    mysqli_stmt_bind_param($stmt, "s", $divisi);
                                }

                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);

                                $no = 1;
                                while ($data = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td class="text-center"> <?php echo $no++; ?> </td>
                                        <td class="text-center"> <?php echo htmlspecialchars($data['nama']); ?> </td>
                                        <td class="text-center"> <?php echo htmlspecialchars($data['jabatan']); ?> </td>
                                        <td class="text-center"> 
                                            <?php 
                                            if ($data['status'] == 'menunggu') {
                                                echo "Menunggu Persetujuan";
                                                echo "<br><small class='text-danger'>Petugas tidak bisa digunakan</small>";
                                            } else {
                                                echo "Disetujui";
                                            }
                                            ?>
                                        </td>
                                        <?php if ($level == 'administrator') { ?>
                                            <td class="text-center"> <?php echo htmlspecialchars($data['divisi']); ?> </td>
                                        <?php } ?>
                                        <?php if ($level == 'administrator' || $level == 'kepala') { ?>
                                            <td class="text-center">
                                                <?php if ($level == 'administrator') { ?>
                                                    <?php if ($data['status'] == 'menunggu') { ?>
                                                        <a class="btn btn-outline-success btn-sm mx-1" href="?page=setuju&id=<?php echo $data['id']; ?>">
                                                            <i class="fa fa-check"></i> Setujui
                                                        </a>
                                                        <a class="btn btn-outline-danger btn-sm mx-1" href="?page=tolak&id=<?php echo $data['id']; ?>">
                                                            <i class="fa fa-times"></i> Tolak
                                                        </a>
                                                    <?php } ?>
                                                <?php } ?>
                                                
                                                <?php if ($level == 'kepala' && $data['status'] == 'disetujui') { ?>
                                                    <a class="btn btn-outline-warning btn-sm mx-1" href="?page=petugasEdit&id=<?php echo $data['id']; ?>">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                <?php } ?>
                                                
                                                <?php if ($level == 'administrator' || $level == 'kepala') { ?>
                                                    <a class="btn btn-outline-danger btn-sm mx-1" href="#" onclick="confirmDelete(<?php echo $data['id']; ?>)">
                                                        <i class="fa fa-trash"></i> Hapus
                                                    </a>
                                                <?php } ?>
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
            window.location.href = '?page=petugasDelete&id=' + id;
        }
    });
}
</script>