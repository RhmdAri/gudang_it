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
include '../connection.php';
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

        $kode = mysqli_real_escape_string($con, $row[0]);
        $nama = mysqli_real_escape_string($con, $row[1]);
        $kategori = mysqli_real_escape_string($con, $row[2]);
        $stok = (int)$row[3];
        $kategoriQuery = mysqli_query($con, "SELECT id FROM kategori WHERE nama = '$kategori'");
        if (mysqli_num_rows($kategoriQuery) > 0) {
            $kategoriData = mysqli_fetch_assoc($kategoriQuery);
            $kategoriId = $kategoriData['id'];
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Kategori tidak ditemukan',
                        text: 'Kategori untuk barang: $nama tidak ditemukan!',
                    });
                  </script>";
            continue; 
        }
        $barangQuery = mysqli_query($con, "SELECT id FROM barang WHERE kode = '$kode'");
        if (mysqli_num_rows($barangQuery) > 0) {
            echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Barang sudah ada',
                        text: 'Barang dengan kode $kode sudah ada di database.',
                    });
                  </script>";
            continue;
        }
        $insertQuery = "INSERT INTO barang (kode, nama, idKategori, stok, devisi) 
                        VALUES ('$kode', '$nama', '$kategoriId', '$stok', '$divisi')";
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
                            <div class="d-flex flex-wrap gap-2" style="gap: 10px;">
                                <a href="?page=barangAdd" class="btn waves-effect waves-light btn-primary btn-outline-primary">
                                    <i class="fa fa-plus"></i> Tambah
                                </a>
                                <a href="../barang/print.php?export_excel=true" class="btn waves-effect waves-light btn-info btn-outline-info">
                                    <i class="fa fa-download"></i> Export ke Excel
                                </a>
                                <a href="../barang/print.php" target="_blank" class="btn waves-effect waves-light btn-secondary btn-outline-secondary">
                                    <i class="fa fa-print"></i> Cetak
                                </a>
                                <form action="?page=barang" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                                    <input type="file" name="file" accept=".xlsx, .xls, .csv" class="form-control mr-2" style="width: 250px;" required>
                                    <button type="submit" name="import" class="btn btn-warning btn-outline-warning">
                                        <i class="fa fa-upload"></i> Impor Barang
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
                                            <th class="text-center">Kode Barang</th>
                                            <th class="text-center">Nama Barang</th>
                                            <th class="text-center">Kategori</th>
                                            <th class="text-center">Stok</th>
                                            <th class="text-center">Devisi</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody id="table-body">
                                        <?php
                                        $divisi = $_SESSION['divisi'];

                                        $query = "SELECT barang.*, kategori.nama AS kategori 
                                                  FROM barang 
                                                  LEFT JOIN kategori ON barang.idKategori = kategori.id 
                                                  WHERE barang.devisi = '$divisi'";
                                        $result = mysqli_query($con, $query);
                                        $no = 1;
                                        while ($data = mysqli_fetch_array($result)) { ?>
                                            <tr>
                                                <td class="text-center"><?php echo $no++; ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['kode']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['nama']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['kategori']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['stok']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['devisi']); ?></td>
                                                <td class="text-center">
                                                    <a class="btn waves-effect waves-dark btn-success btn-outline-success btn-sm" href="?page=barangEdit&id=<?php echo $data['id']; ?>">
                                                        <i class="fa fa-pencil"></i> Edit
                                                    </a>
                                                    <a class="btn btn-outline-danger btn-sm mx-1" href="#" onclick="confirmDelete(<?php echo $data['id']; ?>)">
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
            window.location.href = '?page=barangDelete&id=' + id;
        }
    });
}
</script>
