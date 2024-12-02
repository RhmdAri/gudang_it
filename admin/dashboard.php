<?php
// Menghitung total kategori
$query = mysqli_query($con, "SELECT * FROM kategori");
$totalKategori = mysqli_num_rows($query);

// Menghitung total inventaris
$query = mysqli_query($con, "SELECT * FROM inventaris");
$totalInventaris = mysqli_num_rows($query);

// Menghitung total barang
$query = mysqli_query($con, "SELECT * FROM barang");
$totalBarang = mysqli_num_rows($query);

// Menghitung total petugas
$query = mysqli_query($con, "SELECT * FROM petugas");
$totalPetugas = mysqli_num_rows($query);

// Menghitung total barang masuk per bulan
$query = mysqli_query($con, "SELECT COUNT(*) AS jumlah_masuk FROM masuk WHERE MONTH(waktu) = MONTH(CURRENT_DATE()) AND YEAR(waktu) = YEAR(CURRENT_DATE())");
$row = mysqli_fetch_assoc($query);
$totalMasuk = $row['jumlah_masuk'];

// Menghitung total barang keluar per bulan
$query = mysqli_query($con, "SELECT COUNT(*) AS jumlah_keluar FROM keluar WHERE MONTH(waktu) = MONTH(CURRENT_DATE()) AND YEAR(waktu) = YEAR(CURRENT_DATE())");
$row = mysqli_fetch_assoc($query);
$totalKeluar = $row['jumlah_keluar'];

// Menghitung jumlah peminjaman per bulan
$query = mysqli_query($con, "SELECT COUNT(*) AS jumlah_peminjaman FROM pinjam WHERE MONTH(waktu) = MONTH(CURRENT_DATE()) AND YEAR(waktu) = YEAR(CURRENT_DATE())");
$row = mysqli_fetch_assoc($query);
$totalPinjamBulanIni = $row['jumlah_peminjaman'];

// Menghitung kegiatan, misalnya jumlah barang masuk dan keluar (atau sesuai kebutuhan)
$query = mysqli_query($con, "SELECT COUNT(*) AS jumlah_kegiatan FROM (SELECT * FROM masuk UNION ALL SELECT * FROM keluar) AS kegiatan WHERE MONTH(waktu) = MONTH(CURRENT_DATE()) AND YEAR(waktu) = YEAR(CURRENT_DATE())");
$row = mysqli_fetch_assoc($query);
$totalKegiatan = $row['jumlah_kegiatan'];

// Menghitung total user
$query = mysqli_query($con, "SELECT * FROM user");
$totalUser = mysqli_num_rows($query);
?>

<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10"><?= $title; ?></h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Dashboard</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <?php
                        $dataCards = [
                            ['label' => 'Data Kategori', 'count' => $totalKategori, 'icon' => 'fa-bars', 'bg' => 'purple'],
                            ['label' => 'Data Barang', 'count' => $totalBarang, 'icon' => 'fa-cubes', 'bg' => 'purple'],
                            ['label' => 'Data Inventaris', 'count' => $totalInventaris, 'icon' => 'fa-file-text-o', 'bg' => 'green'],
                            ['label' => 'Peminjaman Per-Bulan', 'count' => $totalPinjamBulanIni, 'icon' => 'fa-handshake-o', 'bg' => 'green'],
                            ['label' => 'Barang Masuk Per-Bulan', 'count' => $totalMasuk, 'icon' => 'fa-sign-in', 'bg' => 'red'],
                            ['label' => 'Barang Keluar Per-Bulan', 'count' => $totalKeluar, 'icon' => 'fa-sign-out', 'bg' => 'red'],
                            ['label' => 'Data Petugas', 'count' => $totalPetugas, 'icon' => 'fa-users', 'bg' => 'blue'],
                            ['label' => 'Data User', 'count' => $totalUser, 'icon' => 'fa-address-book-o', 'bg' => 'blue'],
                            ['label' => 'Kegiatan Per-Bulan', 'count' => $totalKegiatan, 'icon' => 'fa-refresh', 'bg' => 'yellow'],
                        ];

                        foreach ($dataCards as $card) : ?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h4 class="text-c-<?= $card['bg']; ?>"><?= $card['count']; ?></h4>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class="fa <?= $card['icon']; ?> f-28"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-c-<?= $card['bg']; ?>">
                                        <p class="text-white m-b-0"><?= $card['label']; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
