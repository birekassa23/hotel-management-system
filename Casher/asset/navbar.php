<style>
  * {
    font-family: 'Times New Roman', Times, serif;
}

.sidebar .nav-link {
    white-space: nowrap;
}

.sidebar {
    height: 100vh;
    overflow-y: auto;
    max-height: 100%;
}

.sidebar .collapse .nav-link {
    padding-left: 1.5rem;
}

.sidebar .collapse .collapse .nav-link {
    padding-left: 2rem;
}

.sidebar .nav-link:hover {
    background-color: #343a40;
    border-radius: 5px;
}

.profile-section {
    text-align: center;
    margin-bottom: 30px;
}

.profile-section img {
    border-radius: 50%;
    margin-bottom: 10px;
}

.sidebar .nav-item.active > .nav-link {
    background-color: #007bff;
    color: white;
}

.navbar.fixed-top {
    z-index: 1030;
}

@media (max-width: 767px) {
    .sidebar {
        display: none;
    }

    .main-content {
        margin-left: 0;
        margin-top: 60px;
    }
}

</style>
<!-- navbar.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Ehototmamachochi Hotel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Cashier Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="confirmLogout(event)" >Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Include SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        function confirmLogout(event) {
    event.preventDefault(); // Prevent the default behavior of the link (no page reload)

    Swal.fire({
        icon: 'question',  // Icon for the dialog box
        title: 'Are you sure?',  // Title of the confirmation modal
        text: 'Do you want to log out?',  // Text displayed under the title
        showCancelButton: true,  // Show a cancel button
        confirmButtonText: 'Yes, log out',  // Confirm button text
        cancelButtonText: 'Cancel'  // Cancel button text
    }).then((result) => {
        if (result.isConfirmed) {
            // If the user confirms the logout, redirect to the login page or home page
            window.location.href = "http://localhost/New/Ehitimamachochi/index/index.php";
        }
    });
}
</script>
