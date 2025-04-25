<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | CarRentalPro</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    .contact-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 3rem;
      max-width: 1000px;
      margin: 0 auto;
    }
    .contact-info {
      background: #f9f9f9;
      padding: 2rem;
      border-radius: 10px;
    }
    .contact-info h4 {
      margin-top: 1.5rem;
      color: #0f1123;
    }
    .contact-info h4:first-child {
      margin-top: 0;
    }
    @media (max-width: 768px) {
      .contact-container {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>CarRentalPro</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="cars.php">Vehicles</a>
      <a href="book.php">Book</a>
      <a href="contact.php">Contact</a>
    </nav>
  </header>

  <section class="section">
    <h3>Contact Our Team</h3>
    
    <div class="contact-container">
      <div class="contact-info">
        <h4>Our Location</h4>
        <p>123 Rental Street<br>Cityville, CV 12345</p>
        
        <h4>Phone</h4>
        <p>(123) 456-7890</p>
        
        <h4>Email</h4>
        <p>info@carrentalpro.com</p>
        
        <h4>Business Hours</h4>
        <p>Monday-Friday: 9am-6pm<br>Saturday: 10am-4pm<br>Sunday: Closed</p>
      </div>
      
      <form class="contact-form" action="#" method="post">
        <div class="form-group">
          <input type="text" name="name" placeholder="Your Name" required>
        </div>
        <div class="form-group">
          <input type="email" name="email" placeholder="Email Address" required>
        </div>
        <div class="form-group">
          <input type="tel" name="phone" placeholder="Phone Number">
        </div>
        <div class="form-group">
          <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
        </div>
        <button type="submit" class="btn" style="background-color: #0f1123; width: 100%;">Send Message</button>
      </form>
    </div>
  </section>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> CarRentalPro. All rights reserved.</p>
  </footer>
</body>
</html>