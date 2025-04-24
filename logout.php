<?php
    session_start();
    session_destroy(); // End the session before redirecting.

    echo "<script>
            alert('Logout Successful');
            window.location.href = 'login.php';
          </script>";
    exit();
?>
