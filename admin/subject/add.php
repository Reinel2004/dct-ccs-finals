<?php
    session_start();
    $pageTitle = "Add Subject";

    include('../partials/header.php'); 
    include('../../functions.php');

   
    // if (empty($_SESSION['email'])) {
    //     header("Location: ../../index.php");
    //     exit;
    // }

    
    $errors = [];
    $subject_data = [];


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $subject_code = sanitize_input($_POST['subject_code']);
        $subject_name = sanitize_input($_POST['subject_name']);

       
        if (addSubjectData($subject_code, $subject_name)) {
            header("Location: add.php"); 
            exit;
        } else {
            $errors[] = "Failed to add subject. Check for duplicates or errors.";
        }
    }


    $conn = con();
    $sql_select = "SELECT * FROM subjects";
    $result = mysqli_query($conn, $sql_select);
    $subjects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
?>

<div class="container-fluid">
    <div class="row">
        <?php include('../partials/side-bar.php'); ?>

        <div class="col-lg-10 col-md-9 mt-5">
            <h2>Add a New Subject</h2>
            <br>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
                </ol>
            </nav>
            <hr>
            <br>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger mt-3">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="subject_code">Subject Code</label>
                    <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="Enter Subject Code" required>
                </div>
                <div class="form-group">
                    <label for="subject_name">Subject Name</label>
                    <input type="text" class="form-control" id="subject_name" name="subject_name" placeholder="Enter Subject Name" required>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Add Subject</button>
            </form>

            <hr>
            <h3 class="mt-5">Subject List</h3>

     
            <table class="table">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($subjects) > 0): ?>
                        <?php foreach ($subjects as $subject): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                                <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                                <td>
                                    <a href="edit.php?subject_code=<?php echo urlencode($subject['subject_code']); ?>" class="btn btn-info btn-sm">Edit</a>
                                    <a href="delete.php?subject_code=<?php echo urlencode($subject['subject_code']); ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No subjects found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
