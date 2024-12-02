<?php
$id = $_GET['id'];
$result = mysqli_query($con, "SELECT * FROM petugas WHERE id=$id");
while ($data = mysqli_fetch_array($result)) {
  $nama = $data['nama'];
}



if (isset($_POST['submit'])) {
  $nama = $_POST['nama'];
  $result = mysqli_query($con, "UPDATE petugas SET nama='$nama' WHERE id=$id");
  echo "<script>window.location.href ='?page=petugas';</script>";
}
?>

<div class="pcoded-content">
                        <!-- Page-header start -->
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
                                          <li class="breadcrumb-item"><a href="#!"><?php echo $title ?></a>
                                          </li>
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

                                    <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5><?php echo $title ?></h5>
                                                        <!--<span>Add class of <code>.form-control</code> with <code>&lt;input&gt;</code> tag</span>-->
                                                    </div>
                                                    <div class="card-block">
                                                        <form class="form-material" method="POST">
                                                            <div class="form-group form-default">
                                                                <input type="text"  class="form-control" name="nama" value="<?php echo $nama ?>" required>
                                                                <span class="form-bar"></span>
                                                                <label for="namaPetugas" class="float-label">Nama Petugas</label>
                                                            </div>
                                                            <div class="row">
                                                            <div class="col offset">
                                                            <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                                            <a href="?page=petugas" class="btn btn-warning"><i class="fa fa-chevron-left"></i> Kembali</a>
                                                        </div>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div id="styleSelector">
                            </div>
                        </div>
                    </div>
</div>
