<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <!-- Font Google -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <!-- animasi Css Aos -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <!-- My CSS -->
    <link rel="stylesheet" href="css/style.css">

    <title>Ubah Data Mahasiswa</title>
</head>
<body background="img/bg/bck.png">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark text-uppercase">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sistem Admin Data Siswa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Close Navbar -->

    <!-- Container -->
    <div class="container mt-5">
        <h3 class="fw-bold text-uppercase">Ubah Data Mahasiswa</h3>
        <hr>
        <?php
        require 'function.php';
        // Mengambil NIM dari parameter GET
        $nim = $_GET['nim'];
        
        // Mengambil data mahasiswa dari database
        $stmt = $koneksi->prepare("SELECT * FROM Mahasiswa WHERE nim = ?");
        $stmt->bind_param("s", $nim);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        ?>
        <form action="update.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="nim" value="<?php echo htmlspecialchars($row['nim']); ?>">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control w-50" id="nama" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="tmpt_Lahir" class="form-label">Tempat Lahir</label>
                <input type="text" class="form-control w-50" id="tmpt_Lahir" name="tmpt_Lahir" value="<?php echo htmlspecialchars($row['tmpt_Lahir']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="tgl_Lahir" class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control w-50" id="tgl_Lahir" name="tgl_Lahir" value="<?php echo htmlspecialchars($row['tgl_Lahir']); ?>" required>
            </div>
            <div class="mb-3">
            
                
    <label>Jenis Kelamin</label>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="jekel" id="Laki-Laki" value="Laki-Laki" <?php echo ($row['jekel'] == 'Laki-Laki') ? 'checked' : ''; ?>>
        <label class="form-check-label" for="Laki-Laki">Laki-Laki</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="jekel" id="Perempuan" value="Perempuan" <?php echo ($row['jekel'] == 'Perempuan') ? 'checked' : ''; ?>>
        <label class="form-check-label" for="Perempuan">Perempuan</label>
    </div>
</div>
            <div class="mb-3">
                <label for="jurusan" class="form-label">Jurusan</label>
                <select class="form-select w-50" id="jurusan" name="jurusan" required>
                    <option disabled value>Pilih Jurusan</option>
                    <option value="Informatika" <?php echo ($row['jurusan'] == 'Informatika') ? 'selected' : ''; ?>>Informatika</option>
                    <option value="Teknik Jaringan Akses" <?php echo ($row['jurusan'] == 'Teknik Jaringan Akses') ? 'selected' : ''; ?>>Teknik Jaringan Akses</option>
                    <option value="Teknik Komputer dan Jaringan" <?php echo ($row['jurusan'] == 'Teknik Komputer dan Jaringan') ? 'selected' : ''; ?>>Teknik Komputer dan Jaringan</option>
                    <option value="Multimedia" <?php echo ($row['jurusan'] == 'Multimedia') ? 'selected' : ''; ?>>Multimedia</option>
                    <option value="Rekayasa Perangkat Lunak" <?php echo ($row['jurusan'] == 'Rekayasa Perangkat Lunak') ? 'selected' : ''; ?>>Rekayasa Perangkat Lunak</option>
                    <option value="Geomatika" <?php echo ($row['jurusan'] == 'Geomatika') ? 'selected' : ''; ?>>Geomatika</option>
                    <option value="Mesin" <?php echo ($row['jurusan'] == 'Mesin') ? 'selected' : ''; ?>>Mesin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-Mail</label>
                <input type="email" class="form-control w-50" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar</label>
                <input type="file" class="form-control w-50" id="gambar" name="gambar">
                <img src="img/<?php echo htmlspecialchars($row['gambar']); ?>" width="100" alt="Gambar">
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control w-50" id="alamat" name="alamat" rows="5" required><?php echo htmlspecialchars($row['alamat']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="bapak" class="form-label">Nama Bapak</label>
                <input type="text" class="form-control w-50" id="bapak" name="bapak" value="<?php echo htmlspecialchars($row['bapak']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="ibu" class="form-label">Nama Ibu</label>
                <input type="text" class="form-control w-50" id="ibu" name="ibu" value="<?php echo htmlspecialchars($row['ibu']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="medsos" class="form-label">ID Medsos</label>
                <input type="text" class="form-control w-50" id="medsos" name="medsos" value="<?php echo htmlspecialchars($row['medsos']); ?>" required>
            </div>
            <hr>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
        <?php
        } else {
            echo "<p>Data mahasiswa tidak ditemukan.</p>";
        }
        ?>
    </div>
    <!-- Close Container -->

    <!-- Footer -->
    <div class="container-fluid">
        <div class="row bg-dark text-white text-center">
            <div class="col my-2" id="about">
                <br><br><br>
                <h4 class="fw-bold text-uppercase">About</h4>
                <p>Pembuat: 1. Farhan Ade Atalarik (2135038)</p>
            </div>
        </div>
    </div>
    <!-- Close Footer -->

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- animasi gsap-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/TextPlugin.min.js"></script>
    <script>
        gsap.registerPlugin(TextPlugin);
        gsap.to('.Tambah_data', {
            duration: 2,
            delay: 1,
            text: '<i class="bi bi-person-plus-fill"></i>Ubah Data Mahasiswa :)'
        });
        gsap.from('.navbar', {
            duration: 1,
            y: '-100%',
            opacity: 0,
            ease: 'bounce',
        });
    </script>
</body>
</html>
