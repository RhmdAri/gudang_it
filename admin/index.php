<!DOCTYPE html>
<html lang="en">

<head>
    <title>GUDANG IT</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Mega Able Bootstrap admin template with huge ready-made features, UI components, and pages to fulfill any dashboard needs." />
    <meta name="keywords" content="bootstrap, admin template, admin theme, admin dashboard, dashboard template, responsive" />
    <meta name="author" content="codedthemes" />

    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/pages/waves/css/waves.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="../assets/icon/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/icon/icofont/css/icofont.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Pre-loader Start -->
    <div class="theme-loader">
        <div class="loader-track">
            <div class="preloader-wrapper">
                <!-- Spinner Layers -->
                <?php 
                $colors = ['blue', 'red', 'yellow', 'green'];
                foreach ($colors as $color): ?>
                    <div class="spinner-layer spinner-<?= $color ?>">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="gap-patch">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- Pre-loader End -->

    <div id="pcoded" class="pcoded">
        <?php include_once '../logincheck.php'; ?>
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            
            <!-- Navbar -->
            <?php include_once '../layout/navbar.php'; ?>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    
                    <!-- Sidebar Menu -->
                    <?php include_once '../layout/menu.php'; ?>

                    <!-- Dashboard Content -->
                    <?php
                    include_once '../connection.php';
                    error_reporting(0);

                    $page = $_GET['page'] ?? 'dashboard';
                    $pages = [
                        'dashboard' => ['title' => 'Dashboard', 'path' => 'dashboard.php'],
                        'petugas' => ['title' => 'Data Petugas', 'path' => '../petugas/show.php'],
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
                        'barangAdd' => ['title' => 'Tambah Data Barang', 'path' => '../barang/add.php'],
                        'barangEdit' => ['title' => 'Edit Data Barang', 'path' => '../barang/edit.php'],
                        'barangDelete' => ['title' => 'Hapus Data Barang', 'path' => '../barang/delete.php'],
                        'pinjam' => ['title' => 'Data Pinjam', 'path' => '../peminjaman/show.php'],
                        'proses' => ['title' => 'Proses Peminjaman', 'path' => '../peminjaman/completeProcess.php'],
                        'pinjamAdd' => ['title' => 'Tambah Data Pinjam', 'path' => '../peminjaman/add.php'],
                        'masuk' => ['title' => 'Data Masuk', 'path' => '../masuk/show.php'],
                        'masukAdd' => ['title' => 'Tambah Data Masuk', 'path' => '../masuk/add.php'],
                        'masukEdit' => ['title' => 'Tambah Data Masuk', 'path' => '../masuk/edit.php'],
                        'keluar' => ['title' => 'Data Keluar', 'path' => '../keluar/show.php'],
                        'keluarAdd' => ['title' => 'Tambah Data Keluar', 'path' => '../keluar/add.php'],
                        'keluarEdit' => ['title' => 'Tambah Data Keluar', 'path' => '../keluar/edit.php'],
                        'kegiatan' => ['title' => 'Data Kegiatan', 'path' => '../kegiatan/show.php'],
                        'kegiatanAdd' => ['title' => 'Tambah Data Kegiatan', 'path' => '../kegiatan/add.php'],
                        'kegiatanEdit' => ['title' => 'Tambah Data Kegiatan', 'path' => '../kegiatan/edit.php']
                    ];

                    $title = $pages[$page]['title'] ?? 'Dashboard';
                    include $pages[$page]['path'] ?? 'dashboard.php';
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../layout/js.php'; ?>
</body>

</html>
