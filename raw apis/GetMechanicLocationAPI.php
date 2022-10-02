<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['fk_mechanic_id'])
    ) {
        $query = "SELECT cl_latitude,cl_longitude 
        FROM `tbl_current_location` WHERE fk_mechanic_id=? ";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $_POST['fk_mechanic_id']);
        if($stmt->execute()){
            $stmt->store_result();
            $stmt->bind_result(
                $cl_latitude,
                $cl_longitude
            );
            while ($row = $stmt->fetch()) {
                $response['cl_latitude'] =  $cl_latitude;
                $response['cl_longitude'] =  $cl_longitude;
            }
            $response['error'] = false;
            $response['code'] = 200;
            $response['message'] = "Location fetch Successfull";
    
        }else{
            $response['error'] = true;
            $response['code'] = 404;
            $response['message'] = "Failed to Get Location";
        }
    } else {
        $response['error'] = true;
        $response['code'] = 404;
        $response['message'] = "Required fields Missing";
    }
} else {
    $response['error'] = true;
    $response['code'] = 500;
    $response['message'] = "Invalid Request Method";
}
echo json_encode($response);
