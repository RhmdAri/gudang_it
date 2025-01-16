<?php
session_start();

$userLevel = $_SESSION['level']; 
$isAdmin = $userLevel == 'admin'; 

if (!$isAdmin) {
    header('Location: index.php?page=dashboard');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $messageTitle = $_POST['message_title'];
    $messageContent = $_POST['message_content'];

    $messageFile = 'messages.json';
    $messages = [];

    if (file_exists($messageFile)) {
        $messages = json_decode(file_get_contents($messageFile), true);
    }

    $newMessage = [
        'id' => count($messages) + 1,
        'user' => 'Admin', 
        'content' => $messageContent,
        'created_at' => date('Y-m-d H:i:s'),
    ];

    $messages[] = $newMessage;
    file_put_contents($messageFile, json_encode($messages));

    header('Location: index.php?page=dashboard');
    exit();
}

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
                    <h6 class="card-title text-center">Buat Pesan Baru</h6>
                    <form action="create_message.php" method="POST">
                        <div class="mb-3">
                            <label for="messageTitle" class="form-label">Judul Pesan</label>
                            <input type="text" class="form-control" id="messageTitle" name="message_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="messageContent" class="form-label">Isi Pesan</label>
                            <textarea class="form-control" id="messageContent" name="message_content" rows="4" required></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                            <a href="?page=dashboard" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-body {
    padding: 30px;
}

.form-label {
    font-weight: bold;
}

.mb-3 {
    margin-bottom: 1rem;
}

button[type="submit"], .btn-secondary {
    width: 48%;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .col-md-8 {
        width: 100%;
    }

    .btn-secondary, button[type="submit"] {
        width: 100%;
        margin-top: 10px;
    }
}
</style>
