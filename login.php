<?php
require_once 'include/DB_Function.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['phone']) && isset($_POST['password'])) {
 
    // menerima parameter POST ( phone dan password )
    $phone = $_POST['phone'];
    $password = $_POST['password'];
 
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