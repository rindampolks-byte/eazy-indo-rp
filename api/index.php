<?php
// Konfigurasi Database
// Ubah bagian ini sesuai dengan kredensial database Anda yang mendukung Remote MySQL
$host = "139.99.52.209"; // Ganti dengan Host Database
$user = "u212_FYjWEThrqf"; // Ganti dengan Username Database
$pass = "BJLNPkGe@i!Srjhs^O9Ma@.c"; // Ganti dengan Password Database
$dbname = "s212_sadsasa"; // Ganti dengan Nama Database

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Membuat tabel otomatis jika belum ada
$tableQuery = "CREATE TABLE IF NOT EXISTS `whitelist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(24) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
)";
$conn->query($tableQuery);

$pesan = "";

// Proses ketika tombol daftar ditekan
if (isset($_POST['register'])) {
    $username = $conn->real_escape_string($_POST['username']);

    // Cek apakah username sudah ada
    $check = $conn->query("SELECT * FROM whitelist WHERE username = '$username'");
    if ($check->num_rows > 0) {
        $pesan = "<p style='color:red;'>Nama sudah terdaftar! Menunggu persetujuan admin.</p>";
    } else {
        // Insert data dengan status 0 (Belum di-approve)
        $sql = "INSERT INTO whitelist (username, status) VALUES ('$username', 0)";
        if ($conn->query($sql) === TRUE) {
            $pesan = "<p style='color:green;'>Registrasi berhasil! Silakan tunggu admin menyetujui akun Anda.</p>";
        } else {
            $pesan = "<p style='color:red;'>Terjadi kesalahan: " . $conn->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Whitelist Server</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; padding-top: 50px; }
        .container { background: white; width: 300px; margin: auto; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px 0px #0000001a; }
        input[type="text"] { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #28a745; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; width: 100%; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>

<div class="container">
    <h2>Daftar Whitelist</h2>
    <?php echo $pesan; ?>
    <form method="POST" action="">
        <label>Nama In-Game (Misal: Budi_Santoso)</label>
        <input type="text" name="username" required placeholder="Masukkan nama...">
        <button type="submit" name="register">Daftar Sekarang</button>
    </form>
</div>

</body>
</html>
