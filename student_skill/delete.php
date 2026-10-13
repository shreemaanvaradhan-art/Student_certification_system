<?php
session_start();
include "db.php";
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "student") {
    die("Unauthorized: Staff cannot modify or delete student records.");
}
if (!isset($_GET["student_id"]) || !isset($_GET["badge_level"])) {
    die("Required record info missing.");
}
$student_id = mysqli_real_escape_string($conn, $_GET["student_id"]);
$badge_level = mysqli_real_escape_string($conn, $_GET["badge_level"]);
$student_table = $_SESSION["student_table"];
$deleted_table = $_SESSION["deleted_table"];
$copy_sql = "INSERT INTO `$deleted_table`
             (student_name, student_id, email, skill_name, badge_level, certificate_file)
             SELECT student_name, student_id, email, skill_name, badge_level, certificate_file
             FROM `$student_table`
             WHERE student_id = '$student_id'
             AND badge_level = '$badge_level'
             LIMIT 1";
if (mysqli_query($conn, $copy_sql)) {
    $delete_sql = "DELETE FROM `$student_table`
                   WHERE student_id = '$student_id'
                   AND badge_level = '$badge_level'
                   LIMIT 1";
    if (mysqli_query($conn, $delete_sql)) {
        header("Location: view.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Error copying record to deleted table: " . mysqli_error($conn);
}
?>