<!DOCTYPE html>
<html lang="en">

<head>
    <title>GUDANG</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Gudang IT Management System" />
    <meta name="keywords" content="admin, dashboard, management system" />
    <meta name="author" content="GUDANG IT" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/pages/waves/css/waves.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="../assets/icon/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/icon/icofont/css/icofont.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <div class="theme-loader">
        <div class="loader-track">
            <div class="preloader-wrapper">
                <?php 
                $colors = ['blue', 'red', 'yellow', 'green'];
                foreach ($colors as $color): ?>
                    <div class="spinner-layer spinner-<?= $color ?>">
                        <div class="circle-clipper left"><div class="circle"></div></div>
                        <div class="gap-patch"><div class="circle"></div></div>
                        <div class="circle-clipper right"><div class="circle"></div></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div id="pcoded" class="pcoded">
        <?php 
        include_once '../logincheck.php'; 
        include_once '../connection.php';
        error_reporting(0);
        ?>

        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            
            <?php include_once '../layout/navbar.php'; ?>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <?php include_once '../layout/menu.php'; ?>
                    <?php
                    $pages = [
                        'dashboard' => ['title' => 'Dashboard', 'path' => 'dashboard.php'],
                        'create' => ['title' => 'Dashboard', 'path' => 'create_message.php'],
                        'petugas' => ['title' => 'Data Petugas', 'path' => '../petugas/show.php'],
                        'setuju' => ['title' => 'Setujui', 'path' => '../petugas/setuju.php'],
                        'tolak' => ['title' => 'Tolak', 'path' => '../petugas/tolak.php'],
                        'petugasAdd' => ['title' => 'Tambah Data Petugas', 'path' => '../petugas/add.php'],
                        'petugasEdit' => ['title' => 'Edit Data Petugas', 'path' => '../petugas/edit.php'],
                        'petugasDelete' => ['title' => 'Hapus Data Petugas', 'path' => '../petugas/delete.php'],
                        'kategori' => ['title' => 'Data Kategori', 'path' => '../kategori/show.php'],
                        'kategoriAdd' => ['title' => 'Tambah Data Kategori', 'path' => '../kategori/add.php'],
                        'kategoriEdit' => ['title' => 'Edit Data Kategori', 'path' => '../kategori/edit.php'],
                        'kategoriDelete' => ['title' => 'Hapus Data Kategori', 'path' => '../kategori/delete.php'],
                        'inventaris' => ['title' => 'Data Inventaris', 'path' => '../inventaris/show.php'],
                        'inventarisAdd' => ['title' => 'Tambah Data Inventaris', 'path' => '../inventaris/add.php'],
                        'inventarisEdit' => ['title' => 'Edit Data Inventaris', 'path' => '../inventaris/edit.php'],
                        'inventarisDelete' => ['title' => 'Hapus Data Inventaris', 'path' => '../inventaris/delete.php'],
                        'barang' => ['title' => 'Data Barang', 'path' => '../barang/show.php'],
                        'import' => ['title' => 'Data Barang', 'path' => '../barang/barangImport.php'],
                        'barangAdd' => ['title' => 'Tambah Data Barang', 'path' => '../barang/add.php'],
                        'barangEdit' => ['title' => 'Edit Data Barang', 'path' => '../barang/edit.php'],
                        'barangDelete' => ['title' => 'Hapus Data Barang', 'path' => '../barang/delete.php'],
                        'pinjam' => ['title' => 'Data Pinjam', 'path' => '../peminjaman/show.php'],
                        'proses' => ['title' => 'Proses Peminjaman', 'path' => '../peminjaman/completeProcess.php'],
                        'ambil' => ['title' => 'Pilih Data Inventaris', 'path' => '../peminjaman/get_inventaris.php'],
                        'pinjamAdd' => ['title' => 'Tambah Data Pinjam', 'path' => '../peminjaman/add.php'],
                        'masuk' => ['title' => 'Data Masuk', 'path' => '../masuk/show.php'],
                        'masukAdd' => ['title' => 'Tambah Data Masuk', 'path' => '../masuk/add.php'],
                        'masukEdit' => ['title' => 'Edit Data Masuk', 'path' => '../masuk/edit.php'],
                        'masukDelete' => ['title' => 'Hapus Data Masuk', 'path' => '../masuk/delete.php'],
                        'hapusSementara' => ['title' => 'Hapus', 'path' => '../masuk/hapusSementara.php'],
                        'keluar' => ['title' => 'Data Keluar', 'path' => '../keluar/show.php'],
                        'keluarAdd' => ['title' => 'Tambah Data Keluar', 'path' => '../keluar/add.php'],
                        'keluarEdit' => ['title' => 'Edit Data Keluar', 'path' => '../keluar/edit.php'],
                        'keluarDelete' => ['title' => 'Hapus Data Keluar', 'path' => '../keluar/delete.php'],
                        'hapusKeluar' => ['title' => 'Hapus', 'path' => '../keluar/hapusSementara.php'],
                        'kegiatan' => ['title' => 'Data Kegiatan', 'path' => '../kegiatan/show.php'],
                        'kegiatanAdd' => ['title' => 'Tambah Data Kegiatan', 'path' => '../kegiatan/add.php'],
                        'kegiatanEdit' => ['title' => 'Edit Data Kegiatan', 'path' => '../kegiatan/edit.php'],
                        'kegiatanDelete' => ['title' => 'Edit Data Kegiatan', 'path' => '../kegiatan/delete.php'],
                        'akun' => ['title' => 'Data Akun', 'path' => '../akun/show.php'],
                        'add' => ['title' => 'Data Akun', 'path' => '../akun/add.php'],
                        'reset' => ['title' => 'Data Akun', 'path' => '../akun/reset.php'],
                        'delete' => ['title' => 'Data Akun', 'path' => '../akun/delete.php'],
                        'devisi' => ['title' => 'Data Devisi', 'path' => '../devisi/show.php'],
                        'addDev' => ['title' => 'Data Devisi', 'path' => '../devisi/add.php'],
                    ];

                    $page = $_GET['page'] ?? 'dashboard';

                    if (array_key_exists($page, $pages)) {
                        $title = $pages[$page]['title'];
                        include $pages[$page]['path'];
                    } else {
                        echo "<div class='alert alert-danger text-center'>Halaman tidak ditemukan!</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../layout/js.php'; ?>
</body>
</html>
