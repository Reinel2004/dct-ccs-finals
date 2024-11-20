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

    function logOut($loginForm){
        unset($_SESSION['email']);

        
        session_destroy();
        header("Location: $loginForm");
        exit;
    }


    function guard() {
        $indexPage = 'index.php';
        if (empty($_SESSION['email']) && basename($_SERVER['PHP_SELF']) != $indexPage) {
            header("Location: $indexPage"); 
            exit;
        }
    }

    function checkUserSessionIsActive() {
        $dashboardPage = 'admin/dashboard.php';
        $indexPage = 'index.php';
        if (isset($_SESSION['email']) && basename($_SERVER['PHP_SELF']) == $indexPage) {
            header("Location: $dashboardPage");
            exit;
        }
    }

    function validateSubjectData($subject_data) {
        $errors = [];
        if (empty($subject_data['subject_code'])) {
            $errors[] = "Subject Code is required.";
        }
        if (empty($subject_data['subject_name'])) {
            $errors[] = "Subject Name is required.";
        }
        return $errors;
    }
    

    function validateStudentData($student_data) {
   
        $errors = [];
        if (empty($student_data['student_id'])) {
            $errors[] = "Student ID is required.";
        }
        if (empty($student_data['first_name'])) {
            $errors[] = "First Name is required.";
        }
    
        if (empty($student_data['last_name'])) {
            $errors[] = "Last Name is required.";
        }
    
        return $errors;
    
    }

    function checkDuplicateStudentData($student_id) {
        $conn = con(); // Establish connection
    
        $sql = "SELECT student_id FROM students WHERE student_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            // Bind the parameter
            mysqli_stmt_bind_param($stmt, "s", $student_id);
            mysqli_stmt_execute($stmt);
    
            $result = mysqli_stmt_get_result($stmt);
            $existing_student = mysqli_fetch_assoc($result);
    
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
    
            // Check if a matching row is found
            if ($existing_student) {
                return ["Duplicate Student ID found: " . $student_id];
            }
        } else {
            mysqli_close($conn);
            return ["Error checking duplicate student ID"];
        }
    
        // No duplicates found
        return [];
    }
    
    

    function addStudentData($student_id, $student_firstname, $student_lastname) {
        $checkStudentData = validateStudentData([
            'student_id' => $student_id,
            'first_name' => $student_firstname,
            'last_name' => $student_lastname,
        ]);
        $checkDuplicateData = checkDuplicateStudentData($student_id);
    
        if (count($checkStudentData) > 0) {
            echo displayErrors($checkStudentData);
            return false;
        }
    
        if (count($checkDuplicateData) > 0) {
            echo displayErrors($checkDuplicateData);
            return false;
        }
    
        $conn = con();
    

        $sql_insert = "INSERT INTO students (student_id, first_name, last_name) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql_insert);
    
        if ($stmt) {
            
            mysqli_stmt_bind_param($stmt, "sss", $student_id, $student_firstname, $student_lastname);
    
         
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                return true;
            } else {
                echo "Error: " . mysqli_error($conn); 
            }
        } else {
            echo "Error preparing statement: " . mysqli_error($conn);
        }
    
       
        mysqli_close($conn);
        return false;
    }
    
    
    function selectStudents() {
        $conn = con();
    
        $sql_select = "SELECT * FROM students";
        $result = mysqli_query($conn, $sql_select);
    
        $students = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $students[] = $row;
            }
        }
    
        mysqli_close($conn);
    
        return $students;
    }

    function getSelectedStudentById($student_id) {
        $conn = con();
    
        try {
            $sql = "SELECT * FROM students WHERE student_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
    
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $student_id);
                mysqli_stmt_execute($stmt);
    
                $result = mysqli_stmt_get_result($stmt);
    
                if ($result && mysqli_num_rows($result) > 0) {
                    $student = mysqli_fetch_assoc($result);
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    return $student; 
                } else {
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    return null; 
                }
            }
        } catch (Exception $e) {
           
            return null;
        }
    }
    
?>