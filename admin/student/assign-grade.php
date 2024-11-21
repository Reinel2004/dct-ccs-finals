<?php 
session_start();
$pageTitle = "Assign Grade to Student";

include '../partials/header.php';
include '../../functions.php';

?>


<div class="container">
    <div class="row">
        <?php include('../partials/side-bar.php'); ?>
        <div class="col-lg-10 col-md-9 mt-5">
            <h2>Delete a Student</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
                </ol>
            </nav>
            <div class="card mt-3">
                <div class="card-body">

                   
                        <h3>Selected Student and Subject Information</h3>
                        <ul>
                            <!-- <li><strong>Student ID:</strong> <?= htmlspecialchars($studentToDelete['student_id']) ?></li>
                            <li><strong>Name:</strong> <?= htmlspecialchars($studentToDelete['first_name']) . ' ' . htmlspecialchars($studentToDelete['last_name']) ?> </li>
                            <li><strong>Subject Code:</strong> </li>
                            <li><strong>Subject Name:</strong> </li> -->
                        </ul>
                        <hr>
                        <form method="POST">
                            <label for="grade" class="form-label">Grade</label>
                            <input type="number" class="form-control" id="grade" name="grade">
                            <br>
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='register.php';">Cancel</button>
                            <button type="submit" class="btn btn-primary">Assign Grade to Subject</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>