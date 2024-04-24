<?php
require_once "config.php";

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'DELETE':
        if (!empty($_GET["nim"]) && !empty($_GET["kode_mk"])) {
            $nim = $_GET["nim"];
            $kode_mk = $_GET["kode_mk"];
            delete_data($nim, $kode_mk);
        } else {
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(array("status" => 0, "message" => "Missing parameters"));
        }
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
    }

    function delete_data($nim, $kode_mk)
    {
        global $mysqli;
        $query = "DELETE FROM perkuliahan 
                  WHERE nim='$nim' AND kode_mk='$kode_mk'";
        if ($mysqli->query($query)) {
            echo json_encode(array("status" => 1, "message" => "Data deleted successfully"));
        } else {
            header("HTTP/1.0 500 Internal Server Error");
            echo json_encode(array("status" => 0, "message" => "Failed to delete data"));
        }
    }
?>
