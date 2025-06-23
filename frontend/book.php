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
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      background: linear-gradient(to bottom right, #e0f7fa, #ffffff);
      color: #333;
    }

    header {
      background-color: #0d1b2a;
      color: #fff;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    header h1 {
      font-size: 1.8rem;
      color: #ff4c29;
    }

    nav a {
      color: #fff;
      margin-left: 1.5rem;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    nav a:hover {
      color: #ffcc00;
    }

    .section {
      max-width: 700px;
      margin: 3rem auto;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      padding: 2rem;
    }

    .section h2 {
      text-align: center;
      color: #0a58ca;
      margin-bottom: 1.5rem;
      font-size: 2rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
      transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
      border-color: #0a58ca;
      outline: none;
    }

    .form-group p {
      margin: 0.25rem 0;
    }

    button[type="submit"] {
      background-color: #ff4c29;
      color: #fff;
      border: none;
      padding: 0.75rem 2rem;
      font-size: 1rem;
      font-weight: 600;
      border-radius: 10px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      display: block;
      margin: 0 auto;
    }

    button[type="submit"]:hover {
      background-color: #e03e1c;
    }

    footer {
      text-align: center;
      padding: 1rem 0;
      margin-top: 3rem;
      background-color: #f0f0f0;
      color: #666;
    }
  </style>
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
        <input type="text" name="customer_name" placeholder="Full Name" required autocomplete="new-name">
      </div>
      <div class="form-group">
        <input type="email" name="customer_email" placeholder="Email" required autocomplete="off" 
       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
       title="Please enter a valid email address">
      </div>
      <div class="form-group">
        <input type="tel" name="customer_phone" placeholder="Phone Number" required autocomplete="off"
       pattern="\d{10}" title="Phone number must be exactly 10 digits">
      </div>
      <div class="form-group">
        <input type="date" name="pickup_date" placeholder="Start Date" required autocomplete="off">
      </div>
      <div class="form-group">
        <input type="date" name="return_date" placeholder="End Date" required autocomplete="off">
      </div>
      <div class="form-group">
        <input type="text" name="driver_license" placeholder="Driver License Number" required autocomplete="off">
      </div>
      <div class="form-group">
  <label for="payment_method">Payment Method:</label>
  <select name="payment_method" id="payment_method" required>
    <option value="">-- Select Payment Method --</option>
    <option value="Credit Card">Credit Card</option>
    <option value="Debit Card">Debit Card</option>
    <option value="UPI">UPI</option>
    <option value="Cash">Cash</option>
  </select>
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
