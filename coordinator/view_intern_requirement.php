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
    <a class="nav-link collapsed" href="dashboard.php">
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
        $stmt = $pdo->prepare("SELECT firstname, lastname, program_id FROM tbl_users WHERE student_id = :student_id");
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
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="assets/img/intern.png" alt="Profile" class="rounded-circle">
                        <h2><?php echo htmlspecialchars($user['firstname'] . " " . $user['lastname']); ?></h2>
                        <h3>Intern</h3>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <h3><b>Requirement Checklist</b></h3>
                    </div>
                    <?php
                    // Fetch student_id from the URL
                    $student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

                    // Validate student_id (simple validation)
                    if (empty($student_id)) {
                        echo "No student ID provided.";
                        exit;
                    }

                    // Fetch the approved form types for the specific student_id
                    $stmt = $pdo->prepare("SELECT form_type FROM tbl_requirements WHERE student_id = :student_id AND status = 'Approved'");
                    $stmt->bindParam(':student_id', $student_id);
                    $stmt->execute();
                    $approvedForms = $stmt->fetchAll(PDO::FETCH_COLUMN);

                    // List of form types and their full descriptions
                    $forms = [
                        "PNC:AA-FO-20" => "Annual Report in the Implementation of Student Internship Program in the Philippines (SIPP)",
                        "PNC:AA-FO-21" => "Report on the List of Host Training Establishments (HTES) and Student Interns Participants Student Internship Program in the Philippines",
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
                    ?>

                    <div class="card-body profile-card pt-4 d-flex flex-column">
                        <div class="col-12">
                            <?php foreach ($forms as $formCode => $formDescription): ?>
                                <div class="form-check" >
                                    <input class="form-check-input" style="opacity:1 !important;" type="checkbox" id="formCheck<?php echo $formCode; ?>"
                                        <?php echo in_array($formCode, $approvedForms) ? 'checked' : ''; ?> disabled/>
                                    <label class="form-check-label"  style="opacity:1 !important;" for="formCheck<?php echo $formCode; ?>">
                                        <?php echo htmlspecialchars($formDescription); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">
                        <h5><?php echo htmlspecialchars($user['firstname'] . " " . $user['lastname']); ?>'s submitted requirements</h5>

                        <div class="card-body">
                            <table id="datatablesSimple" class="table">
                                <thead>
                                    <tr>
                                        <th>Form</th>
                                        <th>Uploaded File</th>
                                        <th>Status</th>
                                        <th>Date Submitted</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($files) > 0): ?>
                                        <?php foreach ($files as $file): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($file['form_type']); ?></td>
                                                <td><?php echo htmlspecialchars($file['file_name']); ?></td>
                                                <td>
                                                    <?php
                                                    // Display status with badge styling
                                                    if ($file['status'] == "Pending") {
                                                        echo "<span class='badge rounded-pill bg-warning text-dark'>" . htmlspecialchars($file['status']) . "</span>";
                                                    } elseif ($file['status'] == "Approved") {
                                                        echo "<span class='badge rounded-pill bg-success text-white'>" . htmlspecialchars($file['status']) . "</span>";
                                                    } elseif ($file['status'] == "Cancelled") {
                                                        echo "<span class='badge rounded-pill bg-danger text-white'>" . htmlspecialchars($file['status']) . "</span>";
                                                    } else {
                                                        echo "<span class='badge rounded-pill bg-secondary text-white'>" . htmlspecialchars($file['status']) . "</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo formatTimestamp($file['uploaded_at']); ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#fileModal<?php echo $file['id']; ?>">
                                                        <i class="ri-folder-open-line" style="color: #fff;"></i> View File
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- File Detail Modal -->
                                            <div class="modal fade" id="fileModal<?php echo $file['id']; ?>" tabindex="-1" aria-labelledby="fileModalLabel<?php echo $file['id']; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="fileModalLabel<?php echo $file['id']; ?>">
                                                                <?php echo htmlspecialchars($file['file_name']); ?>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?php
                                                            $filePath = '../Intern/requirements/' . htmlspecialchars($file['file_name']);
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
                                                                    <p>Microsoft Word Document: <a href="<?php echo $filePath; ?>" target="_blank">Dowwnload Document</a></p>
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
                                                            <?php if (isset($file['status']) && $file['status'] == "Cancelled"): ?>
                                                            <form method="post" action="update_file_status.php" class="d-inline">
                                                                <input type="hidden" name="file_id" value="<?php echo htmlspecialchars($file['id']); ?>">
                                                                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">
                                                                <button type="submit" name="approve" class="btn btn-success">Approve</button>
                                                            </form>
                                                            <?php elseif (isset($file['status']) && $file['status'] == "Approved"): ?>
                                                            <form method="post" action="update_file_status.php" class="d-inline">
                                                                <input type="hidden" name="file_id" value="<?php echo htmlspecialchars($file['id']); ?>">
                                                                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">
                                                                <button type="submit" name="cancel" class="btn btn-danger">Cancel</button>
                                                            </form>
                                                            <?php else: ?>
                                                            <form method="post" action="update_file_status.php" class="d-inline">
                                                                <input type="hidden" name="file_id" value="<?php echo htmlspecialchars($file['id']); ?>">
                                                                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">
                                                                <button type="submit" name="approve" class="btn btn-success">Approve</button>
                                                            </form>
                                                            <form method="post" action="update_file_status.php" class="d-inline">
                                                                <input type="hidden" name="file_id" value="<?php echo htmlspecialchars($file['id']); ?>">
                                                                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">
                                                                <button type="submit" name="cancel" class="btn btn-danger">Cancel</button>
                                                            </form>
                                                            <?php endif; ?>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5">No files have been submitted yet.</td>
                                        </tr>
                                    <?php endif; ?>
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