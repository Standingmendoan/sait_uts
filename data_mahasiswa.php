<?php
require_once "config.php";

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        if (!empty($_GET["nim"])) {
            $nim = $_GET["nim"];
            get_mahasiswa_detail($nim);
        } else {
            get_all_data_mahasiswa();
        }
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function get_all_data_mahasiswa()
{
    global $mysqli;
    $query = "SELECT mahasiswa.nim, mahasiswa.nama, mahasiswa.alamat, matakuliah.nama_mk, perkuliahan.nilai 
              FROM mahasiswa
              LEFT JOIN perkuliahan ON mahasiswa.nim = perkuliahan.nim
              LEFT JOIN matakuliah ON perkuliahan.kode_mk = matakuliah.kode_mk";

    $data = array();
    $result = $mysqli->query($query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $nim = $row['nim'];
            if (!isset($data[$nim])) {
                $data[$nim] = array(
                    'nim' => $row['nim'],
                    'nama' => $row['nama'],
                    'alamat' => $row['alamat'],
                    'nilai_matakuliah' => array()
                );
            }

            if (!is_null($row['nama_mk'])) {
                $data[$nim]['nilai_matakuliah'][] = array(
                    'nama_mk' => $row['nama_mk'],
                    'nilai' => $row['nilai']
                );
            }
        }

        $mahasiswa_data = array_values($data);

        if (!empty($mahasiswa_data)) {
            $response = array(
                'status' => 1,
                'message' => 'Get All Mahasiswa Data Successfully.',
                'mahasiswa' => $mahasiswa_data
            );
        } else {
            $response = array(
                'status' => 0,
                'message' => 'No mahasiswa data found.'
            );
        }
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Error fetching mahasiswa data from database.'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

function fetchDataMataKuliah() {
    global $mysqli;
    $data = array();
    $query = "SELECT kode_mk, nama_mk FROM matakuliah";
    $result = $mysqli->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        // If query failed, return empty array
        return array();
    }

    return $data;
}

function get_mahasiswa_detail($nim)
{
    global $mysqli;
    $query = "SELECT mahasiswa.nim, mahasiswa.nama, mahasiswa.alamat, matakuliah.nama_mk, perkuliahan.nilai 
              FROM mahasiswa
              LEFT JOIN perkuliahan ON mahasiswa.nim = perkuliahan.nim
              LEFT JOIN matakuliah ON perkuliahan.kode_mk = matakuliah.kode_mk
              WHERE mahasiswa.nim = '$nim'";

    $data = array();
    $result = $mysqli->query($query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $nim = $row['nim'];
            if (!isset($data[$nim])) {
                $data[$nim] = array(
                    'nim' => $row['nim'],
                    'nama' => $row['nama'],
                    'alamat' => $row['alamat'],
                    'nilai_matakuliah' => array()
                );
            }

            if (!is_null($row['nama_mk'])) {
                $data[$nim]['nilai_matakuliah'][] = array(
                    'nama_mk' => $row['nama_mk'],
                    'nilai' => $row['nilai']
                );
            }
        }

        $mahasiswa_data = array_values($data);

        if (!empty($mahasiswa_data)) {
            $response = array(
                'status' => 1,
                'message' => 'Get Mahasiswa Detail Successfully.',
                'mahasiswa' => $mahasiswa_data[0]
            );
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Mahasiswa not found with NIM: ' . $nim
            );
        }
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Error fetching mahasiswa detail from database.'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
