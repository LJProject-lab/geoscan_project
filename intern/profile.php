<?php
include "nav.php";

$student_id = $_SESSION['student_id'];

// Check if student_id is set
if (isset($student_id)) {
    // Query to fetch the profile picture filename from the database
    $sql = "SELECT profile_pic FROM tbl_users WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    
    // Bind the student_id parameter
    $stmt->bindParam(':student_id', $student_id);
    
    // Execute the query
    $stmt->execute();
    
    // Fetch the result as an associative array
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If profile_pic exists in the database, use it. Otherwise, fall back to a default image
    $profile_pic = !empty($user['profile_pic']) ? $user['profile_pic'] : 'profile.png';
} else {
    echo "User is not logged in.";
    exit;
}
?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../assets/css/table.css" rel="stylesheet">
<!-- ======= Sidebar ======= -->

<aside id="sidebar" class="sidebar">

<style>
    /* profile.css */

.profile-card {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  padding: 20px;
  margin: 20px;
}

.profile-header {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.profile-picture {
  flex: 0 0 150px;
  margin-right: 20px;
}

.profile-picture img {
  border-radius: 50%;
  width: 150px;
  height: 150px;
  object-fit: cover;
}

.profile-details {
  flex: 1;
}

.profile-details h2 {
  margin: 0;
  color: #198754;
  font-size: 24px;
}

.profile-details p {
  margin: 5px 0;
  font-size: 16px;
}

.profile-body {
  margin-top: 20px;
}

.profile-body h3 {
  margin: 0;
  font-size: 20px;
}

.profile-body p {
  font-size: 16px;
  line-height: 1.6;
}

</style>

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
  <a class="nav-link collapsed" href="requirement_checklist.php">
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
    <h1>My Profile</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">My Profile</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

            <img src="uploads/profile_pics/<?php echo htmlspecialchars($profile_pic, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Picture" style="border-radius: 5px;" >
              <!--<img src="uploads/<?php echo $newFileName; ?>" alt="Profile" class="rounded-circle">-->
              <h2><?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); ?></h2>
              <h3>Intern</h3>

              <div class="center-text">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verticalycentered">
                  <i class="ri-image-2-fill" style="color: #fff;"></i> Upload Profile</button>
              </div>

              <form method="POST" action="upload.php" enctype="multipart/form-data">
                <div class="modal fade" id="verticalycentered" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Change Profile Picture</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row mb-3">
                          <div class="col-sm-12">
                            <input class="form-control" type="file" name="profile_image" id="formFile">
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                      </div>
                    </div>
                  </div>
              </form>

                </div>

            </div>
          </div>

        </div>


        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Pin</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
              
                  <h5 class="card-title">Profile Details</h5>

                <?php
                $program_id = $_SESSION['program_id'];

                $sql = "SELECT p.program_name 
                FROM tbl_users u
                INNER JOIN tbl_programs p ON u.program_id = p.program_id
                WHERE u.program_id = ?"
                ?>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Firstname</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['firstname']; ?></div>
                  </div>
                  
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Lastname</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['lastname']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Program</div>
                    <div class="col-lg-9 col-md-8">
                    <?php
                    $sql = "
                    SELECT p.program_name 
                    FROM tbl_users u
                    INNER JOIN tbl_programs p ON u.program_id = p.program_id
                    WHERE u.program_id = :program_id";
            
                    // Prepare the statement
                    $stmt = $pdo->prepare($sql);
                
                    // Bind the parameter (use named placeholder :program_id)
                    $stmt->bindParam(':program_id', $program_id, PDO::PARAM_INT);
                
                    // Execute the statement
                    $stmt->execute();
                
                    // Fetch the result
                    $program_name = $stmt->fetchColumn();
                
                    if ($program_name) {
                        // Echo the program name
                        echo htmlspecialchars($program_name);
                    } else {
                        echo "No program found for the given program ID.";
                    }
                    ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['email']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Contact Number</div>
                    <div class="col-lg-9 col-md-8"><?php echo "0" . $_SESSION['phone']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Address</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['address']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Student ID</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['student_id']; ?></div>
                  </div>

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form id="changePinForm">
                    <div class="row mb-3">
                        <label for="currentPin" class="col-md-4 col-lg-3 col-form-label">Current Pin</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="pin" type="password" class="form-control" id="currentPin" placeholder="Enter Current 4 pin" required
                            minlength="4" maxlength="4" pattern="[0-9]{4}" title="Please enter a 4-digit number." oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="newPin" class="col-md-4 col-lg-3 col-form-label">New Pin</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="newpin" type="password" class="form-control" id="newPin" placeholder="Enter new 4 digit pin" required 
                            minlength="4" maxlength="4" pattern="[0-9]{4}" title="Please enter a 4-digit number." oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="renewPin" class="col-md-4 col-lg-3 col-form-label">Re-enter New Pin</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="renewpin" type="password" class="form-control" id="newPin" placeholder="Re-enter new 4 digit pin" required
                            minlength="4" maxlength="4" pattern="[0-9]{4}" title="Please enter a 4-digit number." oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-success"><i class="ri-lock-2-line" style="color:#fff;"> </i>Change Pin</button>
                    </div>
                </form>

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>

</main><!-- End #main -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    document.getElementById('changePinForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    // Gather form data
    const formData = new FormData(this);

    // Send AJAX request to change password
    fetch('change_password.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Display success or error message based on server response
        if (data.trim() === 'success') {
            Swal.fire({
                title: 'Success!',
                text: 'Pin changed successfully.',
                icon: 'success',
                confirmButtonText: 'Ok'
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: data,
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error!',
            text: 'Failed to change pin.',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
        console.error('Error:', error);
    });
});


document.getElementById('changePinForm').addEventListener('submit', function(event) {
    const newPin = document.getElementById('newPin').value;

    if (!/^\d{4}$/.test(newPin)) {
        event.preventDefault(); // Prevent form submission
        Swal.fire({
            title: 'Error!',
            text: 'Please enter a valid 4-digit pin.',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
    }
});

    </script>

<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
  crossorigin="anonymous"></script>

<?php include "footer.php"; ?>