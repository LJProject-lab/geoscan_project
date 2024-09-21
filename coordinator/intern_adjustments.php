<?php
include "nav.php";
include "crypt_helper.php";
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
        </li>

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
            <a class="nav-link" href="intern_adjustments.php">
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
        <h1>List of Intern Adjustments</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Intern Adjustments</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

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

    <div class="col-xl-12">

        <?php
        // Fetch pending adjustments for coordinator's interns
        $stmt = $pdo->prepare("
    SELECT a.*, u.firstname, u.lastname
    FROM tbl_adjustments a
    LEFT JOIN tbl_users u ON a.student_id = u.student_id
    WHERE a.status IN ('Pending', 'Approved', 'Adjusted', 'Rejected')
    AND u.coordinator_id = :coordinator_id
");
        $stmt->execute(['coordinator_id' => $_SESSION['coordinator_id']]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if there are any pending adjustments
        $hasPending = false;
        foreach ($users as $user) {
            if ($user['status'] == 'Pending') {
                $hasPending = true;
                break;
            }
        }
        ?>

        <div class="card">
            <div class="card-body">
                <table id="datatablesSimple" class="table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Date with no Time Out</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <?php if ($hasPending): ?>
                                <th>Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($user['records']); ?></td>
                                <td><?php echo htmlspecialchars($user['reason']); ?></td>
                                <td>
                                    <?php
                                    // Display status
                                    if ($user['status'] == 'Pending') {
                                        echo "<span class='badge badge-warning' style='color:black;background-color:orange;'>Pending</span>";
                                    } elseif ($user['status'] == 'Approved') {
                                        echo "<span class='badge badge-warning' style='color:black;background-color:orange;'>Under Review</span>";
                                    } elseif ($user['status'] == 'Rejected') {
                                        echo "<span class='badge badge-warning' style='color:white;background-color:red;'>Rejected</span>";
                                    } else {
                                        echo "<span class='badge badge-warning' style='color:white;background-color:#198754;'>Adjusted</span>";
                                    }
                                    ?>
                                </td>
                                <?php if ($hasPending): ?>
                                    <td>
                                        <?php if ($user['status'] == 'Pending'): ?>
                                            <button class="btn btn-success me-md-2" data-toggle='modal' data-target='#ReqModal'
                                                data-student_id="<?php echo $user['student_id']; ?>"
                                                data-records="<?php echo htmlspecialchars($user['records']); ?>"
                                                data-reason="<?php echo htmlspecialchars($user['reason']); ?>"
                                                data-id="<?php echo $user['id']; ?>">Approve</button>

                                            <button class="btn btn-danger me-md-2" data-toggle='modal' data-target='#RejectModal'
                                                data-student_id="<?php echo $user['student_id']; ?>"
                                                data-id="<?php echo $user['id']; ?>">Reject</button>
                                        <?php endif; ?>
                                    </td>

                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>



    <!-- Modal for requesting adjustment -->
    <div class="modal fade" id="ReqModal" tabindex="-1" role="dialog" aria-labelledby="ReqModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ReqModalLabel" style="color:#198754;font-weight:bold;">Request for
                        Adjustment</h5>
                    <i class="fa-solid fa-xmark" style="font-size:20px; cursor:pointer;" data-dismiss="modal"
                        aria-label="Close"></i>
                </div>
                <div class="modal-body">
                    <h4>Missing Time-Out Entries</h4>
                    <p>Please confirm your request for the following dates where the intern has missed logging their
                        time-out:</p>
                    <ul id="missing-dates"></ul>
                    <h5><b>Intern's Reason:</b></h5>
                    <p id="intern-reason"></p>
                </div>
                <div class="modal-footer">
                    <form id="approveForm" method="POST">
                        <input type="hidden" id="student_id" name="student_id">
                        <input type="hidden" id="adjustment_id" name="adjustment_id">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for rejecting adjustment -->
    <div class="modal fade" id="RejectModal" tabindex="-1" role="dialog" aria-labelledby="RejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="RejectModalLabel" style="color:#198754;font-weight:bold;">Reject
                        Adjustment</h5>
                    <i class="fa-solid fa-xmark" style="font-size:20px; cursor:pointer;" data-dismiss="modal"
                        aria-label="Close"></i>
                </div>
                <div class="modal-body">
                    <h4>Provide a reason for rejection:</h4>
                    <textarea id="reject-reason" class="form-control" rows="4"
                        placeholder="Enter rejection reason"></textarea>
                </div>
                <div class="modal-footer">
                    <form id="rejectForm" method="POST">
                        <input type="hidden" id="reject_student_id" name="student_id">
                        <input type="hidden" id="reject_adjustment_id" name="adjustment_id">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Submit Rejection</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</main><!-- End #main -->

<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="functions/js/intern-adjustments.js"></script>
<script src="functions/js/reject-adjustments.js"></script>
<?php include "footer.php"; ?>