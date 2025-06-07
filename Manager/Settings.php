<?php
// Function to get hotel information from hotel_config.json
function getHotelInfo()
{
    $filePath = '../assets/hotel_config.json';

    if (file_exists($filePath)) {
        $jsonData = file_get_contents($filePath);
        if ($jsonData === false) {
            echo "Error: Unable to read the configuration file.";
            return null;
        }

        $data = json_decode($jsonData, true);
        if ($data === null) {
            echo "Error: Failed to decode JSON data.";
            return null;
        }

        return $data;
    } else {
        echo "Error: Configuration file not found.";
        return null;
    }
}

// Function to update the hotel information
function updateHotelInfo($updatedData)
{
    $filePath = '../assets/hotel_config.json';
    $currentData = getHotelInfo();

    if ($currentData) {
        $newData = array_merge($currentData, $updatedData);

        if (file_put_contents($filePath, json_encode($newData, JSON_PRETTY_PRINT)) === false) {
            echo "Error: Failed to save the updated data to the configuration file.";
        }
    }
}

// Initialize success message
$successMessage = "";

// Check if form is submitted via POST to update the hotel information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedData = [
        'name' => $_POST['name'] ?? '',
        'adress' => $_POST['address'] ?? '',
        'town' => $_POST['town'] ?? '',
        'regin' => $_POST['region'] ?? '',
        'Countery' => $_POST['country'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'email' => $_POST['email'] ?? '',
        'facebook' => $_POST['facebook'] ?? '',
        'twitter' => $_POST['twitter'] ?? '',
        'instagram' => $_POST['instagram'] ?? '',
    ];

    updateHotelInfo($updatedData);

    // Set success message
    $successMessage = "Hotel Information updated successfully!";
}

// Get current hotel information
$currentHotelInfo = getHotelInfo();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Settings</title>
    <?php include 'assets/header.php'; ?>
    <link rel="stylesheet" href="setting.css">
</head>

<body class="d-flex flex-column">
    <!-- Navbar -->
    <?php include "assets/navbar.php"; ?>

    <div class="container">
        <!-- Success Alert (conditionally displayed) -->
        <?php if ($successMessage): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($successMessage); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="container my-5">
            <div class="accordion" id="settingsAccordion">
                <!-- System Settings Accordion -->
                <div class="accordion-item" style="margin:10px">
                    <h2 class="accordion-header" id="systemSettingsHeader">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#systemSettings" aria-expanded="false" aria-controls="systemSettings">
                            <i class="bi bi-gear me-2"></i> System Settings
                        </button>
                    </h2>
                    <div id="systemSettings" class="accordion-collapse collapse" aria-labelledby="systemSettingsHeader"
                        data-bs-parent="#settingsAccordion">
                        <div class="accordion-body">
                            <div class="collapsible-section">
                                <h5 class="section-header text-center mb-4">Update Hotel Information</h5>
                                <form method="POST" action="">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Hotel Name</label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                value="<?php echo htmlspecialchars($currentHotelInfo['name']); ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" id="address" name="address" class="form-control"
                                                value="<?php echo htmlspecialchars($currentHotelInfo['adress']); ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="town" class="form-label">Town</label>
                                            <input type="text" id="town" name="town" class="form-control"
                                                value="<?php echo htmlspecialchars($currentHotelInfo['town']); ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="region" class="form-label">Region</label>
                                            <input type="text" id="region" name="region" class="form-control"
                                                value="<?php echo htmlspecialchars($currentHotelInfo['regin']); ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="country" class="form-label">Country</label>
                                            <input type="text" id="country" name="country" class="form-control"
                                                value="<?php echo htmlspecialchars($currentHotelInfo['Countery']); ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" id="phone" name="phone" class="form-control"
                                                value="<?php echo htmlspecialchars($currentHotelInfo['phone']); ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" id="email" name="email" class="form-control"
                                                value="<?php echo htmlspecialchars($currentHotelInfo['email']); ?>"
                                                required>
                                        </div>
                                       <div class="col-md-6">
                                            <label for="instagram" class="form-label">Instagram</label>
                                            <input type="text" id="instagram" name="instagram" class="form-control"
                                                value="<?php echo htmlspecialchars($currentHotelInfo['instagram']); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="facebook" class="form-label">Facebook</label>
                                            <input type="text" id="facebook" name="facebook" class="form-control"
                                                value="<?php echo htmlspecialchars($currentHotelInfo['facebook']); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="twitter" class="form-label">Twitter</label>
                                            <input type="text" id="twitter" name="twitter" class="form-control"
                                                value="<?php echo htmlspecialchars($currentHotelInfo['twitter']); ?>">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center gap-3 mt-4">
                                        <button type="reset" class="btn btn-danger px-4" style="width:40%">Reset</button>
                                        <button type="submit" class="btn btn-primary px-4"style="width:40%">Save Changes</button>
                                    </div>
                                </form>
                            </div>

                            <div class="collapsible-section">
                                <h5 class="section-header">Change Room Images</h5>
                                <button class="btn btn-primary" onclick="selectImage('Standard Room')">Standard
                                    Room</button>
                                <button class="btn btn-primary" onclick="selectImage('Deluxe Room')">Deluxe
                                    Room</button>
                                <button class="btn btn-primary" onclick="selectImage('Suite Room')">Suite Room</button>
                                <button class="btn btn-primary" onclick="selectImage('Luxury Room')">Luxury
                                    Room</button>
                            </div>

                            <div class="collapsible-section">
                                <h5 class="section-header">Change Hall Images</h5>
                                <button class="btn btn-primary" onclick="selectImage('Conference Hall')">Conference
                                    Hall</button>
                                <button class="btn btn-primary" onclick="selectImage('Event Hall')">Event Hall</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Settings Accordion -->
                <div class="accordion-item" style="margin:10px">
                    <h2 class="accordion-header" id="accountSettingsHeader">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#accountSettings" aria-expanded="false" aria-controls="accountSettings">
                            <i class="bi bi-person-circle me-2"></i> Account Settings
                        </button>
                    </h2>
                    <div id="accountSettings" class="accordion-collapse collapse"
                        aria-labelledby="accountSettingsHeader" data-bs-parent="#settingsAccordion">
                        <div class="accordion-body">
                            <div class="collapsible-section">
                                <h5 class="section-header">Change Username</h5>
                                <form method="POST" action="">
                                    <label for="newUsername" class="form-label">New Username</label>
                                    <input type="text" id="newUsername" name="newUsername" class="form-control"
                                        required>
                                    <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                                </form>
                            </div>

                            <div class="collapsible-section">
                                <h5 class="section-header">Change Password</h5>
                                <form method="POST" action="">
                                    <label for="newPassword" class="form-label">New Password</label>
                                    <input type="password" id="newPassword" name="newPassword" class="form-control"
                                        required>
                                    <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logout Button -->
                <div class="accordion-item" style="margin:10px">
                    <a href="logout.php" class="logout-btn d-flex justify-content-center align-items-center">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include '../assets/footer.php'; ?>
</body>

</html>