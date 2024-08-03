<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        }
        .message {
            color: red;
            font-weight: bold;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label, input {
            margin: 5px 0;
        }
        button {
            margin-top: 10px;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <form id="registerForm">
            <label for="student_id">Student ID:</label>
            <input type="text" id="student_id" name="student_id" required>

            <label for="pin">Pin:</label>
            <input type="text" id="pin" name="pin" required>

            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <button type="submit">Register</button>
        </form>
        <div id="message" class="message"></div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async (event) => {
            event.preventDefault();
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = '';

            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('register_process.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    messageDiv.className = 'success';
                    messageDiv.textContent = 'Registration successful.';
                    event.target.reset();
                } else {
                    messageDiv.className = 'message';
                    messageDiv.textContent = result.message;
                }
            } catch (error) {
                messageDiv.className = 'message';
                messageDiv.textContent = 'An error occurred. Please try again.';
            }
        });
    </script>
</body>
</html>
