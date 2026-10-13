<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_name = mysqli_real_escape_string($conn, $_POST["teacher_name"]);
    $staff_password = $_POST["staff_password"];
    $student_name = mysqli_real_escape_string($conn, $_POST["student_name"]);
    $student_roll = mysqli_real_escape_string($conn, $_POST["student_roll"]);
    $user_query = "SELECT * FROM users WHERE username = '$teacher_name' AND role = 'staff'";
    $user_result = mysqli_query($conn, $user_query);

    if (mysqli_num_rows($user_result) > 0) {
        $staff_data = mysqli_fetch_assoc($user_result);
        
        if ($staff_password === $staff_data["password"]) {
            
            $safe_roll = preg_replace('/[^a-zA-Z0-9_]/', '_', $student_roll);
            $student_table = "cert_" . $safe_roll;

            $check_table = mysqli_query($conn, "SHOW TABLES LIKE '$student_table'");
            
            if (mysqli_num_rows($check_table) > 0) {
                $_SESSION["role"] = "staff";
                $_SESSION["teacher_name"] = $teacher_name;
                $_SESSION["target_student_name"] = $student_name;
                $_SESSION["target_student_id"] = $student_roll;
                $_SESSION["student_table"] = $student_table;

                header("Location: view.php");
                exit();
            } else {
                $error = "No certification records found for Roll No: " . htmlspecialchars($student_roll);
            }
        } else {
            $error = "Incorrect Staff Password!";
        }
    } else {
        // Register staff if account does not exist
        $insert_staff = "INSERT INTO users (username, roll_no, password, role) 
                        VALUES ('$teacher_name', 'STAFF', '$staff_password', 'staff')";
        
        if (mysqli_query($conn, $insert_staff)) {
            $safe_roll = preg_replace('/[^a-zA-Z0-9_]/', '_', $student_roll);
            $student_table = "cert_" . $safe_roll;

            $_SESSION["role"] = "staff";
            $_SESSION["teacher_name"] = $teacher_name;
            $_SESSION["target_student_name"] = $student_name;
            $_SESSION["target_student_id"] = $student_roll;
            $_SESSION["student_table"] = $student_table;

            header("Location: view.php");
            exit();
        } else {
            $error = "Failed to register staff details.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher's Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Teacher's Login</h1>

   
    

    <form action="teacher_login.php" method="post">
        <label>Teacher Name</label>
        <input type="text" name="teacher_name" required>

        <label>Staff Password</label>
        <input type="password" name="staff_password" required>

        <label>Student Name</label>
        <input type="text" name="student_name" required>

        <label>Student Roll No</label>
        <input type="text" name="student_roll" required>

        <button type="submit" class="btn btn-submit" style="margin-top:20px;">View Student Details</button>
    </form>

    <div style="text-align: center; margin-top: 15px;">
        <a href="landing.php" style="color: #1565c0; text-decoration: none;">Back to Home</a>
    </div>
</div>

</body>
</html>