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

$level = $_SESSION['level'];
$divisi = $_SESSION['divisi'];
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date('m');
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : date('Y');
$query = "SELECT pinjam.waktu, pinjam.nama, pinjam.tempat, pinjam.keperluan, pinjam.devisi, pinjam.devisi_tujuan, pinjam.status, 
             GROUP_CONCAT(CONCAT(pinjam.jumlah, ' ', inventaris.nama) SEPARATOR ', ') AS barang_pinjam
          FROM pinjam 
          LEFT JOIN inventaris ON pinjam.idInventaris = inventaris.id";
if ($level !== 'administrator') {
    $query .= " WHERE (pinjam.devisi = '$divisi' OR pinjam.devisi_tujuan = '$divisi')";
}

$query .= ($level === 'administrator' ? " WHERE" : " AND") . " MONTH(pinjam.waktu) = '$bulan' AND YEAR(pinjam.waktu) = '$tahun'";
$query .= " GROUP BY pinjam.waktu, pinjam.devisi, pinjam.devisi_tujuan, pinjam.nama, pinjam.tempat, pinjam.keperluan, pinjam.status
            ORDER BY pinjam.waktu DESC";
$result = mysqli_query($con, $query);

if (isset($_POST['selesai_waktu'])) {
    $waktuPinjam = mysqli_real_escape_string($con, $_POST['selesai_waktu']);
    $divisiTujuan = mysqli_real_escape_string($con, $_POST['divisi_tujuan']);
    if ($divisi === $divisiTujuan) {
        $queryPinjam = mysqli_query($con, "SELECT idInventaris, jumlah FROM pinjam 
                                           WHERE waktu = '$waktuPinjam' AND devisi_tujuan = '$divisi' AND status = 'dipinjam'");

        while ($dataPinjam = mysqli_fetch_assoc($queryPinjam)) {
            $idInventaris = $dataPinjam['idInventaris'];
            $jumlahPinjam = $dataPinjam['jumlah'];

            mysqli_query($con, "UPDATE pinjam SET status = 'selesai' WHERE waktu = '$waktuPinjam' AND devisi_tujuan = '$divisi'");
            mysqli_query($con, "UPDATE inventaris SET stok = stok + $jumlahPinjam WHERE id = '$idInventaris'");
        }

        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Peminjaman selesai, stok diperbarui!'
            }).then(() => {
                window.location.href = '?page=pinjam';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Anda tidak berwenang menyelesaikan peminjaman ini!'
            });
        </script>";
    }
}
?>
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5>Data Pinjam</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#">Data Pinjam</a></li>
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
                            <h5>Data Pinjam</h5>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-wrap gap-2" style="gap: 10px;">
                                    <a href="?page=pinjamAdd" class="btn btn-outline-primary">
                                        <i class="fa fa-plus"></i> Tambah
                                    </a>
                                    <a href="../peminjaman/print.php?export_excel=true&bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>" class="btn btn-info btn-outline-info">
                                        <i class="fa fa-download"></i> Export ke Excel
                                    </a>
                                    <a href="../peminjaman/print.php" target="_blank" class="btn btn-secondary btn-outline-secondary">
                                        <i class="fa fa-print"></i> Cetak
                                    </a>
                                </div>

                                <form method="POST" action="">
                                    <div class="d-flex align-items-center">
                                        <label for="bulan" class="mr-2">Bulan:</label>
                                        <select id="bulan" name="bulan" class="form-control" style="width: 150px;" onchange="this.form.submit()">
                                            <?php
                                            $months = [
                                                "01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April",
                                                "05" => "Mei", "06" => "Juni", "07" => "Juli", "08" => "Agustus",
                                                "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember"
                                            ];
                                            foreach ($months as $key => $month) {
                                                $selected = ($key == $bulan) ? "selected" : "";
                                                echo "<option value=\"$key\" $selected>$month</option>";
                                            }
                                            ?>
                                        </select>
                                        <label for="tahun" class="mr-2 ml-2">Tahun:</label>
                                        <select id="tahun" name="tahun" class="form-control" style="width: 150px;" onchange="this.form.submit()">
                                            <?php
                                            $currentYear = date('Y');
                                            for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
                                                $selected = ($i == $tahun) ? "selected" : "";
                                                echo "<option value=\"$i\" $selected>$i</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-block table-border-style">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center">No</th>
                                            <th class="text-center">Waktu</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Tempat</th>
                                            <th class="text-center">Keperluan</th>
                                            <th class="text-center">Barang yang Dipinjam</th>
                                            <th class="text-center">Divisi Penginput</th>
                                            <th class="text-center">Divisi Tujuan</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        while ($data = mysqli_fetch_assoc($result)) { ?>
                                            <tr>
                                                <td class="text-center"><?php echo $no++; ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['waktu'] ?? '-'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['nama'] ?? '-'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['tempat'] ?? '-'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['keperluan'] ?? '-'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['barang_pinjam'] ?? 'Tidak ada barang'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['devisi'] ?? '-'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['devisi_tujuan'] ?? '-'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['status'] ?? 'Tidak diketahui'); ?></td>
                                                <td class="text-center">
                                                    <?php if ($data['status'] === 'dipinjam' && $data['devisi_tujuan'] === $divisi) { ?>
                                                        <form method="POST">
                                                            <input type="hidden" name="selesai_waktu" value="<?php echo htmlspecialchars($data['waktu'] ?? ''); ?>">
                                                            <input type="hidden" name="divisi_tujuan" value="<?php echo htmlspecialchars($data['devisi_tujuan']); ?>">
                                                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                                                <i class="fa fa-check"></i> Selesai
                                                            </button>
                                                        </form>
                                                    <?php } ?>
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
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>