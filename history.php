<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'include/DB_Connect.php';
$db = new DB_Connect();
$connection = $db->connect();

$sql = "SELECT * FROM tbl_kehadiran";
		
$query = mysqli_query($connection,$sql);

$response = array("error" => FALSE);
$response["records"]=array();
$response["num"] =mysqli_num_rows($query);

if(mysqli_num_rows($query) > 0){
    while ($row = mysqli_fetch_assoc($query))
    {           
                $history_list=array(
                "id" => $row['id'],
                "uuid" => $row['uuid'],
                "uuid_user" =>$row['uuid_user'],
                "keterangan" => $row['keterangan'],
                "is_in_office" => $row['is_in_office'],
                "lokasi" => $row['lokasi'],
                "created_at" => $row['created_at']
            );
    
            
            array_push($response["records"], $history_list);
            
    }
    
    echo json_encode($response);

} else{
    $response = array("error" => TRUE);
    $response["error_msg"] = "Anda belum memiliki history presensi";
    echo json_encode($response);
}

?>