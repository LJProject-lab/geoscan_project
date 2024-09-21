<?php
include "nav.php";


$uploadedFilePath = isset($_SESSION['uploadedFilePath']) ? $_SESSION['uploadedFilePath'] : null;
unset($_SESSION['uploadedFilePath']);


function formatTimestamp($timestamp)
{
  $dateTime = new DateTime($timestamp);
  return $dateTime->format('F j, Y g:i a');
}
?>

<style>
  .file-card {
    border: 1px solid #ddd;
    border-radius: 0.5rem;
    padding: 1rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .file-card:hover {
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
  }

  .file-info {
    flex: 1;
  }

  .form-check-input {
    margin-right: 1rem;
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['alert_type']) && isset($_SESSION['alert_message'])): ?>
  <script>
    Swal.fire({
      icon: '<?php echo $_SESSION['alert_type']; ?>',
      title: '<?php echo $_SESSION['alert_message']; ?>',
      showConfirmButton: false,
      timer: 1500
    });
  </script>
  <?php
  // Clear the session data after showing the alert
  unset($_SESSION['alert_type']);
  unset($_SESSION['alert_message']);
?>
<?php endif; ?>

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

    <li class="nav-heading">Pages</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="my_attendance.php">
        <i class="ri-fingerprint-line"></i>
        <span>My Attendance</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link " href="requirement_checklist.php">
        <i class="bi bi-file-earmark-check-fill"></i>
        <span>Requirements Checklist</span>
      </a>
    </li><!-- End Login Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="progress_report.php">
        <i class="ri-line-chart-fill"></i>
        <span>Progress Report</span>
      </a>
    </li><!-- End Blank Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="settings.php">
        <i class='bx bxs-cog'></i>
        <span>Settings</span>
      </a>
    </li><!-- End Blank Page Nav -->

  </ul>

</aside><!-- End Sidebar-->

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Requirements Checklist</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Requirements Checklist</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button class="btn btn-success me-md-2" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
      <i class="bi bi-folder-symlink" style="color: #fff;"></i>&nbsp;Upload Forms
    </button>
  </div><br>

  <!-- Upload Forms Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Upload Forms</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- File Upload Form -->
          <form action="upload_file.php" method="post" enctype="multipart/form-data">
            <!-- Hidden Fields -->
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION['student_id']); ?>"
              name="student_id" required hidden>
            <input type="text" class="form-control" value="Pending" name="status" required hidden>

            <!-- Dropdown for Forms -->
            <div class="col-md-12">
              <label for="validationCustom04" class="form-label">List of Forms</label>
              <select class="form-select" id="validationCustom04" name="form_type" required>
                <option selected disabled value="">Choose...</option>
                <option value="PNC:AA-FO-20">PNC:AA-FO-20 Annual Report in the Implementation of Student Internship
                  Program in the Philippines (SIPP)</option>
                <option value="PNC:AA-FO-21">PNC:AA-FO-21 Report on the List of Host Training Establishments (HTES) and
                  Student Interns Participants Student Internship Program in the Philippines</option>
                <option value="PNC:AA-FO-22">PNC:AA-FO-22 Internship Host Training Establishment Evaluation Form
                </option>
                <option value="PNC:AA-FO-23">PNC:AA-FO-23 Internship Program Evaluation Form</option>
                <option value="PNC:AA-FO-24">PNC:AA-FO-24 Student Intern Performance Evaluation Form</option>
                <option value="PNC:AA-FO-25">PNC:AA-FO-25 Student Internship Training Plan Form</option>
                <option value="PNC:AA-FO-26">PNC:AA-FO-26 Request for HTE Recommendation Letter</option>
                <option value="PNC:AA-FO-27">PNC:AA-FO-27 Student Curriculum Vitae</option>
                <option value="PNC:AA-FO-28">PNC:AA-FO-28 Student Internship Consent Form</option>
                <option value="PNC:AA-FO-29">PNC:AA-FO-29 Student Internship Acceptance Form</option>
                <option value="PNC:AA-FO-30">PNC:AA-FO-30 Student Internship Daily Time Record (DTR) Form</option>
                <option value="PNC:AA-FO-31">PNC:AA-FO-31 Weekly Daily Journal</option>
              </select>
              <div class="invalid-feedback">
                Please select a valid form.
              </div>
            </div><br>

            <!-- File Input -->
            <div class="mb-3">
              <label for="file" class="form-label">Attach File</label>
              <input class="form-control" type="file" id="file" name="file" required>
            </div>
            <button type="submit" class="btn-main">Upload</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <?php
  $student_id = $_SESSION['student_id'];
  // Fetch the files related to the logged-in student
  $stmt = $pdo->prepare("SELECT * FROM tbl_requirements WHERE student_id = :student_id");
  $stmt->bindParam(':student_id', $student_id);
  $stmt->execute();
  $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <h5 class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
          Requirement Forms Checklist
          <button type="button" class="btn btn-warning btn-sm mt-2 mt-md-0" data-bs-toggle="modal"
            data-bs-target="#largeModal">
            <i class="ri-folders-line" style="color: #000;"></i> List of Forms need to comply
          </button>
        </h5>

        <br>

        <?php
        $student_id = $_SESSION['student_id'];

        // Fetch the approved form types for the specific student_id
        $stmt = $pdo->prepare("SELECT form_type FROM tbl_requirements WHERE student_id = :student_id AND status = 'Approved'");
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        $approvedForms = $stmt->fetchAll(PDO::FETCH_COLUMN);
        ?>

        <!-- List of Forms Modal -->
        <div class="modal fade" id="largeModal" tabindex="-1">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">List of Forms</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <ul class="list-group">
                  <?php
                  // List of form types and their full descriptions
                  $forms = [
                    "PNC:AA-FO-20" => "PNC:AA-FO-20 Annual Report in the Implementation of Student Internship Program in the Philippines (SIPP)",
                    "PNC:AA-FO-21" => "PNC:AA-FO-21 Report on the List of Host Training Establishments (HTES) and Student Interns Participants Student Internship Program in the Philippines",
                    "PNC:AA-FO-22" => "PNC:AA-FO-22 Internship Host Training Establishment Evaluation Form",
                    "PNC:AA-FO-23" => "PNC:AA-FO-23 Internship Program Evaluation Form",
                    "PNC:AA-FO-24" => "PNC:AA-FO-24 Student Intern Performance Evaluation Form",
                    "PNC:AA-FO-25" => "PNC:AA-FO-25 Student Internship Training Plan Form",
                    "PNC:AA-FO-26" => "PNC:AA-FO-26 Request for HTE Recommendation Letter",
                    "PNC:AA-FO-27" => "PNC:AA-FO-27 Student Curriculum Vitae",
                    "PNC:AA-FO-28" => "PNC:AA-FO-28 Student Internship Consent Form",
                    "PNC:AA-FO-29" => "PNC:AA-FO-29 Student Internship Acceptance Form",
                    "PNC:AA-FO-30" => "PNC:AA-FO-30 Student Internship Daily Time Record (DTR) Form",
                    "PNC:AA-FO-31" => "PNC:AA-FO-31 Weekly Daily Journal",
                  ];

                  foreach ($forms as $formCode => $formDescription) {
                    // Check if this form type is in the list of approved forms
                    $isChecked = in_array($formCode, $approvedForms) ? 'checked' : '';
                    echo "<li class='list-group-item'><input class='form-check-input' type='checkbox' $isChecked disabled> $formDescription</li>";
                  }
                  ?>
                </ul>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <?php if (count($files) > 0): ?>
          <?php foreach ($files as $file): ?>
            <div class="mb-4">
              <div
                class="file-card p-3 mb-3 border rounded d-flex flex-column flex-md-row justify-content-between align-items-start">
                <div class="file-info">
                  <input class="form-check-input me-2" type="checkbox" id="form<?php echo $file['id']; ?>" <?php echo $file['status'] === 'Approved' ? 'checked' : ''; ?> disabled>
                  <strong><?php echo htmlspecialchars($file['form_type']); ?></strong><br>
                  <small>File Name: <?php echo htmlspecialchars($file['file_name']); ?></small><br>
                  <small>Status:
                    <?php
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
                  </small><br>
                  <?php
                  $file['cancel_reason'] = '';
                  ?>
                  <small>Remarks: <?php echo htmlspecialchars($file['cancel_reason']); ?></small><br>
                  <small>Uploaded At: <?php echo formatTimestamp($file['uploaded_at']); ?></small>
                </div>

                <div class="mt-3 mt-md-0">
                  <?php if ($file['status'] === 'Pending' || $file['status'] === 'Cancelled'): ?>
                    <button type="button" class="btn btn-danger btn-sm me-2 delete-btn"
                      data-file-id="<?php echo $file['id']; ?>">
                      <i class="ri-delete-bin-5-line" style="color: #fff;"></i> Delete
                    </button>
                  <?php endif; ?>
                  <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                    data-bs-target="#fileModal<?php echo $file['id']; ?>">
                    <i class="ri-folder-open-line" style="color: #fff;"></i> View File
                  </button>
                </div>
              </div>

            </div>

            <!-- File Modal -->
            <div class="modal fade" id="fileModal<?php echo $file['id']; ?>" tabindex="-1"
              aria-labelledby="fileModalLabel<?php echo $file['id']; ?>" aria-hidden="true">
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
                    $filePath = htmlspecialchars($file['file_path']);
                    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                    if (in_array($fileExtension, ['pdf'])): ?>
                      <embed src="<?php echo $filePath; ?>" type="application/pdf" width="100%" height="500px" />
                    <?php elseif (in_array($fileExtension, ['png', 'jpg', 'jpeg'])): ?>
                      <img src="<?php echo $filePath; ?>" class="img-fluid" alt="File Preview">
                    <?php elseif (in_array($fileExtension, ['mp4', 'avi', 'mov'])): ?>
                      <video controls style="width: 100%; height: auto;">
                        <source src="<?php echo $filePath; ?>" type="video/<?php echo $fileExtension; ?>">
                        Your browser does not support the video tag.
                      </video>
                    <?php elseif (in_array($fileExtension, ['doc', 'docx'])): ?>
                      <p>Microsoft Word Document: <a href="<?php echo $filePath; ?>" target="_blank">Download Document</a></p>
                    <?php else: ?>
                      <p>Unsupported file type.</p>
                    <?php endif; ?>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No files have been submitted yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>
<!-- End #main -->

<div id="preloader">
  <div class="loader"></div>
</div>
<script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="../assets/js/main.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-btn').forEach(function (button) {
      button.addEventListener('click', function () {
        const fileId = this.getAttribute('data-file-id');

        Swal.fire({
          title: 'Are you sure?',
          text: 'This action cannot be undone!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'No, cancel!',
        }).then((result) => {
          if (result.isConfirmed) {
            // Create a form dynamically to submit the file deletion request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'delete_file.php';  // Your delete file script

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'file_id';
            input.value = fileId;
            form.appendChild(input);

            // Use Fetch API to submit the form and handle the response
            fetch('delete_file.php', {
              method: 'POST',
              body: new FormData(form)
            })
              .then(response => response.json())
              .then(data => {
                if (data.status === 'success') {
                  Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: data.message,
                    timer: 1500
                  }).then(() => {
                    // Optionally, reload the page or remove the deleted file element from the DOM
                    location.reload(); // Reloads the page to reflect changes
                  });
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message,
                    confirmButtonColor: '#3085d6',
                  });
                }
              })
              .catch(error => {
                Swal.fire({
                  icon: 'error',
                  title: 'Error!',
                  text: 'An unexpected error occurred.',
                  confirmButtonColor: '#3085d6',
                });
              });
          }
        });
      });
    });
  });
</script>

<?php include "footer.php"; ?>