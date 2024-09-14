<?php
session_start();
require 'config.php';
require 'includes/getAddress.php';
$dateFilter = $_GET['date'] ?? date('Y-m-d');

// Initialize $timelogs
$timelogs = [];

try {
    $sql = "SELECT * FROM tbl_timelogs WHERE DATE(timestamp) = :date";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':date' => $dateFilter]);
    $timelogs = $stmt->fetchAll();
} catch (Exception $e) {
    die('Error fetching timelogs: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'includes/top_include.php'; ?>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        img {
            max-width: 50px; /* Adjust as needed */
            height: auto;
            cursor: pointer; /* Indicate the image is clickable */
        }
        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4); /* Black with opacity */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Student Timelogs</h1>
    <form method="get" action="coordinators.php">
        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($dateFilter); ?>">
        <button type="submit">Filter</button>
    </form>
    <br>
    <a href="register_v3.php">Register Student</a>
    <br>
    <br>
    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Longitude</th>
                <th>Latitude</th>
                <th>Address</th>
                <th>Records</th>
                <th>Date & Time</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($timelogs)): ?>
                <?php foreach ($timelogs as $log): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($log['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($log['longitude']); ?></td>
                        <td><?php echo htmlspecialchars($log['latitude']); ?></td>
                        <td><?php echo htmlspecialchars(getAddress($log['latitude'], $log['longitude'])); ?></td>
                        <td><?php echo htmlspecialchars($log['type']); ?></td>
                        <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
                        <td>
                            <?php if (!empty($log['photo'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($log['photo']); ?>" alt="photo" onclick="openModal('uploads/<?php echo htmlspecialchars($log['photo']); ?>')">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No timelogs found for the selected date.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="Large photo" style="width: 100%; height: auto;">
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the image and insert it inside the modal
        function openModal(imageSrc) {
            var modalImg = document.getElementById("modalImage");
            modal.style.display = "block";
            modalImg.src = imageSrc;
        }

        // Close the modal
        function closeModal() {
            modal.style.display = "none";
        }

        // Close the modal when clicking outside of the modal content
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
