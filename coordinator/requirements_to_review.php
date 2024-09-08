<?php
include "nav.php";
?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../assets/css/table.css" rel="stylesheet">
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link collapsed" href="index.php">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-heading">Configuration</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="add_intern.php">
        <i class="bi bi-person-plus-fill"></i>
        <span>Add Intern</span>
      </a>
    </li>

    <li class="nav-heading">Pages</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="intern.php">
        <i class="bi bi-people-fill"></i>
        <span>List of Intern</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="requirement_checklist.php">
        <i class="bi bi-file-earmark-check-fill"></i>
        <span>Requirements Checklist</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="interns_attendance.php">
        <i class="bx bxs-user-detail"></i>
        <span>Interns Attendance</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="interns_progress_report.php">
        <i class="ri-line-chart-fill"></i>
        <span>Progress Report</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="intern_adjustments.php">
        <i class='bx bxs-cog'></i>
        <span>Intern Adjustments</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="action_logs.php">
        <i class='bx bx-history'></i>
        <span>Audit Trail</span>
      </a>
    </li>

  </ul>

  </aside><!-- End Sidebar-->

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Requirements to Review</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Requirements to Review</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <table id="datatablesSimple" class="table">
          <thead>
            <tr>
              <th>Student ID</th>
              <th>Student Name</th>
              <th>Form</th>
              <th>Status</th>
              <th>Date Submitted</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt = $pdo->prepare("
                    SELECT r.id, r.student_id, 
                    CONCAT(u.firstname, ' ', u.lastname) AS student_name, 
                    r.form_type, 
                    r.file_name,
                    r.status,
                    r.file_path,
                    r.uploaded_at 
              FROM tbl_requirements r
              JOIN tbl_users u ON r.student_id = u.student_id
              WHERE u.coordinator_id = :coordinator_id
              AND r.status = 'Pending';
                ");
            $stmt->execute(['coordinator_id' => $_SESSION['coordinator_id']]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $row):
            ?>
            <tr>
              <td><?php echo htmlspecialchars($row['student_id']); ?></td>
              <td><?php echo htmlspecialchars($row['student_name']); ?></td>
              <td><?php echo htmlspecialchars($row['form_type']); ?></td>
              <td>
                <?php
                // Display status with badge styling
                if ($row['status'] == "Pending") {
                    echo "<span class='badge rounded-pill bg-warning text-dark'>" . htmlspecialchars($row['status']) . "</span>";
                } elseif ($row['status'] == "Approved") {
                    echo "<span class='badge rounded-pill bg-success text-white'>" . htmlspecialchars($row['status']) . "</span>";
                } elseif ($row['status'] == "Cancelled") {
                    echo "<span class='badge rounded-pill bg-danger text-white'>" . htmlspecialchars($row['status']) . "</span>";
                } else {
                    echo "<span class='badge rounded-pill bg-secondary text-white'>" . htmlspecialchars($row['status']) . "</span>";
                }
                ?>
              </td>
              <td><?php echo htmlspecialchars($row['uploaded_at']); ?></td>
              <td>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#fileModal<?php echo $row['id']; ?>">
                  <i class="ri-folder-open-line" style="color: #fff;"></i> Review File
                </button>
              </td>
            </tr>
            
<!-- Modal for file review -->
<div class="modal fade" id="fileModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="fileModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fileModalLabel<?php echo $row['id']; ?>">
          <?php echo htmlspecialchars($row['form_type']); ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php
        $filePath = '../Intern/requirements/' . htmlspecialchars($row['file_name']);
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        ?>
        <?php if (file_exists($filePath)): ?>
          <?php if (in_array($fileExtension, ['pdf'])): ?>
            <embed src="<?php echo $filePath; ?>" type="application/pdf" width="100%" height="500px" />
          <?php elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
            <img src="<?php echo $filePath; ?>" alt="Image preview" style="width: 100%; height: auto;" />
          <?php elseif (in_array($fileExtension, ['mp4', 'avi', 'mov'])): ?>
            <video controls style="width: 100%; height: auto;">
              <source src="<?php echo $filePath; ?>" type="video/<?php echo $fileExtension; ?>">
              Your browser does not support the video tag.
            </video>
          <?php elseif (in_array($fileExtension, ['doc', 'docx'])): ?>
            <p>Microsoft Word Document: <a href="<?php echo $filePath; ?>" target="_blank">Download Document</a></p>
          <?php elseif (in_array($fileExtension, ['xls', 'xlsx'])): ?>
            <p>Microsoft Excel Spreadsheet: <a href="<?php echo $filePath; ?>" target="_blank">Download Spreadsheet</a></p>
          <?php else: ?>
            <p>Unsupported file type.</p>
          <?php endif; ?>
        <?php else: ?>
          <p>File not found.</p>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <?php if ($row['status'] == "Pending"): ?>
          <form method="post" action="file_review.php" class="d-inline">
            <input type="hidden" name="file_id" value="<?php echo htmlspecialchars($row['id']); ?>">
            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($row['student_id']); ?>">
            <button type="submit" name="approve" class="btn btn-success">Approve</button>
          </form>
          <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal<?php echo $row['id']; ?>">Cancel</button>
        <?php endif; ?>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal for cancellation reason -->
<div class="modal fade" id="cancelModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="cancelModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cancelModalLabel<?php echo $row['id']; ?>">Cancel Requirement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="file_review.php">
          <input type="hidden" name="file_id" value="<?php echo htmlspecialchars($row['id']); ?>">
          <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($row['student_id']); ?>">
          <div class="mb-3">
            <label for="cancel_reason<?php echo $row['id']; ?>" class="form-label">Cancellation Reason</label>
            <textarea id="cancel_reason<?php echo $row['id']; ?>" name="cancel_reason" class="form-control" required></textarea>
          </div>
          <button type="submit" name="cancel" class="btn btn-danger">Submit Cancellation</button>
        </form>
      </div>
    </div>
  </div>
</div>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main><!-- End #main -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    switch (status) {
        case 'Approved':
            Swal.fire({
                title: 'Success!',
                text: 'The requirement has been approved.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            break;
        case 'Cancelled':
            Swal.fire({
                title: 'Cancelled!',
                text: 'The requirement has been cancelled.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            break;
        case 'error':
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while processing your request.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            break;
        default:
            // Do nothing if status is not recognized
            break;
    }
});
</script>

<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<?php include "footer.php"; ?>