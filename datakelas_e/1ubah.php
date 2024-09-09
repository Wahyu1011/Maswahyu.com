<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Memanggil atau membutuhkan file function.php
require 'function.php';

// Cek koneksi ke database
if (!$koneksi) {
    die('Koneksi ke database gagal: ' . mysqli_connect_error());
}

// Inisialisasi variabel output untuk HTML
$output = '<div class="table-responsive">
               <table class="table table-bordered">';

// Jika dataMahasiswa diklik maka
if (isset($_POST['datamahasiswa'])) {
    // Menghindari SQL Injection dengan menggunakan parameterized query
    $nim = htmlspecialchars($_POST['datamahasiswa'], ENT_QUOTES, 'UTF-8');
    $sql = "SELECT * FROM mahasiswa WHERE nim = ?";
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $nim);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Cek apakah ada hasil
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Validasi bahwa gambar adalah file yang aman untuk ditampilkan
                $gambar = htmlspecialchars($row['gambar'], ENT_QUOTES, 'UTF-8');
                if (preg_match('/\.(jpeg|jpg|png|gif)$/i', $gambar)) {
                    $gambar = 'img/' . $gambar;
                } else {
                    $gambar = 'img/default.png'; // Atau gambar default jika tidak valid
                }

                $output .= '<tr align="center">
                                <td colspan="2"><img src="' . $gambar . '" width="50%"></td>
                            </tr>
                            <tr>
                                <th width="40%">NIM</th>
                                <td width="60%">' . htmlspecialchars($row['nim'], ENT_QUOTES, 'UTF-8') . '</td>
                            </tr>
                            <tr>
                                <th width="40%">Nama</th>
                                <td width="60%">' . htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8') . '</td>
                            </tr>
                            <tr>
                                <th width="40%">Tempat dan Tanggal Lahir</th>
                                <td width="60%">' . htmlspecialchars($row['tmpt_Lahir'], ENT_QUOTES, 'UTF-8') . ', ' . date("d M Y", strtotime($row['tgl_Lahir'])) . '</td>
                            </tr>
                            <tr>
                                <th width="40%">Jenis Kelamin</th>
                                <td width="60%">' . htmlspecialchars($row['jekel'], ENT_QUOTES, 'UTF-8') . '</td>
                            </tr>
                            <tr>
                                <th width="40%">Jurusan</th>
                                <td width="60%">' . htmlspecialchars($row['jurusan'], ENT_QUOTES, 'UTF-8') . '</td>
                            </tr>
                            <tr>
                                <th width="40%">E-Mail</th>
                                <td width="60%">' . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . '</td>
                            </tr>
                            <tr>
                                <th width="40%">Alamat</th>
                                <td width="60%">' . htmlspecialchars($row['alamat'], ENT_QUOTES, 'UTF-8') . '</td>
                            </tr>
                            <tr>
                                <th width="40%">ID Medsos</th>
                                <td width="60%">' . htmlspecialchars($row['medsos'], ENT_QUOTES, 'UTF-8') . '</td>
                            </tr>
                            <tr>
                                <th width="40%">Orang Tua / Wali</th>
                                <td width="60%">' . htmlspecialchars($row['bapak'], ENT_QUOTES, 'UTF-8') . ' & ' . htmlspecialchars($row['ibu'], ENT_QUOTES, 'UTF-8') . '</td>
                            </tr>';
            }
        } else {
            $output .= '<tr><td colspan="2" class="text-center">Data mahasiswa tidak ditemukan.</td></tr>';
        }

        // Menutup prepared statement
        mysqli_stmt_close($stmt);
    } else {
        $output .= '<tr><td colspan="2" class="text-center">Terjadi kesalahan dalam menyiapkan pernyataan SQL.</td></tr>';
    }
} else {
    $output .= '<tr><td colspan="2" class="text-center">Silakan masukkan NIM untuk mencari data mahasiswa.</td></tr>';
}

$output .= '</table></div>';

// Tampilkan $output
echo $output;

// Menutup koneksi database
mysqli_close($koneksi);
?>
