<?php
// create user to database in json format
class Users implements JsonSerializable {

    private $uid;
    private $name;
    private $age;
    private $email;
    private $phone;

    public function __construct($uid,$name,$age,$email,$phone) {
        $this->uid = $uid;
        $this->name = $name;
        $this->age = $age;
        $this->email = $email;
        $this->phone = $phone;
    }

    public function getUID() {
        return $this->uid;
    }
    public function getName() {
        return $this->name;
    }
    public function getAge() {
        return $this->age;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getPhone() {
        return $this->phone;
    }

    public function jsonSerialize() {
        return [
            'users'=> [
                'uid'=>$this->uid,
                'name'=>$this->name,
                'age'=>$this->age,
                'email'=>$this->email,
                'phone'=>$this->phone
            ]
        ];
    }

    // original createuser funtion - worked well, but did not meet the brief
    // keeping for my record
    /*
    public function createUser($name,$age,$email,$phone) {
        //$uid = rand(1000,2000);
        $users = array();
        $users[] = array('uid'=>$uid,'name'=>$name,'age'=>$age,'email'=>$email,'phone'=>$phone);
        $fp = fopen('users.json','w');
        fwrite($fp,json_encode($users));
        fclose($fp);
        echo "success";
    }
    */
}
?>