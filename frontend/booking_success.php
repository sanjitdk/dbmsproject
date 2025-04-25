<?php
// Check if booking was actually made
if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], 'process_booking.php') === false) {
    header("Location: book.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Confirmed | CarRentalPro</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>CarRentalPro</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="index.php#vehicles">Vehicles</a>
      <a href="book.php">Book Again</a>
    </nav>
  </header>

  <section class="section" style="min-height: 50vh;">
    <div style="text-align: center; max-width: 600px; margin: 0 auto;">
      <h2 style="font-size: 2rem; color: #27ae60;">🎉 Booking Confirmed!</h2>
      <p>Thank you for choosing CarRentalPro. Your booking has been successfully processed.</p>
      <p>We've sent the confirmation details to your email address.</p>
      <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
        <a href="index.php" class="btn">Back to Home</a>
        <a href="book.php" class="btn" style="background-color: #0f1123;">Book Another Car</a>
      </div>
    </div>
  </section>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> CarRentalPro. All rights reserved.</p>
  </footer>
</body>
</html>