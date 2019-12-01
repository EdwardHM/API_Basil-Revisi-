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
 
    // menerima parameter POST ( phone dan password )
    // $phone = $_POST['phone'];
    // $password = $_POST['password'];
    $phone = $data->phone;
    $password =$data->password;
 
    // get the user by phone and password
    // get user berdasarkan phone dan password
    $user = $db->getUserByphoneAndPassword($phone, $password);
 
    if ($user != false) {
        // user ditemukan
        $response["error"] = FALSE;
        $response["uid"] = $user["unique_id"];
        $response["user"]["nama"] = $user["nama"];
        $response["user"]["phone"] = $user["phone"];
        echo json_encode($response);
    } else {
        // user tidak ditemukan password/phone salah
        $response["error"] = TRUE;
        $response["error_msg"] = "Login gagal. Password/phone salah";
        echo json_encode($response);
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parameter (phone atau password) ada yang kurang";
    echo json_encode($response);
}
?>