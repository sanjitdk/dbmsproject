<?php
require_once '../backend/connection.php';

// Initialize variables
$selected_vehicle = [
    'VehicleID' => '',
    'Make' => '',
    'Model' => '',
    'RentalPricePerDay' => ''
];
$vehicle_id = isset($_GET['car_id']) ? (int)$_GET['car_id'] : 0;

// If a specific vehicle is selected, get its details
if ($vehicle_id > 0) {
    $stmt = $conn->prepare("SELECT VehicleID, Make, Model, RentalPricePerDay FROM vehicle WHERE VehicleID = ?");
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $selected_vehicle = $result->fetch_assoc();
    $stmt->close();
}

// Get all available vehicles for dropdown
$vehicles = $conn->query("SELECT VehicleID, Make, Model, RentalPricePerDay FROM vehicle");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book a Vehicle | CarRentalPro</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>CarRentalPro</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="cars.php">All Vehicles</a>
      <a href="book.php">Book</a>
      <a href="contact.php">Contact</a>
    </nav>
  </header>

  <section class="section">
    <h2>Book a Vehicle</h2>
    <form action="../backend/process_booking.php" method="POST">
      <?php if($selected_vehicle && !empty($selected_vehicle['Make'])): ?>
        <div class="form-group">
          <label>Selected Vehicle:</label>
          <p><strong><?php echo htmlspecialchars($selected_vehicle['Make'] . ' ' . $selected_vehicle['Model']); ?></strong></p>
          <p>Price/Day: $<?php echo number_format($selected_vehicle['RentalPricePerDay'], 2); ?></p>
          <input type="hidden" name="car_id" value="<?php echo $selected_vehicle['VehicleID']; ?>">
        </div>
      <?php else: ?>
        <div class="form-group">
          <label for="car_id">Select Vehicle:</label>
          <select name="car_id" id="car_id" required>
            <option value="">-- Choose a vehicle --</option>
            <?php while($vehicle = $vehicles->fetch_assoc()): ?>
              <option value="<?php echo $vehicle['VehicleID']; ?>">
                <?php 
                echo htmlspecialchars(
                    ($vehicle['Make'] ?? '[No Make]') . ' ' . 
                    ($vehicle['Model'] ?? '[No Model]') . 
                    ' ($' . number_format($vehicle['RentalPricePerDay'] ?? 0, 2) . '/day)'
                ); 
                ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
      <?php endif; ?>

      <div class="form-group">
        <input type="text" name="customer_name" placeholder="Full Name" required>
      </div>
      <div class="form-group">
        <input type="email" name="customer_email" placeholder="Email" required>
      </div>
      <div class="form-group">
        <input type="tel" name="customer_phone" placeholder="Phone Number" required>
      </div>
      <div class="form-group">
        <input type="date" name="pickup_date" placeholder="Start Date" required>
      </div>
      <div class="form-group">
        <input type="date" name="return_date" placeholder="End Date" required>
      </div>
      <div class="form-group">
    <input type="text" name="driver_license" placeholder="Driver License Number" required>
</div>
      <button type="submit">Confirm Booking</button>
    </form>
  </section>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> CarRentalPro. All rights reserved.</p>
  </footer>
</body>
</html>
<?php $conn->close(); ?>