<?php 
require_once '../backend/connection.php';

// Use the correct column name: AvailabilityStatus
$query = "SELECT VehicleID, Make, Model, LicensePlate, RentalPricePerDay, AvailabilityStatus FROM vehicle";
$result = $conn->query($query);

if (!$result) {
    die("Database query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Our Fleet | CarRentalPro</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <style>
    .card-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 2rem;
      padding: 2rem;
      max-width: 1200px;
      margin: 0 auto;
    }
    .card {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      background: #f5f5f5;
    }
    .card-content {
      padding: 1.5rem;
    }
    .card h3 {
      margin: 0 0 0.5rem;
      color: #2c3e50;
      font-size: 1.2rem;
    }
    .card p {
      margin: 0.3rem 0;
      color: #7f8c8d;
    }
    .btn {
      display: inline-block;
      margin-top: 1rem;
      padding: 0.5rem 1rem;
      background: #3498db;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: 600;
      transition: background 0.3s;
    }
    .btn:hover {
      background: #2980b9;
    }
    .btn.disabled {
      background: #bdc3c7;
      pointer-events: none;
      cursor: not-allowed;
    }
    .no-vehicles {
      text-align: center;
      grid-column: 1/-1;
      padding: 2rem;
      color: #7f8c8d;
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
    </nav>
  </header>

  <main>
    <h2 style="text-align: center; margin: 2rem 0 1rem; color: #2c3e50;">Our Complete Fleet</h2>
    
    <div class="card-container">
      <?php if ($result->num_rows > 0): ?>
        <?php while($vehicle = $result->fetch_assoc()): ?>
          <?php $available = $vehicle['AvailabilityStatus'] == 1; ?>
          <div class="card">
            <img src="../assets/images/vehicles/<?php echo strtolower($vehicle['Make'].'-'.$vehicle['Model'].'.jpg'); ?>" 
                 alt="<?php echo htmlspecialchars($vehicle['Make'] . ' ' . $vehicle['Model']); ?>"
                 onerror="this.src='../assets/images/default-car.jpg'">
            <div class="card-content">
              <h3><?php echo htmlspecialchars($vehicle['Make'] . ' ' . $vehicle['Model']); ?></h3>
              <p><strong>Availability:</strong> 
                <span style="color: <?php echo $available ? 'green' : 'red'; ?>">
                  <?php echo $available ? 'Available' : 'Unavailable'; ?>
                </span>
              </p>
              <p><strong>Price/Day:</strong> $<?php echo number_format($vehicle['RentalPricePerDay'], 2); ?></p>
              <p><strong>License:</strong> <?php echo htmlspecialchars($vehicle['LicensePlate'] ?? 'N/A'); ?></p>
              <a href="<?php echo $available ? 'book.php?car_id=' . $vehicle['VehicleID'] : '#'; ?>" 
                 class="btn <?php echo $available ? '' : 'disabled'; ?>">
                 <?php echo $available ? 'Book Now' : 'Unavailable'; ?>
              </a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="no-vehicles">
          <p>No vehicles currently available. Please check back later.</p>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> CarRentalPro. All rights reserved.</p>
  </footer>
</body>
</html>
<?php $conn->close(); ?>
