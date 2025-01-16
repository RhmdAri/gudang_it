<?php
session_start();

if ($_SESSION['level'] != 'administrator') {
    header('Location: ../');
    exit();
}

if (isset($_GET['message_id'])) {
    $messageId = $_GET['message_id'];
    $messageFile = 'messages.json';

    if (file_exists($messageFile)) {
        $messages = json_decode(file_get_contents($messageFile), true);
    } else {
        $messages = [];
    }
    foreach ($messages as $key => $message) {
        if ($message['id'] == $messageId) {
            unset($messages[$key]);
            break;
        }
    }

    file_put_contents($messageFile, json_encode(array_values($messages)));

    header('Location: ../');
    exit();
}
?>
