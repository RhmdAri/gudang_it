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
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
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
                            <h5>Kegiatan</h5>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="?page=kegiatanAdd" class="btn waves-effect waves-light btn-primary btn-outline-primary">
                                        <i class="icofont icofont-user-alt-3"></i>Tambah</a>
                                    <a href="../kegiatan/print.php?export_excel=true&bulan=<?php echo isset($_POST['bulan']) ? $_POST['bulan'] : date('m'); ?>&tahun=<?php echo isset($_POST['tahun']) ? $_POST['tahun'] : date('Y'); ?>" class="btn waves-effect waves-light btn-info btn-outline-info">
                                        <i class="fa fa-download"></i> Export ke Excel
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
                                <table id="datatable" class="table table-hover table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Waktu</th>
                                            <th>Nama Petugas</th>
                                            <th>Tempat</th>
                                            <th>Kegiatan</th>
                                            <th>Foto</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        <?php
                                        $bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date('m');
                                        $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : date('Y');

                                        $query = "SELECT * FROM kegiatan WHERE MONTH(waktu) = '$bulan' AND YEAR(waktu) = '$tahun'";
                                        $result = mysqli_query($con, $query);

                                        while ($data = mysqli_fetch_array($result)) {
                                        ?>
                                            <tr>
                                                <td><?php echo $data['waktu']; ?></td>
                                                <td><?php echo htmlspecialchars($data['petugas']); ?></td>
                                                <td><?php echo htmlspecialchars($data['tempat']); ?></td>
                                                <td><?php echo htmlspecialchars($data['kegiatan']); ?></td>
                                                <td>
                                                    <?php if (!empty($data['foto'])) { ?>
                                                        <img src="../uploads/<?php echo $data['foto']; ?>" alt="Foto" style="width: 50px; height: 50px; cursor: pointer;" onclick="viewImage('../uploads/<?php echo $data['foto']; ?>')">
                                                    <?php } else { ?>
                                                        <span>Tidak Ada Foto</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
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
        <div id="styleSelector"></div>
    </div>
</div>
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Pratinjau Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Foto" class="img-fluid" style="max-height: 400px;">
            </div>
        </div>
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
                window.location.href = '?page=kegiatanDelete&id=' + id;
            }
        });
    }

    function viewImage(src) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = src;

        $('#imageModal').modal('show');
    }
</script>