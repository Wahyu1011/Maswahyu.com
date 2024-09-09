<?php
// Memanggil atau membutuhkan file function.php
require 'function.php';

// Jika dataMahasiswa diklik maka
if (isset($_POST['datamahasiswa'])) {
    $output = '';

    // Mengambil data mahasiswa dari nim yang berasal dari dataMahasiswa
    $nim = $_POST['datamahasiswa'];

    // Menggunakan prepared statements untuk menghindari SQL Injection
    $stmt = $koneksi->prepare("SELECT * FROM Mahasiswa WHERE nim = ?");
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $result = $stmt->get_result();

    $output .= '<div class="table-responsive">
                    <table class="table table-bordered">';

    // Memastikan hasil query ada sebelum melakukan looping
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $output .= '<tr align="center">
                            <td colspan="2"><img src="img/' . htmlspecialchars($row['gambar']) . '" width="50%"></td>
                        </tr>
                        <tr>
                            <th width="40%">NIM</th>
                            <td width="60%">' . htmlspecialchars($row['nim']) . '</td>
                        </tr>
                        <tr>
                            <th width="40%">Nama</th>
                            <td width="60%">' . htmlspecialchars($row['nama']) . '</td>
                        </tr>
                        <tr>
                            <th width="40%">Tempat dan Tanggal Lahir</th>
                            <td width="60%">' . htmlspecialchars($row['tmpt_Lahir']) . ', ' . date("d M Y", strtotime($row['tgl_Lahir'])) . '</td>
                        </tr>
                        <tr>
                            <th width="40%">Jenis Kelamin</th>
                            <td width="60%">' . htmlspecialchars($row['jekel']) . '</td>
                        </tr>
                        <tr>
                            <th width="40%">Jurusan</th>
                            <td width="60%">' . htmlspecialchars($row['jurusan']) . '</td>
                        </tr>
                        <tr>
                            <th width="40%">E-Mail</th>
                            <td width="60%">' . htmlspecialchars($row['email']) . '</td>
                        </tr>
                        <tr>
                            <th width="40%">Alamat</th>
                            <td width="60%">' . htmlspecialchars($row['alamat']) . '</td>
                        </tr>
                        <tr>
                            <th width="40%">ID Medsos</th>
                            <td width="60%">' . htmlspecialchars($row['medsos']) . '</td>
                        </tr>
                        <tr>
                            <th width="40%">Orang Tua / Wali</th>
                            <td width="60%">' . htmlspecialchars($row['bapak']) . ' & ' . htmlspecialchars($row['ibu']) . '</td>
                        </tr>';
        }
    } else {
        $output .= '<tr><td colspan="2">Data tidak ditemukan</td></tr>';
    }

    $output .= '</table></div>';
    // Tampilkan $output
    echo $output;

    // Menutup prepared statement
    $stmt->close();
}
?>
