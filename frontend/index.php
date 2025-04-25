<?php 
require_once '../backend/connection.php';
$cars = $conn->query("SELECT * FROM cars WHERE availability = 1 LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CarRentalPro</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>CarRentalPro</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="cars.php">Vehicles</a>
      <a href="book.php">Book</a>
      <a href="contact.php">Contact</a>
      <a href="../backend/admin_login.php">Admin</a>
    </nav>
  </header>

  <section class="hero">
    <h2>Ride in Style</h2>
    <p>Browse & Book Premium Cars</p>
    <a href="book.php" class="btn">Book Now</a>
  </section>

  <section class="section" id="vehicles">
    <h3>Available Vehicles</h3>
    <div class="card-container">
      <?php while($car = $cars->fetch_assoc()): ?>
      <div class="card">
        <img src="../<?php echo $car['image_url'] ?: 'assets/images/default-car.jpg'; ?>" alt="<?php echo $car['model']; ?>">
        <h4><?php echo $car['brand'].' '.$car['model']; ?></h4>
        <p>Price/Day: $<?php echo number_format($car['price_per_day'], 2); ?></p>
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
