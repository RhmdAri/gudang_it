<?php
session_start();
include '../connection.php';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($con, "SELECT * FROM pengguna WHERE id=$id");
    $data = mysqli_fetch_array($result);
    if ($data) {
        $nama = $data['nama'];
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Ditemukan',
                text: 'User tidak ditemukan!'
            }).then(() => {
                window.location.href = '?page=akun';
            });
        </script>";
        exit();
    }
}
if (isset($_POST['submit'])) {
    $new_nama = trim($_POST['nama']);
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);

    if (!empty($new_nama)) {
        $update_query = "UPDATE pengguna SET nama='$new_nama'";

        if (!empty($password) && !empty($password_confirm)) {
            if ($password === $password_confirm) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $update_query .= ", password='$password_hash'";
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Gagal',
                        text: 'Konfirmasi password tidak sesuai!'
                    });
                </script>";
                exit();
            }
        }
        $update_query .= " WHERE id=$id";
        $update = mysqli_query($con, $update_query);

        if ($update) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil diperbarui!'
                }).then(() => {
                    window.location.href = '?page=akun';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memperbarui data.'
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Input Tidak Valid',
                text: 'nama wajib diisi!'
            });
        </script>";
    }
}
?>
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Edit nama & Reset Password</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Edit Data</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Edit nama & Reset Password</h5>
                            </div>
                            <div class="card-block">
                                <form class="form-material" method="POST" id="editForm" novalidate>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($nama); ?>" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nama Baru</label>
                                    </div>

                                    <div class="form-group form-default">
                                        <input type="password" class="form-control" name="password">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Password Baru (Opsional)</label>
                                    </div>

                                    <div class="form-group form-default">
                                        <input type="password" class="form-control" name="password_confirm">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Konfirmasi Password (Opsional)</label>
                                    </div>

                                    <div class="row">
                                        <div class="col offset">
                                            <button type="submit" name="submit" class="btn btn-outline-primary">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                            <a href="?page=akun" class="btn btn-outline-warning">
                                                <i class="fa fa-chevron-left"></i> Kembali
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
