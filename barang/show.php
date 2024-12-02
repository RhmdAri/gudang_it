<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); // Aktifkan error reporting

require '../vendor/autoload.php'; // Pastikan autoloader Composer sudah diimpor
use PhpOffice\PhpSpreadsheet\IOFactory;
include '../connection.php';

// Cek koneksi database
if (!$con) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Menangani form import
if (isset($_POST['import'])) {
    if ($_FILES['file']['error'] != UPLOAD_ERR_OK) {
        // Tampilkan SweetAlert jika upload gagal
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Upload gagal',
                    text: 'Terjadi kesalahan saat mengupload file!',
                });
              </script>";
        exit;
    }

    // Pastikan file yang diupload adalah file yang valid
    $fileExt = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExt, ['xlsx', 'xls', 'csv'])) {
        // Tampilkan SweetAlert jika file tidak valid
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'File tidak valid',
                    text: 'File harus dalam format .xlsx, .xls, atau .csv!',
                });
              </script>";
        exit;
    }

    // Cek apakah file Excel bisa dibaca
    try {
        $spreadsheet = IOFactory::load($_FILES['file']['tmp_name']);
    } catch (Exception $e) {
        // Tampilkan SweetAlert jika terjadi error pada file Excel
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat membaca file: " . $e->getMessage() . "',
                });
              </script>";
        exit;
    }

    // Membaca data dari file Excel
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    // Proses setiap baris dan memasukkan data ke dalam database
    foreach ($rows as $key => $row) {
        if ($key == 0) continue; // Skip header

        $kode = mysqli_real_escape_string($con, $row[0]);
        $nama = mysqli_real_escape_string($con, $row[1]);
        $kategori = mysqli_real_escape_string($con, $row[2]);
        $stok = (int)$row[3];

        // Cek apakah kategori ada di database
        $kategoriQuery = mysqli_query($con, "SELECT id FROM kategori WHERE nama = '$kategori'");
        if (mysqli_num_rows($kategoriQuery) > 0) {
            $kategoriData = mysqli_fetch_assoc($kategoriQuery);
            $kategoriId = $kategoriData['id'];
        } else {
            // Tampilkan SweetAlert jika kategori tidak ditemukan
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Kategori tidak ditemukan',
                        text: 'Kategori untuk barang: $nama tidak ditemukan!',
                    });
                  </script>";
            continue; // Skip item ini
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
        $insertQuery = "INSERT INTO barang (kode, nama, idKategori, stok) 
                        VALUES ('$kode', '$nama', '$kategoriId', '$stok')";
        
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
                        <h5 class="m-b-10">Barang</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Barang</a></li>
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
                            <h5>Barang</h5>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex">
                                    <a href="?page=barangAdd" class="btn waves-effect waves-light btn-primary btn-outline-primary mr-2">
                                        <i class="icofont icofont-user-alt-3"></i>Tambah
                                    </a>
                                    <a href="../barang/print.php" target="_blank" class="btn waves-effect waves-light btn-success btn-outline-success mr-2">
                                        <i class="fa fa-print"></i> Cetak
                                        <a href="../barang/print.php?export_excel=true" class="btn waves-effect waves-light btn-info btn-outline-info mr-2">
                                        <i class="fa fa-download"></i> Export to Excel
                                    </a>
                                    </a>
                                    <form action="?page=barang" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                                        <input type="file" name="file" accept=".xlsx, .xls, .csv" class="form-control mr-2" style="width: 250px;" required>
                                        <button type="submit" name="import" class="btn btn-warning btn-outline-warning">
                                            <i class="fa fa-upload"></i> Impor Barang
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card-block table-border-style">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Kategori</th>
                                            <th>Stok</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        <?php
                                        $result = mysqli_query($con, "
                                            SELECT 
                                                barang.id, barang.kode, barang.nama, barang.stok,
                                                kategori.nama as namaKategori
                                            FROM barang
                                            INNER JOIN kategori ON barang.idKategori = kategori.id
                                            ORDER BY kategori.nama ASC
                                        ");
                                        while ($data = mysqli_fetch_array($result)) {
                                        ?>
                                            <tr>
                                                <td><?php echo $data['kode']; ?></td>
                                                <td><?php echo $data['nama']; ?></td>
                                                <td><?php echo $data['namaKategori']; ?></td>
                                                <td><?php echo $data['stok']; ?></td>
                                                <td>
                                                    <a href="?page=barangEdit&id=<?php echo $data['id']; ?>" 
                                                    class="btn waves-effect waves-light btn-success btn-outline-success btn-sm">
                                                        <i class="fa fa-pencil"></i> 
                                                    </a>
                                                    <!-- Tombol Hapus -->
                                                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $data['id']; ?>)" 
                                                    class="btn waves-effect waves-light btn-danger btn-outline-danger btn-sm">
                                                        <i class="fa fa-trash"></i> 
                                                    </a>

                                                    <script>
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
                                                                    // Mengarahkan ke halaman barangDelete.php dengan parameter id
                                                                    window.location.href = '?page=barangDelete&id=' + id;
                                                                }
                                                            });
                                                        }
                                                    </script>
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

<script type="text/javascript">
    const searchInput = document.getElementById('search');
    const tableBody = document.getElementById('table-body');
    const rows = tableBody.getElementsByTagName('tr');
    searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toLowerCase();
        for (let i = 0; i < rows.length; i++) {
            const namaBarangTd = rows[i].getElementsByTagName('td')[1];
            if (namaBarangTd) {
                const txtValue = namaBarangTd.textContent || namaBarangTd.innerText;
                rows[i].style.display = txtValue.toLowerCase().includes(filter) ? '' : 'none';
            }
        }
    });
</script>