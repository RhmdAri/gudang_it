<?php
session_start();
include '../connection.php';
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

$title = "Manajemen Kategori";
$level = $_SESSION['level'];
$userId = $_SESSION['id'];
$divisi = $_SESSION['divisi'];
?>

<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10"><?php echo $title; ?></h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!"><?php echo $title; ?></a></li>
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
                            <h5><?php echo $title; ?></h5>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="?page=kategoriAdd" class="btn waves-effect waves-light btn-primary btn-outline-primary">
                                    <i class="fa fa-plus"></i>Tambah</a>
                            </div>
                        </div>
                        <div class="card-block table-border-style">
                            <div class="table-responsive">
                            <table id="datatable" class="table table-hover table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center">No</th>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Keterangan</th>
                                        <?php if ($level == 'administrator') { ?>
                                            <th class="text-center">Divisi</th>
                                        <?php } ?>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody id="table-body">
                                    <?php
                                    if ($level == 'administrator') {
                                        $query = "SELECT * FROM kategori";
                                    } else {
                                        $query = "SELECT * FROM kategori WHERE divisi = ?";
                                    }
                                    $stmt = mysqli_prepare($con, $query);
                                    if ($level != 'administrator') {
                                        mysqli_stmt_bind_param($stmt, "s", $divisi);
                                    }
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);
                                    $no = 1;
                                    while ($data = mysqli_fetch_array($result)) { ?>
                                        <tr>
                                            <td class="text-center"><?php echo $no++; ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($data['nama']); ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($data['keterangan']); ?></td>
                                            <?php if ($level == 'administrator') { ?>
                                                <td class="text-center"><?php echo htmlspecialchars($data['divisi']); ?></td>
                                            <?php } ?>
                                            <td class="text-center">
                                                <a class="btn waves-effect waves-dark btn-success btn-outline-success btn-sm" href="?page=kategoriEdit&id=<?php echo $data['id']; ?>">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>
                                                <a class="btn waves-effect waves-dark btn-danger btn-outline-danger btn-sm" href="#" onclick="confirmDelete(<?php echo $data['id']; ?>)">
                                                    <i class="fa fa-trash"></i> Hapus
                                                </a>
                                            </td>
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
        <div id="styleSelector"></div>
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
            window.location.href = '?page=kategoriDelete&id=' + id;
        }
    });
}
</script>
