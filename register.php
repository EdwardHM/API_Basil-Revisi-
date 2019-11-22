<?php
 
require_once 'include/DB_Function.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['nama']) && isset($_POST['phone']) && isset($_POST['password'])) {
 
    // menerima parameter POST ( nama, phone, password )
    $nama = $_POST['nama'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
 
    // Cek jika user ada dengan phone yang sama
    if ($db->isUserExisted($phone)) {
        // user telah ada
        $response["error"] = TRUE;
        $response["error_msg"] = "User telah ada dengan phone " . $phone;
        echo json_encode($response);
    } else {
        // buat user baru
        $user = $db->simpanUser($nama, $phone, $password);
        if ($user) {
            // simpan user berhasil
            $response["error"] = FALSE;
            $response["uid"] = $user["unique_id"];
            $response["user"]["nama"] = $user["nama"];
            $response["user"]["phone"] = $user["phone"];
            echo json_encode($response);
        } else {
            // gagal menyimpan user
            $response["error"] = TRUE;
            $response["error_msg"] = "Terjadi kesalahan saat melakukan registrasi";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parameter (nama, phone, atau password) ada yang kurang";
    echo json_encode($response);
}
?>