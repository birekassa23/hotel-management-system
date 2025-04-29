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
                <!-- Toggle button for mobile view -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Navbar content -->
                <a class="navbar-brand" href="index.php">perchaser panel</a>
                <div class="collapse navbar-collapse h-100 d-flex align-items-center" id="navbarNav">
                    <ul class="navbar-nav d-flex justify-content-center w-100 mb-0">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="index.php" style="margin: 0 1rem;"
                                onclick="showSection('defaultSection')">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="perchase.php" style="margin: 0 1rem;">Other Expense </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="add_beverage.php" style="margin: 0 1rem;">Instock Beverage </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="Reports.php" style="margin: 0 1rem;">View reports</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="Settings.php" style="margin: 0 1rem;">Account
                                Settings</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
