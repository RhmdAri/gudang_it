<?php
session_start();
include '../connection.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['fileExcel']) && $_FILES['fileExcel']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['fileExcel']['tmp_name'];
        $fileName = $_FILES['fileExcel']['name'];
        $allowedExtensions = ['xls', 'xlsx'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            echo json_encode(['success' => false, 'message' => 'Format file tidak valid. Hanya .xls atau .xlsx yang diperbolehkan.']);
            exit();
        }

        try {
            $spreadsheet = IOFactory::load($fileTmpPath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $successfulInsertions = 0;
            $failedInsertions = 0;
            $divisi = $_SESSION['divisi'];

            foreach ($sheetData as $key => $row) {
                if ($key == 1) continue;

                $kode = mysqli_real_escape_string($con, $row['A']);
                $nama = mysqli_real_escape_string($con, $row['B']);
                $kategori = mysqli_real_escape_string($con, $row['C']);
                $stok = (int)$row['D'];
                if (empty($kode) || empty($nama) || empty($kategori) || $stok <= 0) {
                    $failedInsertions++;
                    continue;
                }
                $checkQuery = "
                    SELECT * FROM barang 
                    WHERE kode = '$kode' AND nama = '$nama' AND divisi = '$divisi'
                ";
                if (mysqli_num_rows($result) > 0) {
                    $failedInsertions++;
                    continue;
                }
                $query = "
                    INSERT INTO barang (kode, nama, idKategori, stok, divisi) 
                    VALUES (
                        '$kode', 
                        '$nama', 
                        (SELECT id FROM kategori WHERE nama='$kategori' LIMIT 1), 
                        '$stok', 
                        '$divisi'
                    )
                ";

                if (mysqli_query($con, $query)) {
                    $successfulInsertions++;
                } else {
                    $failedInsertions++;
                }
            }
            if ($successfulInsertions > 0) {
                echo json_encode([
                    'success' => true, 
                    'message' => "$successfulInsertions data berhasil diimpor, $failedInsertions data gagal."
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Tidak ada data yang berhasil diimpor.'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false, 
                'message' => 'Kesalahan membaca file: ' . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'File tidak ditemukan atau gagal diunggah.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metode request tidak valid.']);
}
