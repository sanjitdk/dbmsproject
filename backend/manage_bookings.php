<?php
session_start();
require_once 'connection.php';

// Check admin login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// FIXED: Cancel booking logic — Delete payment first
if (isset($_GET['cancel'])) {
    $cancelId = (int)$_GET['cancel'];

    // Delete from payment table first
    $conn->query("DELETE FROM payment WHERE BookingID = $cancelId");

    // Then delete from booking table
    $conn->query("DELETE FROM booking WHERE BookingID = $cancelId");

    // Redirect after deletion
    header("Location: manage_bookings.php");
    exit();
}

// Fetch booking details with customer info
$sql = "
    SELECT 
        b.BookingID, b.VehicleID, b.StartDate, b.EndDate, b.TotalCost, b.BookingStatus,
        c.Name AS CustomerName, c.Email, c.Phone
    FROM booking b
    JOIN customer c ON b.CustomerID = c.CustomerID
    ORDER BY b.StartDate DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Bookings - Admin</title>
    <link rel="stylesheet" href="../frontend/style.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #1e3a8a;
        }

        .top-buttons {
            margin-bottom: 20px;
        }

        .top-buttons a {
            display: inline-block;
            margin-right: 10px;
            padding: 10px 15px;
            background-color: #1e3a8a;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .top-buttons a:hover {
            background-color: #3749ad;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        th {
            background: #1e3a8a;
            color: white;
        }

        a.button {
            padding: 6px 12px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-weight: bold;
        }

        .edit-btn {
            background: #3b82f6;
        }

        .edit-btn:hover {
            background: #2563eb;
        }

        .cancel-btn {
            background: #ef4444;
        }

        .cancel-btn:hover {
            background: #dc2626;
        }
    </style>
</head>
<body>

    <h1>Manage Bookings</h1>

    <div class="top-buttons">
        <a href="admin_dashboard.php">← Back to Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>

    <table>
        <tr>
            <th>Booking ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Vehicle ID</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Total Cost</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['BookingID']; ?></td>
            <td><?= htmlspecialchars($row['CustomerName']); ?></td>
            <td><?= htmlspecialchars($row['Email']); ?></td>
            <td><?= htmlspecialchars($row['Phone']); ?></td>
            <td><?= $row['VehicleID']; ?></td>
            <td><?= $row['StartDate']; ?></td>
            <td><?= $row['EndDate']; ?></td>
            <td>$<?= $row['TotalCost']; ?></td>
            <td><?= $row['BookingStatus']; ?></td>
            <td>
                <a class="button edit-btn" href="edit_booking.php?id=<?= $row['BookingID']; ?>">Edit</a>
                <a class="button cancel-btn" href="manage_bookings.php?cancel=<?= $row['BookingID']; ?>" onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel</a>
            </td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>
