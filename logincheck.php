<?php
session_start();
include_once 'connection.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: ../");
    exit();
}

if (!isset($_SESSION['id'])) {
    $username = $_SESSION['username'];

    $query = "SELECT id FROM pengguna WHERE username = ?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['id'] = $row['id']; 
        } else {
            session_destroy();
            header("Location: ../");
            exit();
        }

        mysqli_stmt_close($stmt);
    } else {
        session_destroy();
        header("Location: ../");
        exit();
    }
}
?>
