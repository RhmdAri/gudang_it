<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10"><?php echo $title ?></h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!"><?php echo $title ?></a></li>
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
                            <h5><?php echo $title ?></h5>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="?page=keluarAdd" class="btn btn-primary btn-outline-primary">
                                        <i class="icofont icofont-user-alt-3"></i>Tambah</a>
                                    <a href="../keluar/print.php?bulan=<?php echo isset($_POST['bulan']) ? $_POST['bulan'] : date('m'); ?>&tahun=<?php echo isset($_POST['tahun']) ? $_POST['tahun'] : date('Y'); ?>" target="_blank" class="btn btn-success btn-outline-success">
                                        <i class="fa fa-print"></i>Cetak</a>
                                    <a href="../keluar/print.php?export_excel=true&bulan=<?php echo isset($_POST['bulan']) ? $_POST['bulan'] : date('m'); ?>&tahun=<?php echo isset($_POST['tahun']) ? $_POST['tahun'] : date('Y'); ?>" class="btn btn-info btn-outline-info">
                                        <i class="fa fa-download"></i>Export ke Excel</a>
                                </div>
                                <div>
                                    <!-- Form filter bulan dan tahun -->
                                    <form method="POST" class="form-inline" id="filterForm">
                                        <label for="bulan" class="mr-2">Bulan:</label>
                                        <select name="bulan" class="form-control mr-2" id="bulan" onchange="loadData()">
                                            <?php
                                            $months = [
                                                "01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April",
                                                "05" => "Mei", "06" => "Juni", "07" => "Juli", "08" => "Agustus",
                                                "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember"
                                            ];
                                            foreach ($months as $key => $month) {
                                                $selected = ($key == (isset($_POST['bulan']) ? $_POST['bulan'] : date('m'))) ? 'selected' : '';
                                                echo "<option value=\"$key\" $selected>$month</option>";
                                            }
                                            ?>
                                        </select>
                                        <label for="tahun" class="mr-2">Tahun:</label>
                                        <select name="tahun" class="form-control" id="tahun" onchange="loadData()">
                                            <?php
                                            $currentYear = date('Y');
                                            for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
                                                $selected = ($i == (isset($_POST['tahun']) ? $_POST['tahun'] : $currentYear)) ? 'selected' : '';
                                                echo "<option value=\"$i\" $selected>$i</option>";
                                            }
                                            ?>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel untuk menampilkan data -->
                        <div class="card-block table-border-style">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Waktu</th>
                                            <th>Nama Petugas</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-table-body">
                                    <!-- Data akan dimuat dengan AJAX -->
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

<script>
// Fungsi untuk memuat data berdasarkan bulan dan tahun
function loadData() {
    var bulan = document.getElementById('bulan').value;
    var tahun = document.getElementById('tahun').value;

    // Menggunakan fetch untuk memuat data tanpa refresh halaman
    fetch('../keluar/fetch_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'bulan=' + bulan + '&tahun=' + tahun
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('data-table-body').innerHTML = data;
    })
    .catch(error => console.error('Error:', error));
}

// Panggil loadData untuk memuat data awal
window.onload = loadData;
</script>
