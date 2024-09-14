<?php
include "nav.php";
require_once 'functions/fetch-records.php';

// Fetch coordinator ID from session or another source
$coordinator_id = $_SESSION['coordinator_id']; // Make sure this is set properly

// Fetch all interns for this coordinator
$internsQuery = "
    SELECT U.student_id, U.firstname, U.lastname, U.program_id 
    FROM tbl_users AS U 
    WHERE U.coordinator_id = :coordinator_id
";
$internsStmt = $pdo->prepare($internsQuery);
$internsStmt->execute(['coordinator_id' => $coordinator_id]);
$interns = $internsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch progress data for each intern
$progressData = [];
foreach ($interns as $intern) {
    $progressData[] = [
        'student_id' => $intern['student_id'],
        'name' => $intern['firstname'] . ' ' . $intern['lastname'],
        'progress' => getInternProgress($intern['student_id'], $intern['program_id'], $pdo)
    ];
}
?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../assets/css/table.css" rel="stylesheet">

<style>
    .small-chart {
        width: 100%;
        max-height: 200px;
    }
</style>
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
            <a class="nav-link" href="#">
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
        <h1>Interns Progress Report</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Interns Progress Report</li>
            </ol>
        </nav>
    </div>

    <!-- Filtering Input -->
    <div class="d-grid gap-2 d-md-flex justify-content">
        <input type="text" id="searchInput" class="form-control" placeholder="Search Intern"
            style="width: 50%; max-width: 500px;">

        <a href="interns_attendance.php" style="color:white;"> <button class="btn btn-success me-md-2" type="button"> <i
                    class='bx bxs-message-square-detail'></i> &nbsp; View All Logs </button></a>

    </div>
    <br>
    <!-- Loop through progress data and create a canvas for each intern -->
    <?php foreach ($progressData as $data): ?>
        <div class="col-xl-12 card-container">
            <div class="card">
                <div class="card-body">
                    <h5><b><?php echo htmlspecialchars($data['name']); ?></b></h5>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    </div>
                    <canvas id="progressChart-<?php echo $data['student_id']; ?>" class="small-chart"></canvas>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</main>

<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Fetch progress data from PHP
    const progressData = <?php echo json_encode($progressData); ?>;

    // Initialize the charts
    progressData.forEach(data => {
        const canvasId = `progressChart-${data.student_id}`;
        const ctx = document.getElementById(canvasId);
        if (ctx) {
            const context = ctx.getContext('2d');
            new Chart(context, {
                type: 'bar',
                data: {
                    labels: ['Progress'],
                    datasets: [
                        {
                            label: 'Hours Rendered',
                            data: [data.progress.total_hours],
                            backgroundColor: '#198754',
                        },
                        {
                            label: 'Hours Remaining',
                            data: [data.progress.hours_remaining],
                            backgroundColor: '#f68c09',
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Internship Progress'
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                            beginAtZero: true,
                            max: data.progress.required_hours
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            });
        } else {
            console.warn(`Canvas with ID ${canvasId} not found.`);
        }
    });

    // Filter charts based on search input
    document.getElementById('searchInput').addEventListener('input', function () {
        const searchValue = this.value.toLowerCase();
        const allCharts = document.querySelectorAll('.card-container');

        allCharts.forEach(container => {
            const internName = container.querySelector('h5').textContent.toLowerCase();
            if (internName.includes(searchValue) || searchValue === '') {
                container.style.display = 'block'; // Show matching charts
            } else {
                container.style.display = 'none'; // Hide non-matching charts
            }
        });
    });
</script>

<?php include "footer.php"; ?>