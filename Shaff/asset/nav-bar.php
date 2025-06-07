<style>
    .nav-item {
        font-size: 16px;
    }

    .nav-item:hover {
        border-bottom: 1px solid blue;
    }
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="font-size: 1.25rem; height: 100px;">
    <div class="container-xl h-100">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="index.php">Shaff Panel</a>
        <div class="collapse navbar-collapse h-100 d-flex align-items-center" id="navbarNav">
            <ul class="navbar-nav d-flex justify-content-center w-100 mb-0">
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php" onclick="showSection('defaultSection')">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="Write_reports.php">Write Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="View_reports.php">View Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="Settings.php">Account Settings</a>
                </li>
            </ul>
        </div>
    </div>
</nav>