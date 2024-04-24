<?php
require_once "config.php";

$query = "SELECT kode_mk, nama_mk FROM matakuliah";
$result = $mysqli->query($query);

if ($result && $result->num_rows > 0) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    $response = array(
        'status' => 1,
        'message' => 'Get Mata Kuliah Data Successfully.',
        'matakuliah' => $data
    );
} else {
    $response = array(
        'status' => 0,
        'message' => 'No mata kuliah data found.'
    );
}

header('Content-Type: application/json');
echo json_encode($response);
?>
