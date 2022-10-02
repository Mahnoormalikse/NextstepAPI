<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../operations/DbOperations.php';
include_once '../include/DbConnect.php';

$db = new DbConnect();
$con = $db->connect();
$data = array();
$response = array();
$resultSet = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['id'])
        && isset($_POST['comp_stype'])
        && isset($_POST['comp_status'])
    ) {
        if ($_POST['comp_stype'] == 'c') {
            $query = "SELECT tbl_mechanic.mechanic_name,comp_id,comp_sub,comp_msg,comp_datetime,
            comp_num, comp_status FROM `tbl_complain`
            JOIN tbl_mechanic ON tbl_mechanic.mechanic_id=tbl_complain.fk_m_id
            WHERE fk_c_id=? AND comp_stype=? AND comp_status=?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('iss', $_POST['id'], $_POST['comp_stype'], $_POST['comp_status']);
            $stmt->execute();
            $stmt->store_result();
            $row = $stmt->num_rows() > 0;
            if ($row == 1) {
                $stmt->bind_result(
                    $d_name,
                    $comp_id,
                    $comp_sub,
                    $comp_msg,
                    $comp_datetime,
                    $comp_num,
                    $comp_status
                );
                while ($row = $stmt->fetch()) {
                    $data = array(
                        'name' => $d_name,
                        'comp_id' => $comp_id,
                        'comp_sub' => $comp_sub,
                        'comp_msg' => $comp_msg,
                        'comp_datetime' => $comp_datetime,
                        'comp_num' => $comp_num,
                        'comp_status' => $comp_status
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
        } else if ($_POST['comp_stype'] == 'm') {
            $query = "SELECT tbl_customer.customer_id,comp_id,comp_sub,comp_msg,comp_datetime,
            comp_num, comp_status FROM `tbl_complaint`
            JOIN tbl_customer ON tbl_customer.customer_id=tbl_complain.fk_c_id
            WHERE fk_m_id=? AND comp_stype=? AND comp_status=?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('iss', $_POST['id'], $_POST['comp_stype'],  $_POST['comp_status']);
            $stmt->execute();
            $stmt->store_result();
            $row = $stmt->num_rows() > 0;
            if ($row == 1) {
                $stmt->bind_result(
                    $d_name,
                    $comp_id,
                    $comp_sub,
                    $comp_msg,
                    $comp_datetime,
                    $comp_num,
                    $comp_status
                );
                while ($row = $stmt->fetch()) {
                    $data = array(
                        'name' => $d_name,
                        'comp_id' => $comp_id,
                        'comp_sub' => $comp_sub,
                        'comp_msg' => $comp_msg,
                        'comp_datetime' => $comp_datetime,
                        'comp_num' => $comp_num,
                        'comp_status' => $comp_status
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
        }
    } else {
        $response['error'] = true;
        $response['code'] = 404;
        $response['message'] = "Required Field Missing";
    }
} else {
    $response['error'] = true;
    $response['code'] = 404;
    $response['message'] = "Server Error";
}

echo json_encode($response);
