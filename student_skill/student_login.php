<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn,$_POST["username"]);
    $roll_no = mysqli_real_escape_string($conn,$_POST["roll_no"]);
    $password = mysqli_real_escape_string($conn,$_POST["password"]);

    if (!empty($username) && !empty($roll_no) && !empty($password)) {
        
        $safe_roll = preg_replace('/[^a-zA-Z0-9_]/', '_',$roll_no);
        $student_table = "cert_" . $safe_roll;
        $deleted_table = "del_cert_" . $safe_roll;
        $create_student_table = "CREATE TABLE IF NOT EXISTS `$student_table` (
            student_name VARCHAR(100),
            student_id VARCHAR(50),
            email VARCHAR(100),
            skill_name VARCHAR(100),
            badge_level VARCHAR(50),
            certificate_file VARCHAR(255)
        )";
        mysqli_query($conn,$create_student_table);
        $create_deleted_table = "CREATE TABLE IF NOT EXISTS `$deleted_table` (
            student_name VARCHAR(100),
            student_id VARCHAR(50),
            email VARCHAR(100),
            skill_name VARCHAR(100),
            badge_level VARCHAR(50),
            certificate_file VARCHAR(255)
        )";
        mysqli_query($conn,$create_deleted_table);

        $_SESSION["role"] = "student";
        $_SESSION["student_name"] = $username;
        $_SESSION["student_id"] = $roll_no;
        $_SESSION["student_table"] = $student_table;
        $_SESSION["deleted_table"] = $deleted_table;

        header("Location: index.php");
        exit();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Student Login</h1>

    <?php if ($error != "") { echo "<p style='color:red; text-align:center;'>$error</p>"; } ?>

    <form action="student_login.php" method="post">
        <label>User Name</label>
        <input type="text" name="username" required>

        <label>Roll No</label>
        <input type="text" name="roll_no" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn btn-submit" style="margin-top:20px;">Login to Portal</button>
    </form>
    
    <div style="text-align: center; margin-top: 15px;">
        <a href="landing.php" style="color: #1565c0; text-decoration: none;">Back to Home</a>
    </div>
</div>

</body>
</html>