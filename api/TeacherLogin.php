<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['email'])
        && isset($_POST['password'])
       
    ) {

        $db = new DbOperations();
        $result = $db->CheckTeacherLogin($_POST['email'], $_POST['password']);
        if ($result == 1) {
            $query = "SELECT id,name,email,qualification,gender FROM `tbl_t_profile` WHERE email=?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('s', $_POST['email']);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result(
                $id,
                $name,
                $email,
                $qualification,
                $gender
            );
            while ($row = $stmt->fetch()) {
                $response['id'] =  $id;
                $response['name'] =  $name;
                $response['email'] =  $email;
                $response['qualification'] =  $qualification;
                $response['gender'] =  $gender;
            }

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
