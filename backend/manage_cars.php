<?php
session_start();
require_once 'connection.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle car deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM cars WHERE id = $id");
    header("Location: manage_cars.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $make = $conn->real_escape_string($_POST['make']);
    $model = $conn->real_escape_string($_POST['model']);
    $year = (int)$_POST['year'];
    $price = (float)$_POST['price'];
    $available = isset($_POST['available']) ? 1 : 0;
    $image = $conn->real_escape_string($_POST['image']);

    if (isset($_POST['car_id'])) { // Update existing
        $id = (int)$_POST['car_id'];
        $conn->query("UPDATE cars SET 
            make='$make', model='$model', year=$year, 
            price_per_day=$price, available=$available, image='$image'
            WHERE id=$id");
    } else { // Insert new
        $conn->query("INSERT INTO cars (make, model, year, price_per_day, available, image)
            VALUES ('$make', '$model', $year, $price, $available, '$image')");
    }
    header("Location: manage_cars.php");
    exit();
}

// Get all cars
$cars = $conn->query("SELECT * FROM cars ORDER BY make, model");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Cars</title>
    <link rel="stylesheet" href="../frontend/style.css">
    <style>
        .car-form {
            background: #f9f9f9;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .form-row {
            margin-bottom: 15px;
        }
        .form-row label {
            display: inline-block;
            width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #f2f2f2;
        }
        .actions a {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="../frontend/index.php">View Site</a>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h1>Manage Cars</h1>
        
        <div class="car-form">
            <h2><?php echo isset($_GET['edit']) ? 'Edit Car' : 'Add New Car'; ?></h2>
            <form method="post">
                <?php
                $car = null;
                if (isset($_GET['edit'])) {
                    $id = (int)$_GET['edit'];
                    $car = $conn->query("SELECT * FROM cars WHERE id = $id")->fetch_assoc();
                    echo '<input type="hidden" name="car_id" value="'.$id.'">';
                }
                ?>
                <div class="form-row">
                    <label>Make:</label>
                    <input type="text" name="make" value="<?php echo $car ? $car['make'] : ''; ?>" required>
                </div>
                <div class="form-row">
                    <label>Model:</label>
                    <input type="text" name="model" value="<?php echo $car ? $car['model'] : ''; ?>" required>
                </div>
                <div class="form-row">
                    <label>Year:</label>
                    <input type="number" name="year" min="1900" max="<?php echo date('Y')+1; ?>" 
                           value="<?php echo $car ? $car['year'] : date('Y'); ?>" required>
                </div>
                <div class="form-row">
                    <label>Price/Day:</label>
                    <input type="number" step="0.01" name="price" 
                           value="<?php echo $car ? $car['price_per_day'] : '50.00'; ?>" required>
                </div>
                <div class="form-row">
                    <label>Image:</label>
                    <input type="text" name="image" value="<?php echo $car ? $car['image'] : 'car-default.jpg'; ?>">
                </div>
                <div class="form-row">
                    <label>Available:</label>
                    <input type="checkbox" name="available" <?php echo ($car && $car['available']) || !isset($_GET['edit']) ? 'checked' : ''; ?>>
                </div>
                <button type="submit" class="button"><?php echo isset($_GET['edit']) ? 'Update' : 'Add'; ?> Car</button>
                <?php if(isset($_GET['edit'])): ?>
                    <a href="manage_cars.php" class="button secondary">Cancel</a>
                <?php endif; ?>
            </form>
        </div>

        <h2>Current Cars</h2>
        <table>
            <tr>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>Price/Day</th>
                <th>Available</th>
                <th>Actions</th>
            </tr>
            <?php while($car = $cars->fetch_assoc()): ?>
            <tr>
                <td><?php echo $car['make']; ?></td>
                <td><?php echo $car['model']; ?></td>
                <td><?php echo $car['year']; ?></td>
                <td>$<?php echo number_format($car['price_per_day'], 2); ?></td>
                <td><?php echo $car['available'] ? 'Yes' : 'No'; ?></td>
                <td class="actions">
                    <a href="manage_cars.php?edit=<?php echo $car['id']; ?>" class="button">Edit</a>
                    <a href="manage_cars.php?delete=<?php echo $car['id']; ?>" class="button secondary" 
                       onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>
</html>