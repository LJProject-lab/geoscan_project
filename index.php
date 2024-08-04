<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        .message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<<<<<<< Updated upstream
    <a href="login.html">Login</a>
    <h1>Register</h1>
    <form id="registerForm">
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required><br>

        <label for="pin">Pin:</label>
        <input type="text" id="pin" name="pin" required><br>

        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required><br>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br>

        <button type="submit">Register</button>
    </form>
    <div id="message" class="message"></div>

    <script>
        function bufferToBase64(buffer) {
            let binary = '';
            const bytes = new Uint8Array(buffer);
            for (let i = 0; i < bytes.byteLength; i++) {
                binary += String.fromCharCode(bytes[i]);
            }
            return window.btoa(binary);
        }

        document.getElementById('registerForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const student_id = document.getElementById('student_id').value;
            const pin = document.getElementById('pin').value;
            const firstname = document.getElementById('firstname').value;
            const lastname = document.getElementById('lastname').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const address = document.getElementById('address').value;
            const messageDiv = document.getElementById('message');

            // Clear previous messages
            messageDiv.textContent = '';

            try {
                // Fetch the challenge from the server
                const challengeResponse = await fetch('generate_challenge.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ student_id })
                });

                const challengeData = await challengeResponse.json();
                if (!challengeData.success) {
                    messageDiv.textContent = challengeData.message;
                    return;
                }

                const { challenge } = challengeData;
                const publicKey = {
                    challenge: Uint8Array.from(atob(challenge), c => c.charCodeAt(0)),
                    rp: { name: "Your Application Name" },
                    user: {
                        id: Uint8Array.from(window.btoa(student_id), c => c.charCodeAt(0)),
                        name: student_id,
                        displayName: student_id
                    },
                    pubKeyCredParams: [
                        { type: "public-key", alg: -7 },  // ES256
                        { type: "public-key", alg: -257 } // RS256
                    ],
                    authenticatorSelection: { userVerification: "preferred" },
                    timeout: 60000,
                    attestation: "direct"
                };

                const credential = await navigator.credentials.create({ publicKey });

                // Send the credential to the server for validation and storage
                const response = await fetch('register_fingerprint.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        student_id: student_id,
                        pin: pin,
                        firstname: firstname,
                        lastname: lastname,
                        email: email,
                        phone: phone,
                        address: address,
                        credential: {
                            id: bufferToBase64(credential.rawId),
                            rawId: bufferToBase64(credential.rawId),
                            response: {
                                attestationObject: bufferToBase64(credential.response.attestationObject),
                                clientDataJSON: bufferToBase64(credential.response.clientDataJSON)
                            },
                            type: credential.type
                        }
                    })
                });

                const result = await response.json();
                if (result.success) {
                    messageDiv.textContent = 'Registration successful. You can now log in with your fingerprint.';
                } else {
                    messageDiv.textContent = result.message;
                }
            } catch (error) {
                console.error('Error:', error);
                messageDiv.textContent = 'Registration failed. Please try again.';
            }
        });
    </script>
=======
    <div class="container">
        <div class="top-section">
            <a href="login.php">Login</a>
            <a href="register_v3.php">Register</a>
        </div>
        <h1>Welcome</h1>
        <div class="center-section">
            <button onclick="location.href='time_scan.php'">Scan Time In</button>
            <button onclick="location.href='time_record.php'">4 Pin Time In</button>
        </div>
        <div class="description">
            <h2>Scan Time In</h2>
            <p>The Scan Time In option allows you to time in by simply pressing a button. It automatically fetches your location and the current time.</p>
            <h2>4 Pin Time In</h2>
            <p>The 4 Pin option requires additional supporting evidence. You need to provide a picture and a 4-digit pin for time in.</p>
        </div>
    </div>
>>>>>>> Stashed changes
</body>
</html>
