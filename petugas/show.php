<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10"><?php echo htmlspecialchars($title); ?></h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!"><?php echo htmlspecialchars($title); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->

    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-body start -->
                <div class="page-body">
                    <!-- Hover table card start -->
                    <div class="card">
                        <div class="card-header">
                            <h5><?php echo htmlspecialchars($title); ?></h5>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="?page=petugasAdd" class="btn waves-effect waves-light btn-primary btn-outline-primary">
                                        <i class="icofont icofont-user-alt-3"></i>Tambah</a>
                                </div>
                            </div>
                        </div>

                        <div class="card-block table-border-style">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Nama Petugas</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        <?php
                                        // Query untuk mengambil semua data petugas
                                        $result = mysqli_query($con, "SELECT * FROM petugas");

                                        // Tampilkan hasil query dalam tabel
                                        while ($data = mysqli_fetch_array($result)) {
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($data['nama']); ?></td>
                                                <td>
                                                    <a data-toggle="tooltip" data-placement="top" title="Edit Data" class="btn waves-effect waves-dark btn-success btn-outline-success btn-sm" href="?page=petugasEdit&id=<?php echo $data['id']; ?>">
                                                        <i class="fa fa-pencil"></i></a>
                                                    <a data-toggle="tooltip" data-placement="top" title="Hapus Data" 
                                                    class="btn waves-effect waves-dark btn-danger btn-outline-danger btn-sm"
                                                    href="#" onclick="confirmDelete(<?php echo $data['id']; ?>)">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
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
                    <!-- Hover table card end -->
                </div>
                <!-- Page-body end -->
            </div>
        </div>
        <!-- Main-body end -->

        <div id="styleSelector"></div>
    </div>
</div>

<!-- JavaScript untuk filter pencarian -->
<script type="text/javascript">
    $(document).ready(function () {
  const searchInput = $('#search');
  if (searchInput.length === 0) {
    console.error("Element with ID 'search' not found!");
    return; // Jika elemen tidak ditemukan, hentikan eksekusi
  }

  const tableBody = $('#table-body');
  const rows = tableBody.find('tr');

  searchInput.on('keyup', function () {
    const filter = searchInput.val().toLowerCase();

    rows.each(function () {
      const td = $(this).find('td').first(); // Ambil kolom pertama
      const txtValue = td.text() || td.html();
      $(this).toggle(txtValue.toLowerCase().indexOf(filter) > -1);
    });
  });
});
    // Konfirmasi penghapusan data
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
                window.location.href = '?page=petugasDelete&id=' + id;
            }
        });
    }
</script>