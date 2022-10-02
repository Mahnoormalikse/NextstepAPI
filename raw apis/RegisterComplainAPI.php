<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../operations/DbOperations.php';
include_once '../include/DbConnect.php';

$response  = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['comp_sub'])
        && isset($_POST['comp_msg'])
        && isset($_POST['fk_c_id'])
        && isset($_POST['fk_m_id'])
        && isset($_POST['comp_stype'])
    ) {
        $db = new DbOperations();
        $result = $db->CreateComplain(($_POST['comp_sub']),
            $_POST['comp_msg'],
            $_POST['fk_c_id'],
            $_POST['fk_m_id'],
            $_POST['comp_stype']
        );
        if ($result == 0) {
            $response['error'] = true;
            $response['code'] = 404;
            $response['message'] = "Your Complaint is in Process. Please Wait";
        } else if ($result == 1) {
            $response['error'] = false;
            $response['code'] = 200;
            $response['message'] = "Complaint Registered Successfully";
        } else {
            $response['error'] = true;
            $response['code'] = 404;
            $response['message'] = "Try Again Later";
        }
    } else {
        $response['error'] = true;
        $response['code'] = 404;
        $response['message'] = "Required field missing";
    }
} else {
    $response['error'] = true;
    $response['code'] = 404;
    $response['message'] = "Server connection";
}
echo json_encode($response);
