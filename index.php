<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <h2 class="mt-5 mb-3">Dashboard</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="mahasiswaTable">
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function(){
            loadMahasiswaData();
        });

        function loadMahasiswaData() {
            $.ajax({
                url: 'data_mahasiswa.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if(data.status == 1) {
                        var tableContent = '';
                        $.each(data.mahasiswa, function(index, item) {
                            tableContent += '<tr>';
                            tableContent += '<td>' + item.nim + '</td>';
                            tableContent += '<td>' + item.nama + '</td>';
                            tableContent += '<td>' + item.alamat + '</td>';
                            tableContent += '<td><button class="btn btn-primary" onclick="redirectToDetailMahasiswa(\'' + item.nim + '\')">Lihat Mata Kuliah</button></td>';
                            tableContent += '</tr>';
                        });
                        $('#mahasiswaTable').html(tableContent);
                    } else {
                        $('#mahasiswaTable').html('<tr><td colspan="4">Data not found</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        function redirectToDetailMahasiswa(nim) {
            // Redirect to detail mahasiswa page with query parameter nim
            window.location.href = 'detail_mahasiswa.php?nim=' + encodeURIComponent(nim);
        }
    </script>
</body>
</html>
