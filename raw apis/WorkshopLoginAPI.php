<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['wmechanic_email'])
        && isset($_POST['wmechanic_password'])
       
    ) {

        $db = new DbOperations();
        $result = $db->CheckLoginWorkshop($_POST['wmechanic_email'], $_POST['wmechanic_password']);
        if ($result == 1) {
            $response['error'] = false;
            $response['code'] = 200;
            $response['message'] = "Login Successfull";
        } else {
            $response['error'] = true;
            $response['code'] = 404;
            $response['message'] = "Login Failed";
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
