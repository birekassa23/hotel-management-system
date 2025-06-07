<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Page - Ehototmamachochi Hotel</title>
    <?php include 'asset/bootstrap5.1.3_links.php'; ?> <!-- Include Bootstrap links -->
    <style>
        .sidebar {
        /* height: 100vh; */
        height: 100%;
        position: fixed;
        /* overflow-y: auto;
        max-height: 100%; */
    }
    </style>
</head>

<body>
    <?php include 'asset/navbar.php'; ?> <!-- Include Navbar -->
    <div class="container-fluid" style="margin-top: 50px;">
        <div class="row">
            <?php include 'asset/sidebar.php'; ?> <!-- Include Sidebar --> 
            <div class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <!-- Include DataTables CSS -->
                <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/css/dataTables.bootstrap5.min.css"
                    rel="stylesheet">

                <!-- Section 1 with a DataTable -->
                <div class="section1">
                    <p>this is the 1st report</p>
                    <table id="table1" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>City</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>John Doe</td>
                                <td>28</td>
                                <td>New York</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jane Smith</td>
                                <td>34</td>
                                <td>Los Angeles</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Sam Wilson</td>
                                <td>45</td>
                                <td>Chicago</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Section 2 with a DataTable -->
                <div class="section2">
                    <p>this is the 2nd report</p>
                    <table id="table2" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Apple</td>
                                <td>$1.00</td>
                                <td>50</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Banana</td>
                                <td>$0.80</td>
                                <td>120</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Orange</td>
                                <td>$1.20</td>
                                <td>200</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Section 3 with a DataTable -->
                <div class="section3">
                    <p>this is the 3rd report</p>
                    <table id="table3" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>John Doe</td>
                                <td>HR</td>
                                <td>$5000</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jane Smith</td>
                                <td>IT</td>
                                <td>$6000</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Sam Wilson</td>
                                <td>Finance</td>
                                <td>$7000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Section 4 with a DataTable -->
                <div class="section4">
                    <p>this is the 4th report</p>
                    <table id="table4" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Course</th>
                                <th>Instructor</th>
                                <th>Credits</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Math 101</td>
                                <td>Dr. Adams</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Physics 201</td>
                                <td>Prof. Lee</td>
                                <td>4</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Chemistry 301</td>
                                <td>Dr. Brown</td>
                                <td>3</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Section 5 with a DataTable -->
                <div class="section5">
                    <p>this is the 5th report</p>
                    <table id="table5" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Laptop</td>
                                <td>Electronics</td>
                                <td>30</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Smartphone</td>
                                <td>Electronics</td>
                                <td>50</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Headphones</td>
                                <td>Accessories</td>
                                <td>100</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</body>

</html>