<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();
$data = array();
$resultSet = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
if(isset($_POST['fk_id']) && isset($_POST['user_type'])){
    $query = "SELECT service_id ,service_name,service_price,service_discount,
    service_status
    FROM `tbl_service` WHERE fk_id=? AND user_type=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('is',$_POST['fk_id'],$_POST['user_type']);
    $stmt->execute();
    $stmt->store_result();
    $row = $stmt->num_rows() > 0;
    if ($row == 1) {
        $stmt->bind_result($id,$name, $price, $discount, $status);
        while ($row = $stmt->fetch()) {
            $data = array(
                'service_id' => $id,
                'service_name' => $name,
                'service_price'=>$price,
                'service_discount'=>$discount,
                'service_status'=>$status,
            );
            array_push($resultSet, $data);
        }
    }
        $response['error'] = false;
        $response['code'] = 200;
        $response['message'] = "Data Fetch Successfully";
        $response['records'] = $resultSet;
    } else {
        $response['error'] = true;
        $response['code'] = 404;
        $response['message'] = "Something went wrong";
    }
} else {
    $response['error'] = true;
    $response['code'] = 500;
    $response['message'] = "Invalid Request Method";
}
echo json_encode($response);