<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'include/DB_Connect.php';
$db = new DB_Connect();
$connection = $db->connect();

$response = array("error" => FALSE);
$data = json_decode(file_get_contents("php://input"));

if (!is_null($data)) {

    $user_id = $data->user_id;

    $sql = "SELECT * FROM tbl_kehadiran WHERE uuid_user = '".$user_id."'";
    $sql2 = "SELECT uuid_user AS uuid_user, DATE_FORMAT(created_at, '%m-%Y') 
    AS Month,COUNT(case keterangan when 'presensi' then 1 else null end) AS Presensi, 
    COUNT(case keterangan when 'dinas' then 1 else null end) AS Dinas, 
    COUNT(case keterangan when 'izin' then 1 else null end) AS Izin FROM tbl_kehadiran 
    WHERE uuid_user = '".$user_id."' GROUP BY DATE_FORMAT(created_at, '%m-%Y')";

$query = mysqli_query($connection,$sql);
$query2 = mysqli_query($connection,$sql2);

$response = array("error" => FALSE);
$response["total_keterangan"]=array();

if(mysqli_num_rows($query) > 0){  
    while ($row = mysqli_fetch_assoc($query2))
    {         

            $total_keterangan=array(
                "when" => $row['Month'],
                "jumlah_presensi" => $row['Presensi'],
                "jumlah_dinas" => $row['Dinas'],
                "jumlah_izin" => $row['Izin']
            );
    
            
            array_push($response["total_keterangan"], $total_keterangan);
            
    }
    echo json_encode($response);

} else{
    $response = array("error" => TRUE);
    $response["error_msg"] = "Anda belum memiliki history presensi";
    echo json_encode($response);
}

} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parameter ada yang kurang";
    echo json_encode($response);
}
?>