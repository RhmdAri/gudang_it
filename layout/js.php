
<script type="text/javascript" src="../assets/js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="../assets/js/popper.js/popper.min.js"></script>
<script type="text/javascript" src="../assets/js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../assets/pages/waves/js/waves.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery-slimscroll/jquery.slimscroll.js"></script>
<script type="text/javascript" src="../assets/js/SmoothScroll.js"></script>
<script type="text/javascript" src="../assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="../assets/js/modernizr/modernizr.js"></script>
<script type="text/javascript" src="../assets/js/chart.js/Chart.js"></script>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script type="text/javascript" src="../assets/pages/widget/amchart/gauge.js"></script>
<script type="text/javascript" src="../assets/pages/widget/amchart/serial.js"></script>
<script type="text/javascript" src="../assets/pages/widget/amchart/light.js"></script>
<script type="text/javascript" src="../assets/pages/widget/amchart/pie.min.js"></script>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<script type="text/javascript" src="../assets/js/pcoded.min.js"></script>
<script type="text/javascript" src="../assets/js/vertical-layout.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="../assets/js/script.js "></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
console.log("SweetAlert 2 dimuat!");
</script>
<script type="text/javascript">
$(document).ready(function() {
    $('.fixed-button').remove();
});

document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.querySelector('.sidebar_toggle a');
    const navbar = document.querySelector('.pcoded-navbar');

    if (sidebarToggle && navbar) {
        sidebarToggle.addEventListener('click', function() {
            navbar.classList.toggle('active');
        });
    }

    window.addEventListener('load', function () {
        setTimeout(function () {
            const preloader = document.querySelector('.theme-loader');
            if (preloader) {
                preloader.style.display = 'none';
            }
        }, 350);
    });

    $(document).ready(function() {
    $('#datatable').DataTable({
        responsive: true,
        autoWidth: false,
        paging: true,
        searching: true,
        ordering: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
        }
    });
});
    const $searchInput = $('#search');
    const $tableBody = $('#table-body');
    const $rows = $tableBody.find('tr');

    $searchInput.on('keyup', function () {
        const filter = $searchInput.val().toLowerCase();
        $rows.each(function () {
            const $td = $(this).find('td').first();
            const txtValue = $td.text() || $td.html();
            $(this).toggle(txtValue.toLowerCase().indexOf(filter) > -1);
        });
    });
});

</script>