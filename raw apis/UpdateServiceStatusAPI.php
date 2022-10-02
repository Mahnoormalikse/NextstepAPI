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
        && isset($_POST['service_status'])       
    ) {

        $sql="UPDATE tbl_service SET service_status=?
         WHERE service_id=?";
        $stmt=$con->prepare($sql);
        $stmt->bind_param('si',$_POST['service_status'],$_POST['service_id']);
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
