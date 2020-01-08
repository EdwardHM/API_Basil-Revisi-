<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'include/DB_Function.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
$data = json_decode(file_get_contents("php://input"));
 
if (!is_null($data)) {
 
    $nama = $data->user_name;
    $status = $data->valid;
    $waktu = $data->time;
 
    $validasi = $db->updateValidasi($nama, $waktu, $status);
 
    if ($validasi != false) {
        $response["error"] = FALSE;
        echo json_encode($response);
    } else {
        $response["error"] = TRUE;
        $response["error_msg"] = "Gagal Memperbarui Status";
        echo json_encode($response);
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parameter (nama, status atau waktu) ada yang kurang";
    echo json_encode($response);
}
?>