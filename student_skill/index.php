<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "student") {
    header("Location: student_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Skill Certification</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Student Skill Certification</h1>
    <p style="text-align: center; font-weight: bold; color: #555;">
        Logged in as: <?php echo htmlspecialchars($_SESSION["student_name"]); ?> (<?php echo htmlspecialchars($_SESSION["student_id"]); ?>)
    </p>
    <form action="save.php" method="post" enctype="multipart/form-data">
        <label>Student Name</label>
        <input type="text" name="student_name" value="<?php echo htmlspecialchars($_SESSION["student_name"]); ?>" required readonly>
        <label>Student ID / Roll No</label>
        <input type="text" name="student_id" value="<?php echo htmlspecialchars($_SESSION["student_id"]); ?>" required readonly>
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Skill Name</label>
        <input type="text" name="skill_name" required>
        <label>Badge Level</label>
        <select name="badge_level" required>
            <option value="">Select Badge</option>
            <option value="Achivements">Achivements</option>
            <option value="Participants">Participants</option>
            <option value="Prizes">Prizes</option>
        </select>
        <label>Certificate File</label>
        <input type="file" name="certificate" accept=".pdf,.jpg,.jpeg,.png">
        <div class="button-group">
            <button type="submit" class="btn btn-submit">Upload Certificate</button>
            <a class="btn btn-view" href="view.php">View All Records</a>
            <a class="btn btn-delete" href="deleted_records.php">View Deleted Records</a>
        </div>
    </form>
    <div style="text-align:center; margin-top: 15px;">
        <a href="logout.php" style="color: red; font-weight: bold;">Logout</a>
    </div>
</div>
</body>
</html>