<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama']);
    $tempat = trim($_POST['tempat']);
    $keperluan = trim($_POST['keperluan']);
    $divisi_tujuan = $_POST['divisi_tujuan'];
    $idPetugas = $_POST['petugas'];
    $inventarisId = $_POST['inventaris_id'] ?? [];
    $inventarisJumlah = $_POST['inventaris_jumlah'] ?? [];

    $divisi = $_SESSION['divisi'];

    if (empty($nama) || empty($tempat) || empty($keperluan) || empty($idPetugas) || empty($divisi_tujuan)) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Semua kolom wajib diisi!'
            });
        </script>";
    } else {
        $errors = [];
        foreach ($inventarisId as $id) {
            $jumlah = $inventarisJumlah[$id] ?? 0;
            $stokQuery = mysqli_query($con, "SELECT stok FROM inventaris WHERE id = '$id' AND divisi = '$divisi_tujuan'");
            $dataStok = mysqli_fetch_assoc($stokQuery);
        
            if (!$dataStok) {
                $errors[] = "Inventaris ID $id tidak ditemukan!";
            } elseif ($jumlah <= 0 || $jumlah > $dataStok['stok']) {
                $errors[] = "Stok untuk inventaris ID $id tidak mencukupi!";
            }
        }        

        if (empty($errors)) {
            foreach ($inventarisId as $id) {
                $jumlah = $inventarisJumlah[$id];
                $query = "INSERT INTO pinjam (idPetugas, nama, tempat, keperluan, idInventaris, jumlah, devisi, devisi_tujuan, status) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $query);
                $status = 'dipinjam';
                mysqli_stmt_bind_param($stmt, "isssiisss", $idPetugas, $nama, $tempat, $keperluan, $id, $jumlah, $divisi, $divisi_tujuan, $status);
                mysqli_stmt_execute($stmt);
                $updateStokQuery = "UPDATE inventaris SET stok = stok - ? WHERE id = ? AND divisi = ?";
                $stmtUpdate = mysqli_prepare($con, $updateStokQuery);
                mysqli_stmt_bind_param($stmtUpdate, "iis", $jumlah, $id, $divisi_tujuan);
                mysqli_stmt_execute($stmtUpdate);
            }            

            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data pinjam berhasil disimpan!'
                }).then(() => {
                    window.location.href = '?page=pinjam';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: '<ul style=\"text-align: left;\">".implode("", array_map(fn($msg) => "<li>$msg</li>", $errors))."</ul>'
                });
            </script>";
        }
    }
}
?>

<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Tambah Pinjam</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Tambah Pinjam</a></li>
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
                            <div class="card-header"><h5>Tambah Pinjam</h5></div>
                            <div class="card-block">
                                <form class="form-material" method="POST">
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="nama" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nama</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="tempat" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Tempat</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" class="form-control" name="keperluan" required>
                                        <span class="form-bar"></span>
                                        <label class="float-label">Keperluan</label>
                                    </div>
                                    <div class="form-group form-default">
                                        <label>Divisi Tujuan</label>
                                        <select name="divisi_tujuan" id="divisi_tujuan" class="form-control" required style="border: none; border-bottom: 2px solid #ccc; background: transparent; outline: none;">
                                            <option value="">- Pilih Divisi Tujuan -</option>
                                            <?php
                                            $divisiQuery = mysqli_query($con, "SELECT DISTINCT divisi FROM inventaris");
                                            while ($div = mysqli_fetch_assoc($divisiQuery)) {
                                                echo "<option value='{$div['divisi']}'>{$div['divisi']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group form-default">
                                        <label>Petugas</label>
                                        <select name="petugas" class="form-control" required style="border: none; border-bottom: 2px solid #ccc; background: transparent; outline: none;">
                                            <option value="">- Pilih Petugas -</option>
                                            <?php
                                            $divisi = $_SESSION['divisi'];
                                            $query = "SELECT id, nama FROM petugas WHERE status = 'disetujui' AND divisi = ?";
                                            $stmt = mysqli_prepare($con, $query);
                                            mysqli_stmt_bind_param($stmt, 's', $divisi);
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);
                                            while ($data = mysqli_fetch_assoc($result)) {
                                                echo "<option value='{$data['id']}'>{$data['nama']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group form-default">
                                        <label>Inventaris</label>
                                        <div id="inventaris-container" class="form-control select-style">
                                            <p class="text-muted">Silakan pilih divisi terlebih dahulu.</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" name="submit" class="btn btn-outline-primary">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                            <a href="?page=pinjam" class="btn btn-outline-warning ml-2">
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

<script>
document.getElementById("divisi_tujuan").addEventListener("change", function () {
    const divisi_tujuan = this.value;
    const inventarisContainer = document.getElementById("inventaris-container");

    if (divisi_tujuan) {
        fetch(`../peminjaman/get_inventaris.php?divisi=${divisi_tujuan}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.error) {
                    inventarisContainer.innerHTML = `<p class="text-muted">${data.error}</p>`;
                } else {
                    let html = '<table class="table table-bordered table-hover">';
                    html += `
                        <thead>
                            <tr>
                                <th>Pilih</th>
                                <th>Nama Inventaris</th>
                                <th>Stok</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>`;
                    data.forEach((item) => {
                        html += `
                            <tr>
                                <td>
                                    <input type="checkbox" name="inventaris_id[]" id="inventaris_${item.id}" value="${item.id}" onchange="toggleJumlahInput(${item.id})">
                                </td>
                                <td>${item.nama}</td>
                                <td>${item.stok}</td>
                                <td>
                                    <input type="number" name="inventaris_jumlah[${item.id}]" id="jumlah_${item.id}" value="0" min="1" max="${item.stok}" class="form-control" style="max-width: 80px;" disabled>
                                </td>
                            </tr>`;
                    });
                    html += '</tbody></table>';
                    inventarisContainer.innerHTML = html;
                }
            })
            .catch(() => {
                inventarisContainer.innerHTML = '<p class="text-muted">Terjadi kesalahan saat mengambil data inventaris.</p>';
            });
    } else {
        inventarisContainer.innerHTML = '<p class="text-muted text-center">Silakan pilih divisi terlebih dahulu.</p>';
    }
});

function toggleJumlahInput(id) {
    const checkbox = document.getElementById(`inventaris_${id}`);
    const jumlahInput = document.getElementById(`jumlah_${id}`);
    jumlahInput.disabled = !checkbox.checked;
    if (!checkbox.checked) {
        jumlahInput.value = 0;
    }
}
</script>
<style>
#inventaris-container {
    max-height: 600px;
    height: auto;
    overflow-y: auto;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.inventaris-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    background: #ffffff;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 100%; 
}

.input-group input {
    width: 80px;
    text-align: center;
}

.card {
    width: 100%;
}

.card .card-block {
    flex: 1;
    padding: 20px;
}
</style>