<?php

// Connect to the database
require_once '../config.php';

// Fetch interns for the selection dropdown
$interns = $pdo->query("SELECT student_id, firstname, lastname FROM tbl_users")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Include Bootstrap CSS or your preferred CSS framework -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h1>Export Time Logs to Excel</h1>
    <form action="review_data.php" method="post">
        <div class="mb-3">
            <label for="student_id" class="form-label">Select Intern</label>
            <select class="form-select" id="student_id" name="student_id" required>
                <option value="" disabled selected>Select Intern</option>
                <?php foreach ($interns as $intern): ?>
                    <option value="<?php echo htmlspecialchars($intern['student_id']); ?>">
                        <?php echo htmlspecialchars($intern['lastname'] . ", " . $intern['firstname']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Process Data</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
