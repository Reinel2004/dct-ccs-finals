<?php    
    // All project functions should be placed here
    function con(){
        
        $conn = mysqli_connect("localhost", "root", "", "dct-ccs-finals");
        
        if($conn === false){
            die("Error: Could not connect " .  mysqli_connect_error());
        }

        return $conn;
    }
    // sanitize input
    function sanitize_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
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
    
            $user = mysqli_fetch_assoc($result);
    
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $user ? $user: false;


        } 
        mysqli_close($conn);
        return false;
    }
    
    function displayErrors($errors) {
        $output = "<ul>";
        foreach ($errors as $error) {
            $output .= "<li>" . htmlspecialchars($error) . "</li>";
        }
        $output .= "</ul>";
        return $output;
    }

?>