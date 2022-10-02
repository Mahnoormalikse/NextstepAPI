<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['booking_id'])
        && isset($_POST['booking_status'])
        && isset($_POST['payment_additional'])
        && isset($_POST['payment_total'])
    ) {

        $sql="UPDATE tbl_booking SET booking_status=? WHERE booking_id=?";
        $stmt=$con->prepare($sql);
        $stmt->bind_param('si',$_POST['booking_status'],$_POST['booking_id']);
        if($stmt->execute()){
            $sql="UPDATE tbl_payment SET payment_additional=?,payment_total=?,payment_status=? 
            WHERE fk_booking_id=?";
            $stmt=$con->prepare($sql);
            $stmt->bind_param('iisi',$_POST['payment_additional'], $_POST['payment_total'],
            $_POST['booking_status'],$_POST['booking_id']);
            if($stmt->execute()){
                $response['error'] = false;
                $response['code'] = 200;
                $response['message'] = "Status Updated Successfully";    
            }else{
                $response['error'] = true;
                $response['code'] = 404;
                $response['message'] = "Status Not Updated Successfully";    
            }
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
