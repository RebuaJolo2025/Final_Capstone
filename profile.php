<?php
session_start();
include 'conn.php';

if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

// Fetch user details from the database
$email = $_SESSION['email'];
$query = "SELECT * FROM userdata WHERE email='$email'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f0f5f9;
            color: #333;
            line-height: 1.6;
            transition: background 0.3s ease-in-out;
        }

        header {
            background: linear-gradient(90deg, #4CAF50, #66BB6A);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1.8em;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .profile-container {
            width: 80%;
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .profile-header {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-header img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid #4CAF50;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .profile-header img:hover {
            transform: scale(1.05);
        }

        .profile-header h2 {
            font-size: 1.8em;
            margin-top: 10px;
        }

        .profile-details {
            margin-top: 20px;
            text-align: left;
            padding: 20px;
            border-radius: 10px;
            background: #f9f9f9;
        }

        .profile-details p {
            font-size: 1.2em;
            margin: 10px 0;
        }

        .profile-actions {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .button {
            display: inline-block;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s ease-in-out, transform 0.2s;
        }

        .button:hover {
            transform: scale(1.05);
        }

        .edit-button {
            background: #4CAF50;
            color: white;
        }

        .logout-button {
            background: #f44336;
            color: white;
        }

        .edit-button:hover {
            background: #45a049;
        }

        .logout-button:hover {
            background: #d32f2f;
        }

        .upload-button {
            background: #2196F3;
            color: white;
            margin-top: 15px;
        }

        .upload-button:hover {
            background: #1976D2;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ccc;
            color: black;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }

        .back-button:hover {
            background-color: #aaa;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 30px;
        }

        footer p {
            font-size: 1em;
            margin: 0;
        }
    </style>
</head>
<body>

    <header>
        User Profile
    </header>

    <div class="profile-container">
        
        <a href="index.html" class="back-button">⬅ Back to Home</a>

        <div class="profile-header">
            <img src="./img/jolo.jpg" alt="Profile Picture">
            <h2><?php echo htmlspecialchars($user['fullname']); ?></h2>
            <p><?php echo htmlspecialchars($user['email']); ?></p>
            <a href="#" class="button upload-button">Upload New Photo</a>
        </div>

        <div class="profile-details">
            <p><strong> Username:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
            <p><strong> Phone Number:</strong> <?php echo htmlspecialchars($user['phonenumber']); ?></p>
            <p><strong> Location:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
        </div>

        <div class="profile-actions">
            <a href="#" class="button edit-button"> Edit Profile</a>
            <a href="logout.php" class="button logout-button"> Log Out</a>
        </div>
    </div>



</body>

</html>
