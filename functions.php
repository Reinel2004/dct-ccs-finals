<?php    
    // All project functions should be placed here
    function con(){
        
        $conn = mysqli_connect("localhost", "root", "", "dct-ccs-finals");
        
        if($conn === false){
            die("Error: Could not connect " .  mysqli_connect_error());
        }

        return $conn;
    }

    function users($email, $password){
        $conn = con();
        $hashedpw = md5($password);
    
        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        mysqli_stmt_bind_param($stmt, "ss", $email, $hashedpw);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    
        return $user;
    }

    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

?>