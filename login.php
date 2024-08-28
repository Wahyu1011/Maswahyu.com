<?php
include("inc_koneksi.php");

session_start();

$host     = "localhost";
$username = "root";
$password = "";
$database = "multi_user";
$err      = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    if ($username == '' || $password == '') {
        $err .= "<li>Masukkan Username dan Password</li>";
    }

    if (empty($err)) {
        $sql1 = "SELECT * FROM admin WHERE username = ?";
        $stmt = mysqli_prepare($koneksi, $sql1);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $er1 = mysqli_fetch_array($result);

        if ($er1) {
            if (!password_verify($password, $er1['password'])) {
                $err .= "<li>Username atau Password Salah!</li>";
            }
        } else {
            $err .= "<li>Username atau Password Salah!</li>";
        }
    }

    if (empty($err)) {
        $_SESSION['admin_username'] = $username;
        header("Location: ../../../maswahyu.com/index.html");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link href="favicon.ico" rel="icon">
  <link href="apple-touch-icon.png" rel="apple-touch-icon">
</head>
<body>
    <h1>Login</h1>
            <?php
            if (!empty($err)) {
            echo "<ul>$err</ul>";
        }   
        ?>
    <div id="app">
        <form action="" method="post">
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" class="input" placeholder="Masukkan Username">
            <input type="password" name="password" class="input" placeholder="Masukkan Password">
            <input type="submit" name="login" class="input" value="Masuk">
        </form>
    </div>
</body>
</html>
