<?php 
require_once '../backend/connection.php';

// Get 3 available vehicles
$cars = $conn->query("SELECT * FROM vehicle WHERE AvailabilityStatus = 1 LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CarRentalPro</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <header>
    <h1>CarRentalPro</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="cars.php">Vehicles</a>
      <a href="book.php">Book</a>
      <a href="login.php" class="logout-btn">Logout</a>
    </nav>
  </header>

  <section class="hero">
    <h2>Ride in Style</h2>
    <p>Browse & Book Premium Cars</p>
    <a href="cars.php" class="btn">Book Now</a>
  </section>

  <section class="section" id="vehicles">
    <h3>Available Vehicles</h3>
    <div class="card-container">
      <?php while($car = $cars->fetch_assoc()): ?>
      <div class="card">
        <img src="../assets/images/default-car.jpg" alt="<?php echo htmlspecialchars($car['Model']); ?>" />
        <h4><?php echo htmlspecialchars($car['Make'] . ' ' . $car['Model']); ?></h4>
        <p>Color: White</p>
        <p>Year: <?php echo htmlspecialchars($car['Year']); ?></p>
        <p>Price/Day: ₹<?php echo number_format($car['RentalPricePerDay'], 2); ?></p>
      </div>
      <?php endwhile; ?>
    </div>
  </section>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> CarRentalPro. All rights reserved.</p>
  </footer>
</body>
</html>
<?php $conn->close(); ?>
