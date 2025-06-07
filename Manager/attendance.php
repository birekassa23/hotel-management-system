<?php
// Include database connection
include '../assets/conn.php';

// Retrieve form data for attendance
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['take_attendance'])) {
    if (isset($_POST['emp_id'], $_POST['is_present'])) {
        $emp_id = $conn->real_escape_string($_POST['emp_id']);
        $is_present = $conn->real_escape_string($_POST['is_present']);
        // Set the current date
        $attendance_date = date('Y-m-d');
        // Convert `is_present` value ('yes' means present, anything else means absent)
        $is_present_value = ($is_present === 'yes') ? 'yes' : 'not';
        try {
            // Start transaction
            $conn->begin_transaction();
            // Check if the attendance record for this employee on this date already exists
            $check_sql = "SELECT * FROM attendance WHERE employee_id = '$emp_id' AND DATE(attendance_date) = '$attendance_date'";
            $result = $conn->query($check_sql);
            if ($result->num_rows > 0) {
                // If the record exists, update it
                $sql = "UPDATE attendance SET is_present = '$is_present_value', attendance_date = NOW() WHERE employee_id = '$emp_id' AND DATE(attendance_date) = '$attendance_date'";
                if ($conn->query($sql) === TRUE) {
                    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Attendance has been updated to present.',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.history.back();
                                        }
                                    });
                                });
                            </script>";
                } else {
                    throw new Exception("Error updating attendance: " . $conn->error);
                }
            } else {
                // If the record doesn't exist, insert a new one
                $sql = "INSERT INTO attendance (employee_id, attendance_date, is_present) VALUES ('$emp_id', NOW(), '$is_present_value')";
                if ($conn->query($sql) === TRUE) {
                    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Attendance recorded successfully.',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.history.back();
                                        }
                                    });
                                });
                            </script>";
                } else {
                    throw new Exception("Error recording attendance: " . $conn->error);
                }
            }

            // Update the employee's status in the `employees` table
            $update_sql = "UPDATE employees SET is_present = '$is_present_value' WHERE id = '$emp_id'";
            if ($conn->query($update_sql) === TRUE) {
                $conn->commit();
            } else {
                throw new Exception("Error updating employee status: " . $conn->error);
            }

        } catch (Exception $e) {
            $conn->rollback();
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: '" . $e->getMessage() . "',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.history.back();
                                }
                            });
                        });
                    </script>";
        }
        // Close connection
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Panel</title>
    <!-- Include Header with all the resources -->
    <?php include 'assets/header.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
               /* Internal CSS */
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card-body {
            padding: 2rem;
        }
        .input-group-text {
            background-color: #007bff;
            color: white;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body class="d-flex flex-column">
  <!-- Navbar -->
  <?php include "assets/navbar.php"; ?>

  <!-- Search Form -->
  <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Search Employee</h5>
                    <form class="d-flex">
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-person"></i></span>
                            <input type="search" class="form-control" name="emp_id" id="emp_id" placeholder="Enter Employee ID" aria-label="Search" aria-describedby="basic-addon1">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>
                    <!-- Search Results -->
                    <div id="search-results" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
  </div>

    <!-- Include Footer -->
    <?php include '../assets/footer.php'; ?>
  <script>
    // JavaScript for real-time search
    document.getElementById('emp_id').addEventListener('input', function() {
        var empId = this.value;

        if (empId.length > 0) {
            fetch('fetch_employee.php?emp_id=' + encodeURIComponent(empId))
                .then(response => response.json())
                .then(data => {
                    let output = '';

                    // Create table structure
                    output += `
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Position</th>
                                    <th>Email</th>
                                    <th>is_present</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    if (data.length > 0) {
                        data.forEach(employee => {
                            output += `
                                <tr>
                                    <td><b>${employee.id}</b></td>
                                    <td>${employee.f_name}</td>
                                    <td>${employee.l_name}</td>
                                    <td>${employee.position}</td>
                                    <td>${employee.email}</td>
                                    <td>
                                        <button class="btn btn-success" onclick="submitEmployeeForm('${employee.id}', '${employee.is_present}')">
                                            ${employee.is_present === 'yes' ? 'Present' : 'Mark Present'}
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        output += '<tr><td colspan="6">No employees found</td></tr>';
                    }

                    output += `
                            </tbody>
                        </table>
                    `;

                    document.getElementById('search-results').innerHTML = output;
                })
                .catch(error => console.error('Error fetching employee data:', error));
        } else {
            document.getElementById('search-results').innerHTML = '';
        }
    });

    // Function to create and submit a form with employee details
    function submitEmployeeForm(id, is_present) {
        if (is_present === 'yes') {
            Swal.fire({
                icon: 'info',
                title: 'Employee Already Present',
                text: 'This employee has already been marked as present today.',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Create a form element
        var form = document.createElement('form');
        form.method = 'post';
        form.innerHTML = `
            <input type="hidden" name="emp_id" value="${id}">
            <input type="hidden" name="is_present" value="yes">
            <input type="hidden" name="take_attendance" value="1">
        `;
        
        // Append the form and submit it
        document.body.appendChild(form);
        form.submit();
    }
  </script>
</body>
</html>
