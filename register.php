<?php
include("inc_koneksi.php"); // Memasukkan file koneksi ke database

// Inisialisasi variabel
$host     = "localhost";
$username = "root";
$password = "";
$database = "multi_user";
$err      = "";


if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Validasi input
    if ($username == '' || $password == '') {
        $err .= "<li>Masukkan Username dan Password</li>";
    }

    // Jika tidak ada error, lanjutkan proses registrasi
    if (empty($err)) {
        // Cek apakah username sudah ada
        $sql1 = "SELECT * FROM admin WHERE username = ?";
        $stmt = mysqli_prepare($koneksi, $sql1);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_fetch_array($result)) {
            $err .= "<li>Username sudah digunakan!</li>";
        } else {
            // Hash password sebelum menyimpan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Masukkan user baru ke database
            $sql2 = "INSERT INTO admin (username, password) VALUES (?, ?)";
            $stmt2 = mysqli_prepare($koneksi, $sql2);
            mysqli_stmt_bind_param($stmt2, "ss", $username, $hashed_password);
            $success = mysqli_stmt_execute($stmt2);

            if ($success) {
                echo "<li>Pendaftaran berhasil! Silakan <a href='login.php'>login</a>.</li>";
            } else {
                $err .= "<li>Terjadi kesalahan saat mendaftarkan pengguna.</li>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <link href="favicon.ico" rel="icon">
  <link href="apple-touch-icon.png" rel="apple-touch-icon">
</head>
<body>
    <div id="app">
        <h1>Daftar</h1>
        <?php
            if (!empty($err)) {
            echo "<ul>$err</ul>";
        }   
        ?>
  
        <form action="" method="post">
            <input type="text" name="username" class="input" placeholder="Masukkan Username" value="<?php echo htmlspecialchars($username); ?>">
            <input type="password" name="password" class="input" placeholder="Masukkan Password">
            <input type="submit" name="register" class="input" value="Daftar">
        </form>
    </div>
</body>
</html>
