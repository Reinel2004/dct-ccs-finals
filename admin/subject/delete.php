<?php
session_start();
$pageTitle = "Delete Subject";
include('../../functions.php');
include('../partials/header.php'); 

// if (empty($_SESSION['email'])) {
//     header("Location: ../index.php");
//     exit;
// }

$subjectToDelete = null;
$errors = [];

if (isset($_GET['subject_code'])) {
    $subject_code = sanitize_input($_GET['subject_code']);
    $subjectToDelete = getSubjectByCode($subject_code); 

    if (!$subjectToDelete) {
        $errors[] = "Subject not found.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject_code'])) {
    $subject_code = sanitize_input($_POST['subject_code']);

    $deleteSubject = deleteSubjectByCode($subject_code);

    if ($deleteSubject) {

        header("Location: add.php");
        exit;
    } else {
        $errors[] = "Failed to delete the student record. Please try again.";
    }
}

?>

<div class="container">
    <div class="row">
        <?php include('../partials/side-bar.php'); ?>
        <div class="col-lg-10 col-md-9 mt-5">
            <h2>Delete a Subject</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Delete Subject</li>
                </ol>
            </nav>
            <div class="card mt-3">
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($subjectToDelete): ?>
                        <h5>Are you sure you want to delete the following subject record?</h5>
                        <ul>
                            <li><strong>Subject Code:</strong> <?= htmlspecialchars($subjectToDelete['subject_code']) ?></li>
                            <li><strong>Subject Name:</strong> <?= htmlspecialchars($subjectToDelete['subject_name']) ?></li>
                        </ul>
                        <form method="post">
                            <input type="hidden" name="subject_code" value="<?= htmlspecialchars($subjectToDelete['subject_code']) ?>">
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='add.php';">Cancel</button>
                            <button type="submit" class="btn btn-primary">Delete Subject Record</button>
                        </form>
                    <?php endif; ?> 
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php include '../partials/footer.php'; ?>
