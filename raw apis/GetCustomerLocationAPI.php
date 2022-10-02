<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['customer_id'])
    ) {
        $query = "SELECT customer_lat,customer_lng 
        FROM `tbl_customer` WHERE customer_id=? ";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $_POST['customer_id']);
        if($stmt->execute()){
            $stmt->store_result();
            $stmt->bind_result(
                $customer_lat,
                $customer_lng
            );
            while ($row = $stmt->fetch()) {
                $response['customer_lat'] =  $customer_lat;
                $response['customer_lng'] =  $customer_lng;
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
