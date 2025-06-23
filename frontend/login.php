<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - CarRentalPro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(to bottom right, #0f172a, #1e3a8a);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1e3a8a;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 600;
        }

        select, input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .login-button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-button:hover {
            background: #1e40af;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login to CarRentalPro</h2>
        <form method="POST" action="../backend/process_login.php">
            <label>Login as:</label>
            <select name="role" required>
                <option value="User">User</option>
                <option value="Admin">Admin</option>
            </select>

            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
</body>
</html>
