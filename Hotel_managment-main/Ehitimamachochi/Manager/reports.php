
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <!-- Include Header with all the resources -->
    <?php include 'assets/header.php'; ?>
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

        .content {
            flex: 1;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .hidden {
            display: none;
        }

        .comment-item {
            background-color: rgb(85, 82, 82);
            border-radius: 10px;
            margin-bottom: 1rem;
            padding: 0.5rem;
            color: white;
        }

        .comment-item p {
            margin: 0;
        }

        .comment-item p:first-child {
            text-align: left;
        }

        .comment-item .date {
            font-size: 0.9rem;
            text-align: right;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include "assets/navbar.php"; ?>
    <!-- Main Container -->
    <div id="mainContainer" class="container" style="margin-top:20px;">
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                onclick="showSection('ViewReports')">
                <span><i class="bi bi-file-earmark-text me-2"></i>View Reports</span>
                <i class="bi bi-chevron-right"></i>
            </a>

            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                onclick="showSection('commentsSection')">
                <span><i class="bi bi-chat-dots me-2"></i>View Comments</span>
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- View Reports Section -->
    <section id="ViewReports" class="hidden container" style="margin-top:20px;">
        <button onclick="goBack()" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back
        </button>
        <div class="list-group">
            <style>
                /* Style for the accordion container */
                .accordion-container {
                    width: 90%;
                    margin: 20px auto;
                    /* Center the container */
                }

                /* Style for the accordion header */
                .accordion {
                    background-color: #007bff;
                    /* Attractive blue */
                    color: white;
                    /* Text color */
                    cursor: pointer;
                    padding: 15px 20px;
                    /* Padding for bigger buttons */
                    width: 100%;
                    border: none;
                    text-align: left;
                    outline: none;
                    font-size: 16px;
                    /* Slightly bigger font size */
                    font-weight: bold;
                    /* Make text bold */
                    border-radius: 5px;
                    /* Rounded corners */
                    transition: all 0.3s ease;
                    /* Smooth transition */
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                /* Hover effect */
                .accordion:hover {
                    background-color: #0056b3;
                    /* Darker blue when hovering */
                }

                /* Active state for opened accordions */
                .active {
                    background-color: #004085;
                    /* Darker blue for active accordion */
                }

                /* Panel containing the content */
                .panel {
                   margin: 10px;
                    padding: 0 18px;
                    background-color: #f9f9f9;
                    max-height: 0;
                    overflow: hidden;
                    transition: max-height 0.3s ease-out;
                    border-radius: 0 0 5px 5px;/* Rounded bottom corners */
                }

                /* Rotate the icon when the panel is opened */
                .accordion.active .bi {
                    transform: rotate(90deg);
                    transition: transform 0.3s ease;
                }

                /* Make sure icons are properly spaced */
                .accordion .bi {
                    font-size: 18px;
                    /* Larger icons */
                    transition: transform 0.3s ease;
                }

                /* Optional: Style for mobile responsiveness */
                @media (max-width: 767px) {
                    .accordion {
                        font-size: 14px;
                        padding: 12px 15px;
                        /* Adjust padding for mobile */
                    }
                }
            </style>

            <!-- Accordion container -->
            <div class="accordion-container">
                <!-- Inventory Reports Accordion -->
                <p href="#" class="accordion" id="inventoryAccordion">
                    <span><i class="bi bi-bar-chart me-2"></i>Inventory Reports</span>
                    <i class="bi bi-chevron-right"></i>
                </p>
                <div class="panel">
                    <!-- Table structure -->
                    <table style="width:100%;">
                        <caption>Employee List</caption> <!-- Optional: Table Title -->
                        <thead> <!-- Table Header -->
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody> <!-- Table Body -->
                            <!-- Table Row -->
                            <tr>
                                <td>1</td>
                                <td>John Doe</td>
                                <td>Software Engineer</td>
                                <td>IT</td>
                                <td>johndoe@example.com</td>
                            </tr>
                            <!-- Another Row -->
                            <tr>
                                <td>2</td>
                                <td>Jane Smith</td>
                                <td>Project Manager</td>
                                <td>Marketing</td>
                                <td>janesmith@example.com</td>
                            </tr>
                            <!-- More rows as needed -->
                        </tbody>
                    </table>

                    <!-- Table structure -->
                    <table  style="width:100%;">
                        <caption>Employee List</caption> <!-- Optional: Table Title -->
                        <thead> <!-- Table Header -->
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody> <!-- Table Body -->
                            <!-- Table Row -->
                            <tr>
                                <td>1</td>
                                <td>John Doe</td>
                                <td>Software Engineer</td>
                                <td>IT</td>
                                <td>johndoe@example.com</td>
                            </tr>
                            <!-- Another Row -->
                            <tr>
                                <td>2</td>
                                <td>Jane Smith</td>
                                <td>Project Manager</td>
                                <td>Marketing</td>
                                <td>janesmith@example.com</td>
                            </tr>
                            <!-- More rows as needed -->
                        </tbody>
                    </table>
                </div>

                <!-- Rooms Reports Accordion -->
                <p href="#" class="accordion" id="roomsAccordion">
                    <span><i class="bi bi-archive me-2"></i>Rooms Reports</span>
                    <i class="bi bi-chevron-right"></i>
                </p>
                <div class="panel">
                    <!-- Table structure -->
                    <table  style="width:100%;">
                        <caption>Employee List</caption> <!-- Optional: Table Title -->
                        <thead> <!-- Table Header -->
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody> <!-- Table Body -->
                            <!-- Table Row -->
                            <tr>
                                <td>1</td>
                                <td>John Doe</td>
                                <td>Software Engineer</td>
                                <td>IT</td>
                                <td>johndoe@example.com</td>
                            </tr>
                            <!-- Another Row -->
                            <tr>
                                <td>2</td>
                                <td>Jane Smith</td>
                                <td>Project Manager</td>
                                <td>Marketing</td>
                                <td>janesmith@example.com</td>
                            </tr>
                            <!-- More rows as needed -->
                        </tbody>
                    </table>
                </div>

                <!-- Halls Reports Accordion -->
                <p href="#" class="accordion" id="hallsAccordion">
                    <span><i class="bi bi-building me-2"></i>Halls Reports</span>
                    <i class="bi bi-chevron-right"></i>
                </p>
                <div class="panel">
                    <!-- Table structure -->
                    <table  style="width:100%;">
                        <caption>Employee List</caption> <!-- Optional: Table Title -->
                        <thead> <!-- Table Header -->
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody> <!-- Table Body -->
                            <!-- Table Row -->
                            <tr>
                                <td>1</td>
                                <td>John Doe</td>
                                <td>Software Engineer</td>
                                <td>IT</td>
                                <td>johndoe@example.com</td>
                            </tr>
                            <!-- Another Row -->
                            <tr>
                                <td>2</td>
                                <td>Jane Smith</td>
                                <td>Project Manager</td>
                                <td>Marketing</td>
                                <td>janesmith@example.com</td>
                            </tr>
                            <!-- More rows as needed -->
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
                // Function to handle accordion clicks and store their state
                function setupAccordion() {
                    var acc = document.getElementsByClassName("accordion");

                    for (let i = 0; i < acc.length; i++) {
                        // Check if this accordion's state is saved in localStorage
                        const accordionId = acc[i].id;
                        if (localStorage.getItem(accordionId) === 'true') {
                            acc[i].classList.add("active");
                            acc[i].nextElementSibling.style.maxHeight = acc[i].nextElementSibling.scrollHeight + "px";
                        }

                        // Add click event listener to toggle accordion
                        acc[i].addEventListener("click", function () {
                            // Toggle active class for the clicked accordion
                            this.classList.toggle("active");

                            // Toggle panel visibility
                            var panel = this.nextElementSibling;
                            if (panel.style.maxHeight) {
                                panel.style.maxHeight = null;
                                // Save state in localStorage
                                localStorage.setItem(accordionId, 'false');
                            } else {
                                panel.style.maxHeight = panel.scrollHeight + "px";
                                // Save state in localStorage
                                localStorage.setItem(accordionId, 'true');
                            }
                        });
                    }
                }
                // Initialize accordion functionality
                setupAccordion();
            </script>
        </div>
    </section>



    <!-- Income Reports Section -->
    <!-- <section id="incomeReports" class="hidden container-fluid">
        <button onclick="goBack()" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back
        </button>
        <div class="list-group">
            <a href="#"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <span><i class="bi bi-cash-stack me-2"></i>Today's Income Reports</span>
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </section> -->

    <!-- Comments Section -->
    <section id="commentsSection" class="hidden container" style="margin-top:20px;">
        <div class="d-flex justify-content-between mb-3">
            <button onclick="goBack()" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </button>
        </div>
        <div class="d-flex justify-content-end mb-2">
            <button class="btn btn-danger" onclick="deleteAllComments()">
                <i class="bi bi-trash"></i> Delete all comments
            </button>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                function deleteAllComments() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you really want to delete all comments?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('delete_all_comments.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                }
                            })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        loadComments(); // Reload comments after deletion
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Deleted!',
                                            text: 'All comments have been deleted.',
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Error deleting comments: ' + result.error,
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error deleting comments:', error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'An error occurred while deleting comments.',
                                    });
                                });
                        }
                    });
                }
            </script>
        </div>

        <div id="commentsContainer">
            <!-- Comments will be dynamically inserted here -->
        </div>

        <script>
            // Function to fetch and display comments
            function loadComments() {
                fetch('fetch_comments.php')  // Adjust the URL to your PHP script
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(comments => {
                        const commentsContainer = document.getElementById('commentsContainer');
                        commentsContainer.innerHTML = ''; // Clear any existing content

                        if (comments.length === 0) {
                            commentsContainer.innerHTML = `
                    <div style="width: 400px; margin: 0 auto; text-align: center;">
                        <p>No comments provided yet.</p>
                    </div>
                `;
                        } else {
                            comments.forEach(comment => {
                                const commentDiv = document.createElement('div');
                                commentDiv.classList.add('comment-item');

                                commentDiv.innerHTML = `
                        <p><i class="bi bi-person"></i> <strong>${comment.fromUserName}</strong></p>
                        <hr>
                        <p style="text-align:center;">${comment.theComment}</p>
                        <p class="date">Date: ${comment.Date}</p>
                        <p class="date">
                            <button onclick="deletetheComment('${comment.fromUserName}', '${comment.theComment}', '${comment.Date}')" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </p>
                    `;

                                commentsContainer.appendChild(commentDiv);
                            });
                        }
                    })
                    .catch(error => console.error('Error loading comments:', error));
            }

            // Function to handle comment deletion
            function deletetheComment(fromUserName, theComment, Date) {
                // Show confirmation dialog using SweetAlert2
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you really want to delete this comment?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('delete_comment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                'from_user_name': fromUserName,
                                'the_comment': theComment,
                                'comment_date': Date
                            }),
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok: ' + response.statusText);
                                }
                                return response.json();
                            })
                            .then(result => {
                                if (result.success) {
                                    loadComments(); // Reload comments after deletion
                                    Swal.fire(
                                        'Deleted!',
                                        result.message,
                                        'success'
                                    ); // Show success message
                                } else {
                                    console.error('Error deleting comment:', result.error);
                                    Swal.fire(
                                        'Error!',
                                        'Error deleting comment: ' + result.error,
                                        'error'
                                    ); // Show error message
                                }
                            })
                            .catch(error => {
                                console.error('Error deleting comment:', error);
                                Swal.fire(
                                    'Error!',
                                    'Error deleting comment: ' + error.message,
                                    'error'
                                ); // Show error message
                            });
                    }
                });
            }
            // Call the function to load comments when the page is loaded
            window.onload = loadComments;
        </script>
    </section>

    <!-- Include Footer -->
    <?php include '../assets/footer.php'; ?>

    <!--  -->
    <script>
        function showSection(id) {
            document.getElementById('mainContainer').classList.add('hidden');
            document.querySelectorAll('section').forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(id).classList.remove('hidden');
        }

        function goBack() {
            document.getElementById('mainContainer').classList.remove('hidden');
            document.querySelectorAll('section').forEach(section => {
                section.classList.add('hidden');
            });
        }

        function addRow() {
            const tableBody = document.querySelector('#reportTable tbody');
            const rowCount = tableBody.rows.length;
            const newRow = tableBody.insertRow();
            newRow.innerHTML = `
                <tr>
                    <td>${rowCount + 1}</td>
                    <td><input type="text" class="form-control" name="list[]"></td>
                    <td><input type="text" class="form-control" name="measurement[]"></td>
                    <td><input type="number" class="form-control" name="quantity[]"></td>
                    <td><input type="number" class="form-control" name="single_price[]"></td>
                    <td><input type="number" class="form-control" name="total_price[]"></td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-trash"></i> Remove</button></td>
                </tr>
            `;
        }

        function removeRow(button) {
            button.closest('tr').remove();
        }
    </script>
</body>

</html>