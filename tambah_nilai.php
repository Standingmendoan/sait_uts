<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Nilai Mahasiswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 500px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2 class="mt-5 mb-3">Tambah Nilai Mahasiswa</h2>
        <form id="nilaiForm">
            <div class="form-group">
                <label for="kode_mk">Mata Kuliah:</label>
                <select class="form-control" id="kode_mk" name="kode_mk" required>
                    <option value="">Pilih Mata Kuliah</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nilai">Nilai:</label>
                <input type="text" class="form-control" id="nilai" name="nilai" required>
            </div>
            <!-- Tambahkan input tersembunyi untuk menyimpan nilai nim -->
            <input type="hidden" id="nim" name="nim" value="trpl_001">
            <button type="button" onclick="submitNilai()" class="btn btn-primary">Simpan</button>
            <a href="detail_mahasiswa.php?nim=trpl_001" class="btn btn-secondary">Kembali</a> <!-- Tombol Kembali dengan nim -->
        </form>
    </div>

    <script>
        function fetchDataMataKuliah() {
            fetch('data_matakuliah.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const selectElem = document.getElementById('kode_mk');
                data.matakuliah.forEach(matakuliah => {
                    const optionElem = document.createElement('option');
                    optionElem.value = matakuliah.kode_mk;
                    optionElem.textContent = matakuliah.nama_mk;
                    selectElem.appendChild(optionElem);
                });
            })
            .catch(error => console.error('Error fetching mata kuliah:', error));
        }

        function submitNilai() {
            const form = document.getElementById('nilaiForm');
            const formData = new FormData(form);

            // Ubah nim pada form data sesuai dengan kebutuhan
            formData.set('nim', document.getElementById('nim').value);

            fetch('create_nilai.php', {
                method: 'POST',
                body: JSON.stringify(Object.fromEntries(formData)),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 1) {
                    alert(data.message);
                    // Clear form fields after successful submission
                    form.reset();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error submitting nilai:', error));
        }

        // Fetch data mata kuliah saat halaman dimuat
        fetchDataMataKuliah();
    </script>
</body>
</html>
