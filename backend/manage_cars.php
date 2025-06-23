<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../frontend/login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM vehicle WHERE VehicleID = $id");
    header("Location: manage_cars.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $make = $conn->real_escape_string($_POST['make']);
    $model = $conn->real_escape_string($_POST['model']);
    $year = (int)$_POST['year'];
    $color = $conn->real_escape_string($_POST['color']);
    $plate = $conn->real_escape_string($_POST['license']);
    $availability = isset($_POST['availability']) ? 1 : 0;
    $price = (float)$_POST['price'];

    if (isset($_POST['vehicle_id'])) {
        $id = (int)$_POST['vehicle_id'];
        $conn->query("UPDATE vehicle SET 
            Make='$make', Model='$model', Year=$year, Color='$color',
            LicensePlate='$plate', AvailabilityStatus=$availability,
            RentalPricePerDay=$price
            WHERE VehicleID=$id");
    } else {
        $conn->query("INSERT INTO vehicle 
            (Make, Model, Year, Color, LicensePlate, AvailabilityStatus, RentalPricePerDay) 
            VALUES ('$make', '$model', $year, '$color', '$plate', $availability, $price)");
    }
    header("Location: manage_cars.php");
    exit();
}

$vehicles = $conn->query("SELECT * FROM vehicle ORDER BY Make, Model");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Vehicles - CarRentalPro</title>
    <link rel="stylesheet" href="../frontend/style.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        header {
            background: #1e3a8a;
            color: white;
            padding: 20px;
        }

        header nav a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
            font-weight: 500;
        }

        main {
            padding: 30px;
        }

        h1, h2 {
            color: #1e3a8a;
        }

        .vehicle-form {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-row {
            margin-bottom: 15px;
        }

        .form-row label {
            display: block;
            margin-bottom: 6px;
            color: #374151;
        }

        .form-row input[type="text"],
        .form-row input[type="number"] {
            width: 100%;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
        }

        .form-row input[type="checkbox"] {
            transform: scale(1.2);
            margin-left: 5px;
        }

        .button {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .button:hover {
            background-color: #1d4ed8;
        }

        .button.secondary {
            background-color: #9ca3af;
        }

        .button.secondary:hover {
            background-color: #6b7280;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        th {
            background-color: #f9fafb;
            color: #374151;
        }

        td {
            color: #1f2937;
        }

        .actions a {
            margin-right: 10px;
        }

        @media screen and (max-width: 768px) {
            .form-row label {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Manage Vehicles</h1>
    <nav>
        <a href="../frontend/index.php">View Site</a>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <div class="vehicle-form">
        <h2><?php echo isset($_GET['edit']) ? 'Edit Vehicle' : 'Add New Vehicle'; ?></h2>
        <form method="post">
            <?php
            $vehicle = null;
            if (isset($_GET['edit'])) {
                $id = (int)$_GET['edit'];
                $vehicle = $conn->query("SELECT * FROM vehicle WHERE VehicleID = $id")->fetch_assoc();
                echo '<input type="hidden" name="vehicle_id" value="'.$id.'">';
            }
            ?>
            <div class="form-row">
                <label>Make:</label>
                <input type="text" name="make" value="<?php echo $vehicle ? $vehicle['Make'] : ''; ?>" required>
            </div>
            <div class="form-row">
                <label>Model:</label>
                <input type="text" name="model" value="<?php echo $vehicle ? $vehicle['Model'] : ''; ?>" required>
            </div>
            <div class="form-row">
                <label>Year:</label>
                <input type="number" name="year" value="<?php echo $vehicle ? $vehicle['Year'] : '2023'; ?>" required>
            </div>
            <div class="form-row">
                <label>Color:</label>
                <input type="text" name="color" value="<?php echo $vehicle ? $vehicle['Color'] : ''; ?>">
            </div>
            <div class="form-row">
                <label>License Plate:</label>
                <input type="text" name="license" value="<?php echo $vehicle ? $vehicle['LicensePlate'] : ''; ?>" required>
            </div>
            <div class="form-row">
                <label>Price Per Day:</label>
                <input type="number" step="0.01" name="price" value="<?php echo $vehicle ? $vehicle['RentalPricePerDay'] : '50.00'; ?>" required>
            </div>
            <div class="form-row">
                <label>Available:
                    <input type="checkbox" name="availability" <?php echo ($vehicle && $vehicle['AvailabilityStatus']) || !isset($_GET['edit']) ? 'checked' : ''; ?>>
                </label>
            </div>
            <button type="submit" class="button"><?php echo isset($_GET['edit']) ? 'Update' : 'Add'; ?> Vehicle</button>
            <?php if (isset($_GET['edit'])): ?>
                <a href="manage_cars.php" class="button secondary">Cancel</a>
            <?php endif; ?>
        </form>
    </div>

    <h2>Current Vehicles</h2>
    <table>
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Year</th>
            <th>Color</th>
            <th>License</th>
            <th>Price/Day</th>
            <th>Available</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $vehicles->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['Make']; ?></td>
                <td><?php echo $row['Model']; ?></td>
                <td><?php echo $row['Year']; ?></td>
                <td><?php echo $row['Color']; ?></td>
                <td><?php echo $row['LicensePlate']; ?></td>
                <td>$<?php echo number_format($row['RentalPricePerDay'], 2); ?></td>
                <td><?php echo $row['AvailabilityStatus'] ? 'Yes' : 'No'; ?></td>
                <td class="actions">
                    <a href="manage_cars.php?edit=<?php echo $row['VehicleID']; ?>" class="button">Edit</a>
                    <a href="manage_cars.php?delete=<?php echo $row['VehicleID']; ?>" class="button secondary"
                       onclick="return confirm('Are you sure you want to delete this vehicle?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</main>

</body>
</html>
