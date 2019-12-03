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

if(!is_null($data)){
    $user_id = $data->user_id;
    $keterangan = $data->keterangan ;
    $is_in_office = $data->is_in_office;
    $lokasi = $data->lokasi;
   
    // buat kehadiran baru
    $kehadiran = $db->simpanKehadiran($user_id, $keterangan, $is_in_office, $lokasi);
    if ($kehadiran) {
        // simpan kehadiran berhasil
        $response["error"] = FALSE;
        $response["success"] = "Berhasil menyimpan data";
        echo json_encode($response);
    } else {
        // gagal menyimpan user
        $response["error"] = TRUE;
        $response["error_msg"] = "Terjadi kesalahan saat melakukan penyimpanan data";
        echo json_encode($response);
    }
}

else {
    $response["error"] = TRUE;
    $response["error_msg"] = "ada yang kurang";
    echo json_encode($response);
}
 
?>