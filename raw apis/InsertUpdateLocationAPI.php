<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['cl_latitude'])
        && isset($_POST['cl_longitude'])
        && isset($_POST['fk_mechanic_id'])
    ) {

        $db = new DbOperations();
        $result = $db->InsertLocation($_POST['cl_latitude'], $_POST['cl_longitude'], $_POST['fk_mechanic_id']);
        if($result==0){
            $sql="UPDATE tbl_current_location SET cl_latitude=?,cl_longitude=? WHERE fk_mechanic_id=?";
            $stmt=$con->prepare($sql);
            $stmt->bind_param('ddi',$_POST['cl_latitude'],$_POST['cl_longitude'],$_POST['fk_mechanic_id']);
            if($stmt->execute()){
                $response['error'] = false;
                $response['code'] = 200;
                $response['message'] = "Location Update Successfull";    
            }else{
                $response['error'] = true;
                $response['code'] = 404;
                $response['message'] = "Failed to Update";    
            }
        }
        else if ($result == 1) {
            $response['error'] = false;
            $response['code'] = 200;
            $response['message'] = "Location Update Successfull";
        } else {
            $response['error'] = true;
            $response['code'] = 404;
            $response['message'] = "Failed to Update";
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
