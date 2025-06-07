
<style>
* {
    font-family: 'Times New Roman', Times, serif;
}

.sidebar {
    height: 100vh;
    overflow-y: auto;
    max-height: 100%;
}

.sidebar .nav-link {
    white-space: nowrap;
}

.sidebar .nav-link:hover {
    background-color: #343a40;
    border-radius: 5px;
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
<!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse show">
            <div class="position-sticky pt-3">
                <!-- Profile Section -->
                <div class="profile-section text-white">
                    <img src="image.png" alt="Profile Picture" class="rounded-circle" width="80" height="80">
                    <!-- <p class="mt-2 mb-0 fw-bold">Casher Name</p>
                    <small>ID: 12345</small> -->
                </div>

                <!-- Navigation Menu with Collapsible Sections -->
                <ul class="nav flex-column mt-4">
                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" href="index.php">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>

                    <!-- Reports Section -->
                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" data-bs-toggle="collapse" href="#reportsCollapse"
                            role="button" aria-expanded="false" aria-controls="reportsCollapse">
                            <i class="fas fa-chart-line"></i> Reports
                        </a>
                        <div class="collapse" id="reportsCollapse">
                            <ul class="nav flex-column ms-4">
                                <li><a class="nav-link text-white" href="todays.php">Today's Report</a></li>
                                <li><a class="nav-link text-white" href="#">Monthly Report</a></li>
                                <li><a class="nav-link text-white" href="#">Annual Report</a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Settings Section -->
                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" href="Settings.php">
                            <i class="fas fa-cogs"></i> Settings
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
