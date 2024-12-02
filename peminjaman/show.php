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
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"> <i class="fa fa-home"></i> </a>
                        </li>
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
                            <h5>Peminjaman</h5>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="?page=pinjamAdd" class="btn waves-effect waves-light btn-primary btn-outline-primary">
                                        <i class="icofont icofont-user-alt-3"></i>Tambah</a>
                                        <a href="../peminjaman/print.php?bulan=<?php echo date('m'); ?>&tahun=<?php echo date('Y'); ?>" 
                                        target="_blank" 
                                        class="btn waves-effect waves-light btn-success btn-outline-success">
                                            <i class="fa fa-print"></i> Cetak
                                        </a>
                                        <a href="../peminjaman/print.php?export_excel=true&bulan=<?php echo date('m'); ?>&tahun=<?php echo date('Y'); ?>" 
                                        class="btn waves-effect waves-light btn-info btn-outline-info">
                                            <i class="fa fa-download"></i> Export ke Excel
                                        </a>
                                </div>
                                <div>
                                <div class="d-flex align-items-center">
                                    <label for="bulan" class="mr-2">Bulan:</label>
                                    <select id="bulan" class="form-control" style="width: 150px;">
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
                                        foreach ($months as $key => $month) {
                                            echo "<option value=\"$key\"" . (($key == date('m')) ? " selected" : "") . ">$month</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="tahun" class="mr-2 ml-2">Tahun:</label>
                                    <select id="tahun" class="form-control" style="width: 150px;">
                                        <?php
                                        $currentYear = date('Y');
                                        for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
                                            echo "<option value=\"$i\"" . (($i == $currentYear) ? " selected" : "") . ">$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-block">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover table-striped">
                                <thead>
                                    <tr class="text-center">
                                        <th>Waktu</th>
                                        <th>Nama Peminjam</th>
                                        <th>Petugas</th>
                                        <th>Inventaris</th>
                                        <th>Tempat</th>
                                        <th>Keperluan</th>
                                        <th>Aksi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                <?php
                                $currentMonth = date('m');
                                $currentYear = date('Y');

                                $query = "
                                    SELECT 
                                        pinjam.id, pinjam.waktu, pinjam.nama, pinjam.tempat, pinjam.keperluan, pinjam.status,
                                        GROUP_CONCAT(CONCAT(inventaris.nama, ' (', pinjam.jumlah, ')') SEPARATOR ', ') AS inventaris,
                                        petugas.nama AS namaPetugas
                                    FROM pinjam
                                    INNER JOIN petugas ON pinjam.idPetugas = petugas.id
                                    INNER JOIN inventaris ON inventaris.id = pinjam.idInventaris
                                    WHERE MONTH(pinjam.waktu) = '$currentMonth' AND YEAR(pinjam.waktu) = '$currentYear'
                                    GROUP BY pinjam.nama, pinjam.tempat, pinjam.keperluan, pinjam.status, petugas.nama
                                    ORDER BY MAX(pinjam.waktu) DESC
                                ";

                                $result = mysqli_query($con, $query);

                                while ($data = mysqli_fetch_array($result)) {
                                    $waktuFormatted = date('d-m-Y H:i:s', strtotime($data['waktu']));
                                    echo "
                                        <tr>
                                            <td>{$waktuFormatted}</td>
                                            <td>" . htmlspecialchars($data['nama']) . "</td>
                                            <td>" . htmlspecialchars($data['namaPetugas']) . "</td>
                                            <td>" . htmlspecialchars($data['inventaris']) . "</td>
                                            <td>" . htmlspecialchars($data['tempat']) . "</td>
                                            <td>" . htmlspecialchars($data['keperluan']) . "</td>
                                            <td>
                                                <a class='btn waves-effect waves-dark btn-info btn-outline-info btn-sm' 
                                                href='javascript:void(0);' 
                                                onclick=\"confirmFinish('" . htmlspecialchars($data['nama']) . "')\">Selesai</a>
                                            </td>
                                            <td>" . htmlspecialchars($data['status']) . "</td>
                                        </tr>
                                    ";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="styleSelector"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
   function confirmFinish(nama) {
    Swal.fire({
        title: 'Konfirmasi Penyelesaian',
        text: 'Apakah Anda yakin ingin menyelesaikan semua inventaris yang dipinjam oleh ' + nama + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Selesaikan!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "?page=proses&nama=" + encodeURIComponent(nama);
        }
    });
}

document.getElementById('bulan').addEventListener('change', updateTable);
document.getElementById('tahun').addEventListener('change', updateTable);

function updateTable() {
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;

    fetch(`../peminjaman/fetch_data.php?bulan=${bulan}&tahun=${tahun}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            document.getElementById('table-body').innerHTML = data;

            // Update links for Export to Excel and Print
            const exportExcelBtn = document.querySelector('.btn-warning');
            const cetakBtn = document.querySelector('.btn-success');

            exportExcelBtn.href = `../peminjaman/print.php?export_excel=true&bulan=${bulan}&tahun=${tahun}`;
            cetakBtn.href = `../peminjaman/print.php?bulan=${bulan}&tahun=${tahun}`;
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            document.getElementById('table-body').innerHTML = '<tr><td colspan="8">Error loading data.</td></tr>';
        });
}
</script>
