<?php
session_start();
include "db.php";
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "student") {
    die("<h2 style='color:red; text-align:center; margin-top:50px;'>Access Denied: Staff members cannot view deleted student records.</h2><p style='text-align:center;'><a href='view.php'>Back to Active Records</a></p>");
}
$deleted_table =$_SESSION["deleted_table"];
$sql = "SELECT * FROM `$deleted_table`";
$result = mysqli_query($conn,$sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Deleted Certifications</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container wide">
    <h1>Deleted Certifications</h1>
    <a class="add-button" href="view.php">Back to Active Records</a>
    <table>
        <tr>
            <th>Student Name</th>
            <th>Student ID</th>
            <th>Email</th>
            <th>Skill</th>
            <th>Badge</th>
            <th>Certificate</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row["student_name"]); ?></td>
            <td><?php echo htmlspecialchars($row["student_id"]); ?></td>
            <td><?php echo htmlspecialchars($row["email"]); ?></td>
            <td><?php echo htmlspecialchars($row["skill_name"]); ?></td>
            <td>
                <span class="badge">
                    <?php echo htmlspecialchars($row["badge_level"]); ?>
                </span>
            </td>
            <td>
                <?php 
                if (!empty($row["certificate_file"])) { 
                    $file_path = "uploads/" . $row["certificate_file"];
                    if (file_exists($file_path)) {
                ?>
                    <a class="view-cert-btn" href="<?php echo $file_path; ?>" target="_blank">
                        View Certificate
                    </a>
                <?php 
                    } else {
                        echo "<span style='color:red;'>File Missing</span>";
                    }
                } else { 
                    echo "No File";
                } 
                ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>