<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['mechanic_id'])
        && isset($_POST['mechanic_status'])
       
    ) {

        $sql="UPDATE tbl_mechanic SET mechanic_status=? WHERE mechanic_id=?";
        $stmt=$con->prepare($sql);
        $stmt->bind_param('si',$_POST['mechanic_status'],$_POST['mechanic_id']);
        if($stmt->execute()){
            $response['error'] = false;
            $response['code'] = 200;
            $response['message'] = "Status Updated Successfully";
        }else{
            $response['error'] = true;
            $response['code'] = 404;
            $response['message'] = "Status Not Updated Successfully";
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
