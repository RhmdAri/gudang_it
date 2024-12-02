<?php include_once("../logincheck.php")
?>
<nav class="pcoded-navbar">
                      <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                      <div class="pcoded-inner-navbar main-menu">
                          <div class="">
                              <div class="main-menu-header">
                                  <img class="img-80 img-radius" src="../assets/images/rsi.png" alt="User-Profile-Image">
                                  <div class="user-details">
                                      <span id="more-details">IT DEV<i class="fa fa-caret-down"></i></span>
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
                              <li class="<?php
                                        if ($_GET['page'] == 'dashboard') {
                                            echo 'active';
                                        } 
                                    ?>">
                                  <a href="?page=dashboard" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <hr>
                              <div class="pcoded-navigation-label" data-i18n="nav.category.navigation">Menu Data</div>
                              <li class="<?php
                                        if ($_GET['page'] == 'petugas') {
                                            echo 'active';
                                        }
                                    ?>">
                                  <a href="?page=petugas" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="fa fa-user-o"></i><b>D</b></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Petugas</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class="<?php
                                        if ($_GET['page'] == 'kategori') {
                                            echo 'active';
                                        }
                                    ?>">
                                  <a href="?page=kategori" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-view-grid"></i><b>D</b></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Kategori</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class="<?php
                                        if ($_GET['page'] == 'inventaris') {
                                            echo 'active';
                                        }
                                    ?>">
                                  <a href="?page=inventaris" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="fa fa-archive"></i><b>D</b></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Inventaris</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class="<?php
                                        if ($_GET['page'] == 'barang') {
                                            echo 'active';
                                        }
                                    ?>">
                                  <a href="?page=barang" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-package"></i><b>D</b></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Barang</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                          </ul>
                          <div class="pcoded-navigation-label" data-i18n="nav.category.forms">Main Menu</div>
                          <ul class="pcoded-item pcoded-left-item">
                          <li class="<?php
                                        if ($_GET['page'] == 'pinjam') {
                                            echo 'active';
                                        }
                                    ?>">
                                  <a href="?page=pinjam" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-reload"></i><b>D</b></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Peminjaman</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class="<?php
                                        if ($_GET['page'] == 'kegiatan') {
                                            echo 'active';
                                        }
                                    ?>">
                                  <a href="?page=kegiatan" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-book"></i><b>D</b></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Kegiatan</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class="pcoded-hasmenu ">
                                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                                      <span class="pcoded-micon"><i class="ti-direction-alt"></i><b>M</b></span>
                                      <span class="pcoded-mtext" data-i18n="nav.menu-levels.main">Aktivitas</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                                  <ul class="pcoded-submenu">
                                      <li class="">
                                          <a href="?page=masuk" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="nav.menu-levels.menu-level-21">Barang Masuk</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                      <li class="">
                                          <a href="?page=keluar" class="waves-effect waves-dark">
                                              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                              <span class="pcoded-mtext" data-i18n="nav.menu-levels.menu-level-23">Barang Keluar</span>
                                              <span class="pcoded-mcaret"></span>
                                          </a>
                                      </li>
                                  </ul>
                              </li>
                          </ul>
                          <ul class="pcoded-item pcoded-left-item">
                              
                          </ul>
                          </ul>

                      </div>
                  </nav>