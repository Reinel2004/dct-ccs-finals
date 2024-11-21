<?php 
session_start();
$pageTitle = "Assign Grade to Student";

include '../partials/header.php';
include '../../functions.php';


$student_id = $_GET['student_id'] ?? null;
$subject_code = $_GET['subject_code'] ?? null;
$subject = null;


if ($student_id && $subject_code) {
    $student = getSelectedStudentById($student_id);
    $subject = getSubjectByCode($subject_code);
    
    if (!$student || !$subject) {
        $_SESSION['error_message'] = "Invalid student or subject.";
        header("Location: register.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "Student or subject not specified.";
    header("Location: register.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['grade'])) {
    $grade = $_POST['grade'];

    if ($grade >= 0 && $grade <= 100) {
        if (assignGradeToStudent($student_id, $subject['id'], $grade)) {
            header("Location: attach-subject.php?student_id=" . urlencode($student_id));
            exit;
        } else {
            $_SESSION['error_message'] = "Failed to assign grade.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid grade. Enter a number between 0 and 100.";
    }
}
?>

<div class="container">
    <div class="row">
        <?php include('../partials/side-bar.php'); ?>
        <div class="col-lg-10 col-md-9 mt-5">
            <h2>Assign Grade to Student</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Assign Grade</li>
                </ol>
            </nav>
            <div class="card mt-3">
                <div class="card-body">
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['error_message']); ?>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($_SESSION['success_message']); ?>
                        </div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>

                    <h3>Selected Student and Subject Information</h3>
                    <ul>
                        <li><strong>Student ID:</strong> <?= htmlspecialchars($student['student_id']) ?></li>
                        <li><strong>Name:</strong> <?= htmlspecialchars($student['first_name']) . ' ' . htmlspecialchars($student['last_name']) ?></li>
                        <li><strong>Subject Code:</strong> <?= htmlspecialchars($subject['subject_code']) ?></li>
                        <li><strong>Subject Name:</strong> <?= htmlspecialchars($subject['subject_name']) ?></li>
                    </ul>
                    <hr>
                    <form method="POST">
                        <label for="grade" class="form-label">Grade</label>
                        <input type="number" class="form-control" id="grade" name="grade" min="0" max="100" required>
                        <br>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='register.php';">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign Grade to Subject</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
