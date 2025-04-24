<?php
    include 'conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - InfluenceX</title>
    <style>
      
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

         
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        
        body {
            background: linear-gradient(135deg, #4CAF50, #2196F3);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

       
        .login-container {
    background: rgba(255, 255, 255, 0.2); 
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 400px;
    text-align: center;
    transition: transform 0.3s ease-in-out;
    backdrop-filter: blur(10px);
}

    

        .login-container:hover {
            transform: scale(1.03);
        }

        .login-container h2 {
            color: #333;
            margin-bottom: 20px;
        }

      
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 1.1em;
            color: #333;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            transition: 0.3s ease;
        }

        .input-group input:focus {
            border-color: #2196F3;
            outline: none;
            box-shadow: 0 0 5px rgba(33, 150, 243, 0.5);
        }

        
        
        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #2196F3, #4CAF50);
            color: white;
            font-size: 1.1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(90deg, #4CAF50, #2196F3);
        }

        
        .signup-link {
            margin-top: 15px;
            font-size: 0.9em;
        }

        .signup-link a {
            color: #2196F3;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login to InfluenceX</h2>
        
        <form action="process.php" method="POST">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <button type="submit" name="login" class="btn">Login</button>
        </form>

        <div class="signup-link">
            <p>Don't have an account? <a href="register.html">Sign up here</a></p>
        </div>
    </div>

</body>
</html>
