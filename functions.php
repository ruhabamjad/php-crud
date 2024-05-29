<?php
require_once("connect.php");

function check_input($data){
    global $conn;
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    $data = trim($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

function validate_file($folder, $image_type, $image_size, $allowedExtentions){
    if(!file_exists($folder)){mkdir($folder, 0777);}
    else if(!in_array($image_type, $allowedExtentions)){
        echo "Please upload a valid image file.<br>Only jpg, jpeg, and png files allowed.";
    }else if($image_size > 2 * 1024 * 1024){
            echo "File is too large.<br>Please upload less than 2 mb.";
    }return true;
}
?>