<style>
    .nav-item {
        font-size: 16px;
    }

    .nav-item:hover {
        border-bottom: 1px solid blue;
    }

    .section {
        display: none;
    }

    .section.active {
        display: block;
    }

    #defaultSection {
        display: block;
    }

    /* Additional styling for the report section */
    .report-section {
        margin-top: 20px;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="font-size: 1.25rem; height: 100px;">
    <div class="container-xl h-100">
        <!-- Toggle button for mobile view -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Navbar content -->
        <a class="navbar-brand" href="index.php">Store Man Panel</a>
        <div class="collapse navbar-collapse h-100 d-flex align-items-center" id="navbarNav">
            <ul class="navbar-nav d-flex justify-content-center w-100 mb-0">
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php" style="margin: 0 1rem;" onclick="showSection('defaultSection')">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="instock_items.php" style="margin: 0 1rem;">In-Stock Items</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="outstock_items.php" style="margin: 0 1rem;">Out-Stock Items</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="display_item.php" style="margin: 0 1rem;">Display all Items</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="reports.php" style="margin: 0 1rem;">Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="settings.php" style="margin: 0 1rem;">Account Settings</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
