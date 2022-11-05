<?php

class DbOperations
{

    function __construct()
    {
        require_once '../include/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }

    public function CreateUser($name, $email, $pass)
    {

        $password = md5($pass);
        if ($this->CheckCustomerEmail($email)) {
            return 0;
        } else {
            $sql = "INSERT INTO tbl_user(name,email,password) VALUES (?,?,?)";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param('sss', $name, $email, $password);
            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        }
    }
    public function CreateTeacherProfile(
        $id,
        $name,
        $email,
        $qualification,
        $gender,
        $specialization
    ) {
            $sql = "INSERT INTO tbl_t_profile(id,name,email,qualification,gender,specialization)
             VALUES (?,?,?,?,?,?)";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param(
                'isssss',
                $id,
                $name,
                $email,
                $qualification,
                $gender,
                $specialization
            );
            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        
    }
    public function CreateStudentProfile(
        $id,
        $name,
        $email,
        $gender
    ) {
            $sql = "INSERT INTO tbl_s_profile(id,name,email,gender)
             VALUES (?,?,?,?)";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param(
                'isss',
                $id,
                $name,
                $email,
                $gender
            );
            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        
    }

    public function updatePassword($email, $pass)
    {
        $password = md5($pass);
        $sql="UPDATE  tbl_user SET password=? WHERE email=? ";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('ss', $password, $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0; 
        
    }



    public function RegisterStudent(
        $t_id,
        $s_id
    ) {
            $sql = "INSERT INTO tbl_register_student(t_id,s_id)
             VALUES (?,?)";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param(
                'ii',
                $t_id,
                $s_id
            );
            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        
    }

    public function Timetable(
        $id,
        $date,
        $start_time,
        $end_time,
        $link,
        $name
       
    ) {
            $sql = "INSERT INTO tbl_timetable(id,date,start_time,end_time,link,name)
             VALUES (?,?,?,?,?,?)";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param(
                'isssss',
                $id,
                $date,
                $start_time,
                $end_time,
                $link,
                $name
            );
            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        
    }

    public function CreateWorkshopMechanicAccount(
        $wm_name,
        $wm_password,
        $wm_address,
        $wm_email,
        $wm_lng,
        $wm_lat,
        $wm_contact,
        $wm_cnic,
        $v_type
    ) {
        $wm_pass = md5($wm_password);
        if ($this->CheckWorkshopMechanicEmail($wm_email)) {
            return 0;
        } else {
            $sql = "INSERT INTO tb_workshop(wmechanic_name,wmechanic_password,wmechanic_address,
    wmechanic_email,wmechanic_lng,wmechanic_lat,wmechanic_contact,wmechanic_cnic, vehicle_type)
    VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param('ssssddsss', $wm_name, $wm_pass, $wm_address, $wm_email, $wm_lng, $wm_lat, $wm_contact, $wm_cnic, $v_type);
            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        }
    }


    private function CheckCustomerEmail($email)
    {
        $sql = "SELECT * FROM tbl_user WHERE email=? ";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function getCurrentUser($email)
    {
        $sql = "SELECT * FROM tbl_user WHERE email=? ";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        echo $stmt->insert_id;
    }

    private function CheckMechanicEmail($email)
    {
        $sql = "SELECT * FROM tbl_mechanic WHERE mechanic_email=? ";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    private function CheckWorkshopMechanicEmail($email)
    {
        $sql = "SELECT * FROM tb_workshop WHERE wmechanic_email=? ";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function CheckStudentLogin($email, $password)
    {
        $pass = md5($password);
        $sql = "SELECT * FROM tbl_user WHERE email=? AND password=?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('ss', $email, $pass);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function CheckTeacherLogin($email, $password)
    {
        $pass = md5($password);
        $sql = "SELECT * FROM tbl_user WHERE email=? AND password=?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('ss', $email, $pass);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function CheckLoginMechanic($email, $password)
    {
        $pass = md5($password);
        $sql = "SELECT * FROM tbl_mechanic WHERE mechanic_email=? AND mechanic_password=?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('ss', $email, $pass);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
    public function CheckLoginAdmin($email, $password)
    {
        $sql = "SELECT * FROM tbl_admin WHERE email=? AND password=?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function CheckLoginWorkshop($email, $password)
    {
        $pass = md5($password);
        $sql = "SELECT * FROM tb_workshop WHERE wmechanic_email=? AND wmechanic_password=?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('ss', $email, $pass);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function CreateNearby(
        $title
    ) {
        if ($this->ChecknearbyTitle($title)) {
            return 0;
        } else {
            $sql = "INSERT INTO tbl_nearby_areas(na_title) VALUES (?)";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param('s', $title);
            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        }
    }

    public function ChecknearbyTitle($title)
    {
        $sql = "SELECT * FROM tbl_nearby_areas WHERE na_title=?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('s', $title);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function CreateBooking($fk_id, $fk_customer_id, $user_type, $booking_fee, $booking_description)
    {
        $booking_num = $this->generate_number(8);
        $sql = "INSERT INTO tbl_booking (booking_num,fk_id,fk_customer_id,user_type,booking_fee, booking_description)
                VALUES (?,?,?,?,?,?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('siisis', $booking_num, $fk_id, $fk_customer_id, $user_type, $booking_fee, $booking_description);
        if ($stmt->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function CreateService($service_name, $service_price, $service_discount, $fk_id, $user_type)
    {
        $sql = "INSERT INTO  tbl_service (service_name,service_price,service_discount,fk_id,user_type)
                VALUES (?,?,?,?,?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('siiis', $service_name, $service_price, $service_discount, $fk_id, $user_type);
        if ($stmt->execute()) {
            return 1;
        } else {
            return 0;
        }
    }


    public function generate_number($size)
    {
        $alpha_key = '';
        $keys = range('A', 'Z');

        for ($i = 0; $i < 2; $i++) {
            $alpha_key .= $keys[array_rand($keys)];
        }

        $length = $size - 2;

        $key = '';
        $keys = range(0, 9);

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        return $alpha_key . $key;
    }

    public function CheckMechanicLocation($fk_mechanic_id)
    {
        $sql = "SELECT * FROM tbl_current_location WHERE fk_mechanic_id=?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('i', $fk_mechanic_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }


    public function InsertLocation($cl_latitude,$cl_longitude,$fk_mechanic_id){
        if($this->CheckMechanicLocation($fk_mechanic_id)){
            return 0;
        }else{
            $sql="INSERT INTO tbl_current_location (cl_latitude,cl_longitude,fk_mechanic_id)
            VALUES (?,?,?)";
            $stmt=$this->con->prepare($sql);
            $stmt->bind_param('ddi',$cl_latitude,$cl_longitude,$fk_mechanic_id);
            if($stmt->execute()){
                return 1;
            }else{
                return 2;
            }
        }
    }
    public function SendMessage($chat_msg, $send_id, $rec_id, $s_type)
    {

        $sql = "INSERT INTO tbl_chat (chat_message,sender_id,receiver_id,sender_type)
             VALUES (?,?,?,?)";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param(
            'siis',
            $chat_msg,
            $send_id,
            $rec_id,
            $s_type
        );
        if ($stmt->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

        /**
         * function to create new Complaint
         */
        public function CreateComplain($comp_sub, $comp_msg, $fk_p_id, $fk_d_id, $comp_stype)
        {
            $date = new DateTime('now', new DateTimeZone('Asia/Karachi'));
            $createdDateTime = $date->format('Y-m-d H:i:s');
            $compNum = $this->generate_number(8);
            if ($this->CheckComplaint($fk_p_id, $fk_d_id, $createdDateTime)) {
                return 0;
            } else {
                $sql = "INSERT INTO tbl_complain (comp_sub,comp_msg,fk_c_id,fk_m_id,
                        comp_stype,comp_datetime,comp_num) 
                VALUES (?,?,?,?,?,?,?)";
                $stmt = $this->con->prepare($sql);
                $stmt->bind_param(
                    'ssiisss',
                    $comp_sub,
                    $comp_msg,
                    $fk_p_id,
                    $fk_d_id,
                    $comp_stype,
                    $createdDateTime,
                    $compNum
                );
                if ($stmt->execute()) {
                    return 1;
                } else {
                    return 0;
                }
            }
        }

        public function CheckComplaint($fk_p_id, $fk_d_id, $checkDate)
        {
            $sql = "SELECT * FROM `tbl_complain` 
            WHERE fk_c_id=? AND fk_m_id=? AND comp_datetime=?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param('iis', $fk_p_id, $fk_d_id, $checkDate);
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;
        }

}
