<!-- Required JavaScript Libraries -->
<script type="text/javascript" src="../assets/js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="../assets/js/popper.js/popper.min.js"></script>
<script type="text/javascript" src="../assets/js/bootstrap/js/bootstrap.min.js"></script>

<!-- Optional Libraries (Waves, Slimscroll, etc.) -->
<script type="text/javascript" src="../assets/pages/waves/js/waves.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery-slimscroll/jquery.slimscroll.js"></script>
<script type="text/javascript" src="../assets/js/SmoothScroll.js"></script>
<script type="text/javascript" src="../assets/js/jquery.mCustomScrollbar.concat.min.js"></script>

<!-- Modernizr, Chart.js, and Amcharts -->
<script type="text/javascript" src="../assets/js/modernizr/modernizr.js"></script>
<script type="text/javascript" src="../assets/js/chart.js/Chart.js"></script>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script type="text/javascript" src="../assets/pages/widget/amchart/gauge.js"></script>
<script type="text/javascript" src="../assets/pages/widget/amchart/serial.js"></script>
<script type="text/javascript" src="../assets/pages/widget/amchart/light.js"></script>
<script type="text/javascript" src="../assets/pages/widget/amchart/pie.min.js"></script>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>

<!-- Menu and Layout Scripts -->
<script type="text/javascript" src="../assets/js/pcoded.min.js"></script>
<script type="text/javascript" src="../assets/js/vertical-layout.min.js"></script>

<!-- Select2 Library -->
<script src="../vendor/select2/js/select2.full.min.js"></script>

<!-- Custom Page-Specific JavaScript -->
<script type="text/javascript" src="../js/quixnav-init.js"></script>
<script type="text/javascript" src="../js/custom.min.js"></script>
<script type="text/javascript" src="../js/plugins-init/select2-init.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap4.min.js"></script>

<!-- JavaScript for Search Filter (Using jQuery) -->
<script type="text/javascript">
  $(document).ready(function () {
    const $searchInput = $('#search');
    const $tableBody = $('#table-body');
    const $rows = $tableBody.find('tr');

    // Filter table rows based on the input
    $searchInput.on('keyup', function () {
        const filter = $searchInput.val().toLowerCase();
        $rows.each(function () {
            const $td = $(this).find('td').first(); // Get the first column (Nama Petugas)
            const txtValue = $td.text() || $td.html();
            $(this).toggle(txtValue.toLowerCase().indexOf(filter) > -1);
        });
    });
  });
</script>

<!-- Preloader Hide on Load -->
<script>
  window.addEventListener('load', function () {
    setTimeout(function () {
        const preloader = document.querySelector('.theme-loader');
        if (preloader) {
            preloader.style.display = 'none';
        }
    }, 350); // Adjust duration as needed
  });
</script>
<script type="text/javascript">
$(document).ready(function() {
    $('#datatable').DataTable({
        "lengthMenu": [10, 20, 50, 100],
        "pageLength": 10,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.5/i18n/Indonesian.json" // Untuk Bahasa Indonesia
        },
        "responsive": true, // Jika tabel perlu responsif
        "dom": 'lftip', // Kontrol layout DataTables
        "order": [[0, 'desc']], // Urutkan berdasarkan kolom 0 (Waktu) secara menurun (terbaru di atas)
    });
});
</script>

