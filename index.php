<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .container {
            width: 80%;
            max-width: 500px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .top-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .top-section a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }
        .center-section {
            margin-top: 20px;
        }
        .center-section button {
            display: block;
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .center-section button:hover {
            background-color: #0056b3;
        }
        .description {
            font-style: italic;
            margin-top: 20px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-section">
            <a href="login.php">Login</a>
            <a href="register_v3.php">Register</a>
        </div>
        <h1>Welcome</h1>
        <div class="center-section">
            <button onclick="location.href='time_scan.php'">Scan Time In</button>
            <button onclick="location.href='time_pin.php'">4 Pin Time In</button>
        </div>
        <div class="description">
            <h2>Scan Time In</h2>
            <p>The Scan Time In option allows you to time in by simply pressing a button. It automatically fetches your location and the current time.</p>
            <h2>4 Pin Time In</h2>
            <p>The 4 Pin option requires additional supporting evidence. You need to provide a picture and a 4-digit pin for time in.</p>
        </div>
    </div>
</body>
</html>
