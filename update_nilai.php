<?php
require_once "config.php";

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'POST':
        update_nilai_mahasiswa();
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function update_nilai_mahasiswa()
{
    global $mysqli;

    // Decode data JSON dari input
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data["nim"]) && !empty($data["kode_mk"]) && isset($data["nilai"])) {
        $nim = $data["nim"];
        $kode_mk = $data["kode_mk"];
        $nilai = $data["nilai"];
        
        // Periksa apakah panjang nilai sesuai dengan yang diinginkan
        if (strlen($nilai) <= 10) { // Sesuaikan dengan panjang VARCHAR yang Anda tentukan
            $query = "UPDATE perkuliahan SET nilai = '$nilai' WHERE nim = '$nim' AND kode_mk = '$kode_mk'";

            if ($mysqli->query($query)) {
                $response = array(
                    'status' => 1,
                    'message' => 'Nilai Mahasiswa Updated Successfully.'
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'Failed to update Nilai Mahasiswa.'
                );
            }
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Length of nilai exceeds maximum allowed.'
            );
        }
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Parameters nim, kode_mk, or nilai are missing.'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
