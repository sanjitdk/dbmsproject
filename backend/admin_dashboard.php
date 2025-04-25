<?php
session_start();
require_once 'connection.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Get counts for dashboard
$cars_count = $conn->query("SELECT COUNT(*) FROM cars")->fetch_row()[0];
$bookings_count = $conn->query("SELECT COUNT(*) FROM bookings")->fetch_row()[0];
$available_cars = $conn->query("SELECT COUNT(*) FROM cars WHERE available = 1")->fetch_row()[0];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../frontend/style.css">
    <style>
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .card .number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #3498db;
            margin: 10px 0;
        }
        .admin-nav {
            background: #34495e;
            padding: 10px 0;
            margin-bottom: 20px;
        }
        .admin-nav a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="../frontend/index.php">View Site</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="admin-nav">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_cars.php">Manage Cars</a>
        <a href="manage_bookings.php">Manage Bookings</a>
    </div>

    <main>
        <h1>Admin Dashboard</h1>
        
        <div class="dashboard-cards">
            <div class="card">
                <h3>Total Cars</h3>
                <div class="number"><?php echo $cars_count; ?></div>
            </div>
            <div class="card">
                <h3>Total Bookings</h3>
                <div class="number"><?php echo $bookings_count; ?></div>
            </div>
            <div class="card">
                <h3>Available Cars</h3>
                <div class="number"><?php echo $available_cars; ?></div>
            </div>
        </div>

        <div class="recent-bookings">
            <h2>Recent Bookings</h2>
            <?php
            $bookings = $conn->query("
                SELECT b.*, c.make, c.model 
                FROM bookings b
                JOIN cars c ON b.car_id = c.id
                ORDER BY b.booking_date DESC
                LIMIT 5
            ");
            ?>
            <table>
                <tr>
                    <th>Customer</th>
                    <th>Car</th>
                    <th>Pickup Date</th>
                    <th>Return Date</th>
                    <th>Booking Date</th>
                </tr>
                <?php while($booking = $bookings->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $booking['customer_name']; ?></td>
                    <td><?php echo $booking['make'].' '.$booking['model']; ?></td>
                    <td><?php echo date('M j, Y', strtotime($booking['pickup_date'])); ?></td>
                    <td><?php echo date('M j, Y', strtotime($booking['return_date'])); ?></td>
                    <td><?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>
</body>
</html>