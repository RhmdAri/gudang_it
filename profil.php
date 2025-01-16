<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Rumah Sakit Sultan Agung Banjarbaru</title>
    <link rel="shortcut icon" href="assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/aset.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg position-fixed">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/images/rsi.png" alt="Logo" width="30" height="30">
            <span class="m-0 ms-2 fw-bold text-success">RSI Sultan Agung Banjarbaru</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-3 fw-semibold">
                <li class="nav-item">
                    <a class="nav-link text-success" href="index.php">Beranda</a>
                </li>
		        <li class="nav-item">
                    <a class="nav-link text-success" href="profil.php">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-success" href="login.php?page=login">Masuk</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main role="main" class="container flex-grow-1">
    <section class="profil py-5">
        <div class="container">
            <h2 class="text-center mb-5 text-success">Berkhidmat Menyelamatkan Ummat</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <img src="assets/images/rs.jpeg" class="card-img-top" alt="RSI Building">
                        <div class="card-body">
                            <h5 class="card-title text-success">Sejarah Kami</h5>
                            <p class="card-text">Rumah Sakit Sultan Agung telah berdiri sejak tahun  17 Agustus 1971 dengan tujuan memberikan pelayanan kesehatanmenurut syariat islam. Dengan dukungan tenaga medis profesional dan fasilitas yang lengkap, kami berkomitmen untuk memberikan pelayanan terbaik dan Berkhidmat untuk ummat.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <img src="assets/images/dok.png" class="card-img-top" alt="Doctor and Patient">
                        <div class="card-body">
                            <h5 class="card-title text-success">Visi dan Misi</h5>
                            <p class="card-text"><strong>Visi:</strong> Rumah Sakit Islam terkemuka dalam pelayanan kesehatan, Pendidikan dan Pembangunan Peradaban Islam, menuju masyarakat sehat sejahtera yang dirahmati Allah.</p>
                            <p class="card-text"><strong>Misi:</strong> Menyelenggarakan pelayanan kesehatan yang selamat menyelamatkan dijiwai semangat Mencintai Allah Menyayangi Sesama. Menyelenggarakan pelayanan pendidikan dalam rangka membangun generasi khaira ummah. Membangun peradaban Islam menuju masyarakat sehat sejahtera yang dirahmati Allah.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-success">Fasilitas Kami</h5>
                            <ul>
                                <li>Poli Umum</li>
                                <li>Pemeriksaan Laboratorium</li>
                                <li>Unit Gawat Darurat (UGD)</li>
                                <li>Ruang Rawat Inap</li>
                                <li>Fasilitas Radiologi dan CT Scan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="bg-success text-light py-4 mt-5 p-3">
    <div class="container">
        <div class="row">
            <div class="col-md-5 mb-3 mb-md-0">
                <a class="navbar-brand d-flex align-items-center" href="index.php">
                    <img src="assets/images/rsi.png" alt="Logo" width="30" height="30">
                    <span class="m-0 ms-2 fw-bold text-light fs-4">RSI Sultan Agung Banjarbaru</span>
                </a>
                <p class="mt-3">Memberikan Yang Terbaik untuk Kesehatan Anda</p>
                <div class="d-flex">
                    <a href="https://www.facebook.com/" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                    <a href="https://twitter.com/" class="text-light me-3"><i class="bi bi-twitter"></i></a>
                    <a href="https://www.instagram.com/" class="text-light me-3"><i class="bi bi-instagram"></i></a>
                    <a href="https://www.linkedin.com/" class="text-light"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <h5>Cabang</h5>
                <ul class="list-unstyled">
                    <li>RSI Sultan Agung Semarang</li>
                    <li>RSI Sultan Agung Banjarbaru</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Alamat</h5>
                <address>
                    <p><i class="bi bi-geo-alt-fill me-2"></i> Komplek Kota Citra Graha, Jl. A. Yani No.Km. 17,5, RT.015/RW.003, Kelurahan Landasan Ulin Barat, Kec. Liang Anggang, Kota Banjar Baru, Kalimantan Selatan 70722</p>
                </address>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function kirimPesan(event) {
        event.preventDefault();
        Swal.fire({
            icon: 'success',
            title: 'Pesan Terkirim',
            text: 'Terima kasih telah menghubungi kami!'
        });
    }
</script>

</body>
</html>
