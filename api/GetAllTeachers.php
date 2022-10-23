<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../operations/DbOperations.php';
include_once '../include/DbConnect.php';

$response  = array();
$db = new DbConnect();
$con = $db->connect();

$data = array();
$resultSet = array();
$img = array();

$data1 = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $query = "SELECT * FROM `tbl_t_profile`";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $row = $stmt->num_rows() > 0;
    if ($row == 1) {
        $stmt->bind_result(
            $id,
            $name,
            $email,
            $qualification,
            $gender,
            $specialization
        );
        while ($row = $stmt->fetch()) {
            $data = array(
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'qualification' => $qualification,
                'gender' => $gender,
                'specialization' => $specialization
            );
            array_push($resultSet, $data);
        }

        $response['error'] = false;
        $response['code'] = 200;
        $response['message'] = "record found";
        $response['records']   = $resultSet;
    } else if ($row == 0) {
        $response['error'] = true;
        $response['code'] = 404;
        $response['message'] = "Record not found";
    }
} else {
    $response['error'] = true;
    $response['code'] = 404;
    $response['message'] = "Server Error";
}
echo json_encode($response);
