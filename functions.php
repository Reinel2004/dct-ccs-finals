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
        $conn = conn();
    
        $sql = "SELECT * FROM students WHERE student_id = :student_id";
        $stmt = $conn->prepare($sql);
    
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
    
        $existing_student = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existing_student) {
            return ["Duplicate student id"];
        }
    
        return [];

        mysqli_close($conn);
    }

    function addStudentData($student_id, $student_firstname, $student_lastname){
        $checkStudentData = validateStudentData($student_id, $student_firstname, $student_lastname);
        $checkDuplicateData = checkDuplicateStudentData($student_id);

        if(count($checkStudentData) > 0){
            echo displayErrors($checkStudentData);
            return;
        }
    
        if(count($checkDuplicateData) == 1){
            echo displayErrors($checkDuplicateData);
            return;
        }
        

        $conn = con();

        try{
            $sql_insert = 'INSERT INTO students(student_id, student_firstname, student_lastname) VALUES (:student_id, :student_firstname, :student_lastname)';
            $stmt = $conn->prepare($sql_insert);

            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':first_name', $student_firstname);
            $stmt->bindParam(':last_name', $student_lastname);

            if ($stmt->execute()) {

                return true;
            } else {
                return "Error: Can't add data."; 
            } 
        } catch(PDOException $e) {
            return 'Error: ' . $e->getMessage();
        }

        mysqli_close($conn);
    }
    
    function selectStudents(){
        $conn = con();

    try {
        $sql_select = "SELECT * FROM students";
        $stmt = $conn->prepare($sql_select);

        $stmt->execute();

        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $students;
    } catch (PDOException $e) {
        return [];
    }
    }
?>