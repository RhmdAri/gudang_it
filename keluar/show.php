<?php
session_start();
include '../connection.php';
if (!isset($_SESSION['divisi'])) {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Akses Ditolak',
            text: 'Silakan login terlebih dahulu!'
        }).then(() => {
            window.location.href = 'login.php';
        });
    </script>";
    exit();
}

$title = "Manajemen Barang Keluar";
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
                                <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                                    <a href="?page=keluarAdd" class="btn waves-effect waves-light btn-primary btn-outline-primary">
                                        <i class="fa fa-plus"></i> Tambah</a>
                                    <a href="../keluar/print.php?export_excel=true&bulan=<?php echo isset($_POST['bulan']) ? $_POST['bulan'] : date('m'); ?>&tahun=<?php echo isset($_POST['tahun']) ? $_POST['tahun'] : date('Y'); ?>" class="btn waves-effect waves-light btn-info btn-outline-info">
                                        <i class="fa fa-download"></i> Export ke Excel
                                    </a>
                                    <a href="../keluar/print.php?bulan=<?php echo isset($_POST['bulan']) ? $_POST['bulan'] : date('m'); ?>&tahun=<?php echo isset($_POST['tahun']) ? $_POST['tahun'] : date('Y'); ?>" target="_blank" class="btn waves-effect waves-light btn-secondary btn-outline-secondary">
                                        <i class="fa fa-print"></i> Cetak
                                    </a>
                                </div>
                                <div>
                                    <form method="POST" action="">
                                        <div class="d-flex align-items-center">
                                            <label for="bulan" class="mr-2">Bulan:</label>
                                            <select id="bulan" name="bulan" class="form-control" style="width: 150px;" onchange="this.form.submit()">
                                                <?php
                                                $months = [
                                                    "01" => "Januari",
                                                    "02" => "Februari",
                                                    "03" => "Maret",
                                                    "04" => "April",
                                                    "05" => "Mei",
                                                    "06" => "Juni",
                                                    "07" => "Juli",
                                                    "08" => "Agustus",
                                                    "09" => "September",
                                                    "10" => "Oktober",
                                                    "11" => "November",
                                                    "12" => "Desember"
                                                ];
                                                $selectedBulan = isset($_POST['bulan']) ? $_POST['bulan'] : date('m');
                                                foreach ($months as $key => $month) {
                                                    $selected = ($key == $selectedBulan) ? "selected" : "";
                                                    echo "<option value=\"$key\" $selected>$month</option>";
                                                }
                                                ?>
                                            </select>
                                            <label for="tahun" class="mr-2 ml-2">Tahun:</label>
                                            <select id="tahun" name="tahun" class="form-control" style="width: 150px;" onchange="this.form.submit()">
                                                <?php
                                                $currentYear = date('Y');
                                                $selectedTahun = isset($_POST['tahun']) ? $_POST['tahun'] : $currentYear;
                                                for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
                                                    $selected = ($i == $selectedTahun) ? "selected" : "";
                                                    echo "<option value=\"$i\" $selected>$i</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-block table-border-style">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center">No</th>
                                            <th class="text-center">Waktu</th>
                                            <th class="text-center">Nama Barang</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-center">Petugas</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody id="table-body">
                                        <?php
                                        $bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date('m');
                                        $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : date('Y');
                                        $divisi = $_SESSION['divisi'];
                                        $query = "
                                            SELECT keluar.*, barang.nama AS namaBarang, petugas.nama AS namaPetugas
                                            FROM keluar
                                            LEFT JOIN barang ON keluar.idBarang = barang.id
                                            LEFT JOIN petugas ON keluar.idPetugas = petugas.id
                                            WHERE keluar.devisi = '$divisi' 
                                            AND MONTH(keluar.waktu) = '$bulan' 
                                            AND YEAR(keluar.waktu) = '$tahun'
                                        ";

                                        $result = mysqli_query($con, $query);
                                        $no = 1;

                                        while ($data = mysqli_fetch_array($result)) { ?>
                                            <tr>
                                                <td class="text-center"><?php echo $no++; ?></td>
                                                <td class="text-center"><?php echo $data['waktu']; ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['namaBarang']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['jumlah']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['namaPetugas']); ?></td>
                                                <td class="text-center">
                                                    <a class="btn waves-effect waves-dark btn-success btn-outline-success btn-sm" href="?page=keluarEdit&id=<?php echo $data['id']; ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a class="btn waves-effect waves-dark btn-danger btn-outline-danger btn-sm" href="#" onclick="confirmDelete(<?php echo $data['id']; ?>)">
                                                        <i class="fa fa-trash"></i>
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
                window.location.href = '?page=keluarDelete&id=' + id;
            }
        });
    }
</script>
