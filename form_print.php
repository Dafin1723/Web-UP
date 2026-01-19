<!-- form_print.php -->
<div class="space-y-6">
  <div class="grid grid-cols-2 gap-4">
    <input type="text" name="nama" placeholder="Nama Lengkap" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500">
    <input type="email" name="email" placeholder="Email / WA" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500">
  </div>

  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-bold mb-2">Ukuran Kertas</label>
      <select name="ukuran_kertas" id="ukuran" required class="w-full px-4 py-3 border rounded-lg">
        <option value="A4">A4</option>
        <option value="Sertifikat">Sertifikat</option>
        <option value="A3+">A3+</option>
        <option value="Stiker F4">Stiker F4</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-bold mb-2">Warna</label>
      <select name="warna" id="warna" required class="w-full px-4 py-3 border rounded-lg">
        <option value="Hitam Putih">Hitam Putih</option>
        <option value="Warna">Warna</option>
      </select>
    </div>
  </div>

  <div id="jumlah_manual" class="hidden">
    <label class="block text-sm font-bold mb-2">Jumlah Lembar (manual)</label>
    <input type="number" name="jumlah_lembar" id="jumlah" min="1" value="1" class="w-full px-4 py-3 border rounded-lg">
    <small class="text-red-600">File bukan PDF → isi manual ya</small>
  </div>

  <div class="text-center text-2xl font-bold text-green-600">
    Total: Rp <span id="total">0</span>
  </div>

  <div>
    <label class="block text-sm font-bold mb-2">Metode Pembayaran</label>
    <select name="pembayaran" required class="w-full px-4 py-3 border rounded-lg">
      <option>Tunai di Tempat</option>
    </select>
  </div>

  <div>
    <label class="block text-sm font-bold mb-2">Upload File (PDF/Word/Gambar)</label>
    <input type="file" name="file_upload" id="file" required class="w-full px-4 py-3 border-2 border-dashed border-indigo-300 rounded-lg file:bg-indigo-600 file:text-white">
    <div id="info" class="mt-3 text-lg font-semibold"></div>
  </div>

  <div>
    <textarea name="deskripsi" rows="3" placeholder="Catatan (opsional)" class="w-full px-4 py-3 border rounded-lg"></textarea>
  </div>

  <button type="submit" id="btn" disabled class="w-full bg-gray-400 text-white py-4 rounded-lg text-xl cursor-not-allowed">
    Pilih file dulu...
  </button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
  const fileInput = document.getElementById('file');
  const info = document.getElementById('info');
  const totalSpan = document.getElementById('total');
  const btn = document.getElementById('btn');
  const jumlahManual = document.getElementById('jumlah_manual');

  fileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;

    const ext = file.name.split('.').pop().toLowerCase();
    
    if (ext === 'pdf') {
      jumlahManual.classList.add('hidden');
      info.innerHTML = 'Membaca PDF...';
      btn.disabled = true;
      btn.textContent = 'Sedang baca...';

      const reader = new FileReader();
      reader.onload = function(e) {
        pdfjsLib.getDocument(e.target.result).promise.then(pdf => {
          const pages = pdf.numPages;
          info.innerHTML = `<span class="text-green-600">✔ ${pages} halaman terdeteksi!</span>`;
          hitungHarga(pages);
          btn.disabled = false;
          btn.className = 'w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 rounded-lg text-xl';
          btn.textContent = 'Kirim Orderan 🚀';
        }).catch(() => info.innerHTML = '<span class="text-red-600">PDF rusak!</span>');
      };
      reader.readAsArrayBuffer(file);
    } else {
      jumlahManual.classList.remove('hidden');
      info.innerHTML = `<span class="text-orange-600">File ${ext.toUpperCase()} → isi jumlah lembar manual</span>`;
      document.getElementById('jumlah').oninput = () => hitungHarga(document.getElementById('jumlah').value || 1);
      hitungHarga(1);
      btn.disabled = false;
      btn.className = 'w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 rounded-lg text-xl';
      btn.textContent = 'Kirim Orderan 🚀';
    }
  });

  function hitungHarga(jumlah) {
    const ukuran = document.getElementById('ukuran').value;
    const warna = document.getElementById('warna').value;
    let harga = ukuran === 'A4' ? (warna === 'Warna' ? 1000 : 500) : 
                (ukuran === 'Sertifikat' ? 2000 : (ukuran === 'A3+' ? 10000 : 5000));
    totalSpan.textContent = (harga * jumlah).toLocaleString('id-ID');
  }

  document.getElementById('ukuran').onchange = document.getElementById('warna').onchange = () => {
    const pages = info.textContent.includes('halaman') ? parseInt(info.textContent.match(/\d+/)[0]) : document.getElementById('jumlah').value || 1;
    hitungHarga(pages);
  };
</script>
