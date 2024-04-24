<?php
require_once "config.php";

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        if (!empty($_GET["nim"])) {
            $nim = $_GET["nim"];
            get_nilai_mahasiswa_by_nim($nim);
        } else {
            get_all_nilai_mahasiswa();
        }
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function get_all_nilai_mahasiswa()
{
    global $mysqli;
    $query = "SELECT mahasiswa.nim, mahasiswa.nama AS nama_mahasiswa, mahasiswa.alamat, matakuliah.nama_mk AS nama_matakuliah, perkuliahan.nilai 
              FROM mahasiswa
              INNER JOIN perkuliahan ON mahasiswa.nim = perkuliahan.nim
              INNER JOIN matakuliah ON perkuliahan.kode_mk = matakuliah.kode_mk";

    $data = array();
    $result = $mysqli->query($query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    if (!empty($data)) {
        $response = array(
            'status' => 1,
            'message' => 'Get All Nilai Mahasiswa Successfully.',
            'mahasiswa' => $data
        );
    } else {
        $response = array(
            'status' => 0,
            'message' => 'No nilai mahasiswa found.'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

function get_nilai_mahasiswa_by_nim($nim)
{
    global $mysqli;
    $query = "SELECT mahasiswa.nim, mahasiswa.nama AS nama_mahasiswa, mahasiswa.alamat, matakuliah.nama_mk AS nama_matakuliah, perkuliahan.nilai 
              FROM mahasiswa
              INNER JOIN perkuliahan ON mahasiswa.nim = perkuliahan.nim
              INNER JOIN matakuliah ON perkuliahan.kode_mk = matakuliah.kode_mk
              WHERE mahasiswa.nim = '$nim'";

    $data = array();
    $result = $mysqli->query($query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    if (!empty($data)) {
        $response = array(
            'status' => 1,
            'message' => 'Get Nilai Mahasiswa Successfully.',
            'mahasiswa' => $data
        );
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Mahasiswa with NIM '.$nim.' not found or has no nilai.'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
