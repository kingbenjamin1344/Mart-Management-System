<?php
function pdo_connect_mysql() {
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'rementizo_customer_records';
    try {
        return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
        // If there is an error with the connection, stop the script and display the error.
        exit('Failed to connect to database!');
    }
}

function template_header($title) {
echo <<<EOT
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>$title</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <style>
            /* Adjust navbar height */
            .navbar {
                padding: 10px 0;
            }

            /* Adjust navbar brand font size */
            .navbar-brand {
                font-size: 24px; 
            }

            /* Apply underline hover effect to nav links */
            .nav-link:hover {
                text-decoration: underline;
                color: #007bff;
            }

            /* Set the background color for the entire page */
            body {
                background-color: #f0f8ff; /* Change this color as needed */
            }
        </style>
    </head>
    <body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../Categories/index.php">Mini Mart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Mini Mart</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
           
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Customer/read.php"><i class = "fa fa-user"></i> Customer</a>
                            <a class="nav-link" href="../Categories/read.php"><i class = "fa fa-book"></i> Categories</a>
                            <a class="nav-link" href="../Employees/read.php"><i class = "fa fa-users"></i> Employees</a>
                            <a class="nav-link" href="../Order_Details/read.php"><i class = "fa fa-shopping-cart"></i> Order Details</a>
                            <a class="nav-link" href="../Order/read.php"><i class = "fa fa-shopping-basket"></i> Orders</a>
                            <a class="nav-link" href="../Products/read.php"><i class = "fa fa-archive"></i> Products</a>
                            <a class="nav-link" href="../Shippers/read.php"><i class = "fa fa-ship"></i> Shippers</a>
                            <a class="nav-link" href="../Suppliers/read.php"><i class = "fa fa-rocket"></i> Suppliers</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
EOT;
}

function template_footer() {
echo <<<EOT
    </body>
</html>
EOT;
}
?>
