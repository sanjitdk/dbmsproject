<?php
session_start();
require_once 'connection.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle booking deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $booking = $conn->query("SELECT car_id FROM bookings WHERE id = $id")->fetch_assoc();
    $conn->query("DELETE FROM bookings WHERE id = $id");
    // Make car available again
    $conn->query("UPDATE cars SET available = 1 WHERE id = {$booking['car_id']}");
    header("Location: manage_bookings.php");
    exit();
}

// Get all bookings with car details
$bookings = $conn->query("
    SELECT b.*, c.make, c.model 
    FROM bookings b
    JOIN cars c ON b.car_id = c.id
    ORDER BY b.pickup_date DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="../frontend/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #f2f2f2;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .actions a {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="../frontend/index.php">View Site</a>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h1>Manage Bookings</h1>
        
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Customer</th>
                <th>Car</th>
                <th>Pickup Date</th>
                <th>Return Date</th>
                <th>Booking Date</th>
                <th>Actions</th>
            </tr>
            <?php while($booking = $bookings->fetch_assoc()): ?>
            <tr>
                <td><?php echo $booking['id']; ?></td>
                <td>
                    <?php echo $booking['customer_name']; ?><br>
                    <?php echo $booking['customer_email']; ?><br>
                    <?php echo $booking['customer_phone']; ?>
                </td>
                <td><?php echo $booking['make'].' '.$booking['model']; ?></td>
                <td><?php echo date('M j, Y', strtotime($booking['pickup_date'])); ?></td>
                <td><?php echo date('M j, Y', strtotime($booking['return_date'])); ?></td>
                <td><?php echo date('M j, Y H:i', strtotime($booking['booking_date'])); ?></td>
                <td class="actions">
                    <a href="mailto:<?php echo $booking['customer_email']; ?>" class="button">Email</a>
                    <a href="manage_bookings.php?delete=<?php echo $booking['id']; ?>" class="button secondary" 
                       onclick="return confirm('Are you sure you want to delete this booking?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>
</html>