<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if($_SERVER["REQUEST_METHOD"] === "POST") {

    //-------------------
    // POST VALIDATION START
    //-------------------

    // set vars and strip tags from the fields in case there are PHP tags in there
    $name = strip_tags(getPOSTdata('name'));
    $age = strip_tags(getPOSTdata('age'));
    $email = strip_tags(getPOSTdata('email'));
    $phone = strip_tags(getPOSTdata('phone'));

    // check if all fields are empty, besides phone (which is optional)
    if ($name == "" && $age == "" && $email == "") {
        error(400,'No POST data, all fields are empty');
    }

    //------------------
    // VALIDATE: NAME
    //------------------

    // name cannot be empty
    elseif ( $name == '') {
        error(400,'Name cannot be empty');
    }
    // name must be between 2 and 100 characters
    elseif ( strlen($name) < 2 || strlen($name) > 100 ) {
        error(400,'Name must be between 2 and 100 characters');
    }
    // name can only be A-Z, a-z, - and '
    elseif ( preg_match("/^[a-zA-Z-']+$/", $name) == 0 ) {
        error(400,'Name must contain A-Z or a-z or hyphen or apostrophe only');
    }


    //------------------
    // VALIDATE: AGE
    //------------------

    // age cannot be empty
    elseif ( $age == '') {
        error(400,'Age cannot be empty');
    }
    // age must be an integer
    elseif ( !filter_var($age,FILTER_VALIDATE_INT) ) {
        error(400,'Age must be an integer');
    }
    // age must be between 13 and 130
    elseif ( $age < 13 || $age > 130 ) {
        error(400,'Age must be an integer between 13 and 130');
    }


    //------------------
    // VALIDATE: E-MAIL
    //------------------

    // email cannot be empty
    elseif ( $email == '') {
        error(400,'Email cannot be empty');
    }
    // email must be in email format @ and .xxx
    elseif( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
        error(400,'Invalid Email address format');
    }


    //------------------
    // VALIDATE: PHONE
    //------------------

    //Note: optional, can be empty

    // phone must be digits only
    elseif ( $phone != '' && preg_match("/^[0-9]+$/", $phone) == 0 ) {
        error(400,'Phone must be digits only');
    }
    // phone must begin with 04
    elseif ( $phone != '' && substr($phone,0,2) != 04 ) {
        error(400,'Phone must begin with 04');
    }
    // phone must be a length of only 10 digits
    elseif ( $phone != '' && strlen($phone) < 10 || strlen($phone) > 10 ) {
        error(400,'Phone must be exactly 10 digits in length');
    }


    //------------------
    // VALIDATION PASSED
    //------------------

    // generate user_id and save data to database
    // then send success header
    else {
        require_once('class/Database.php');
        $uid = rand(1000,9999); // create random user_id
        $users = new Users($uid,$name,$age,$email,$phone); // call database class
        json_encode(new Users($uid,$name,$age,$email,$phone));
        file_put_contents('users.json',json_encode($users->jsonSerialize())."\n",FILE_APPEND); // add post data to json file (database)
        success(200,$uid); // send success header
    }

    //-------------------
    // POST VALIDATION END
    //-------------------

}


//-------------------
// GET PARAMETERS FROM REQUEST FUNCTION START
//-------------------
// inspired by James Bishop - Lecture 19 - Circa 2019
// for POST only

function getPOSTdata($param) {
    if ( isset($_POST[$param]) ) {
        return $_POST[$param];
    }
    else {
        return false;
    }
}
//-------------------
// GET PARAMETERS FROM REQUEST FUNCTION END
//-------------------


//-------------------
// ERROR RESPONSE FUNCTION START
//-------------------
// http error codes - when validation fails
// error msg function inspired by James Bishop - Lecture 19 - Circa 2019

function error($code,$message) {
    $responses = [
        400 => "Bad Request",
        404 => "Not Found",
        405 => "Method Not Allowed",
        500 => "Internal server error",
        200 => "OK"
    ];
    http_response_code($code);
    $reason = $responses[$code];
    $protocol = $_SERVER['SERVER_PROTOCOL'];
    echo json_encode(['protocol'=>$protocol,'error'=>$code,'reason'=>$reason,'message'=>$message]);
    die();
}
//-------------------
// ERROR RESPONSE FUNCTION END
//-------------------


//-------------------
// SUCCESS RESPONSE FUNCTION START
//-------------------
function success($code,$uid) {
    http_response_code($code);
    echo json_encode(['user_id'=>$uid]);
    die();
}
//-------------------
// SUCCESS RESPONSE FUNCTION END
//-------------------

// EOF
?>