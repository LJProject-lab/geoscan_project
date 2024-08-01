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
    <a href="login.html">Login</a>
    <h1>Register</h1>
    <form id="registerForm">
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required>
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
</body>
</html>
