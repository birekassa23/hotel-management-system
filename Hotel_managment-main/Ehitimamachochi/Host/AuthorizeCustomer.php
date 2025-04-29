<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host Management - Ehototmamachochi Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- HTML and JavaScript -->
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <!-- Navbar -->
    <?php include 'asset/navbar.php' ?>
    
    <!-- Form for Authorize Customer -->
    <div class="container my-5">
        <h5 style="text-align: center; margin-bottom: 20px;">Authorize Customer Reservation</h5>
        <form action="Authorize_Customer.php" method="post"
            style="display: flex; flex-direction: column; gap: 15px; max-width: 600px; margin: 0 auto;">
            <div style="display: flex; flex-direction: column;">
                <label for="email" style="font-weight: bold;">Username:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required
                    style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div style="display: flex; flex-direction: column;">
                <label for="password" style="font-weight: bold;">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required
                    style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div class="d-flex justify-content-between gap-2">
                <button type="submit" class="btn btn-success btn-lg" style="width:50%;">Submit</button>
                <button type="reset" class="btn btn-danger btn-lg" style="width:50%;">Reset</button>
            </div>
        </form>
    </div>
    <!-- footer -->
    <?php include '../assets/footer.php'; ?>

</body>
<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showSection(sectionId) {
        document.querySelectorAll('.card').forEach(card => card.style.display = 'none');
        document.getElementById(sectionId).style.display = 'block';
    }

    function confirmLogout() {
        if (confirm("Are you sure you want to log out?")) {
            window.location.href = "logout.php";
        }
    }
</script>

</html>