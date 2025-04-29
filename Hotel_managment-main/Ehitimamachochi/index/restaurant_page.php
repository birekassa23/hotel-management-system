<?php
//include mail config connection
include '../assets/email_config.php';

//include database connection
include '../assets/conn.php';
$mysqli=$conn;

// Helper function to determine the table name based on item category
function getTableName($item_category)
{
    return ($item_category === 'foods') ? 'table_foods' : 'table_beverages';
}

// Fetch all available categories and types
function fetchCategoriesAndTypes($mysqli)
{
    $categories = ['foods', 'beverages'];
    $types = [];

    foreach ($categories as $category) {
        $table_name = getTableName($category);
        $sql = "SELECT DISTINCT category FROM $table_name";
        $result = $mysqli->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $types[$category][] = $row['category'];
            }
        }
    }
    return $types;
}

// Handle AJAX requests
if (isset($_GET['ajax'])) {
    if ($_GET['ajax'] === 'get_item_names') {
        $item_category = $_GET['item_category'] ?? '';
        $item_type = $_GET['item_type'] ?? '';

        if ($item_category && $item_type) {
            $table_name = getTableName($item_category);
            $stmt = $mysqli->prepare("SELECT * FROM $table_name WHERE category = ?");
            $stmt->bind_param('s', $item_type);
            $stmt->execute();
            $result = $stmt->get_result();

            $items = [];
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }

            echo json_encode($items);
            exit;
        }
    }

    if ($_GET['ajax'] === 'get_item_details') {
        $item_category = $_GET['item_category'] ?? '';
        $item_type = $_GET['item_type'] ?? '';
        $item_name = $_GET['item_name'] ?? '';

        if ($item_category && $item_type && $item_name) {
            $table_name = getTableName($item_category);
            $stmt = $mysqli->prepare("SELECT quantity, price AS item_price FROM $table_name WHERE category = ? AND item_name = ?");
            $stmt->bind_param('ss', $item_type, $item_name);
            $stmt->execute();
            $result = $stmt->get_result();

            $itemDetails = $result->fetch_assoc();
            echo json_encode($itemDetails);
            exit;
        }
    }
}

// Fetch the category data from the database
$categoryTypes = fetchCategoriesAndTypes($mysqli);
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <style>
    body{
    font-family: 'Times New Roman', Times, serif;
    }
    </style>
</head>

<body style="display: flex; flex-direction: column; min-height: 100vh; margin: 0; ">
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #333333; padding: 1rem; top: 0; width: 100%; z-index: 1; margin-bottom: 1rem;">
    <div class="container justify-content-center">
        <!-- Toggler Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar"
            aria-controls="mynavbar" aria-expanded="false" aria-label="Toggle navigation"
            style="border: 1px solid #000000; font-size: 28px; color: #ffffff; background-color: transparent;">
            <i class="bi bi-list"></i>
        </button>
        
        <!-- Brand -->
        <a class="navbar-brand" href="#" style="color: #ffffff;">Bar & Restaurant</a>
        
        <!-- Collapsible Navbar -->
        <div class="collapse navbar-collapse justify-content-center" id="mynavbar">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <!-- Food Menu -->
                <li class="nav-item mx-2">
                    <a id="open_food_list" class="nav-link d-flex align-items-center" href="#"
                        style="color: #ffffff; text-decoration: none;" onclick="showFoodMenu();"
                        aria-label="Food Menu">
                        <i class="bi bi-egg-fried"></i>
                        <span class="ms-2">Food Menu</span>
                    </a>
                </li>
                
                <!-- Beverage Menu -->
                <li class="nav-item mx-2">
                    <a id="open_beverage_list" class="nav-link d-flex align-items-center" href="#"
                        style="color: #ffffff; text-decoration: none;" onclick="showBeverageMenu();"
                        aria-label="Beverage Menu">
                        <i class="bi bi-cup-straw"></i>
                        <span class="ms-2">Beverage Menu</span>
                    </a>
                </li>
                
                <!-- Reservation Dropdown -->
                <li class="nav-item dropdown mx-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="reservationDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false"
                        style="color: #ffffff; text-decoration: none;" aria-label="Manage Reservation">
                        <i class="bi bi-calendar-check"></i>
                        <span class="ms-2">Manage Reservation</span>
                    </a>
                    <ul class="dropdown-menu bg-dark border-0" aria-labelledby="reservationDropdown">
                        <li>
                            <a class="dropdown-item" onclick="openModal('view_reservation_modal')"
                                style="color: #ffffff;" aria-label="View Reservation">
                                <i class="bi bi-eye"></i> View Reservation
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" onclick="openModal('update_reservation_modal')"
                                style="color: #ffffff;" aria-label="Update Reservation">
                                <i class="bi bi-pencil"></i> Update Reservation
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" onclick="openModal('cancel_reservation_modal')"
                                style="color: #ffffff;" aria-label="Cancel Reservation">
                                <i class="bi bi-x-circle"></i> Cancel Reservation
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End of Navbar -->


    <!-- Modals for Food  Buttons -->
    <div id="food_list" style="display:none; justify-content:center; width:100%; gap: 5%; margin-top: 20px;">
        <button id="normal_food" class="btn"
            style="width: 40%; background-color: black; color: white; border: none; padding: 10px; text-align: center;">
            <i class="bi bi-egg-fried"></i>
            <span style="margin-left: 8px;">Normal Food</span>
        </button>
        <button id="fast_food" class="btn"
            style="width: 40%; background-color: black; color: white; border: none; padding: 10px; text-align: center;">
            <i class="bi bi-burger"></i>
            <span style="margin-left: 8px;">Fast Food</span>
        </button>
    </div>
    <!-- Modals for Beverage Buttons -->
    <div id="beverage_list" style="display:none; justify-content:center; width:100%; gap: 5%; margin-top: 20px;">
        <button id="soft_drink" class="btn"
            style="width: 40%; background-color: black; color: white; border: none; padding: 10px; text-align: center;">
            <i class="bi bi-cup"></i>
            <span style="margin-left: 8px;">Soft Drinks</span>
        </button>
        <button id="alcohol_drink" class="btn"
            style="width: 40%; background-color: black; color: white; border: none; padding: 10px; text-align: center;">
            <i class="bi bi-wine"></i>
            <span style="margin-left: 8px;">Alcohol Drinks</span>
        </button>
    </div>

    <!-- End of Modals -->
    <script>
        // Function to show the food menu
        function showFoodMenu() {
            document.getElementById('food_list').style.display = 'flex';
            document.getElementById('beverage_list').style.display = 'none';
        }

        // Function to show the beverage menu
        function showBeverageMenu() {
            document.getElementById('beverage_list').style.display = 'flex';
            document.getElementById('food_list').style.display = 'none';
        }

        // Display the food list when the page loads
        window.onload = function () {
            document.getElementById('food_list').style.display = 'flex';
        };
    </script>

    <div class="jumbotron bg-light text-center p-5" style="font-family: 'Times New Roman', serif;">
        <h1 class="display-4 mb-4">Welcome to Ehitimamacho Hotel</h1>
        <p class="lead mb-4">
            You can make your reservation by selecting from the menu using the button above.
        </p>
        <hr class="my-4">
        <div class="container text-start">
            <h5 class="font-weight-bold">Note:</h5>
            <ul class="list-unstyled">
                <li>
                    <p>1. After making a reservation, you can update it only within 30 minutes.</p>
                </li>
                <li>
                    <p>2. If you cancel the reservation, you will receive the money physically and also you can cancel
                        it only within 1 hour.</p>
                </li>
            </ul>
        </div>
    </div>


    <!-- view_reservation_modal -->
    <div id="view_reservation_modal" class="modal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-content"
            style="background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 5px; width: 80%; max-width: 600px; position: relative;">
            <div class="modal-header" style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">
                <h5 style="margin: 0;">View Reservation</h5>
                <span onclick="document.getElementById('view_reservation_modal').style.display='none';"
                    style="position: absolute; top: 10px; right: 10px; color: #ff6f61; font-size: 24px; font-weight: bold; cursor: pointer;">
                    <i class="bi bi-x"></i>
                </span>
            </div>
            <div class="modal-body">
                <form action="view_inventory_reservation_process.php" method="post"
                    style="display: flex; flex-direction: column; gap: 15px;">
                    <div style="display: flex; flex-direction: column;">
                        <label for="view_email" style="font-weight: bold;">Email:</label>
                        <input type="email" id="view_email" name="view_email" placeholder="Enter your email" required
                            style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <label for="view_password" style="font-weight: bold;">Password:</label>
                        <input type="password" id="view_password" name="view_password" placeholder="Enter your password"
                            required style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="submit" class="btn btn-success" style="width: 50%;">Submit</button>
                        <button type="reset" class="btn btn-danger" style="width: 50%;">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal for Update Reservation -->
    <div id="update_reservation_modal" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100 text-center">Update Reservation</h5>
                    <span onclick="document.getElementById('update_reservation_modal').style.display='none';"
                        style="position: absolute; top: 10px; right: 10px; color: #ff6f61; font-size: 24px; font-weight: bold; cursor: pointer;">
                        <i class="bi bi-x"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <form action="update_inventory_reservation_process.php" method="post">
                        <!-- User Details -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="new_update_email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="new_update_email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="new_update_password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="new_update_password" name="password"
                                    required>
                            </div>
                        </div>

                        <p class="text-center">The following is new information for updating</p>
                        <hr>

                        <!-- New Item Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="new_update_item_category" class="form-label">New Item Category:</label>
                                <select class="form-select" id="new_update_item_category" name="item_category">
                                    <option value="" selected>Select Category</option>
                                    <option value="foods">Food</option>
                                    <option value="beverages">Beverage</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <span id="new_update_category-error" class="text-danger" style="display:none;">Please
                                    select item
                                    category first</span>
                                <div id="new_update_food-options" style="display:none;">
                                    <label for="new_update_food_type" class="form-label">New Item Type:</label>
                                    <select class="form-select" id="new_update_food_type" name="food_type">
                                        <option value="" selected>Select Type</option>
                                        <option value="food">Normal Food</option>
                                        <option value="fast_food">Fast Food</option>
                                    </select>
                                </div>
                                <div id="new_update_beverage-options" style="display:none;">
                                    <label for="new_update_beverage_type" class="form-label">New Item Type:</label>
                                    <select class="form-select" id="new_update_beverage_type" name="beverage_type">
                                        <option value="" selected>Select Type</option>
                                        <option value="soft-drink">Soft Drinks</option>
                                        <option value="alcohol-drink">Alcohol Drinks</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- New Item Name and Details -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="new_update_item_name" class="form-label">New Item Name:</label>
                                <select class="form-select" id="new_update_item_name" name="item_name">
                                    <option value="" selected>Select Item</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="new_update_available_quantity" class="form-label">Available
                                    Quantity:</label>
                                <input type="number" class="form-control" id="new_update_available_quantity"
                                    name="available_quantity" readonly required>
                            </div>
                        </div>

                        <!-- Required Quantity and Payment -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="new_update_required_quantity" class="form-label">Required Quantity:</label>
                                <input type="number" class="form-control" id="new_update_required_quantity"
                                    name="required_quantity" required>
                            </div>
                            <div class="col-md-6">
                                <label for="new_update_price" class="form-label">Your Payment:</label>
                                <input type="number" class="form-control" id="new_update_price" name="new_update_price"
                                    readonly required>
                            </div>
                        </div>

                        <!-- Date and Time -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="new_update_current_date_and_time" class="form-label">Current Date and
                                    Time:</label>
                                <input type="datetime-local" class="form-control" id="new_update_current_date_and_time"
                                    onchange="ValidateUpdateTime()" name="update_current_date_and_time" readonly required>
                            </div>
                            <div class="col-md-6">
                                <label for="new_update_adjusted_Time" class="form-label">Adjust Date and Time:</label>
                                <input type="datetime-local" class="form-control" id="new_update_adjusted_Time"
                                    onchange="ValidateUpdateTime()" name="adjusted_Time" required>
                            </div>
                            <script>
                                function ValidateUpdateTime() {
                                    let currentDateTime = new Date(document.getElementById('new_update_current_date_and_time').value);
                                    let adjustedDateTime = new Date(document.getElementById('new_update_adjusted_Time').value);

                                    if (currentDateTime >= adjustedDateTime) {
                                        alert('Adjusted date and time must be after the current date and time.');
                                        // Clear only the adjusted date/time if invalid
                                        document.getElementById('new_update_adjusted_Time').value = '';
                                    }
                                }
                            </script>
                        </div>
                         <!-- Terms and policy fild -->
                        <fieldset
                            style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; margin-bottom: 20px; margin-top: 20px;">
                            <legend style="font-weight: bold; font-size: 1.2em;">
                                <input type="checkbox" id="termsCheckbox" required>
                                I accept the Terms and Policy
                            </legend>
                            <ul style="list-style-type: disc; padding-left: 0; padding-left:10%;">
                                <li>Reservations can be canceled before 02:00 PM local time.</li>
                                <li>Reservations can be canceled within 1 hour of booking.</li>
                                <li>You will receive half of 75% of your payment, either in person or by contacting
                                    us
                                    through the phone.</li>
                            </ul>
                        </fieldset>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-center gap-2">
                            <button type="submit" class="btn btn-success" style="width: 50%;">Submit</button>
                            <button type="reset" class="btn btn-secondary" style="width: 50%;">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const updateReservationModal = new bootstrap.Modal(document.getElementById('update_reservation_modal'));
            let basePrice = 0;

            // Function to format date as YYYY-MM-DDTHH:MM
            function formatDateForInput(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${year}-${month}-${day}T${hours}:${minutes}`;
            }

            // Function to update the datetime-local input value every second
            function updateDateTime() {
                const now = new Date();
                const formattedDate = formatDateForInput(now);
                document.getElementById('new_update_current_date_and_time').value = formattedDate;
            }

            // Initial call to set the datetime value
            updateDateTime();

            // Update datetime value every second
            setInterval(updateDateTime, 1000);

            // Open modal function
            function openModal() {
                updateReservationModal.show();
            }

            // Close modal function
            function closeModal() {
                updateReservationModal.hide();
            }

            // Event listener for item category change
            document.getElementById('new_update_item_category').addEventListener('change', function () {
                const itemCategory = this.value;
                const foodOptions = document.getElementById('new_update_food-options');
                const beverageOptions = document.getElementById('new_update_beverage-options');
                const categoryError = document.getElementById('new_update_category-error');

                if (itemCategory === 'foods') {
                    foodOptions.style.display = 'block';
                    beverageOptions.style.display = 'none';
                } else if (itemCategory === 'beverages') {
                    foodOptions.style.display = 'none';
                    beverageOptions.style.display = 'block';
                } else {
                    foodOptions.style.display = 'none';
                    beverageOptions.style.display = 'none';
                }

                categoryError.style.display = itemCategory ? 'none' : 'block';
            });

            // Event listener for item type change
            document.getElementById('new_update_food_type').addEventListener('change', function () {
                loadItems('foods', this.value);
            });

            document.getElementById('new_update_beverage_type').addEventListener('change', function () {
                loadItems('beverages', this.value);
            });

            // Function to load item names based on category and type
            function loadItems(category, type) {
                if (!category) {
                    document.getElementById('new_update_category-error').style.display = 'block';
                    return;
                }

                fetch(`?ajax=get_item_names&item_category=${category}&item_type=${type}`)
                    .then(response => response.json())
                    .then(data => {
                        const itemNameSelect = document.getElementById('new_update_item_name');
                        itemNameSelect.innerHTML = '<option value="">Select Item</option>';
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.item_name;
                            option.text = item.item_name;
                            itemNameSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading items:', error));
            }

            // Event listener for item name change
            document.getElementById('new_update_item_name').addEventListener('change', function () {
                const itemCategory = document.getElementById('new_update_item_category').value;
                const itemType = itemCategory === 'foods'
                    ? document.getElementById('new_update_food_type').value
                    : document.getElementById('new_update_beverage_type').value;
                const itemName = this.value;

                if (itemCategory && itemType && itemName) {
                    fetch(`?ajax=get_item_details&item_category=${itemCategory}&item_type=${itemType}&item_name=${itemName}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('new_update_available_quantity').value = data.quantity || 0;
                            basePrice = parseFloat(data.item_price) || 0;
                            document.getElementById('new_update_price').value = basePrice.toFixed(2);
                        })
                        .catch(error => console.error('Error fetching item details:', error));
                }
            });

            // Function to calculate total payment
            function calculateTotalPayment() {
                const requiredQuantityInput = document.getElementById('new_update_required_quantity');
                const priceInput = document.getElementById('new_update_price');
                const availableQuantity = parseInt(document.getElementById('new_update_available_quantity').value, 10);
                const requiredQuantity = requiredQuantityInput.value.trim(); // Use trim() to handle empty spaces

                if (requiredQuantity === '') {
                    // If the input is empty, revert to base price
                    priceInput.value = basePrice.toFixed(2);
                } else {
                    const quantity = parseFloat(requiredQuantity);

                    if (isNaN(quantity) || quantity < 1 || !Number.isInteger(quantity)) {
                        showAlert('error', 'Invalid Quantity', "Invalid input");
                        requiredQuantityInput.value = '';
                        priceInput.value = basePrice.toFixed(2);
                    } else if (quantity > availableQuantity) {
                        showAlert('error', 'Insufficient Quantity', "We don't have enough quantity for your reservation.");
                        requiredQuantityInput.value = '';
                        priceInput.value = basePrice.toFixed(2);
                    } else {
                        const totalPayment = basePrice * quantity;
                        priceInput.value = totalPayment.toFixed(2);
                    }
                }
            }

            // Event listener for required quantity change
            document.getElementById('new_update_required_quantity').addEventListener('input', calculateTotalPayment);

            // Function to show alert using SweetAlert
            function showAlert(icon, title, message) {
                Swal.fire({
                    icon: icon,
                    title: title,
                    text: message,
                });
            }
        });
    </script>



    <!-- Modal for cancel Reservation -->
    <div id="cancel_reservation_modal" class="modal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-content"
            style="background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 5px; width: 80%; max-width: 600px; position: relative;">
            <div class="modal-header" style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">
                <h5 style="margin: 0;">cancel Reservation</h5>
                <span onclick="document.getElementById('cancel_reservation_modal').style.display='none';"
                    style="position: absolute; top: 10px; right: 10px; color: #ff6f61; font-size: 24px; font-weight: bold; cursor: pointer;">
                    <i class="bi bi-x"></i>
                </span>
            </div>
            <div class="modal-body">
                <form action="cancel_inventory_reservation_process.php" method="post"
                    style="display: flex; flex-direction: column; gap: 15px;">
                    <div style="display: flex; flex-direction: column;">
                        <label for="cancel_email" style="font-weight: bold;">Email:</label>
                        <input type="email" id="cancel_email" name="cancel_email" placeholder="Enter your email"
                            required style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <label for="cancel_password" style="font-weight: bold;">Password:</label>
                        <input type="password" id="cancel_password" name="cancel_password"
                            placeholder="Enter your password" required
                            style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="submit" class="btn btn-success" style="width: 50%;">Submit</button>
                        <button type="reset" class="btn btn-danger" style="width: 50%;">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End of Modal -->

    <!-- This script used to show all Modal -->
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close the modal when the user clicks anywhere outside of the modal content
        window.onclick = function (event) {
            const modals = document.getElementsByClassName('modal');
            for (let i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].style.display = 'none';
                }
            }
        };

    </script>

    <!-- Food Modal Button Container -->
    <!-- Normal Food Modal -->
    <div id="normal_food_modal"
        style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);">
        <div
            style="background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 5px; width: 80%; max-width: 600px; position: relative;">
            <span onclick="document.getElementById('normal_food_modal').style.display='none';"
                style="position: absolute; top: 10px; right: 10px; color: #ff6f61; font-size: 38px; font-weight: bold; cursor: pointer;">
                <i class="bi bi-x"></i>
            </span>
            <h5 style="margin: 0; text-align: center;">Normal Food Menu</h5>
            <div>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Item Name</th>
                            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Price</th>
                            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="normal_food_table_body">
                        <!-- PHP to generate table rows -->
                        <?php
                        //include database connection
                        include '../assets/conn.php';

                        $sql = "SELECT item_name,quantity, price FROM table_foods WHERE category='food'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td style="padding: 8px;">' . htmlspecialchars($row["item_name"]) . '</td>';
                                echo '<td style="padding: 8px;">' . htmlspecialchars($row["price"]) . ' ETB' . '</td>';
                                echo '<td style="padding: 8px;">
                                    <button onclick="openReserveModal(\'' . htmlspecialchars($row["item_name"]) . '\', \'food\',' . htmlspecialchars($row["quantity"]) . ', ' . htmlspecialchars($row["price"]) . ')" 
                                        style="background-color: #4CAF50; color: white; border: none; padding: 8px 16px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 5px;">
                                        Reserve
                                    </button>
                                    </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="3" style="padding: 8px; text-align: center;">No items found</td></tr>';
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Fast Food Modal -->
    <div id="fast_food_modal"
        style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);">
        <div
            style="background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 5px; width: 80%; max-width: 600px; position: relative;">
            <span onclick="document.getElementById('fast_food_modal').style.display='none';"
                style="position: absolute; top: 10px; right: 10px; color: #ff6f61; font-size: 38px; font-weight: bold; cursor: pointer;">
                <i class="bi bi-x"></i>
            </span>
            <h5 style="margin: 0;">Fast Food Menu</h5>
            <div>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Item Name</th>
                            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Price</th>
                            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="fast_food_table_body">
                        <!-- PHP to generate table rows -->
                        <?php
                        //include database connection
                        include '../assets/conn.php';
                        $sql = "SELECT item_name,quantity, price FROM table_foods WHERE category='fast_food'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td style="padding: 8px;">' . htmlspecialchars($row["item_name"]) . '</td>';
                                echo '<td style="padding: 8px;">' . htmlspecialchars($row["price"]) . ' ETB' . '</td>';
                                echo '<td style="padding: 8px;">
                                    <button onclick="openReserveModal(\'' . htmlspecialchars($row["item_name"]) . '\', \'food\', ' . htmlspecialchars($row["quantity"]) . ', ' . htmlspecialchars($row["price"]) . ')" 
                                        style="background-color: #4CAF50; color: white; border: none; padding: 8px 16px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 5px;">
                                        Reserve
                                    </button>
                                    </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="3" style="padding: 8px; text-align: center;">No items found</td></tr>';
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal Control -->
    <script>
        document.getElementById('normal_food').addEventListener('click', function () {
            document.getElementById('normal_food_modal').style.display = 'block';
        });

        document.getElementById('fast_food').addEventListener('click', function () {
            document.getElementById('fast_food_modal').style.display = 'block';
        });

        function openReserveModal(itemName, category, quantity, price) {
            // Implement your reservation logic here
            alert('Reserved ' + quantity + ' ' + itemName + ' at ' + price + ' ETB.');
        }
    </script>

    <!-- Modal Button Container for Beverages -->
    <!-- Soft Drink Modal -->
    <div id="soft_drink_modal"
        style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);">
        <div
            style="background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 5px; width: 80%; max-width: 600px; position: relative;">
            <span onclick="document.getElementById('soft_drink_modal').style.display='none';"
                style="position: absolute; top: 10px; right: 10px; color: #ff6f61; font-size: 38px; font-weight: bold; cursor: pointer;">
                <i class="bi bi-x"></i>
            </span>
            <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">
                <h5 style="margin: 0;">Soft Drink Menu</h5>
            </div>
            <div>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Item Name</th>
                            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Price</th>
                            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="soft_drink_table_body">
                        <!-- PHP to generate table rows -->
                        <?php
                        //include database connection
                        include '../assets/conn.php';

                        $sql = "SELECT item_name,quantity, price FROM table_beverages WHERE category='soft-drink'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td style="padding: 8px;">' . htmlspecialchars($row["item_name"]) . '</td>';
                                echo '<td style="padding: 8px;">' . htmlspecialchars($row["price"]) . ' ETB' . '</td>';
                                echo '<td style="padding: 8px;">
                                    <button onclick="openReserveModal(\'' . htmlspecialchars($row["item_name"]) . '\', \'soft-drink\',' . htmlspecialchars($row["quantity"]) . ', ' . htmlspecialchars($row["price"]) . ')" 
                                        style="background-color: #4CAF50; color: white; border: none; padding: 8px 16px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 5px;">
                                        Reserve
                                    </button>
                                    </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="3" style="padding: 8px; text-align: center;">No items found</td></tr>';
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Alcohol Drink Modal -->
    <div id="alcohol_drink_modal"
        style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);">
        <div
            style="background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 5px; width: 80%; max-width: 600px; position: relative;">
            <span onclick="document.getElementById('alcohol_drink_modal').style.display='none';"
                style="position: absolute; top: 10px; right: 10px; color: #ff6f61; font-size: 28px; font-weight: bold; cursor: pointer;">
                <i class="bi bi-x"></i>
            </span>
            <div style="border-bottom: 1px solid #ddd; margin-bottom: 20px;">
                <h5 style="margin: 0;">Alcohol Drink Menu</h5>
            </div>
            <div>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Item Name</th>
                            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Price</th>
                            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="alcohol_drink_table_body">
                        <!-- PHP to generate table rows -->
                        <?php
                        //include database connection
                        include '../assets/conn.php';

                        $sql = "SELECT item_name, quantity, price FROM table_beverages WHERE category='alcohol-drink'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td style="padding: 8px;">' . htmlspecialchars($row["item_name"]) . '</td>';
                                echo '<td style="padding: 8px;">' . htmlspecialchars($row["price"]) . ' ETB' . '</td>';
                                echo '<td style="padding: 8px;">
                                    <button onclick="openReserveModal(\'' . htmlspecialchars($row["item_name"]) . '\', \'alcohol-drink\',' . htmlspecialchars($row["quantity"]) . ', ' . htmlspecialchars($row["price"]) . ')" 
                                        style="background-color: #4CAF50; color: white; border: none; padding: 8px 16px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 5px;">
                                        Reserve
                                    </button>
                                    </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="3" style="padding: 8px; text-align: center;">No items found</td></tr>';
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal Control -->
    <script>
        // Open Soft Drink Modal
        document.getElementById('soft_drink').addEventListener('click', function () {
            document.getElementById('soft_drink_modal').style.display = 'block';
        });

        // Open Alcohol Drink Modal
        document.getElementById('alcohol_drink').addEventListener('click', function () {
            document.getElementById('alcohol_drink_modal').style.display = 'block';
        });
        // Function to handle reservation
        function openReserveModal(itemName, category, quantity, price) {
            // Implement your reservation logic here
            // alert('Reserved ' + quantity + ' ' + itemName + ' at ' + price + ' ETB.');
        }
    </script>

    <!-- Reservation Modal -->
    <div id="reserve_modal"
        style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);">
        <div
            style="background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 5px; width: 80%; max-width: 600px; position: relative;">
            <!-- Close Button -->
            <span onclick="document.getElementById('reserve_modal').style.display='none';"
                style="position: absolute; top: 10px; right: 10px; color: red; font-size: 38px; font-weight: bold; cursor: pointer; z-index: 1001;">
                <i class="bi bi-x"></i>
            </span>
            <h5 style="margin: 0; text-align: center;">Reservation Details</h5>
            <div id="reserve_details" style="padding: 20px; border-top: 1px solid #ddd; margin-top: 20px;">
                <form id="reserve_modal_form" action="inventory_reserve_pro.php" method="post"
                    style="max-width: 800px; margin: auto;">
                    <p id="specific_error_of_form" style="color:red; font-weight: bold;"></p>
                    <div class="row g-3">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" id="first_name" name="first_name" class="form-control"
                                    placeholder="Enter first name" required>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" id="last_name" name="last_name" class="form-control"
                                    placeholder="Enter last name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Enter email" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" id="phone" name="phone" class="form-control"
                                    placeholder="Enter phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="sex" class="form-label">Sex</label>
                                <select name="sex" id="sex" class="form-control" required>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Available quantity:</label>
                                <input type="text" id="quantity" name="quantity" class="form-control" readonly>
                            </div>
                        </div>
                        <!-- Item Details -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="item_name" class="form-label">Item Name</label>
                                <input type="text" id="item_name" name="item_name" class="form-control" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" id="category" name="category" class="form-control" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Payment</label>
                                <input type="text" id="price" name="price" class="form-control" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="current_date_and_time" class="form-label">Current Date and Time:</label>
                                <input type="datetime-local" id="current_date_and_time" name="current_date_and_time"
                                    class="form-control" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="adjusted_Time" class="form-label">Adjust Date and Time:</label>
                                <input type="datetime-local" id="adjusted_Time" name="adjusted_Time"
                                    class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="required_quantity" class="form-label">Required quantity:</label>
                                <input type="number" id="required_quantity" name="required_quantity"
                                    class="form-control" required>
                            </div>
                        </div>
                         <!-- Terms and policy fild -->
                        <fieldset
                            style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; margin-bottom: 20px; margin-top: 20px;">
                            <legend style="font-weight: bold; font-size: 1.2em;">
                                <input type="checkbox" id="termsCheckbox" required>
                                I accept the Terms and Policy
                            </legend>
                            <ul style="list-style-type: disc; padding-left: 0; padding-left:10%;">
                                <li>Reservations can be canceled before 02:00 PM local time.</li>
                                <li>Reservations can be canceled within 1 hour of booking.</li>
                                <li>You will receive half of 75% of your payment, either in person or by contacting
                                    us
                                    through the phone.</li>
                            </ul>
                        </fieldset>

                        <!-- Buttons -->
                        <div class="col-12" style="display: flex; justify-content: center; gap: 5%; width: 90%;">
                            <button type="submit" class="btn btn-primary" style="width: 40%;">Submit</button>
                            <button type="reset" class="btn btn-secondary" style="width: 40%;">Clear</button>
                        </div>
                    </div>
                </form>

                <script>
                    // Function to set the current date and time in the input field 
                    function setCurrentDateTime() {
                        const now = new Date();
                        const year = now.getFullYear();
                        const month = String(now.getMonth() + 1).padStart(2, '0');
                        const day = String(now.getDate()).padStart(2, '0');
                        const hours = String(now.getHours()).padStart(2, '0');
                        const minutes = String(now.getMinutes()).padStart(2, '0');
                        const seconds = String(now.getSeconds()).padStart(2, '0'); // Include seconds

                        const datetime = `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;
                        document.getElementById('current_date_and_time').value = datetime;
                    }

                    // Function to validate the adjusted time
                    function validateAdjustedTime(currentDateTime, adjustedTime) {
                        if (new Date(adjustedTime) < new Date(currentDateTime)) {
                            alert('Warning: The time you have set for reservation is in the past. Please select a valid future time.');
                            document.getElementById('adjusted_Time').value = ''; // Clear the invalid input
                        }
                    }

                    // Update price based on the required quantity and default price
                    function updatePrice() {
                        const basePrice = parseFloat(document.getElementById('price').dataset.basePrice);
                        const requiredQuantity = parseFloat(document.getElementById('required_quantity').value) || 0;
                        const availableQuantity = parseFloat(document.getElementById('quantity').value) || 0;

                        if (requiredQuantity < 0) {
                            alert('Please insert a valid required quantity.');
                            document.getElementById('required_quantity').value = '';
                            document.getElementById('price').value = '0.00 ETB';
                            return;
                        }

                        if (requiredQuantity > availableQuantity) {
                            alert('Sorry, we do not have enough quantity available.');
                            document.getElementById('required_quantity').value = '';
                            document.getElementById('price').value = '0.00 ETB'; // Set price to 0 if not enough quantity
                            return;
                        }

                        const newPrice = basePrice * requiredQuantity; // Base price multiplied by quantity
                        document.getElementById('price').value = newPrice.toFixed(2) + ' ETB'; // Update price dynamically
                    }

                    // Set the current date and time every second
                    window.onload = function () {
                        setCurrentDateTime(); // Initial call
                        setInterval(setCurrentDateTime, 1000); // Update every second

                        // Add event listener to required quantity input
                        document.getElementById('required_quantity').addEventListener('input', updatePrice);
                    };

                    // Function to open the reservation modal and set its values
                    function openReserveModal(itemName, category, quantity, price) {
                        document.getElementById('item_name').value = itemName;
                        document.getElementById('category').value = category;
                        document.getElementById('quantity').value = quantity;
                        document.getElementById('price').value = price.toFixed(2) + ' ETB'; // Set default price
                        document.getElementById('price').dataset.basePrice = price; // Store base price

                        // Show modal
                        document.getElementById('reserve_modal').style.display = 'block';
                    }
                </script>
            </div>
        </div>
    </div>






    <!-- Footer -->
    <footer style="background-color: #333333; color: #ffffff; padding: 1rem; text-align: center; margin-top: auto;">
        <p style="margin: 0; line-height: 1.5;">
            &copy; 2024 EHITIMAMACHOCHI HOTEL. All rights reserved.<br>Powered by MTU Department of SE Group 1 Members
        </p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>