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
                            <h5>Inventaris</h5>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="?page=inventarisAdd" class="btn waves-effect waves-light btn-primary btn-outline-primary">
                                        <i class="icofont icofont-user-alt-3"></i>Tambah</a>
                                    <a href="../inventaris/print.php" target="_blank" class="btn waves-effect waves-light btn-success btn-outline-success">
                                        <i class="fa fa-print"></i>Cetak</a>
                                    <a href="../inventaris/print.php?export_excel=true" class="btn waves-effect waves-light btn-info btn-outline-info">
                                        <i class="fa fa-download"></i> Ekspor Excel</a>
                                </div>
                            </div>
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="fa fa-wrench open-card-option"></i></li>
                                    <li><i class="fa fa-window-maximize full-card"></i></li>
                                    <li><i class="fa fa-minus minimize-card"></i></li>
                                    <li><i class="fa fa-refresh reload-card"></i></li>
                                    <li><i class="fa fa-trash close-card"></i></li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-block table-border-style">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Nama Barang</th>
                                            <th>Stok</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        <?php
                                        $result = mysqli_query($con, "SELECT * FROM inventaris");

                                        while ($data = mysqli_fetch_array($result)) {
                                        ?>
                                            <tr>
                                                <td><?php echo $data['nama']; ?></td>
                                                <td><?php echo $data['stok']; ?></td>
                                                <td>
                                                    <a data-toggle="tooltip" data-placement="top" title="Edit Data" class="btn waves-effect waves-dark btn-success btn-outline-success btn-sm" href="?page=inventarisEdit&id=<?php echo $data['id']; ?>">
                                                        <i class="fa fa-pencil"></i></a>
                                                    <a data-toggle="tooltip" data-placement="top" title="Hapus Data" class="btn waves-effect waves-dark btn-danger btn-outline-danger btn-sm" href="#" onclick="confirmDelete(<?php echo $data['id']; ?>)">
                                                        <i class="fa fa-trash"></i>
                                                    </a>

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
                                                                    window.location.href = '?page=inventarisDelete&id=' + id;
                                                                }
                                                            });
                                                        }
                                                    </script>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
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
    const searchInput = document.getElementById('search');
    const tableBody = document.getElementById('table-body');
    const rows = tableBody.getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toLowerCase();

        for (let i = 0; i < rows.length; i++) {
            const td = rows[i].getElementsByTagName('td')[0];
            if (td) {
                const txtValue = td.textContent || td.innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    });
</script>
