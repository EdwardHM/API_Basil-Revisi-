<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'include/DB_Connect.php';
$db = new DB_Connect();
$connection = $db->connect();

$sql = "SELECT * from tbl_user ORDER BY nama ASC";
		
$query = mysqli_query($connection,$sql);

$response = array("error" => FALSE);
$response["records"]=array();
$response["num"] =mysqli_num_rows($query);

if(mysqli_num_rows($query) > 0){
    while ($row = mysqli_fetch_assoc($query))
    {           
                $user_list=array(
                "id" => $row['id'],
                "uuid_user" =>$row['uuid_user'],
                "nama" => $row['nama'],
                "phone" => $row['phone']
            );
    
            
            array_push($response["records"], $user_list);
            
    }
    
    echo json_encode($response);

} else{
    $response = array("error" => TRUE);
    $response["error_msg"] = "Anda belum memiliki history presensi";
    echo json_encode($response);
}

?>