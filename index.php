<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rumah Sakit Sultan Agung Banjarbaru</title>
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
                    <a class="nav-link text-success" href="#home">Beranda</a>
                </li>
		        <li class="nav-item">
                    <a class="nav-link text-success" href="profil.php">Profil</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-success" href="#layanan">Layanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-success" href="#mengenai">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-success" href="login.php?page=login">Masuk</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main role="main" class="container flex-grow-1">

    <div class="container-lp d-flex flex-column flex-md-row justify-content-center align-items-center" id="home">
        <div class="container-left col-12 col-md-6 d-flex flex-column justify-content-center gap-3 mt-4 pt-0 mt-md-5 pt-md-5 p-3 p-md-5 order-2 order-md-1">
            <h2>Rumah Sakit <span class="text-success">Terpercaya</span> Untuk Anda!</h2>
            <p>Melayani Anda dengan perawatan medis yang berkualitas, tenaga profesional, dan fasilitas modern. Temukan solusi kesehatan Anda di tempat yang aman dan nyaman.</p>
        </div>
        <div class="container-right col-12 col-md-6 d-flex flex-column justify-content-center align-items-center mt-10 pt-10 pb-0 ps-2 pe-2 md-mt-0 md-pt-0 md-pb-0 md-ps-0 md-pe-0 order-1 order-md-2">
            <img src="assets/images/depan.jpg" alt="dokter image" class="img-fluid">
        </div>
    </div>

    <!-- Layanan Kami -->
    <section class="layanan-kami py-5 mt-5 mt-md-3" id="layanan">
        <div class="container">
            <h2 class="text-center mb-5 text-success">Layanan Kami</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-success">Konsultasi Dokter</h5>
                            <p class="card-text">Konsultasikan masalah kesehatan Anda dengan dokter ahli kami.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-success">Pemeriksaan Rutin</h5>
                            <p class="card-text">Lakukan pemeriksaan kesehatan rutin untuk menjaga kesehatan Anda.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-success">Laboratorium</h5>
                            <p class="card-text">Fasilitas laboratorium modern untuk berbagai jenis pemeriksaan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="tentang-kami py-5 bg-light">
    <div class="container">
    <div class="d-flex justify-content-between align-items-center p-5 bg-light rounded">
        <div class="text-container">
            <h3>Tujuan <span class="text-success">RSISA</span></h3>
            <p>
                Rumah Sakit Islam Terkemuka dalam Pelayanan Kesehatan,
                Menyelenggarakan pelayanan kesehatan yang selamat menyelamatkan dijiwai semangat Mencintai Allah Menyayangi Sesama. Menyelenggarakan pelayanan pendidikan dalam rangka membangun generasi khaira ummah. Membangun peradaban Islam menuju masyarakat sehat sejahtera yang dirahmati Allah.
            </p>
        </div>
        <div class="image-container ms-4">
            <img src="assets/images/tujuan.jpg" alt="Tentang RSISA" class="img-fluid rounded shadow-sm">
        </div>
    </div>
</div>
    </section>
<section class="peta-lokasi py-5" id="mengenai">
    <div class="container">
        <h2 class="text-center mb-4 text-success">Lokasi Kami</h2>
        <div class="embed-responsive embed-responsive-16by9">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3982.6670349689116!2d114.69632397455807!3d-3.4309786417306354!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de69d52d889cd9f%3A0x32435e2ed8e35706!2sRumah%20Sakit%20Islam%20Sultan%20Agung%20Banjarbaru!5e0!3m2!1sid!2sid!4v1734096124854!5m2!1sid!2sid" width="1300" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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
