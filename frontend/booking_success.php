<?php
session_start();

if (empty($_SESSION['booking_success'])) {
    // No booking done, redirect to book.php
    header("Location: book.php");
    exit();
}

// Clear the flag so refreshing the page won't show success without new booking
unset($_SESSION['booking_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Booking Confirmed | CarRentalPro</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f9f9f9;
      color: #333;
      margin: 0;
      padding: 0;
    }
    header {
      background-color: #0d1b2a;
      color: white;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header h1 {
      color: #ff4c29;
      font-size: 1.8rem;
    }
    nav a {
      color: white;
      margin-left: 1.5rem;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }
    nav a:hover {
      color: #ffcc00;
    }
    .section {
      max-width: 600px;
      margin: 4rem auto;
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      text-align: center;
    }
    .section h2 {
      color: #27ae60;
      font-size: 2rem;
      margin-bottom: 1rem;
    }
    .btn {
      background-color: #ff4c29;
      color: white;
      padding: 0.75rem 2rem;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
      margin: 0 0.5rem;
      display: inline-block;
      transition: background-color 0.3s ease;
    }
    .btn:hover {
      background-color: #e03e1c;
    }
    footer {
      text-align: center;
      margin-top: 3rem;
      padding: 1rem 0;
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
      <a href="index.php#vehicles">Vehicles</a>
      <a href="book.php">Book Again</a>
    </nav>
  </header>

  <section class="section">
    <h2>🎉 Booking Confirmed!</h2>
    <p>Thank you for choosing CarRentalPro. Your booking has been successfully processed.</p>
    <p>We've sent the confirmation details to your email address.</p>
    <div style="margin-top: 2rem;">
      <a href="index.php" class="btn">Back to Home</a>
      <a href="book.php" class="btn">Book Another Car</a>
    </div>
  </section>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> CarRentalPro. All rights reserved.</p>
  </footer>
</body>
</html>
