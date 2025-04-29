<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance</title>
    <!-- Include Header with all the resources -->
    <?php include 'assets/header.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

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

        .content {
            flex: 1;
        }

        .list-group-item {
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
            font-size: 1.1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .list-group-item:hover {
            background-color: #e9f6fd;
            transform: translateX(5px);
        }

        .list-group-item i {
            font-size: 1.3rem;
            color: #007bff;
            transition: color 0.3s ease;
        }

        .list-group-item:hover i {
            color: #0056b3;
        }

        .list-group-item span {
            flex-grow: 1;
            font-size: 1rem;
        }

        .list-group-item .bi-chevron-right {
            color: #007bff;
            transition: transform 0.3s ease;
        }

        .list-group-item:hover .bi-chevron-right {
            transform: translateX(5px);
        }

        /* Tooltip styling */
        .list-group-item span i {
            position: relative;
        }

        .list-group-item span i::after {
            content: attr(data-bs-title);
            display: none;
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 5px;
            border-radius: 4px;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .list-group-item span i:hover::after {
            display: block;
        }

        /* Container styling */
        .container-fluid {
            padding: 20px;
        }

        /* Responsive Design */
        @media (max-width: 767px) {
            .list-group-item {
                font-size: 1rem;
            }
            .list-group-item i {
                font-size: 1.1rem;
            }
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .container {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include "assets/navbar.php"; ?>

    <div class="container">
        <div id="set_option" class="list-group">
            <!-- Take Attendance Option -->
            <a href="attendance.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="showSection('attendance')" data-bs-toggle="tooltip" data-bs-placement="right" title="Mark attendance for employees">
                <span><i class="bi bi-person-fill me-2" data-bs-title="Take Attendance"></i> Take Attendance</span>
                <i class="bi bi-chevron-right"></i>
            </a>
            <br>
            <!-- View Attendance Option -->
            <a href="view attendance.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="showSection('view-attendance')" data-bs-toggle="tooltip" data-bs-placement="right" title="View attendance reports">
                <span><i class="bi bi-eye-fill me-2" data-bs-title="View Attendance"></i> View Attendance</span>
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include '../assets/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSection(section) {
            // This function can be used to manage visibility of content
            console.log('Show section:', section);
        }

        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
</body>

</html>
