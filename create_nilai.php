<?php
require_once "config.php";

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'POST':
        create_nilai_mahasiswa();
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function create_nilai_mahasiswa()
{
    global $mysqli;

    // Decode data JSON dari input
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data["nim"]) && !empty($data["kode_mk"])) {
        $nim = $data["nim"];
        $kode_mk = $data["kode_mk"];
        
        // Periksa apakah kunci "nilai" ada dalam data JSON
        if (array_key_exists("nilai", $data)) {
            $nilai = $data["nilai"];

            // Periksa panjang nilai
            if (strlen($nilai) <= 10) { // Sesuaikan dengan panjang VARCHAR yang Anda tentukan
                $query = "INSERT INTO perkuliahan (nim, kode_mk, nilai) VALUES (?, ?, ?)";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("sss", $nim, $kode_mk, $nilai);

                if ($stmt->execute()) {
                    $response = array(
                        'status' => 1,
                        'message' => 'Nilai Mahasiswa Added Successfully.'
                    );
                } else {
                    $response = array(
                        'status' => 0,
                        'message' => 'Failed to add Nilai Mahasiswa.'
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
                'message' => 'Parameter "nilai" is missing.'
            );
        }
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Parameters nim or kode_mk are missing.'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
