<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Nilai Mahasiswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Update Nilai Mahasiswa</h2>
        <?php
        // Check if nim and nama_mk are received from the URL parameters
        if (isset($_GET['nim']) && isset($_GET['nama_mk'])) {
            $nim = $_GET['nim'];
            $nama_mk = $_GET['nama_mk'];
        } else {
            echo '<div class="alert alert-danger" role="alert">NIM atau Nama Mata Kuliah tidak ditemukan.</div>';
            exit;
        }
        ?>
        <form id="updateForm">
            <div class="form-group">
                <label for="nilai">Nilai:</label>
                <input type="text" class="form-control" id="nilai" name="nilai">
            </div>
            <input type="hidden" name="nim" value="<?php echo $nim; ?>">
            <input type="hidden" name="nama_mk" value="<?php echo $nama_mk; ?>">
            <button type="submit" class="btn btn-primary">Update Nilai</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#updateForm').submit(function(e){
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: 'update_nilai.php',
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    success: function(data) {
                        if(data.status == 1) {
                            alert(data.message);
                            window.location.href = 'detail_mahasiswa.php?nim=<?php echo $nim; ?>'; // Redirect to the detail mahasiswa page after update
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
</body>
</html>
