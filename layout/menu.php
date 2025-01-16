<?php include_once("../logincheck.php"); ?>
<nav class="pcoded-navbar">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">
        <div class="">
            <div class="main-menu-header">
                <img class="img-80 img-radius" src="../assets/images/rsi.png" alt="User-Profile-Image">
                <div class="user-details">
                    <span id="more-details">
                    <?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Guest'; ?>
                        <i class="fa fa-caret-down"></i>
                    </span>
                </div>
            </div>

            <div class="main-menu-content">
                <ul>
                    <li class="more-details">
                        <a href="../admin/logout.php"><i class="ti-layout-sidebar-left"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </div>

        <br>
        <ul class="pcoded-item pcoded-left-item">
            <li class="<?php echo ($_GET['page'] == 'dashboard') ? 'active' : ''; ?>">
                <a href="?page=dashboard" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <hr>
            <div class="pcoded-navigation-label" data-i18n="nav.category.navigation">Menu Data</div>
            <li class="<?php echo ($_GET['page'] == 'petugas') ? 'active' : ''; ?>">
                <a href="?page=petugas" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="fa fa-user-o"></i><b>P</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Petugas</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="<?php echo ($_GET['page'] == 'kategori') ? 'active' : ''; ?>">
                <a href="?page=kategori" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-view-grid"></i><b>K</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Kategori</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="<?php echo ($_GET['page'] == 'inventaris') ? 'active' : ''; ?>">
                <a href="?page=inventaris" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="fa fa-archive"></i><b>I</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Inventaris</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="<?php echo ($_GET['page'] == 'barang') ? 'active' : ''; ?>">
                <a href="?page=barang" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-package"></i><b>B</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Barang</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>

        <div class="pcoded-navigation-label" data-i18n="nav.category.forms">Main Menu</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="<?php echo ($_GET['page'] == 'pinjam') ? 'active' : ''; ?>">
                <a href="?page=pinjam" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-reload"></i><b>P</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Peminjaman</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="<?php echo ($_GET['page'] == 'kegiatan') ? 'active' : ''; ?>">
                <a href="?page=kegiatan" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-book"></i><b>K</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Kegiatan</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="pcoded-hasmenu">
                <a href="javascript:void(0)" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-direction-alt"></i><b>M</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.menu-levels.main">Aktivitas</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li>
                        <a href="?page=masuk" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext" data-i18n="nav.menu-levels.menu-level-21">Barang Masuk</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li>
                        <a href="?page=keluar" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext" data-i18n="nav.menu-levels.menu-level-23">Barang Keluar</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <?php if ($_SESSION['level'] == 'administrator') { ?>
        <div class="pcoded-navigation-label" data-i18n="nav.category.forms">Setting</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="<?php echo ($_GET['page'] == 'akun') ? 'active' : ''; ?>">
                <a href="?page=akun" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="fa fa-user-o"></i><b>P</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Kelola Akun</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="<?php echo ($_GET['page'] == 'devisi') ? 'active' : ''; ?>">
                <a href="?page=devisi" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="fa fa-bars"></i><b>P</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Devisi</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
        <?php } ?>
    </div>
</nav>
