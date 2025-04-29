<?php
//include database connection
include '../../assets/conn.php';

// Fetch total number of employees
$sql_total = "SELECT COUNT(*) AS total_employees FROM employees";
$result_total = $conn->query($sql_total);
$total_no_of_employee = $result_total->fetch_assoc()['total_employees'];

// Fetch count of specific roles
$sql_roles = "
    SELECT 
        COUNT(IF(position = 'Admin', 1, NULL)) AS total_admin,
        COUNT(IF(position = 'Manager', 1, NULL)) AS total_manager,
        COUNT(IF(position = 'Casher', 1, NULL)) AS total_casher,
        COUNT(IF(position = 'Receptionist', 1, NULL)) AS total_reception,
        COUNT(IF(position = 'Host', 1, NULL)) AS total_host,
        COUNT(IF(position = 'Bar Man', 1, NULL)) AS total_barman
    FROM employees
";
$result_roles = $conn->query($sql_roles);
$roles_count = $result_roles->fetch_assoc();

$total_no_of_admin = $roles_count['total_admin'];
$total_no_of_manager = $roles_count['total_manager'];
$total_no_of_casher = $roles_count['total_casher'];
$total_no_of_reception = $roles_count['total_reception'];
$total_no_of_host = $roles_count['total_host'];
$total_no_of_barman = $roles_count['total_barman'];

// Fetch employee data
$sql_employees = "SELECT id, f_name, l_name, sex, age, email, phone_no, position, edu_status, document, kebele_id FROM employees";
$result_employees = $conn->query($sql_employees);

$employees = [];
if ($result_employees) {
    while ($row = $result_employees->fetch_assoc()) {
        $employees[] = $row;
    }
} else {
    echo "Error fetching employee data: " . $conn->error;
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees - Ehitimamachochi Hotel</title>
    <!-- Include Footer -->
    <?php include '../assets/header.php'; ?>
    <style>
        .section {
            display: none;
            /* Hide all sections by default */
        }

        .section.active {
            display: block;
            /* Show active section */
        }

        #navbar {
            height: 100px;
        }

        .navbar-nav {
            align-items: center;
            padding-bottom: 0;
        }

        .nav-item:hover {
            font-size: 18px;
        }
    </style>
</head>

<body style="display: flex; flex-direction: column; min-height: 100vh; margin: 0; font-family: 'Times New Roman';">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="margin-bottom: 20px;">
        <div class="container-xl">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar"
                aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="width: 100%; justify-content: center;">
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="http://localhost/New/Ehitimamachochi/Manager/index.php"
                            style="color: white !important; cursor: pointer;">
                            <span style="font-size: 1.2rem; margin-right: 5px;">&#8592;</span> Go Back
                        </a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="#" onclick="showSection('default')"
                            style="color: white !important;">Home</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="#" onclick="showSection('register')"
                            style="color: white !important;">Register Employee</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="#" onclick="showSection('update')"
                            style="color: white !important;">Update Employee</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="#" onclick="showSection('view')" style="color: white !important;">View
                            Employee</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="#" onclick="showSection('delete')"
                            style="color: white !important;">Delete Employee</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Default Section (Home) -->
    <div id="default" class="section active">
        <h3 class="text-center mt-4">Welcome to the Employee Management System</h3>

        <!-- Card displaying total number of employees -->
        <!-- <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card text-center shadow-lg">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Employee Summary</h5>
                        </div>
                        <div class="card-body">
                            <h3 class="text">We have <strong><?php echo $total_no_of_employee; ?></strong> total
                                employees.</h3>
                            <p class="card-text">Admins: <strong><?php echo $total_no_of_admin; ?></strong></p>
                            <p class="card-text">Managers: <strong><?php echo $total_no_of_manager; ?></strong></p>
                            <p class="card-text">Cashiers: <strong><?php echo $total_no_of_casher; ?></strong></p>
                            <p class="card-text">Receptionists: <strong><?php echo $total_no_of_reception; ?></strong>
                            </p>
                            <p class="card-text">Hosts: <strong><?php echo $total_no_of_host; ?></strong></p>
                            <p class="card-text">Barmen: <strong><?php echo $total_no_of_barman; ?></strong></p>
                            <p class="card-text">and more, manage them effectively.</p>
                        </div>
                        <div class="card-footer bg-light">
                            <small class="text-muted">Last updated: <?php echo date("F j, Y, g:i a"); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>

    <style>
        /* Custom CSS for added style */
        .card {
            border-radius: 0.75rem;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            background: linear-gradient(135deg, #ffffff, #f7f7f7);
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            padding: 0.75rem 1rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .card-text {
            text-align: left;
            padding-left: 15px;
            font-size: 1rem;
            color: #333;
        }

        .card-footer {
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            text-align: right;
            padding: 0.5rem 1rem;
        }

        body {
            background: #f0f4f8;
        }

        h3 {
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            color: #34495e;
        }
    </style>


    <div class="container" style="flex: 1; padding: 20px;">
        <!-- Register Employee Section -->
        <div id="register" class="section active">
            <h3 class="text-center">Register Employee</h3>
            <form method="POST" action="add_employee.php" enctype="multipart/form-data"
                onsubmit="return validateForm()">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="f_name" class="form-label">First Name</label>
                            <input type="text" id="f_name" name="f_name" class="form-control" required>
                            <span id="f_name_warning" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="sex" class="form-label">Gender</label>
                            <select name="sex" id="sex" class="form-select" required>
                                <option value="" disabled selected>Select sex</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="phone_no" class="form-label">Phone Number</label>
                            <input type="tel" id="phone_no" name="phone_no" class="form-control" required>
                            <span id="phone_no_warning" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="edu_status" class="form-label">Educational Status</label>
                            <select name="edu_status" id="edu_status" class="form-select">
                                <option value="" disabled selected>Select Educational Status</option>
                                <option value="elementary">Elementary</option>
                                <option value="highschool">High School</option>
                                <option value="bachelor">Bachelor's Degree</option>
                                <option value="degree">Degree</option>
                                <option value="master">Master's Degree</option>
                                <option value="doctorate">Doctorate</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="document" class="form-label">Document</label>
                            <input type="file" id="document" name="document" class="form-control" required>
                            <span id="document_warning" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="cbe_no" class="form-label">CBE Account Number</label>
                            <input type="number" id="cbe_no" name="Account_no" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="l_name" class="form-label">Last Name</label>
                            <input type="text" id="l_name" name="l_name" class="form-control" required>
                            <span id="l_name_warning" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="age" class="form-label">Age:</label>
                            <input type="number" id="age" name="age" class="form-control" required>
                            <span id="age_warning" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email :</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Position :</label>
                            <select id="position" name="position" class="form-select" required>
                                <option value="" disabled selected>Select position</option>
                                <option value="Admin">Admin</option>
                                <option value="Manager">Manager</option>
                                <option value="Receptionist">Receptionist</option>
                                <option value="Bar Man">Bar Man</option>
                                <option value="Casher">Casher</option>
                                <option value="Host">Host</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kebele_id" class="form-label">Kebele ID</label>
                            <input type="file" id="kebele_id" name="kebele_id" class="form-control" required>
                            <span id="kebele_id_warning" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="number" id="salary" name="salary" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div style="display: flex; justify-content: center; gap: 10%;">
                    <button type="submit"
                        style="width: 40%; background-color: #007bff; border: none; color: white; padding: 10px; border-radius: 5px; text-align: center; font-size: 16px; cursor: pointer;">Register</button>
                    <button type="reset"
                        style="width: 40%; background-color: #dc3545; border: none; color: white; padding: 10px; border-radius: 5px; text-align: center; font-size: 16px; cursor: pointer;">Clear</button>
                </div>
            </form>
            <!--  -->
            <script>
                function validateAge() {
                    const age = document.getElementById('age').value;
                    let isValid = true;

                    if (age < 20) {
                        document.getElementById('age_warning').textContent = 'Age must be greater than 20.';
                        isValid = false;
                    } else {
                        document.getElementById('age_warning').textContent = '';
                    }

                    return isValid;
                }

                function validatePhoneNo() {
                    const phoneNo = document.getElementById('phone_no').value;
                    const phonePattern = /^(09\d{8}|\+2519\d{8})$/;
                    let isValid = true;

                    if (!phonePattern.test(phoneNo)) {
                        document.getElementById('phone_no_warning').textContent = 'Invalid phone number. It should start with 09 or +2519 and be 10 or 13 digits long.';
                        isValid = false;
                    } else {
                        document.getElementById('phone_no_warning').textContent = '';
                    }

                    return isValid;
                }

                function validateName(nameId, warningId) {
                    const name = document.getElementById(nameId).value;
                    const namePattern = /^[A-Za-z]+$/;
                    let isValid = true;

                    if (!namePattern.test(name)) {
                        document.getElementById(warningId).textContent = 'Name should only contain letters and no spaces.';
                        isValid = false;
                    } else {
                        document.getElementById(warningId).textContent = '';
                    }

                    return isValid;
                }

                function validateDocument() {
                    const documentFile = document.getElementById('document').files[0];
                    let isValid = true;

                    if (documentFile && !['image/jpeg', 'image/png', 'application/pdf'].includes(documentFile.type)) {
                        document.getElementById('document_warning').textContent = 'Invalid file type for document. Only JPEG, PNG, or PDF allowed.';
                        isValid = false;
                    } else {
                        document.getElementById('document_warning').textContent = '';
                    }

                    return isValid;
                }

                function validateKebeleId() {
                    const kebeleIdFile = document.getElementById('kebele_id').files[0];
                    let isValid = true;

                    if (kebeleIdFile && !['image/jpeg', 'image/png', 'application/pdf'].includes(kebeleIdFile.type)) {
                        document.getElementById('kebele_id_warning').textContent = 'Invalid file type for Kebele ID. Only JPEG, PNG, or PDF allowed.';
                        isValid = false;
                    } else {
                        document.getElementById('kebele_id_warning').textContent = '';
                    }

                    return isValid;
                }

                // Add event listeners for real-time validation
                document.getElementById('age').addEventListener('input', validateAge);
                document.getElementById('phone_no').addEventListener('input', validatePhoneNo);
                document.getElementById('f_name').addEventListener('input', function () { validateName('f_name', 'f_name_warning'); });
                document.getElementById('l_name').addEventListener('input', function () { validateName('l_name', 'l_name_warning'); });
                document.getElementById('document').addEventListener('change', validateDocument);
                document.getElementById('kebele_id').addEventListener('change', validateKebeleId);

                // Call validateForm on form submission
                document.querySelector('form').addEventListener('submit', function (event) {
                    const isAgeValid = validateAge();
                    const isPhoneNoValid = validatePhoneNo();
                    const isFNameValid = validateName('f_name', 'f_name_warning');
                    const isLNameValid = validateName('l_name', 'l_name_warning');
                    const isDocumentValid = validateDocument();
                    const isKebeleIdValid = validateKebeleId();

                    if (!isAgeValid || !isPhoneNoValid || !isFNameValid || !isLNameValid || !isDocumentValid || !isKebeleIdValid) {
                        event.preventDefault(); // Prevent form submission if validation fails
                    }
                });
            </script>
        </div>







        <!-- View Employee Section -->
        <div id="view" class="section">
            <h3 class="text-center">View Employee</h3>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Sex</th>
                        <th>Age</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Position</th>
                        <th>Educational Status</th>
                        <th>Document</th>
                        <th>Kebele ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employee['id']); ?></td>
                            <td><?php echo htmlspecialchars($employee['f_name']); ?></td>
                            <td><?php echo htmlspecialchars($employee['l_name']); ?></td>
                            <td><?php echo htmlspecialchars($employee['sex']); ?></td>
                            <td><?php echo htmlspecialchars($employee['age']); ?></td>
                            <td><?php echo htmlspecialchars($employee['email']); ?></td>
                            <td><?php echo htmlspecialchars($employee['phone_no']); ?></td>
                            <td><?php echo htmlspecialchars($employee['position']); ?></td>
                            <td><?php echo htmlspecialchars($employee['edu_status']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($employee['document']); ?>">View</a></td>
                            <td><a href="<?php echo htmlspecialchars($employee['kebele_id']); ?>">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>




        <!-- Update Employee Section -->
        <div>
            <div id="update" class="section" style="max-width: 100%; margin: 0 auto;">
                <h3 class="text-center">Update Employee</h3>
                <form action="update_employees.php" method="post" enctype="multipart/form-data">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Sex</th>
                                    <th>Age</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Position</th>
                                    <th>Educational Status</th>
                                    <th>Document</th>
                                    <th>Kebele ID</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employees as $employee): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($employee['id']); ?></td>
                                        <td><input type="text" name="f_name[]"
                                                value="<?php echo htmlspecialchars($employee['f_name']); ?>"></td>
                                        <td><input type="text" name="l_name[]"
                                                value="<?php echo htmlspecialchars($employee['l_name']); ?>"></td>
                                        <td>
                                            <select name="sex[]" class="form-select" required>
                                                <option value="" disabled>Select sex</option>
                                                <option value="male" <?php echo $employee['sex'] === 'male' ? 'selected' : ''; ?>>Male</option>
                                                <option value="female" <?php echo $employee['sex'] === 'female' ? 'selected' : ''; ?>>Female</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="age[]"
                                                value="<?php echo htmlspecialchars($employee['age']); ?>"></td>
                                        <td><input type="email" name="email[]"
                                                value="<?php echo htmlspecialchars($employee['email']); ?>"></td>
                                        <td><input type="text" name="phone_no[]"
                                                value="<?php echo htmlspecialchars($employee['phone_no']); ?>"></td>
                                        <td>
                                            <select name="position[]" class="form-select">
                                                <option value="" disabled>Select position</option>
                                                <option value="Admin" <?php echo $employee['position'] === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                                                <option value="Manager" <?php echo $employee['position'] === 'Manager' ? 'selected' : ''; ?>>Manager</option>
                                                <option value="Receptionist" <?php echo $employee['position'] === 'Receptionist' ? 'selected' : ''; ?>>
                                                    Receptionist</option>
                                                <option value="Bar Man" <?php echo $employee['position'] === 'Bar Man' ? 'selected' : ''; ?>>Bar Man</option>
                                                <option value="Casher" <?php echo $employee['position'] === 'Casher' ? 'selected' : ''; ?>>Casher</option>
                                                <option value="Host" <?php echo $employee['position'] === 'Host' ? 'selected' : ''; ?>>Host</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="edu_status[]" class="form-select">
                                                <option value="" disabled>Select Educational Status</option>
                                                <option value="elementary" <?php echo $employee['edu_status'] === 'elementary' ? 'selected' : ''; ?>>Elementary</option>
                                                <option value="highschool" <?php echo $employee['edu_status'] === 'highschool' ? 'selected' : ''; ?>>High School</option>
                                                <option value="bachelor" <?php echo $employee['edu_status'] === 'bachelor' ? 'selected' : ''; ?>>Bachelor's Degree</option>
                                                <option value="degree" <?php echo $employee['edu_status'] === 'degree' ? 'selected' : ''; ?>>Degree</option>
                                                <option value="master" <?php echo $employee['edu_status'] === 'master' ? 'selected' : ''; ?>>Master's Degree</option>
                                                <option value="doctorate" <?php echo $employee['edu_status'] === 'doctorate' ? 'selected' : ''; ?>>Doctorate</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" name="current_document[]"
                                                value="<?php echo htmlspecialchars($employee['document']); ?>">
                                            <input type="file" name="document[]">
                                        </td>
                                        <td>
                                            <input type="hidden" name="current_kebele_id[]"
                                                value="<?php echo htmlspecialchars($employee['kebele_id']); ?>">
                                            <input type="file" name="kebele_id[]">
                                        </td>
                                        <td>
                                            <input type="hidden" name="ids[]"
                                                value="<?php echo htmlspecialchars($employee['id']); ?>">
                                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>






        <!-- Delete Employee Section -->
        <div id="delete" class="section">
            <h3 class="text-center">Delete Employee</h3>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Sex</th>
                        <th>Age</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Position</th>
                        <th>Educational Status</th>
                        <th>Document</th>
                        <th>Kebele ID</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employee['id']); ?></td>
                            <td><?php echo htmlspecialchars($employee['f_name']); ?></td>
                            <td><?php echo htmlspecialchars($employee['l_name']); ?></td>
                            <td><?php echo htmlspecialchars($employee['sex']); ?></td>
                            <td><?php echo htmlspecialchars($employee['age']); ?></td>
                            <td><?php echo htmlspecialchars($employee['email']); ?></td>
                            <td><?php echo htmlspecialchars($employee['phone_no']); ?></td>
                            <td><?php echo htmlspecialchars($employee['position']); ?></td>
                            <td><?php echo htmlspecialchars($employee['edu_status']); ?></td>
                            <td><a href="uploads/<?php echo htmlspecialchars($employee['document']); ?>"
                                    target="_blank">View</a></td>
                            <td><a href="uploads/<?php echo htmlspecialchars($employee['kebele_id']); ?>"
                                    target="_blank">View</a></td>
                            <td>
                                <form action="delete_employee.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($employee['id']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include '../assets/footer.php'; ?>

    <script>
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => section.classList.remove('active'));

            // Show the selected section
            document.getElementById(sectionId).classList.add('active');
        }

        // Show the default section on page load
        document.addEventListener('DOMContentLoaded', function () {
            showSection('default');
        });
    </script>
</body>

</html>