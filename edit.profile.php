<?php
session_start();
include 'conn.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch user data
$query = "SELECT fullname, phonenumber, address FROM userdata WHERE email=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found in the database!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; padding: 50px; }
        .profile-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); display: inline-block; }
        h2 { color: #333; }
        .info { margin: 10px 0; font-size: 18px; }
        .edit-btn { display: inline-block; padding: 10px 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
        .edit-btn:hover { background: #45a049; }
    </style>
</head>
<body>

    <div class="profile-container">
        <h2>User Profile</h2>
        <p class="info"><strong>Full Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
        <p class="info"><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phonenumber']); ?></p>
        <p class="info"><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
        <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
    </div>

</body>
</html>
<?php $conn->close(); ?>
