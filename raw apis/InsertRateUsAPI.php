<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['user_id'])
        && isset($_POST['user_type'])
        && isset($_POST['rf_title'])
        && isset($_POST['rf_star'])
        && isset($_POST['fk_b_id'])
    ) {
        $sql = "INSERT INTO tbl_rate_feedback(user_id, user_type, rf_title,rf_star,fk_b_id) VALUES (?,?,?,?,?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('issfi', $_POST['user_id'], $_POST['user_type'], $_POST['rf_title'], $_POST['rf_star'], $_POST['fk_b_id']);
        if ($stmt->execute()) {
            $response['error'] = false;
            $response['code'] = 200;
            $response['message'] = "Thanks For your Feeback";
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
