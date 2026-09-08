const mysql = require('mysql2/promise');

export default async function handler(req, res) {
    let message = '';

    // Ganti dengan kredensial database Sampryzen Anda
    const dbConfig = {
        host: '139.99.52.209',
        user: 'u212_FYjWETHrqf',
        password: 'BJLNPkGe@i!Srjhs^O9Ma@.c',
        database: 's212_sadsasa'
    };

    try {
        const connection = await mysql.createConnection(dbConfig);
        
        // Buat tabel otomatis jika belum ada
        await connection.execute(`
            CREATE TABLE IF NOT EXISTS whitelist (
              id INT AUTO_INCREMENT PRIMARY KEY,
              username VARCHAR(24) NOT NULL UNIQUE,
              status INT DEFAULT 0
            )
        `);

        // Proses jika tombol Daftar ditekan
        if (req.method === 'POST') {
            const username = req.body.username;
            if (username) {
                const [rows] = await connection.execute('SELECT * FROM whitelist WHERE username = ?', [username]);
                if (rows.length > 0) {
                    message = "<p style='color:red;'>Nama sudah terdaftar! Menunggu persetujuan admin.</p>";
                } else {
                    await connection.execute('INSERT INTO whitelist (username, status) VALUES (?, 0)', [username]);
                    message = "<p style='color:green;'>Registrasi berhasil! Silakan tunggu admin menyetujui akun Anda.</p>";
                }
            }
        }
        await connection.end();
    } catch (error) {
        message = `<p style='color:red;'>Terjadi kesalahan Database: ${error.message}</p>`;
    }

    // Tampilan HTML Website
    const html = `
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Registrasi Whitelist Server</title>
        <style>
            body { font-family: Arial; background: #f4f4f4; text-align: center; padding-top: 50px; }
            .container { background: white; width: 300px; margin: auto; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            input { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; }
            button { background: #28a745; color: white; border: none; padding: 10px; cursor: pointer; border-radius: 4px; width: 100%; }
            button:hover { background: #218838; }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Daftar Whitelist</h2>
            ${message}
            <form method="POST" action="/api/index">
                <label>Nama In-Game (Misal: Budi_Santoso)</label>
                <input type="text" name="username" required placeholder="Masukkan nama...">
                <button type="submit">Daftar Sekarang</button>
            </form>
        </div>
    </body>
    </html>
    `;

    res.setHeader('Content-Type', 'text/html');
    res.status(200).send(html);
}
