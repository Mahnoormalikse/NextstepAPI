<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['service_id'])
        && isset($_POST['service_name'])
        && isset($_POST['service_price'])
        && isset($_POST['service_discount'])
       
    ) {

        $sql="UPDATE tbl_service SET service_name=?, service_price=?, service_discount=?
         WHERE service_id=?";
        $stmt=$con->prepare($sql);
        $stmt->bind_param('siii',$_POST['service_name'],$_POST['service_price'],$_POST['service_discount']
        ,$_POST['service_id']);
        if($stmt->execute()){
            $response['error'] = false;
            $response['code'] = 200;
            $response['message'] = "Service Updated Successfully";
        }else{
            $response['error'] = true;
            $response['code'] = 404;
            $response['message'] = "Service Not Updated Successfully";
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
