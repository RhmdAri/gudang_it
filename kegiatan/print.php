<?php
require '../vendor/autoload.php'; // Include Composer autoload file

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

include('../connection.php');

// Ambil bulan dan tahun dari parameter GET
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Array bulan dalam format teks
$bulanArray = [
    '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
    '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
    '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
];

// Mendapatkan nama bulan sesuai pilihan
$namaBulan = $bulanArray[$bulan];

// Membuat objek spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Menambahkan informasi bulan dan tahun di atas tabel
$sheet->mergeCells('A1:E1');
$sheet->setCellValue('A1', 'Laporan Data Kegiatan');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Menambahkan bulan dan tahun yang dipilih dalam format "November 2024"
$sheet->mergeCells('A2:E2');
$sheet->setCellValue('A2', $namaBulan . ' ' . $tahun);
$sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
$sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Menambahkan header kolom (termasuk kolom No)
$sheet->setCellValue('A3', 'No')
      ->setCellValue('B3', 'Waktu')
      ->setCellValue('C3', 'Nama Petugas')
      ->setCellValue('D3', 'Tempat')
      ->setCellValue('E3', 'Kegiatan');

// Memberikan style pada header kolom
$sheet->getStyle('A3:E3')->applyFromArray([
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFFF00'],
    ],
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
]);

// Query untuk mengambil data kegiatan berdasarkan bulan dan tahun
$query = "SELECT * FROM kegiatan WHERE MONTH(waktu) = '$bulan' AND YEAR(waktu) = '$tahun'";
$result = mysqli_query($con, $query);

// Menambahkan data ke dalam sheet, termasuk nomor urut
$rowNumber = 4; // Mulai dari baris keempat setelah header
$no = 1; // Nomor urut
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNumber, $no++);
    $sheet->setCellValue('B' . $rowNumber, $row['waktu']);
    $sheet->setCellValue('C' . $rowNumber, $row['petugas']);
    $sheet->setCellValue('D' . $rowNumber, $row['tempat']);
    $sheet->setCellValue('E' . $rowNumber, $row['kegiatan']);
    $rowNumber++;
}

// Menambahkan border pada tabel
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];
$sheet->getStyle('A3:E' . ($rowNumber - 1))->applyFromArray($styleArray);

// Menyesuaikan lebar kolom sesuai dengan konten
foreach (range('A', 'E') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Set headers untuk output file Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Data_Kegiatan_' . $namaBulan . '_' . $tahun . '.xls"');
header('Cache-Control: max-age=0');

// Menulis file Excel ke output
$writer = new Xls($spreadsheet);
$writer->save('php://output');
?>