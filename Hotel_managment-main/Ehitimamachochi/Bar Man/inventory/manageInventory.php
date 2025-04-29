<?php
//include database connection
include '../../assets/conn.php';


// Fetch inventory data
$foods = [];
$beverages = [];

$sql = "SELECT item_name, category, purchase_price, quantity, price, created_at FROM table_foods";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $foods[] = $row;
    }
}

$sql = "SELECT item_name, category, purchase_price, quantity, price, created_at FROM table_beverages";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $beverages[] = $row;
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory - Ehototmamachochi Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        .hidden {
            display: none;
        }
        #navbar{
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

<body style="display: flex; flex-direction: column; min-height: 100vh; margin: 0;">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
        <div class="container-xl">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar"
                aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100 justify-content-center">
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="http://localhost/New/Ehitimamachochi/Manager/index.php" style="color: white !important;">
                            <span style="font-size: 1.2rem; margin-right: 5px;">&#8592;</span> Go Back
                        </a>
                    </li>
                    <li class="nav-item mx-3"><a class="nav-link" onclick="showSection('AddInventory')"style="color: white !important;">home </a></li>
                    <li class="nav-item mx-3"><a class="nav-link" onclick="showSection('update')"style="color: white !important;">Update Inventory</a>
                    </li>
                    <li class="nav-item mx-3"><a class="nav-link" onclick="showSection('view')"style="color: white !important;">View Inventory</a></li>
                    <li class="nav-item mx-3"><a class="nav-link" onclick="showSection('delete')"style="color: white !important;">Delete Inventory</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4" style="display: flex; justify-content: center;">
        <div class="section active"style="max-width: 80%; width: 100%;" id="AddInventory">
            <h2 class="mb-4 text-center">Add Inventory</h2>
            <form action="insert_inventory.php" method="post" onsubmit="return validateForm();"
                style="border:1px solid gray; padding:30px;">
                <div class="row">
                    <!-- Column 1 -->
                    <div class="col-md-6 form-column">
                        <div class="mb-3">
                            <label for="insert_item_name" class="form-label">Item Name</label>
                            <input type="text" class="form-control" id="insert_item_name" name="item_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="insert_item_type" class="form-label">Item Type</label>
                            <select name="insert_item_type" id="insert_item_type" class="form-select"
                                onchange="showCategoryFields()" required>
                                <option value="">Select Type</option>
                                <option value="food">Food</option>
                                <option value="beverage">Beverage</option>
                            </select>
                        </div>
                        <div id="food_category" class="mb-3 hidden">
                            <label for="insert_food_category" class="form-label">Food Category</label>
                            <span id="insert_food_category_warn" style="color: red;"></span>
                            <select name="insert_food_category" id="insert_food_category" class="form-select">
                                <option value="" selected>Select Category</option>
                                <option value="food">Normal Food</option>
                                <option value="fast-food">Fast Food</option>
                            </select>
                        </div>
                        <div id="beverage_category" class="mb-3 hidden">
                            <label for="insert_beverage_category" class="form-label">Beverage Category</label>
                            <span id="insert_beverage_category_warn" style="color: red;"></span>
                            <select name="insert_beverage_category" id="insert_beverage_category" class="form-select">
                                <option value="" selected>Select Category</option>
                                <option value="alcohol-drink">Alcohol Drinks</option>
                                <option value="soft-drink">Soft Drinks</option>
                            </select>
                        </div>
                    </div>
                    <!-- Column 2 -->
                    <div class="col-md-6 form-column">
                        <div class="mb-3">
                            <label for="insert_purchase_price" class="form-label">Purchase Price</label>
                            <input type="number" class="form-control" id="insert_purchase_price"
                                name="insert_purchase_price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="insert_item_price" class="form-label">Item Price</label>
                            <input type="number" class="form-control" id="insert_item_price" name="item_price"
                                step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="insert_item_quantity" class="form-label">Item Quantity</label>
                            <input type="number" class="form-control" id="insert_item_quantity" name="item_quantity"
                                required>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-primary" style="width: 45%;">Add Item</button>
                    <button type="reset" class="btn btn-secondary" style="width: 45%; margin-left: 10px;">Clear</button>
                </div>
            </form>
        </div>


        <script>
            function showCategoryFields() {
                var itemType = document.getElementById('insert_item_type').value;
                var foodCategory = document.getElementById('food_category');
                var beverageCategory = document.getElementById('beverage_category');
                var foodWarning = document.getElementById('insert_food_category_warn');
                var beverageWarning = document.getElementById('insert_beverage_category_warn');

                foodCategory.classList.add('hidden');
                beverageCategory.classList.add('hidden');
                foodWarning.innerHTML = '';
                beverageWarning.innerHTML = '';

                var itemType = document.getElementById('insert_item_type').value;
                var foodCategory = document.getElementById('food_category');
                var beverageCategory = document.getElementById('beverage_category');

                if (itemType === 'food') {
                    foodCategory.classList.remove('hidden');
                    beverageCategory.classList.add('hidden');
                } else if (itemType === 'beverage') {
                    beverageCategory.classList.remove('hidden');
                    foodCategory.classList.add('hidden');
                } else {
                    foodCategory.classList.add('hidden');
                    beverageCategory.classList.add('hidden');
                }
            }

            function validateForm() {
                var itemType = document.getElementById('insert_item_type').value;
                var foodCategory = document.getElementById('insert_food_category').value;
                var beverageCategory = document.getElementById('insert_beverage_category').value;
                var foodWarning = document.getElementById('insert_food_category_warn');
                var beverageWarning = document.getElementById('insert_beverage_category_warn');

                if (itemType === 'food' && foodCategory === '') {
                    foodWarning.innerHTML = 'Please select a food category.';
                    return false;
                }

                if (itemType === 'beverage' && beverageCategory === '') {
                    beverageWarning.innerHTML = 'Please select a beverage category.';
                    return false;
                }

                return true;
            }
        </script>


        <!-- Update Inventory Section -->
        <div id="update" class="section">
            <h3 class="text-center">Update Inventory</h3>
            <form id="updateForm" method="POST" action="update_Inventory.php">
                <h4>Foods</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Purchase Price</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Date Added</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($foods as $food): ?>
                            <tr>
                                <td><input type="text" name="item_name[]"
                                        value="<?= htmlspecialchars($food['item_name']) ?>" class="form-control"></td>
                                <td><input type="text" name="category[]" value="<?= htmlspecialchars($food['category']) ?>"
                                        class="form-control"></td>
                                <td><input type="number" name="purchase_price[]"
                                        value="<?= htmlspecialchars($food['purchase_price']) ?>" class="form-control"></td>
                                <td><input type="number" name="quantity[]"
                                        value="<?= htmlspecialchars($food['quantity']) ?>" class="form-control"></td>
                                <td><input type="number" name="price[]" value="<?= htmlspecialchars($food['price']) ?>"
                                        class="form-control"></td>
                                <td><?= htmlspecialchars($food['created_at']) ?></td>
                                <td><button type="submit" name="update_food"
                                        value="<?= htmlspecialchars($food['item_name']) ?>"
                                        class="btn btn-primary btn-sm">Update</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h4>Beverages</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Purchase Price</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Date Added</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($beverages as $beverage): ?>
                            <tr>
                                <td><input type="text" name="item_name[]"
                                        value="<?= htmlspecialchars($beverage['item_name']) ?>" class="form-control"></td>
                                <td><input type="text" name="category[]"
                                        value="<?= htmlspecialchars($beverage['category']) ?>" class="form-control"></td>
                                <td><input type="number" name="purchase_price[]"
                                        value="<?= htmlspecialchars($beverage['purchase_price']) ?>" class="form-control">
                                </td>
                                <td><input type="number" name="quantity[]"
                                        value="<?= htmlspecialchars($beverage['quantity']) ?>" class="form-control"></td>
                                <td><input type="number" name="price[]" value="<?= htmlspecialchars($beverage['price']) ?>"
                                        class="form-control"></td>
                                <td><?= htmlspecialchars($beverage['created_at']) ?></td>
                                <td><button type="submit" name="update_beverage"
                                        value="<?= htmlspecialchars($beverage['item_name']) ?>"
                                        class="btn btn-primary btn-sm">Update</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        </div>

        <!-- View Inventory Section -->
        <div id="view" class="section">
            <h3 class="text-center">View Inventory</h3>
            <h4>Foods</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Purchase Price</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($foods as $food): ?>
                        <tr>
                            <td><?= htmlspecialchars($food['item_name']) ?></td>
                            <td><?= htmlspecialchars($food['category']) ?></td>
                            <td><?= htmlspecialchars($food['purchase_price']) ?></td>
                            <td><?= htmlspecialchars($food['quantity']) ?></td>
                            <td><?= htmlspecialchars($food['price']) ?></td>
                            <td><?= htmlspecialchars($food['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h4>Beverages</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Purchase Price</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($beverages as $beverage): ?>
                        <tr>
                            <td><?= htmlspecialchars($beverage['item_name']) ?></td>
                            <td><?= htmlspecialchars($beverage['category']) ?></td>
                            <td><?= htmlspecialchars($beverage['purchase_price']) ?></td>
                            <td><?= htmlspecialchars($beverage['quantity']) ?></td>
                            <td><?= htmlspecialchars($beverage['price']) ?></td>
                            <td><?= htmlspecialchars($beverage['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Delete Inventory Section -->
        <div id="delete" class="section">
            <h3 class="text-center">Delete Inventory</h3>
            <!-- Delete Foods -->
            <h4>Foods</h4>
            <form id="deleteFoodForm" method="POST" action="delete_inventory.php">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Purchase Price</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Date Added</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($foods as $food): ?>
                            <tr>
                                <td><?= htmlspecialchars($food['item_name']) ?></td>
                                <td><?= htmlspecialchars($food['category']) ?></td>
                                <td><?= htmlspecialchars($food['purchase_price']) ?></td>
                                <td><?= htmlspecialchars($food['quantity']) ?></td>
                                <td><?= htmlspecialchars($food['price']) ?></td>
                                <td><?= htmlspecialchars($food['created_at']) ?></td>
                                <td>
                                    <button type="submit" name="delete_food"
                                        value="<?= htmlspecialchars($food['item_name']) ?>"
                                        class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
            <!-- Delete Beverages -->
            <h4>Beverages</h4>
            <form id="deleteBeverageForm" method="POST" action="delete_inventory.php">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Purchase Price</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Date Added</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($beverages as $beverage): ?>
                            <tr>
                                <td><?= htmlspecialchars($beverage['item_name']) ?></td>
                                <td><?= htmlspecialchars($beverage['category']) ?></td>
                                <td><?= htmlspecialchars($beverage['purchase_price']) ?></td>
                                <td><?= htmlspecialchars($beverage['quantity']) ?></td>
                                <td><?= htmlspecialchars($beverage['price']) ?></td>
                                <td><?= htmlspecialchars($beverage['created_at']) ?></td>
                                <td>
                                    <button type="submit" name="delete_beverage"
                                        value="<?= htmlspecialchars($beverage['item_name']) ?>"
                                        class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

   
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showSection(section) {
            document.querySelectorAll('.section').forEach((el) => el.classList.remove('active'));
            document.getElementById(section).classList.add('active');
        }

        function toggleCategory() {
            const itemType = document.getElementById('item_type').value;
            document.getElementById('foodCategory').style.display = itemType === 'foods' ? 'block' : 'none';
            document.getElementById('beverageCategory').style.display = itemType === 'beverages' ? 'block' : 'none';
        }

        window.onload = toggleCategory;
    </script>
</body>

</html>