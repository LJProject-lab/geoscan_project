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
        $stmt = $pdo->prepare("
            SELECT a.*, u.firstname, u.lastname
            FROM tbl_adjustments a
            LEFT JOIN tbl_users u ON a.student_id = u.student_id
            WHERE a.status = 'Pending'
            AND u.coordinator_id = " . $_SESSION['coordinator_id'] . "
        ");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                            <th>Action</th>
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
                                    <a href="view_intern.php?student_id=<?php echo urlencode(encryptData($user['student_id'])); ?>"
                                        class="btn btn-success btn-sm"><i class="bi bi-eye"></i> Approve</a>
                                    <!-- <a href="javascript:void(0);" onclick="confirmDelete('<?php echo $user['student_id']; ?>')"
                    class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Remove</a> -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>


    </div>



</main><!-- End #main -->

<script>
    function confirmDelete(studentId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'remove_intern.php?student_id=' + studentId;
            }
        });
    }
</script>


<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>

<?php include "footer.php"; ?>