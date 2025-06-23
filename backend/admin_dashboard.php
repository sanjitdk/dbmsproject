<?php
session_start();
require_once 'connection.php';

// Check admin login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// Get counts
$totalCars = $conn->query("SELECT COUNT(*) AS total FROM vehicle")->fetch_assoc()['total'];
$totalAvailable = $conn->query("SELECT COUNT(*) AS total FROM vehicle WHERE AvailabilityStatus = 1")->fetch_assoc()['total'];
$totalBookings = $conn->query("SELECT COUNT(*) AS total FROM booking")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - CarRentalPro</title>
    <link rel="stylesheet" href="../frontend/style.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            margin: 0;
        }

        header {
            background: #1e3a8a;
            padding: 20px;
            color: white;
        }

        nav a {
            margin-right: 20px;
            color: white;
            text-decoration: none;
        }

        .dashboard-container {
            padding: 30px;
        }

        .dashboard-flex {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .summary-card {
            flex: 2;
            order: 1;
        }

        .quick-actions {
            flex: 1;
            order: 2;
        }

        .card h2 {
            margin-bottom: 10px;
            color: #1e3a8a;
        }

        .links a {
            display: block;
            margin-bottom: 10px;
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .links a:hover {
            text-decoration: underline;
            color: #1d4ed8;
        }

        @media screen and (max-width: 768px) {
            .dashboard-flex {
                flex-direction: column;
            }

            .summary-card,
            .quick-actions {
                order: unset;
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, Admin</h1>
        <nav>
            <a href="../frontend/index.php">Home</a>
            <a href="manage_cars.php" class="button">Manage Cars</a>
            <a href="manage_bookings.php" class="button">Manage Bookings</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="dashboard-container">
        <div class="dashboard-flex">
            <div class="card summary-card">
                <h2>Dashboard Summary</h2>
                <p><strong>Total Cars:</strong> <?php echo $totalCars; ?></p>
                <p><strong>Available Cars:</strong> <?php echo $totalAvailable; ?></p>
                <p><strong>Total Bookings:</strong> <?php echo $totalBookings; ?></p>
            </div>

            <div class="card links quick-actions">
                <h2>Quick Actions</h2>
                <a href="manage_cars.php">➤ Manage Cars</a>
                <a href="manage_bookings.php">➤ Manage Bookings</a>
            </div>
        </div>
    </div>
</body>
</html>
