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
                                <a href="?page=addDev" class="btn btn-outline-primary">
                                    <i class="icofont icofont-user-alt-3"></i> Tambah
                                </a>
                            </div>
                        </div>
                        <div class="card-block table-border-style">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center">No</th>
                                            <th class="text-center">Divisi</th>
                                            <th class="text-center">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT devisi, keterangan FROM devisi ORDER BY devisi ASC";
                                        $result = mysqli_query($con, $query);

                                        $no = 1;
                                        while ($data = mysqli_fetch_assoc($result)) { ?>
                                            <tr>
                                                <td class="text-center"><?php echo $no++; ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['devisi']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['keterangan']); ?></td>
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
