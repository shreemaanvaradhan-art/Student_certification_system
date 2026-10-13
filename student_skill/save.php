<?php
session_start();
include "db.php";

if (!isset($_SESSION["student_table"])) {
    die("Unauthorized access.");
}

$student_table =$_SESSION["student_table"];
$student_name = mysqli_real_escape_string($conn,$_POST["student_name"]);
$student_id = mysqli_real_escape_string($conn,$_POST["student_id"]);
$email = mysqli_real_escape_string($conn,$_POST["email"]);
$skill_name = mysqli_real_escape_string($conn,$_POST["skill_name"]);
$badge_level = mysqli_real_escape_string($conn,$_POST["badge_level"]);

$file_name = "";

if (isset($_FILES["certificate"]) && $_FILES["certificate"]["error"] == 0) {
    $original_name =$_FILES["certificate"]["name"];
    $tmp_name =$_FILES["certificate"]["tmp_name"];

    if (!is_dir("uploads")) {
        mkdir("uploads");
    }

    $file_name = time() . "_" . $original_name;
    move_uploaded_file($tmp_name, "uploads/" . $file_name);
}

$sql = "INSERT INTO `$student_table` (student_name, student_id, email, skill_name, badge_level, certificate_file) 
        VALUES ('$student_name', '$student_id', '$email', '$skill_name', '$badge_level', '$file_name')";

if (mysqli_query($conn,$sql)) {
    header("Location: view.php");
    exit();
} else {
    echo "Error saving record: " . mysqli_error($conn);
}

?>