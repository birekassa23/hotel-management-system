<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
           /* Internal CSS */
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            font-family: 'Times New Roman', Times, serif;
        }
        .container {
            margin-top: 20px;
        }

        .filter-buttons {
            margin-bottom: 20px;
        }

        .table-wrapper {
            position: relative;
        }

        /* Add some padding and margin for the table */
        .table th, .table td {
            padding: 10px;
        }

        /* Improve styling for the filter buttons */
        .btn-group button {
            font-weight: bold;
            padding: 10px;
        }
    </style>
</head>

<body style="font-family: 'Times New Roman', serif;">
    <!-- Navbar -->
    <?php include "assets/navbar.php"; ?>
    <div class="container">
        <h1 class="text-center">Employee Attendance</h1>
        <div class="filter-buttons d-flex justify-content-between mb-3 gap-3">
            <input type="date" id="search_date" class="form-control" style="font-size: 18px; font-weight: bold;">
            <div class="d-flex gap-2">
                <button class="btn btn-primary" onclick="filterAttendance('present')">Show Present</button>
                <button class="btn btn-danger" onclick="filterAttendance('absent')">Show Absent</button>
            </div>
        </div>

        <div class="table-wrapper">
            <?php
            // Include database connection
            include '../assets/conn.php';

            // SQL query to get all employees and their attendance status
            $sql = "SELECT e.id, e.f_name, e.l_name, e.sex, e.age, e.email, e.phone_no, e.is_present, a.attendance_date
                    FROM employees e 
                    LEFT JOIN attendance a ON e.id = a.employee_id";

            // Execute the query
            $result = $conn->query($sql);

            // Check if there are results
            if ($result->num_rows > 0) {
                // Output data in a table
                echo '<table class="table table-striped" id="attendance_table">';
                echo '<thead><tr>';
                echo '<th>ID</th>';
                echo '<th>First Name</th>';
                echo '<th>Last Name</th>';
                echo '<th>Sex</th>';
                echo '<th>Age</th>';
                echo '<th>Email</th>';
                echo '<th>Phone No</th>';
                echo '<th>Is Present</th>';
                echo '<th>Attendance Date</th>';
                echo '</tr></thead>';
                echo '<tbody>';

                // Fetch each row and display in table
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td>' . $row['f_name'] . '</td>';
                    echo '<td>' . $row['l_name'] . '</td>';
                    echo '<td>' . $row['sex'] . '</td>';
                    echo '<td>' . $row['age'] . '</td>';
                    echo '<td>' . $row['email'] . '</td>';
                    echo '<td>' . $row['phone_no'] . '</td>';
                    echo '<td>' . ($row['is_present'] == 'yes' ? 'Present' : 'Absent') . '</td>';
                    echo '<td>' . ($row['attendance_date'] ? $row['attendance_date'] : 'N/A') . '</td>';
                    echo '</tr>';
                }

                echo '</tbody></table>';
            } else {
                echo '<p class="text-center">No attendance records found.</p>';
            }

            // Close connection
            $conn->close();
            ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../assets/footer.php'; ?>

    <script>
        function filterAttendance(status) {
            const table = document.getElementById('attendance_table');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let row of rows) {
                const isPresentCell = row.cells[7]; // Adjust index based on your table structure
                const isPresent = isPresentCell.textContent.trim().toLowerCase();

                if (status === 'present' && isPresent === 'present') {
                    row.style.display = '';
                } else if (status === 'absent' && isPresent === 'absent') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        // Handle Date Search
        document.getElementById('search_date').addEventListener('change', function () {
            const date = this.value;
            const table = document.getElementById('attendance_table');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let row of rows) {
                const dateCell = row.cells[8]; // Adjust index based on your table structure
                const attendanceDate = dateCell.textContent.trim();

                if (date === '' || attendanceDate === date) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
