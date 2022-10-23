<?php
header("Content-Type: application/json; charset=UTF-8");

$upload_dir='profilepicture/';

include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response=array();
$db=new DbConnect();
$con=$db->connect();

if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['id']) 
    && isset($_POST['date'])
    && isset($_POST['start_time'])
    && isset($_POST['end_time'])
    && isset($_POST['link'])
    && isset($_POST['name'])
      
    ){

        $db=new DbOperations();
        $result=$db->Timetable($_POST['id'], $_POST['date'], $_POST['start_time'], $_POST['end_time'], $_POST['link'], $_POST['name']);
         if($result==1){
            $response['error']=false;
            $response['code']=200;
            $response['message']="Timetable Updated Successfully";

    }else{
        $response['error']=false;
        $response['code']=200;
        $response['message']="Required fields Missing";
    }

}} else {
    $response['error']=true;
    $response['code']=500;
    $response['message']="Invalid Request Method";
}
echo json_encode($response);