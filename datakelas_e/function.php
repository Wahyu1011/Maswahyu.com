<?php
// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "mydata");

// Membuat fungsi query dalam bentuk array
function query($query)
{
    global $koneksi;

    $result = mysqli_query($koneksi, $query);
    $rows = [];

    // Mengambil semua data dalam bentuk array
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// Membuat fungsi tambah
function tambah($data)
{
    global $koneksi;

    $nim = htmlspecialchars($data['nim']);
    $nama = htmlspecialchars($data['nama']);
    $tmpt_Lahir = htmlspecialchars($data['tmpt_Lahir']);
    $tgl_Lahir = $data['tgl_Lahir'];
    $jekel = $data['jekel'];
    $jurusan = $data['jurusan'];
    $email = htmlspecialchars($data['email']);
    $gambar = upload();
    $alamat = htmlspecialchars($data['alamat']);
    $medsos = htmlspecialchars($data['medsos']);
    $bapak = htmlspecialchars($data['bapak']);
    $ibu = htmlspecialchars($data['ibu']);

    if (!$gambar) {
        return false;
    }

    $sql = "INSERT INTO mahasiswa VALUES ('$nim','$nama','$tmpt_Lahir','$tgl_Lahir','$jekel','$jurusan','$email','$gambar','$alamat','$bapak','$ibu','$medsos')";

    mysqli_query($koneksi, $sql);

    return mysqli_affected_rows($koneksi);
}

// Membuat fungsi hapus
function hapus($nim)
{
    global $koneksi;

    mysqli_query($koneksi, "DELETE FROM mahasiswa WHERE nim = '$nim'");
    return mysqli_affected_rows($koneksi);
}

// Membuat fungsi ubah
function ubah($data)
{
    global $koneksi;

    $nim = $data['nim'];
    $nama = htmlspecialchars($data['nama']);
    $tmpt_Lahir = htmlspecialchars($data['tmpt_Lahir']);
    $tgl_Lahir = $data['tgl_Lahir'];
    $jekel = $data['jekel'];
    $jurusan = $data['jurusan'];
    $email = htmlspecialchars($data['email']);
    $alamat = htmlspecialchars($data['alamat']);
    $medsos = htmlspecialchars($data['medsos']);
    $bapak = htmlspecialchars($data['bapak']);
    $ibu = htmlspecialchars($data['ibu']);

    $gambarLama = $data['gambarLama'];

    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
    }

    $sql = "UPDATE mahasiswa SET 
            nama = '$nama', 
            tmpt_Lahir = '$tmpt_Lahir', 
            tgl_Lahir = '$tgl_Lahir', 
            jekel = '$jekel', 
            jurusan = '$jurusan', 
            email = '$email', 
            gambar = '$gambar', 
            alamat = '$alamat', 
            bapak = '$bapak', 
            ibu = '$ibu', 
            medsos = '$medsos' 
            WHERE nim = '$nim'";

    mysqli_query($koneksi, $sql);

    return mysqli_affected_rows($koneksi);
}

// Membuat fungsi upload gambar
function upload()
{
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    if ($error === 4) {
        echo "<script>alert('Pilih gambar terlebih dahulu!');</script>";
        return false;
    }

    $extValid = ['jpg', 'jpeg', 'png'];
    $ext = strtolower(end(explode('.', $namaFile)));

    if (!in_array($ext, $extValid)) {
        echo "<script>alert('Yang anda upload bukanlah gambar!');</script>";
        return false;
    }

    if ($ukuranFile > 3000000) {
        echo "<script>alert('Ukuran gambar anda terlalu besar!');</script>";
        return false;
    }

    $namaFileBaru = uniqid() . '.' . $ext;

    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

    return $namaFileBaru;
}

// Membuat fungsi registrasi
function registrasi($data)
{
    global $koneksi;

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($koneksi, $data["password"]);
    $password2 = mysqli_real_escape_string($koneksi, $data["password2"]);

    $result = mysqli_query($koneksi, "SELECT username FROM user WHERE username = '$username'");

    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('username sudah terdaftar');</script>";
        return false;
    }

    if ($password !== $password2) {
        echo "<script>alert('konfirmasi password tidak sesuai');</script>";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($koneksi, "INSERT INTO user VALUES('', '$username', '$password')");

    return mysqli_affected_rows($koneksi);
}
?>
