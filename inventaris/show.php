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

ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if (!$con) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_POST['import'])) {
    if ($_FILES['file']['error'] != UPLOAD_ERR_OK) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Upload gagal',
                    text: 'Terjadi kesalahan saat mengupload file!',
                });
              </script>";
        exit;
    }

    $fileExt = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExt, ['xlsx', 'xls', 'csv'])) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'File tidak valid',
                    text: 'File harus dalam format .xlsx, .xls, atau .csv!',
                });
              </script>";
        exit;
    }

    try {
        $spreadsheet = IOFactory::load($_FILES['file']['tmp_name']);
    } catch (Exception $e) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat membaca file: " . $e->getMessage() . "',
                });
              </script>";
        exit;
    }

    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    $divisi = $_SESSION['divisi'];

    foreach ($rows as $key => $row) {
        if ($key == 0) continue;

        $nama = mysqli_real_escape_string($con, $row[0]);
        $stok = mysqli_real_escape_string($con, $row[1]);

        $inventarisQuery = mysqli_query($con, "SELECT id FROM inventaris WHERE nama = '$nama' AND divisi = '$divisi'");
        if (mysqli_num_rows($inventarisQuery) > 0) {
            echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Barang sudah ada',
                        text: 'Barang dengan nama $nama sudah ada di database untuk divisi Anda.',
                    });
                  </script>";
            continue;
        }

        $insertQuery = "INSERT INTO inventaris (nama, stok, divisi) 
                        VALUES ('$nama', '$stok', '$divisi')";
        
        if (mysqli_query($con, $insertQuery)) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Impor Berhasil',
                        text: 'Barang $nama berhasil diimpor.',
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat mengimpor barang $nama.',
                    });
                  </script>";
        }
    }
}

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
                            <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                                <a href="?page=inventarisAdd" class="btn waves-effect waves-light btn-primary btn-outline-primary">
                                    <i class="fa fa-plus"></i>Tambah</a>
                                <a href="../inventaris/print.php?export_excel=true" class="btn waves-effect waves-light btn-info btn-outline-info">
                                    <i class="fa fa-download"></i> Export ke Excel
                                </a>
                                <a href="../inventaris/print.php" target="_blank" class="btn waves-effect waves-light btn-secondary btn-outline-secondary">
                                    <i class="fa fa-print"></i> Cetak
                                </a>
                                <form action="?page=inventaris" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                                    <input type="file" name="file" accept=".xlsx, .xls, .csv" class="form-control mr-2" style="width: 250px;" required>
                                    <button type="submit" name="import" class="btn btn-warning btn-outline-warning">
                                        <i class="fa fa-upload"></i> Impor Inventaris
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-block table-border-style">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama Barang</th>
                                            <th class="text-center">Stok</th>
                                            <th class="text-center">Devisi</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        <?php
                                        $level = $_SESSION['level'];
                                        $divisi = $_SESSION['divisi'];

                                        $query = "
                                            SELECT * 
                                            FROM inventaris 
                                            WHERE divisi = '$divisi'
                                        ";
                                        $result = mysqli_query($con, $query);
                                        $no = 1;
                                        while ($data = mysqli_fetch_array($result)) { ?>
                                            <tr>
                                                <td class="text-center"><?php echo $no++; ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['nama']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['stok']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['divisi']); ?></td>
                                                <td class="text-center">
                                                    <a class="btn waves-effect waves-dark btn-success btn-outline-success btn-sm" href="?page=inventarisEdit&id=<?php echo $data['id']; ?>">
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
        title: 'Yakin ingin menghapus?',
        text: "Data yang sudah dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?page=inventarisDelete&id=' + id;
        }
    });
}
</script>
