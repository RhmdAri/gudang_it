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
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : '';
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
$query = "SELECT * FROM kegiatan";
if ($level !== 'administrator') {
    $query .= " WHERE devisi = '$divisi'";
}
if (!empty($bulan) && !empty($tahun)) {
    $query .= ($level !== 'administrator' ? " AND" : " WHERE") . " MONTH(waktu) = '$bulan' AND YEAR(waktu) = '$tahun'";
}
$query .= " ORDER BY waktu DESC";

$result = mysqli_query($con, $query);
?>
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5>Data Kegiatan</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#">Data Kegiatan</a></li>
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
                                    <a href="?page=kegiatanAdd" class="btn waves-effect waves-light btn-primary btn-outline-primary">
                                        <i class="fa fa-plus"></i>Tambah</a>
                                        <a href="../kegiatan/print.php?export_excel=true&bulan=<?php echo isset($_POST['bulan']) ? $_POST['bulan'] : date('m'); ?>&tahun=<?php echo isset($_POST['tahun']) ? $_POST['tahun'] : date('Y'); ?>" class="btn waves-effect waves-light btn-info btn-outline-info">
                                            <i class="fa fa-download"></i> Export ke Excel
                                        </a>
                                        <a href="../kegiatan/print.php?bulan=<?php echo isset($_POST['bulan']) ? $_POST['bulan'] : date('m'); ?>&tahun=<?php echo isset($_POST['tahun']) ? $_POST['tahun'] : date('Y'); ?>" target="_blank" class="btn waves-effect waves-light btn-secondary btn-outline-secondary">
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
                                            <th class="text-center">Divisi</th>
                                            <th class="text-center">Waktu</th>
                                            <th class="text-center">Petugas</th>
                                            <th class="text-center">Tempat</th>
                                            <th class="text-center">Kegiatan</th>
                                            <th class="text-center">Foto</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        while ($data = mysqli_fetch_assoc($result)) { ?>
                                            <tr>
                                                <td class="text-center"><?php echo $no++; ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['devisi']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['waktu']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['petugas']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['tempat']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($data['kegiatan']); ?></td>
                                                <td class="text-center">
                                                    <?php if (!empty($data['foto'])) { ?>
                                                        <img src="../uploads/<?php echo htmlspecialchars($data['foto']); ?>" 
                                                            alt="Foto Kegiatan" 
                                                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px; cursor: pointer;" 
                                                            onclick="viewPhoto('../uploads/<?php echo htmlspecialchars($data['foto']); ?>')">
                                                    <?php } else { ?>
                                                        Tidak ada foto
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center">
                                                    <a class="btn waves-effect waves-dark btn-success btn-outline-success btn-sm" href="?page=kegiatanEdit&id=<?php echo $data['id']; ?>">
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
    </div>
</div>
<div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img id="photoPreview" src="" alt="Foto Kegiatan" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function viewPhoto(photoUrl) {
        document.getElementById('photoPreview').src = photoUrl;
        $('#photoModal').modal('show');
    }
</script>
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
                window.location.href = '?page=kegiatanDelete&id=' + id;
            }
        });
    }
</script>