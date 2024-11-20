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

    function validateLoginCredentials($email, $password) {
        $errors = [];
    
        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email.";
        }
    
        if (empty($password)) {
            $errors[] = "Password is required.";
        }
    
        if (empty($errors)) {
            $conn = con(); 
            $hashedpw = md5($password);
    
            $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
            $stmt = mysqli_prepare($conn, $sql);
    
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $email, $hashedpw);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
    
                if (mysqli_num_rows($result) === 0) {
                    $errors[] = "Invalid email or password.";
                }
    
                mysqli_stmt_close($stmt);
            } else {
                $errors[] = "Query failed.";
            }
    
            mysqli_close($conn);
        }
    
        return $errors;
    }
    
    function checkLoginCredentials($email, $password) {
        $conn = con(); 
        $hashedPassword = md5($password);
    
        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = mysqli_prepare($conn, $sql);
    
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $email, $hashedPassword);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
    
            if (mysqli_num_rows($result) > 0) {
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                return true;
            }
    
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
        return false; 
    }
    

?>