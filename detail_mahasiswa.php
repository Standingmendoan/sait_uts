<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Mahasiswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 800px;
            margin: 0 auto;
        }
        table {
            width: 100%;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2 class="mt-5 mb-3">Detail Mahasiswa</h2>
        <div id="mahasiswaDetails"></div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script>
            $(document).ready(function(){
                // Ambil NIM dari parameter URL
                var nim = getUrlParameter('nim');
                if (nim !== undefined) {
                    // Jika NIM ditemukan, panggil fungsi untuk mengambil detail mahasiswa
                    loadMahasiswaDetail(nim);
                } else {
                    // Jika NIM tidak ditemukan, tampilkan pesan
                    $('#mahasiswaDetails').html('<p>NIM tidak diberikan</p>');
                }
            });

            // Fungsi untuk mengambil detail mahasiswa berdasarkan NIM
            function loadMahasiswaDetail(nim) {
                $.ajax({
                    url: 'data_mahasiswa.php?nim=' + nim,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if(data.status == 1 && data.mahasiswa) {
                            // Jika data ditemukan, tampilkan detail mahasiswa
                            displayMahasiswaDetails(data.mahasiswa);
                        } else {
                            // Jika data tidak ditemukan, tampilkan pesan
                            $('#mahasiswaDetails').html('<p>Data mahasiswa tidak ditemukan atau terjadi kesalahan</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            // Fungsi untuk menampilkan detail mahasiswa
            function displayMahasiswaDetails(mahasiswa) {
                // Buat tabel untuk menampilkan detail mahasiswa
                var tableContent = '<table>';
                tableContent += '<tr><th>NIM</th><td>' + mahasiswa.nim + '</td></tr>';
                tableContent += '<tr><th>Nama</th><td>' + mahasiswa.nama + '</td></tr>';
                tableContent += '<tr><th>Alamat</th><td>' + mahasiswa.alamat + '</td></tr>';
                tableContent += '</table>';

                // Tambahkan daftar mata kuliah dan nilai
                tableContent += '<h4 class="mt-4">Nilai Mata Kuliah</h4>';
                if (mahasiswa.nilai_matakuliah && mahasiswa.nilai_matakuliah.length > 0) {
                    tableContent += '<table>';
                    tableContent += '<tr><th>Nama Mata Kuliah</th><th>Nilai</th><th>Action</th></tr>';
                    $.each(mahasiswa.nilai_matakuliah, function(index, nilai) {
                        tableContent += '<tr>';
                        tableContent += '<td>' + nilai.nama_mk + '</td>';
                        tableContent += '<td>' + nilai.nilai + '</td>';
                        // Tambahkan tombol "Delete" dengan onclick event yang memanggil fungsi deleteNilai
                        tableContent += '<td><button class="btn btn-danger btn-sm" onclick="deleteNilai(\'' + mahasiswa.nim + '\', \'' + nilai.nama_mk + '\')">Delete</button>';
                        // Tambahkan tombol "Update" dengan onclick event yang memanggil fungsi updateNilai
                        tableContent += '<button class="btn btn-info btn-sm ml-1" onclick="updateNilai(\'' + mahasiswa.nim + '\', \'' + nilai.nama_mk + '\', \'' + nilai.nilai + '\')">Update</button></td>';
                        tableContent += '</tr>';
                    });
                    tableContent += '</table>';
                } else {
                    tableContent += '<p class="text-muted">Belum ada data nilai untuk mahasiswa ini.</p>';
                }

                // Tambahkan tombol tambah nilai baru
                tableContent += '<button class="btn btn-primary mt-3 mr-3" onclick="tambahNilai(\'' + mahasiswa.nim + '\')">Tambah Nilai Baru</button>';

                // Tambahkan tombol kembali
                tableContent += '<a href="index.php" class="btn btn-secondary mt-3">Kembali</a>';

                // Tampilkan tabel dalam elemen dengan id 'mahasiswaDetails'
                $('#mahasiswaDetails').html(tableContent);
            }

            // Fungsi untuk mendapatkan parameter dari URL
            function getUrlParameter(name) {
                name = name.replace(/[[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(location.search);
                return results === null ? undefined : decodeURIComponent(results[1].replace(/\+/g, ' '));
            }

            // Fungsi untuk menambahkan nilai baru
            function tambahNilai(nim) {
                // Redirect ke halaman tambah nilai dengan menyertakan NIM
                window.location.href = 'tambah_nilai.php?nim=' + encodeURIComponent(nim);
            }

            // Fungsi untuk menghapus nilai
            function deleteNilai(nim, nama_mk) {
                if (confirm('Apakah Anda yakin ingin menghapus nilai untuk mata kuliah ' + nama_mk + '?')) {
                    $.ajax({
                        url: 'delete_nilai.php',
                        type: 'POST',
                        dataType: 'json',
                        data: { nim: nim, nama_mk: nama_mk },
                        success: function(data) {
                            if (data.status == 1) {
                                // Jika penghapusan berhasil, muat ulang detail mahasiswa
                                loadMahasiswaDetail(nim);
                            } else {
                                alert('Gagal menghapus nilai: ' + data.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                }
            }

            // Fungsi untuk mengupdate nilai
            function updateNilai(nim, kode_mk, nilai) {
                // Redirect ke halaman update nilai dengan menyertakan NIM, kode MK, dan nilai
                window.location.href = 'update_mahasiswa.php?nim=' + encodeURIComponent(nim) + '&kode_mk=' + encodeURIComponent(kode_mk) + '&nilai=' + encodeURIComponent(nilai);
            }
        </script>
    </div>
</body>
</html>
