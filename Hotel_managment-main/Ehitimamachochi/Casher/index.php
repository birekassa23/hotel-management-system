<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Page - Ehototmamachochi Hotel</title>
    <?php include 'asset/bootstrap5.1.3_links.php'; ?> <!-- Include Bootstrap links -->
</head>

<body>
    <?php include 'asset/navbar.php'; ?> <!-- Include Navbar -->
    <div class="container-fluid" style="margin-top: 50px;">
        <div class="row">
            <?php include 'asset/sidebar.php'; ?> <!-- Include Sidebar -->
            <div class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <h1 class="text-center mt-4">Welcome to the Cashier Page</h1>

                <!-- Default Section -->
                <div id="main-section" class="main-section">
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">Quick Actions</h5>
                            <ul class="list-unstyled">
                                <li><a href="#" onclick="showSection('daily-report-section')">Daily Report</a></li>
                                <li><a href="#" onclick="showSection('weekly-report-section')">Weekly Report</a></li>
                                <li><a href="#" onclick="showSection('monthly-report-section')">Monthly Report</a></li>
                                <li><a href="#" onclick="showSection('write-report-section')">Write Report</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Report Sections -->
                <div class="report-sections">
                    <!-- Daily Report Section -->
                    <div id="daily-report-section" class="section" style="display: none;">
                        <button onclick="goBack()" class="btn btn-secondary mb-3">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>
                        <h3>Daily Report</h3>
                        <table class="table table-bordered table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td>28</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jane Smith</td>
                                    <td>34</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <!-- Weekly Report Section -->
                    <div id="weekly-report-section" class="section" style="display: none;">
                        <button onclick="goBack()" class="btn btn-secondary mb-3">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>

                        <h3>Weekly Report</h3>
                        <table class="table table-bordered table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td>28</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jane Smith</td>
                                    <td>34</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <!-- Monthly Report Section -->
                    <div id="monthly-report-section" class="section" style="display: none;">
                        <button onclick="goBack()" class="btn btn-secondary mb-3">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>
                        <h3>Monthly Report</h3>
                        <table class="table table-bordered table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td>28</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jane Smith</td>
                                    <td>34</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <!-- Write Report Section -->
                    <div id="write-report-section" class="section" style="display: none;">
                        <button onclick="goBack()" class="btn btn-secondary mb-3">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>
                        <button onclick="showStructuredView()" class="btn btn-primary mb-3 end custom-btn">
                            <i class="bi bi-table"></i> Use Structured Way
                        </button>

                        <h3>Write Report</h3>
                        <div class="report-section-scroll">
                            <div class="toolbar-container mb-3">
                                <!-- Toolbar buttons -->
                                <button class="toolbar-button" onclick="toggleActive(this);document.execCommand('bold')"
                                    title="Bold"><i class="fa fa-bold"></i></button>
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('italic')" title="Italic"><i
                                        class="fa fa-italic"></i></button>
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('underline')" title="Underline"><i
                                        class="fa fa-underline"></i></button>
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('strikethrough')"
                                    title="Strikethrough"><i class="fa fa-strikethrough"></i></button>
                                <input type="number" class="font-size-input" placeholder="Font Size" min="8" max="72"
                                    onchange="setFontSize(this.value)" title="Set Font Size (px)">
                                <select onchange="toggleActive(this);changeFontFamily(this.value)" title="Font Family">
                                    <option value="">Font Family</option>
                                    <option value="Arial">Arial</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Times New Roman">Times New Roman</option>
                                    <option value="Verdana">Verdana</option>
                                </select>
                                <input type="color" onchange="toggleActive(this);changeTextColor(this.value)"
                                    title="Text Color">
                                <input type="color" onchange="changeBackgroundColor(this.value)"
                                    title="Background Color">
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('justifyLeft')"
                                    title="Align Left"><i class="fa fa-align-left"></i></button>
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('justifyCenter')"
                                    title="Align Center"><i class="fa fa-align-center"></i></button>
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('justifyRight')"
                                    title="Align Right"><i class="fa fa-align-right"></i></button>
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('justifyFull')" title="Justify"><i
                                        class="fa fa-align-justify"></i></button>
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('insertOrderedList')"
                                    title="Ordered List"><i class="fa fa-list-ol"></i></button>
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('insertUnorderedList')"
                                    title="Unordered List"><i class="fa fa-list-ul"></i></button>
                                <button class="toolbar-button" onclick="toggleActive(this);insertLine()"
                                    title="Insert Horizontal Line"><i class="fa fa-minus"></i></button>
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('insertImage', false, prompt('Enter image URL:'))"
                                    title="Insert Image"><i class="fa fa-image"></i></button>
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('createLink', false, prompt('Enter URL:'))"
                                    title="Insert Link"><i class="fa fa-link"></i></button>
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('unlink')" title="Remove Link"><i
                                        class="fa fa-unlink"></i></button>
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('subscript')" title="Subscript"><i
                                        class="fa fa-subscript"></i></button>
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('superscript')"
                                    title="Superscript"><i class="fa fa-superscript"></i></button>
                                <button class="toolbar-button"
                                    onclick="toggleActive(this);document.execCommand('removeFormat')"
                                    title="Clear Formatting"><i class="fa fa-eraser"></i></button>
                            </div>

                            <form action="" method="post">
                                <div name="written_report" id="reportTextarea" contenteditable="true"
                                    class="report-textarea" placeholder="Write your report here...">
                                    <p readonly>Date : 2/23/2020</p>
                                    <p readonly>Reported by: exaple- example</p>
                                </div>

                                <div class="button-container">
                                    <!-- Clear Button -->
                                    <button type="reset" class="btn btn-danger w-40">Clear</button>
                                    <!-- Send Button -->
                                    <button type="submit" class="btn btn-success w-40">Send</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- JavaScript -->
            <script>
                function toggleActive(button) {
                    // Remove active class from all toolbar buttons
                    const buttons = document.querySelectorAll('.toolbar-button');
                    buttons.forEach(btn => {
                        btn.classList.remove('active');
                    });

                    // Add active class to the clicked button
                    button.classList.add('active');
                }
            </script>

            <!-- Styling -->
            <style>
                .toolbar-button.active {
                    background-color: #e0e0e0;
                    /* Change background for active button */
                    color: #0056b3;
                    /* Change color for active button */
                    border: 1px solid #007bff;
                    /* Add border for active button */
                }

                .toolbar-button {
                    background: transparent;
                    color: #007bff;
                    border: none;
                    cursor: pointer;
                    font-size: 18px;
                }

                .toolbar-button:hover {
                    color: #0056b3;
                }
            </style>
            <!-- Styling -->
            <style>
                .main-section,
                .section {
                    margin-top: 20px;
                }

                .card-title {
                    font-weight: bold;
                    font-size: 1.2rem;
                }

                ul {
                    padding-left: 0;
                }

                ul li {
                    list-style: none;
                    margin-bottom: 10px;
                }

                ul li a {
                    text-decoration: none;
                    color: #007bff;
                    font-weight: 500;
                }

                ul li a:hover {
                    text-decoration: underline;
                }

                .toolbar-container {
                    display: flex;
                    gap: 10px;
                    background: #f7f7f7;
                    padding: 10px;
                    border-radius: 5px;
                }

                .toolbar-button {
                    background: transparent;
                    color: #007bff;
                    border: none;
                    cursor: pointer;
                    font-size: 18px;
                }

                .toolbar-button:hover {
                    color: #0056b3;
                }

                .report-textarea {
                    width: 100%;
                    height: 300px;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    font-size: 16px;
                    font-family: Arial, sans-serif;
                    line-height: 1.5;
                    background-color: #fafafa;
                    outline: none;
                    overflow-y: auto;
                }

                .btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                }
            </style>

            <!-- JavaScript -->
            <script>
                function showSection(sectionId) {
                    const sections = ['main-section', 'daily-report-section', 'weekly-report-section', 'monthly-report-section', 'write-report-section'];
                    sections.forEach(id => {
                        document.getElementById(id).style.display = (id === sectionId) ? 'block' : 'none';
                    });
                }

                function goBack() {
                    showSection('main-section');
                }

                function changeFontFamily(font) {
                    document.execCommand('fontName', false, font);
                }

                function changeTextColor(color) {
                    document.execCommand('foreColor', false, color);
                }

                function changeBackgroundColor(color) {
                    document.execCommand('hiliteColor', false, color);
                }

                function setFontSize(size) {
                    document.getElementById('reportTextarea').style.fontSize = `${size}px`;
                }

                function insertLine() {
                    document.execCommand('insertHorizontalRule', false, null);
                }
            </script>

        </div>
    </div>
</body>

</html>