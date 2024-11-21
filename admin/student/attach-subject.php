<?php
session_start();
$pageTitle = "Attach Subject to Student";

include '../partials/header.php';
include '../../functions.php';

// Uncomment the session validation if necessary
// if (empty($_SESSION['email'])) {
//     header("Location: ../index.php");
//     exit;
// }

// header("Cache-Control: no-store, no-cache, must-revalidate");
// header("Cache-Control: post-check=0, pre-check=0", false);
// header("Pragma: no-cache");

$student_id = $_GET['student_id'] ?? null;
$errors = [];

// Fetch selected student data
$studentToAttach = $student_id ? getSelectedStudentById($student_id) : null;

// Handle form submission for attaching subjects
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['subject_codes']) && !empty($_POST['subject_codes'])) {
        foreach ($_POST['subject_codes'] as $subject_code) {
            $subject_id = getSubjectIdByCode($subject_code);

            if ($subject_id && attachSubjectToStudent($student_id, $subject_id)) {
                // Successfully attached subject, continue to next
                continue;
            } else {
                $errors[] = "Failed to attach subject with code: $subject_code.";
            }
        }
    } else {
        $errors[] = 'At least one subject should be selected.';
    }
}

// Fetch attached subjects
$attachedSubjects = getAttachedSubjectsByStudentId($student_id);

// Fetch all subjects
$allSubjects = getAllSubjects();
$attachedSubjectCodes = array_column($attachedSubjects, 'subject_code');
$availableSubjects = array_filter($allSubjects, function ($subject) use ($attachedSubjectCodes) {
    return !in_array($subject['subject_code'], $attachedSubjectCodes);
});
?>

<div class="container">
    <div class="row">
        <?php include('../partials/side-bar.php'); ?>
        <div class="col-lg-10 col-md-9 mt-5">
            <h2>Attach Subject to Student</h2>
            <br>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Attach Subject to Student</li>
                </ol>
            </nav>
            <hr>
            <br>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>System Errors</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($studentToAttach): ?>
                <div class="container">
                    <h3>Selected Student Information</h3>
                    <ul>
                        <li><strong>Student ID:</strong> <?= htmlspecialchars($studentToAttach['student_id']) ?></li>
                        <li><strong>Name:</strong> <?= htmlspecialchars($studentToAttach['first_name'] . ' ' . $studentToAttach['last_name']) ?></li>
                    </ul>
                </div>
            <?php endif; ?>

            <hr>

            <form method="post">
                <?php if (!empty($availableSubjects)): ?>
                    <h3>Select Subjects to Attach</h3>
                    <?php foreach ($availableSubjects as $subject): ?>
                        <div>
                            <input 
                                type="checkbox" 
                                name="subject_codes[]" 
                                value="<?= htmlspecialchars($subject['subject_code']) ?>"
                            >
                            <?= htmlspecialchars($subject['subject_code']) ?> - <?= htmlspecialchars($subject['subject_name']) ?>
                        </div>
                    <?php endforeach; ?>
                    <br>
                    <button type="submit" class="btn btn-primary">Attach Subjects</button>
                <?php else: ?>
                    <p>No subjects available to attach.</p>
                <?php endif; ?>
            </form>

            <hr>
            <h3 class="mt-5">Attached Subjects for Student</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Grade</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($attachedSubjects)): ?>
                        <?php foreach ($attachedSubjects as $subject): ?>
                            <tr>
                                <td><?= htmlspecialchars($subject['subject_code']); ?></td>
                                <td><?= htmlspecialchars($subject['subject_name']); ?></td>
                                <td>
                                    <a href="detach-subject.php?student_id=<?= urlencode($student_id) ?>&subject_id=<?= urlencode($subject['subject_id']) ?>" class="btn btn-danger btn-sm">Detach Subject</a>
                                </td>
                                <td><a href="#" class="btn btn-primary btn-sm">Assign Grade</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No subjects attached.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
