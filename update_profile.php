<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'include/DB_Function.php';
$db = new DB_Functions();
 
// json response array
$data = json_decode(file_get_contents("php://input"));
 
if (!is_null($data)) {

    $user_id = $data->user_id;
    $phone = $data->telp_Baru;
    $nama = $data->nama_Baru;

    // if ($db->isUserExisted($phone)) {
    //     // user telah ada
    //     $response["error"] = TRUE;
    //     $response["error_msg"] = "User telah ada dengan phone " . $phone;
    //     echo json_encode($response);
    // } else {

        $change = $db->updateProf($user_id,$phone,$nama);
 
        if ($change != false) {
            $response["error"] = FALSE;
            echo json_encode($response);
        } else {
            $response["error"] = TRUE;
            $response["error_msg"] = "Gagal Memperbarui";
            echo json_encode($response);
        }
    // }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parameter ada yang kurang";
    echo json_encode($response);
}
?>