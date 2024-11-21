<?php
session_start();
$pageTitle = "Delete Subject";
include('../../functions.php');
include('../partials/header.php'); 

// if (empty($_SESSION['email'])) {
//     header("Location: ../index.php");
//     exit;
// }


?>

<div class="container mt-5">
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
            <?php // if ($subjectToDelete): ?>
                <h5>Are you sure you want to delete the following subject record?</h5>
                <ul>
                    <!-- <li><strong>Subject Code:</strong> <?= htmlspecialchars($subjectToDelete['subject_code']) ?></li>
                    <li><strong>Subject Name:</strong> <?= htmlspecialchars($subjectToDelete['subject_name']) ?></li> -->
                </ul>
                <form method="post">
                    <input type="hidden" name="subject_code" value="<?= htmlspecialchars($subjectToDelete['subject_code']) ?>">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='add.php';">Cancel</button>
                    <button type="submit" class="btn btn-primary">Delete Subject Record</button>
                </form>
            <?php  // endif; ?> 
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
