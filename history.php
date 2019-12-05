<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'include/DB_Function.php';
$db = new DB_Functions();
$response = array("error" => FALSE);

$stmt = $db->read();
// $num = $stmt->rowCount();
 
// check if more than 0 record found
if(0==0){
 
    // products array
    $history_arr=array("error" => FALSE);
    $history_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $history_list=array(
            "id" => $id,
            "uuid" => $uuid,
            "uuid_user" => $uuid_user,
            "keterangan" => $keterangan,
            "is_in_office" => $is_in_office,
            "lokasi" => $lokasi,
            "created_at" => $created_at
        );
 
        array_push($history_arr["records"], $history_list);
    }
    echo json_encode($history_arr);
}

?>