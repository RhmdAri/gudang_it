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
                        <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fa fa-home"></i></a></li>
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
                                <div>
                                    <a href="?page=masukAdd" class="btn btn-primary btn-outline-primary">
                                        <i class="icofont icofont-user-alt-3"></i>Tambah</a>
                                    <a href="#" id="printButton" target="_blank" class="btn btn-success btn-outline-success">
                                        <i class="fa fa-print"></i>Cetak</a>
                                    <a href="#" id="exportButton" class="btn btn-info btn-outline-info">
                                        <i class="fa fa-download"></i>Export ke Excel</a>
                                </div>
                                <div>
                                    <!-- Form filter bulan dan tahun -->
                                    <form id="filterForm" class="form-inline">
                                        <label for="bulan" class="mr-2">Bulan:</label>
                                        <select id="bulan" name="bulan" class="form-control mr-2">
                                            <?php
                                            $months = [
                                                "01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April",
                                                "05" => "Mei", "06" => "Juni", "07" => "Juli", "08" => "Agustus",
                                                "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember"
                                            ];
                                            $currentMonth = date('m');
                                            foreach ($months as $key => $month) {
                                                echo "<option value=\"$key\" " . ($key == $currentMonth ? 'selected' : '') . ">$month</option>";
                                            }
                                            ?>
                                        </select>
                                        <label for="tahun" class="mr-2">Tahun:</label>
                                        <select id="tahun" name="tahun" class="form-control">
                                            <?php
                                            $currentYear = date('Y');
                                            for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
                                                echo "<option value=\"$i\" " . ($i == $currentYear ? 'selected' : '') . ">$i</option>";
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
                                    <tbody id="table-body">
                                        <!-- Data akan dimuat melalui AJAX -->
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
document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filterForm');
    const bulanSelect = document.getElementById('bulan');
    const tahunSelect = document.getElementById('tahun');
    const tableBody = document.getElementById('table-body');
    const printButton = document.getElementById('printButton');
    const exportButton = document.getElementById('exportButton');

    function loadData() {
        const bulan = bulanSelect.value;
        const tahun = tahunSelect.value;

        // Update URL untuk Cetak dan Export
        printButton.href = `../masuk/print.php?bulan=${bulan}&tahun=${tahun}`;
        exportButton.href = `../masuk/print.php?export_excel=true&bulan=${bulan}&tahun=${tahun}`;

        // Mengambil data menggunakan AJAX
        fetch('../masuk/fetch_data.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `bulan=${bulan}&tahun=${tahun}`
        })
        .then(response => response.text())
        .then(data => {
            tableBody.innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
    }

    // Event listener untuk filter
    bulanSelect.addEventListener('change', loadData);
    tahunSelect.addEventListener('change', loadData);

    // Muat data awal
    loadData();
});
</script>
