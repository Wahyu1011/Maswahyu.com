<?php
session_start();
// Jika tidak bisa login maka balik ke login.php
if (!isset($_SESSION['login'])) {
    header('location:login.php');
    exit;
}

require 'function.php';

// Ambil data dari form
$nim = $_POST['nim'];
$nama = $_POST['nama'];
$tmpt_Lahir = $_POST['tmpt_Lahir'];
$tgl_Lahir = $_POST['tgl_Lahir'];
$jekel = $_POST['jekel'];
$jurusan = $_POST['jurusan'];
$email = $_POST['email'];
$alamat = $_POST['alamat'];
$bapak = $_POST['bapak'];
$ibu = $_POST['ibu'];
$medsos = $_POST['medsos'];

// Cek apakah gambar diupload
if ($_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
    $gambar = $_FILES['gambar']['name'];
    move_uploaded_file($_FILES['gambar']['tmp_name'], 'img/' . $gambar);
} else {
    // Jika tidak ada gambar yang diupload, gunakan gambar lama
    $gambar = $_POST['gambar_lama'];
}

// Update data mahasiswa
$query = "UPDATE Mahasiswa SET nama = ?, tmpt_Lahir = ?, tgl_Lahir = ?, jekel = ?, jurusan = ?, email = ?, gambar = ?, alamat = ?, bapak = ?, ibu = ?, medsos = ? WHERE nim = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ssssssssssss", $nama, $tmpt_Lahir, $tgl_Lahir, $jekel, $jurusan, $email, $gambar, $alamat, $bapak, $ibu, $medsos, $nim);

if ($stmt->execute()) {
    echo "<script>
            alert('Data Mahasiswa berhasil diubah!');
            document.location.href = 'index.php';
        </script>";
} else {
    echo "<script>
            alert('Data Mahasiswa gagal diubah!');
        </script>";
}
?>
