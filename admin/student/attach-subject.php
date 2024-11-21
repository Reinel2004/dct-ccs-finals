<?php
session_start();
$pageTitle = "Attach Subject to Student";
include('../../functions.php');
include('../partials/header.php');

$studentToAttach = null;
$errors = [];

if (isset($_GET['student_id'])) {
    $student_id = sanitize_input($_GET['student_id']);
} elseif (isset($_POST['student_id'])) {
    $student_id = sanitize_input($_POST['student_id']);
} else {
    $errors[] = "No student selected.";
}

if (isset($student_id) && !empty($student_id)) {
   
    if (!empty($_SESSION['student_data'])) {
        foreach ($_SESSION['student_data'] as $student) {
            if ($student['student_id'] === $student_id) {
                $studentToAttach = $student;
                break;
            }
        }
    }
    if (!$studentToAttach) {
        $errors[] = "Student not found.";
    }
} else {
    $errors[] = "Student ID is missing.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!$student_id) {
        $errors[] = 'No student ID provided.';
    } elseif (isset($_POST['subject_codes']) && !empty($_POST['subject_codes'])) {
        // Sanitize and collect selected subject codes
        $subject_codes = array_map('sanitize_input', $_POST['subject_codes']);

        // Initialize session array for attached subjects if not already set
        if (!isset($_SESSION['attached_subjects'])) {
            $_SESSION['attached_subjects'] = [];
        }

        // Initialize the array for the specific student if not set
        if (!isset($_SESSION['attached_subjects'][$student_id])) {
            $_SESSION['attached_subjects'][$student_id] = [];
        }

        // Attach the selected subjects to the student
        $_SESSION['attached_subjects'][$student_id] = array_merge(
            $_SESSION['attached_subjects'][$student_id],
            $subject_codes
        );

        // Remove duplicates by making the list unique
        $_SESSION['attached_subjects'][$student_id] = array_unique($_SESSION['attached_subjects'][$student_id]);

    } else {
        $errors[] = 'At least one subject should be selected.';
    }
}

?>


<div class="container">
    <div class="row">
        <?php include('../partials/side-bar.php'); ?>
        <div class="col-lg-10 col-md-9 mt-5">
            <h2>Attach Subject to Student</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($studentToAttach): ?>
                <h3>Student Details</h3>
                <ul>
                    <li><strong>Student ID:</strong> <?= htmlspecialchars($studentToAttach['student_id']) ?></li>
                    <li><strong>Name:</strong> <?= htmlspecialchars($studentToAttach['first_name'] . ' ' . $studentToAttach['last_name']) ?></li>
                </ul>
            <?php endif; ?>

            <form method="post">
                <input type="hidden" name="student_id" value="<?= htmlspecialchars($student_id) ?>">

                <?php if (isset($student_id)): ?>

                    <?php 
                        $attached_subjects = $_SESSION['attached_subjects'][$student_id] ?? [];
                        $available_subjects = array_filter($_SESSION['subject_data'], function($subject) use ($attached_subjects) {
                            return !in_array($subject['subject_code'], $attached_subjects);
                        });
                    ?>
                    
                
                    <?php if (!empty($available_subjects)): ?>
                        <?php foreach ($available_subjects as $subject): ?>
                            <div>
                                <input type="checkbox" name="subject_codes[]" value="<?= htmlspecialchars($subject['subject_code']) ?>">
                                <?= htmlspecialchars($subject['subject_code']) ?> - <?= htmlspecialchars($subject['subject_name']) ?>
                            </div>
                        <?php endforeach; ?>
                        <button type="submit" class="btn btn-primary mt-3">Attach Selected Subjects</button>
                    <?php else: ?>
                        <p class="text-warning">All available subjects are already attached to this student.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-danger">No student ID provided.</p>
                <?php endif; ?>
            </form>

            <h3 class="mt-5">Attached Subjects</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($attached_subjects)): ?>
                        <?php foreach ($attached_subjects as $subject_code): ?>
                            <tr>
                                <td><?= htmlspecialchars($subject_code) ?></td>
                                <td>
                                    <?php
                                    foreach ($_SESSION['subject_data'] as $subject) {
                                        if ($subject['subject_code'] === $subject_code) {
                                            echo htmlspecialchars($subject['subject_name']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">No subjects attached.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
