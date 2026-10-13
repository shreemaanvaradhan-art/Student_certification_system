<?php
session_start();
include "db.php";
if (!isset($_SESSION["student_table"])) {
    header("Location: landing.php");
    exit();
}
$student_table = $_SESSION["student_table"];
$role = $_SESSION["role"] ?? "student";
$create_table_sql = "CREATE TABLE IF NOT EXISTS `$student_table` (
    student_name VARCHAR(100) NOT NULL,
    student_id VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    badge_level VARCHAR(50) NOT NULL,
    certificate_file VARCHAR(255) NOT NULL
)";
if (!mysqli_query($conn, $create_table_sql)) {
    die("Error creating student table: " . mysqli_error($conn));
}
$sql = "SELECT * FROM `$student_table`";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error loading records: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Certifications</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container wide">
    <h1>Student Certifications</h1>
    <p style="text-align: center; font-weight: bold; color: #1565c0;">
        Viewing Table:
        <?php echo htmlspecialchars($student_table); ?>
        (Logged in as:
        <?php echo htmlspecialchars(ucfirst($role)); ?>)
    </p>
    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
        <?php if ($role === "student") { ?>
            <a class="add-button" href="index.php">
                Add New Certificate
            </a>
            <a class="add-button"
               style="background:#d32f2f;"
               href="deleted_records.php">
                View Deleted Records
            </a>
        <?php } else { ?>
            <a class="add-button" href="teacher_login.php">
                Back to Teacher Login
            </a>
        <?php } ?>
        <a class="add-button"
           style="background: gray;"
           href="logout.php">
            Logout
        </a>
    </div>
    <table>
        <tr>
            <th>Student Name</th>
            <th>Student ID</th>
            <th>Email</th>
            <th>Skill</th>
            <th>Badge</th>
            <th>Certificate</th>
            <?php if ($role === "student") { ?>
                <th>Action</th>
            <?php } ?>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td>
                <?php echo htmlspecialchars($row["student_name"]); ?>
            </td>
            <td>
                <?php echo htmlspecialchars($row["student_id"]); ?>
            </td>
            <td>
                <?php echo htmlspecialchars($row["email"]); ?>
            </td>
            <td>
                <?php echo htmlspecialchars($row["skill_name"]); ?>
            </td>
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
                    <a class="view-cert-btn"
                       href="<?php echo htmlspecialchars($file_path); ?>"
                       target="_blank">
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
            <?php if ($role === "student") { ?>
            <td>
                <a class="delete-btn"
                   href="delete.php?student_id=<?php echo urlencode($row['student_id']); ?>&badge_level=<?php echo urlencode($row['badge_level']); ?>"
                   onclick="return confirm('Are you sure you want to delete this specific record?');">
                    Delete
                </a>
            </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>