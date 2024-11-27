<?php
include "nav.php";
include "crypt_helper.php";
?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../assets/css/table.css" rel="stylesheet">
<!-- ======= Sidebar ======= -->

<style>
    h5{
        font-weight: 600;
    }
    .text-balck{
        color: #000;
        cursor: not-allowed;
    }
</style>

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

    <li class="nav-item">
      <a class="nav-link collapsed" href="generate_report.php">
        <i class="ri-folder-download-line"></i>
        <span>Generate Intern Report</span>
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
    <a class="nav-link " href="requirement_checklist.php">
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
    <a class="nav-link collapsed" href="action_logs.php">
    <i class='bx bx-history' ></i>
        <span>Audit Trail</span>
    </a>
    </li>

    </ul>

</aside><!-- End Sidebar-->

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="requirement_checklist.php">Requirement Checklist</a></li>
                <li class="breadcrumb-item active">Interns Requirement</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <?php
if (isset($_GET['student_id'])) {
    $student_id = decryptData($_GET['student_id']);

    // Fetch user details
    $stmt = $pdo->prepare("SELECT firstname, lastname, program_id, profile_pic FROM tbl_users WHERE student_id = :student_id");
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch submitted files for the given student_id
    $stmt = $pdo->prepare("SELECT * FROM tbl_requirements WHERE student_id = :student_id");
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();
    $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $files = [];  // Initialize as an empty array if no student_id is provided
}

// Function to format timestamp
function formatTimestamp($timestamp) {
    return date('F j, Y g:i a', strtotime($timestamp));
}
?>

<section class="section profile">
    <div class="row">

        <div class="col-xl-12">
            <div class="card">
                <div class="card-body pt-3">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="../intern/uploads/profile_pics/<?php echo $user['profile_pic'] ? htmlspecialchars($user['profile_pic'], ENT_QUOTES, 'UTF-8') : 'profile.png'; ?>"class="rounded-circle" width="100" height="100">
                        <h2><?php echo htmlspecialchars($user['firstname'] . " " . $user['lastname']); ?></h2>
                        <h3>Intern</h3>
                    </div>

                    <div class="card-body">
                        <table id="datatablesSimple" class="table">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Form</th>
                                    <th>Uploaded File</th>
                                    <th>Date Submitted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // List of form types and their full descriptions
                                $forms = [
                                    "PNC:AA-FO-20" => "Annual Report in the Implementation of Student Internship Program in the PHI. (SIPP)",
                                    "PNC:AA-FO-21" => "Report on the List of HTES and Student Interns Participants Student Internship Program in the PHI.",
                                    "PNC:AA-FO-22" => "Internship Host Training Establishment Evaluation Form",
                                    "PNC:AA-FO-23" => "Internship Program Evaluation Form",
                                    "PNC:AA-FO-24" => "Student Intern Performance Evaluation Form",
                                    "PNC:AA-FO-25" => "Student Internship Training Plan Form",
                                    "PNC:AA-FO-26" => "Request for HTE Recommendation Letter",
                                    "PNC:AA-FO-27" => "Student Curriculum Vitae",
                                    "PNC:AA-FO-28" => "Student Internship Consent Form",
                                    "PNC:AA-FO-29" => "Student Internship Acceptance Form",
                                    "PNC:AA-FO-30" => "Student Internship Daily Time Record (DTR) Form",
                                    "PNC:AA-FO-31" => "Weekly Daily Journal"
                                ];

                                // Fetch the approved form types for the specific student_id
                                $stmt = $pdo->prepare("SELECT form_type, status, file_name, id, uploaded_at FROM tbl_requirements WHERE student_id = :student_id");
                                $stmt->bindParam(':student_id', $student_id);
                                $stmt->execute();
                                $submittedForms = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Convert the submitted forms into an associative array for easy access
                                $submittedFormsAssoc = [];
                                foreach ($submittedForms as $file) {
                                    $submittedFormsAssoc[$file['form_type']] = $file;
                                }
                                ?>

                                <?php foreach ($forms as $formCode => $formDescription): ?>
                                    <tr>
                                    <td>
                                            <?php
                                            // Display status with badge styling
                                            if (isset($submittedFormsAssoc[$formCode])) {
                                                $status = $submittedFormsAssoc[$formCode]['status'];
                                                if ($status == "Pending") {
                                                    echo "<span class='badge rounded-pill bg-warning text-dark'><i class='bi bi-exclamation-square'></i> &nbsp;" . htmlspecialchars($status) . "</span>";
                                                } elseif ($status == "Approved") {
                                                    echo "<span class='badge rounded-pill bg-success text-white'><i class='bi bi-check2-square'></i> &nbsp;" . htmlspecialchars($status) . "</span>";
                                                } elseif ($status == "Cancelled") {
                                                    echo "<span class='badge rounded-pill bg-danger text-white'><i class='bi bi-exclamation-square'></i> &nbsp;" . htmlspecialchars($status) . "</span>";
                                                }
                                            } else {
                                                echo "<span class='badge rounded-pill bg-secondary text-white'><i class='bi bi-question-square'></i> &nbsp;Missing</span>";
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($formDescription); ?></td>
                                        <td>
                                            <?php
                                            // Check if the form is submitted
                                            if (isset($submittedFormsAssoc[$formCode])) {
                                                echo htmlspecialchars($submittedFormsAssoc[$formCode]['file_name']);
                                            } else {
                                                echo "-";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            // Display date submitted or "N/A" if not submitted
                                            if (isset($submittedFormsAssoc[$formCode])) {
                                                echo formatTimestamp($submittedFormsAssoc[$formCode]['uploaded_at']);
                                            } else {
                                                echo "-";
                                            }
                                            ?>
                                        </td>
                                        <td>
    <?php if (isset($submittedFormsAssoc[$formCode])): ?>
        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#fileModal<?php echo $submittedFormsAssoc[$formCode]['id']; ?>">
            <i class="ri-folder-open-line" style="color: #fff;"></i> View File
        </button>
        
        <!-- File Detail Modal -->
        <div class="modal fade" id="fileModal<?php echo $submittedFormsAssoc[$formCode]['id']; ?>" tabindex="-1" aria-labelledby="fileModalLabel<?php echo $submittedFormsAssoc[$formCode]['id']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileModalLabel<?php echo $submittedFormsAssoc[$formCode]['id']; ?>">
                            <?php echo htmlspecialchars($submittedFormsAssoc[$formCode]['file_name']); ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $filePath = '../Intern/requirements/' . htmlspecialchars($submittedFormsAssoc[$formCode]['file_name']);
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
                        <?php if ($submittedFormsAssoc[$formCode]['status'] == "Pending"): ?>
                            <!-- Approve Button -->
                            <form method="post" action="update_file_status.php" class="d-inline">
                                <input type="hidden" name="file_id" value="<?php echo htmlspecialchars($submittedFormsAssoc[$formCode]['id']); ?>">
                                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">
                                <button type="submit" name="approve" class="btn btn-success">Approve</button>
                            </form>

                            <!-- Cancel Button with Reason -->
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal<?php echo $submittedFormsAssoc[$formCode]['id']; ?>">Cancel</button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancel Modal -->
        <div class="modal fade" id="cancelModal<?php echo $submittedFormsAssoc[$formCode]['id']; ?>" tabindex="-1" aria-labelledby="cancelModalLabel<?php echo $submittedFormsAssoc[$formCode]['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelModalLabel<?php echo $submittedFormsAssoc[$formCode]['id']; ?>">Cancellation Reason</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="update_file_status.php">
                            <input type="hidden" name="file_id" value="<?php echo htmlspecialchars($submittedFormsAssoc[$formCode]['id']); ?>">
                            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">
                            <input type="hidden" name="cancel" value="Cancelled">
                            <div class="mb-3">
                                <label for="cancelReason<?php echo $submittedFormsAssoc[$formCode]['id']; ?>" class="form-label">Reason for Cancellation</label>
                                <textarea class="form-control" id="cancelReason<?php echo $submittedFormsAssoc[$formCode]['id']; ?>" name="cancel_reason" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger">Cancel File</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>





</main><!-- End #main -->
<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
  crossorigin="anonymous"></script>

<?php include "footer.php"; ?>