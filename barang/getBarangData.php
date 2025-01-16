<?php
session_start();
include '../connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $level = $_SESSION['level'];
    $userId = $_SESSION['id'];

    if ($level == 'admin') {
        $query = "
            SELECT barang.*, kategori.nama AS kategori, pengguna.username 
            FROM barang 
            LEFT JOIN kategori ON barang.idKategori = kategori.id 
            LEFT JOIN pengguna ON barang.user_id = pengguna.id
        ";
    } else {
        $query = "
            SELECT barang.*, kategori.nama AS kategori 
            FROM barang 
            LEFT JOIN kategori ON barang.idKategori = kategori.id 
            WHERE barang.user_id = '$userId'
        ";
    }

    $result = mysqli_query($con, $query);
    $barangData = [];
    $no = 1;

    while ($data = mysqli_fetch_array($result)) {
        $barangData[] = [
            'no' => $no++,
            'id' => $data['id'],
            'kode' => htmlspecialchars($data['kode']),
            'nama' => htmlspecialchars($data['nama']),
            'kategori' => htmlspecialchars($data['kategori']),
            'stok' => (int)htmlspecialchars($data['stok']),
            'username' => isset($data['username']) ? htmlspecialchars($data['username']) : null
        ];
    }

    echo json_encode($barangData);
}
