<?php
session_start();

$userLevel = $_SESSION['level']; 
$isAdmin = $userLevel == 'administrator'; 
$messageFile = 'messages.json';
$messages = [];

if (file_exists($messageFile)) {
    $messages = json_decode(file_get_contents($messageFile), true);
}

$page = isset($_GET['page']) ? $_GET['page'] : '';

if ($page == 'dashboard') {
    ?>
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="page-header-title">
                            <h5 class="m-b-10"><?php echo $title; ?></h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <ul class="breadcrumb-title">
                            <li class="breadcrumb-item">
                                <a href="?page=dashboard"><i class="fa fa-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#!"><?php echo $title; ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 mt-4 justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h6 class="card-title text-center">Pesan Administrator</h6>
                        <?php if ($isAdmin): ?>
                        <div class="mt-3 text-center">
                            <a href="?page=create" class="btn btn-primary btn-sm">Buat Pesan Baru</a>
                        </div>
                        <?php endif; ?>
                        <div class="message-box">
                            <?php foreach ($messages as $message): ?>
                            <div class="message-card shadow-sm p-3 mb-3 rounded">
                                <div class="d-flex align-items-start flex-column">
                                    <div class="message-content ms-3">
                                        <div class="message-bubble">
                                            <p><?= htmlspecialchars($message['content']) ?></p>
                                            <span class="message-time" data-time="<?= $message['created_at'] ?>"></span>
                                        </div>
                                    </div>
                                    <div class="message-avatar mt-2">
                                    <img class="img-80 img-radius" src="../assets/images/rsi.png" alt="User Avatar" class="rounded-circle">
                                    </div>
                                    <?php if ($isAdmin): ?>
                                    <div class="text-center mt-3">
                                        <a href="?page=dashboard&delete_id=<?= $message['id'] ?>" class="btn btn-sm btn-danger delete-message">Hapus</a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($_GET['delete_id']) && $isAdmin) {
        $deleteId = $_GET['delete_id'];
        $messages = array_filter($messages, function($message) use ($deleteId) {
            return $message['id'] != $deleteId;
        });

        file_put_contents($messageFile, json_encode(array_values($messages), JSON_PRETTY_PRINT));
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Pesan berhasil dihapus',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = '?page=dashboard';
            });
        </script>";
    }
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const messageTimes = document.querySelectorAll('.message-time');
        messageTimes.forEach(function(timeElement) {
            const messageTime = timeElement.getAttribute('data-time');
            const date = new Date(messageTime);
            timeElement.textContent = date.toLocaleString();
        });
    });
</script>

<style>
.card-body {
    width: 100vw;
    height: 100vh;
    padding: 20px;
    box-sizing: border-box;
    overflow-y: auto;
}

.pcoded-content {
    display: flex;
    flex-direction: column;
    height: 100vh;
    justify-content: flex-start;
}

.col-md-8 {
    width: 100%;
    max-width: 100%;
}

.message-box {
    padding: 15px;
    height: calc(100vh - 120px);
    overflow-y: auto;
    margin-bottom: 20px;
}

.message-card {
    border-radius: 8px;
    background-color: #f8f9fa;
    box-shadow: 0 2px 8px rgba(255, 255, 253, 0.1);
    transition: all 0.3s ease;
    display: flex;
    align-items: flex-start;
    opacity: 0;
    animation: fadeIn 0.5s forwards;
}

.message-card:nth-child(odd) {
    animation-delay: 0.3s;
}

.message-card:nth-child(even) {
    animation-delay: 0.6s;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.message-avatar img {
    width: 50px;
    height: 50px;
}

.message-bubble {
    background-color: #007bff;
    color: white;
    border-radius: 25px;
    padding: 20px 30px;
    position: relative;
    max-width: 85%;
    margin-bottom: 10px;
    font-size: 16px;
    display: inline-block;
    line-height: 1.5;
}

.message-bubble p {
    margin: 0;
}

.message-bubble:after {
    content: '';
    position: absolute;
    bottom: -12px;
    left: 25px;
    width: 0;
    height: 0;
    border-left: 12px solid transparent;
    border-right: 12px solid transparent;
    border-top: 12px solid #007bff;
}

.message-content {
    display: flex;
    flex-direction: column;
}

.message-time {
    font-size: 14px;
    color: white !important;
}

.modal-content {
    border-radius: 8px;
}

.modal-header {
    background-color: #007bff;
    color: white;
}

.btn-danger {
    background-color: #dc3545;
}
</style>
