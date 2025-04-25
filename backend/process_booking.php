<?php
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Validate inputs
        $vehicle_id = (int)$_POST['car_id'];
        $customer_name = $conn->real_escape_string($_POST['customer_name']);
        $customer_email = $conn->real_escape_string($_POST['customer_email']);
        $customer_phone = $conn->real_escape_string($_POST['customer_phone']);
        $driver_license = $conn->real_escape_string($_POST['driver_license']);
        $pickup_date = $conn->real_escape_string($_POST['pickup_date']);
        $return_date = $conn->real_escape_string($_POST['return_date']);

        // 2. Calculate rental cost
        $days = (strtotime($return_date) - strtotime($pickup_date)) / (60 * 60 * 24) + 1;
        $price_query = $conn->query("SELECT RentalPricePerDay FROM vehicle WHERE VehicleID = $vehicle_id");
        $price_data = $price_query->fetch_assoc();
        $total_cost = $days * $price_data['RentalPricePerDay'];

        // 3. Create customer
        $conn->query("INSERT INTO customer (Name, Email, Phone, DriverLicenseNumber) 
                     VALUES ('$customer_name', '$customer_email', '$customer_phone', '$driver_license')");
        $customer_id = $conn->insert_id;

        // 4. Create booking (always Confirmed with calculated cost)
        $conn->query("INSERT INTO booking (CustomerID, VehicleID, StartDate, EndDate, TotalCost, BookingStatus) 
                     VALUES ($customer_id, $vehicle_id, '$pickup_date', '$return_date', $total_cost, 'Confirmed')");

        header("Location: ../frontend/booking_success.php");
        exit();
        
    } catch (Exception $e) {
        die("Booking failed: " . $e->getMessage());
    }
} else {
    header("Location: ../frontend/book.php");
    exit();
}
?>