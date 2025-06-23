<?php
session_start();
require_once 'connection.php';

// Check admin login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// Get booking ID from URL
if (!isset($_GET['id'])) {
    header("Location: manage_bookings.php");
    exit();
}

$bookingID = (int)$_GET['id'];
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';
    $totalCost = (float)$_POST['total_cost'] ?? 0;
    $status = $_POST['status'] ?? '';
    
    // Customer data
    $customerID = (int)$_POST['customer_id'];
    $name = $_POST['customer_name'] ?? '';
    $email = $_POST['customer_email'] ?? '';
    $phone = $_POST['customer_phone'] ?? '';
    $license = $_POST['driver_license'] ?? '';

    // Simple validation
    if ($startDate && $endDate && $totalCost >= 0 && $status && $name && $email && $phone && $license) {

        // Update booking
        $stmt = $conn->prepare("UPDATE booking SET StartDate=?, EndDate=?, TotalCost=?, BookingStatus=? WHERE BookingID=?");
        $stmt->bind_param("ssdsi", $startDate, $endDate, $totalCost, $status, $bookingID);
        $stmt->execute();
        $stmt->close();

        // Update customer
        $stmt = $conn->prepare("UPDATE customer SET Name=?, Email=?, Phone=?, DriverLicenseNumber=? WHERE CustomerID=?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $license, $customerID);
        $stmt->execute();
        $stmt->close();

        header("Location: manage_bookings.php?msg=updated");
        exit();
    } else {
        $error = "Please fill all fields correctly.";
    }
}

// Fetch booking + customer data
$stmt = $conn->prepare("
    SELECT b.StartDate, b.EndDate, b.TotalCost, b.BookingStatus, 
           c.CustomerID, c.Name, c.Email, c.Phone, c.DriverLicenseNumber
    FROM booking b
    JOIN customer c ON b.CustomerID = c.CustomerID
    WHERE b.BookingID = ?
");
$stmt->bind_param("i", $bookingID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_bookings.php");
    exit();
}

$booking = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking #<?= $bookingID; ?></title>
    <link rel="stylesheet" href="../frontend/style.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            padding: 20px;
        }
        form {
            background: white;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            margin-top: 20px;
            background: #1e3a8a;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background: #3749ad;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .back-link {
            display: block;
            margin: 20px auto;
            text-align: center;
            color: #1e3a8a;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h1>Edit Booking #<?= $bookingID; ?></h1>

<form method="post" action="">
    <input type="hidden" name="customer_id" value="<?= $booking['CustomerID']; ?>">

    <label for="start_date">Start Date</label>
    <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($booking['StartDate']); ?>" required>

    <label for="end_date">End Date</label>
    <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($booking['EndDate']); ?>" required>

    <label for="total_cost">Total Cost (₹)</label>
    <input type="number" id="total_cost" name="total_cost" step="0.01" value="<?= htmlspecialchars($booking['TotalCost']); ?>" required>

    <label for="status">Booking Status</label>
    <select id="status" name="status" required>
        <?php
        $statuses = ['Pending', 'Confirmed', 'Cancelled', 'Completed'];
        foreach ($statuses as $status) {
            $selected = ($booking['BookingStatus'] === $status) ? 'selected' : '';
            echo "<option value=\"$status\" $selected>$status</option>";
        }
        ?>
    </select>

    <hr>

    <label for="customer_name">Customer Name</label>
    <input type="text" id="customer_name" name="customer_name" value="<?= htmlspecialchars($booking['Name']); ?>" required>

    <label for="customer_email">Email</label>
    <input type="email" id="customer_email" name="customer_email" value="<?= htmlspecialchars($booking['Email']); ?>" required>

    <label for="customer_phone">Phone (10 digits)</label>
    <input type="tel" id="customer_phone" name="customer_phone" value="<?= htmlspecialchars($booking['Phone']); ?>" pattern="\d{10}" title="Phone number must be exactly 10 digits" required>

    <label for="driver_license">Driver License Number</label>
    <input type="text" id="driver_license" name="driver_license" value="<?= htmlspecialchars($booking['DriverLicenseNumber']); ?>" required>

    <button type="submit">Update Booking</button>

    <?php if (!empty($error)) { ?>
        <div class="error"><?= htmlspecialchars($error); ?></div>
    <?php } ?>
</form>

<a class="back-link" href="manage_bookings.php">← Back to Manage Bookings</a>

</body>
</html>
