<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <?php include 'includes/top_include.php' ?>
</head>

<body>
    <div class="header h1-header">
        <h1>Tap to scan</h1>
    </div>

    <!-- Dropdown to select Time In or Time Out -->
    <div class="select-time">
        <label for="timeSelection">Select Time:</label>
        <select id="timeSelection" class="time-dropdown">
            <option value="time_in">Time In</option>
            <option value="time_out">Time Out</option>
        </select>
    </div>

    <br>
    <br>
    <br>
    <div class="icon">
        <button class="button-main-scan" id="loginButton"
            style="border-radius:100%; !important; height:200px; width:185px;"><i class="fa-solid fa-fingerprint fa-8x"
                style="color:#fff !important;"></i></button>
    </div>
    <br>
    <br>
    <div class="buttons">
        <button id="BackButton" type="button" class="button-secondary">Back</button>
    </div>
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

        document.getElementById('loginButton').addEventListener('click', async () => {
            const messageDiv = document.getElementById('message');
            const timeSelection = document.getElementById('timeSelection').value;
            messageDiv.textContent = '';

            // Map the timeSelection to formal messages
            const timeSelectionMessages = {
                'time_in': 'Time In',
                'time_out': 'Time Out'
            };
            const formalMessage = timeSelectionMessages[timeSelection] || 'Unknown';

            if (!navigator.geolocation) {
                messageDiv.className = 'message error'; // Apply error class
                messageDiv.textContent = 'Geolocation is not supported by your browser.';
                return;
            }

            const getLocation = () => {
                return new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(resolve, reject);
                });
            };

            try {
                const position = await getLocation();
                const { latitude, longitude } = position.coords;

                try {
                    const challengeResponse = await fetch('generate_challenge.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    const challengeData = await challengeResponse.json();
                    if (!challengeData.success) {
                        messageDiv.className = 'message error'; // Apply error class
                        messageDiv.textContent = challengeData.message;
                        return;
                    }

                    const { challenge, credentials } = challengeData;
                    const allowCredentials = credentials.map(cred => ({
                        type: "public-key",
                        id: Uint8Array.from(atob(cred), c => c.charCodeAt(0))
                    }));

                    const publicKey = {
                        challenge: Uint8Array.from(atob(challenge), c => c.charCodeAt(0)),
                        allowCredentials,
                        timeout: 60000
                    };

                    const assertion = await navigator.credentials.get({ publicKey });

                    const credentialId = bufferToBase64(assertion.rawId);

                    const response = await fetch('login_fingerprint.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            credential: {
                                id: credentialId,
                                rawId: credentialId,
                                response: {
                                    authenticatorData: bufferToBase64(assertion.response.authenticatorData),
                                    clientDataJSON: bufferToBase64(assertion.response.clientDataJSON),
                                    signature: bufferToBase64(assertion.response.signature)
                                },
                                type: assertion.type
                            },
                            longitude,
                            latitude,
                            timeSelection // Include the time selection (in/out)
                        })
                    });

                    const result = await response.json();
                    if (result.success) {
                        messageDiv.className = 'message success'; // Apply success class
                        messageDiv.textContent = `Successfully recorded your ${formalMessage}.`;
                    } else {
                        messageDiv.className = 'message error'; // Apply error class
                        messageDiv.textContent = result.message;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    messageDiv.className = 'message error'; // Apply error class
                    messageDiv.textContent = 'Scan failed. Please try again.';
                }
            } catch (error) {
                if (error.code === error.PERMISSION_DENIED) {
                    messageDiv.className = 'message error'; // Apply error class
                    messageDiv.textContent = 'Please enable your GPS location and try again.';
                } else {
                    messageDiv.className = 'message error'; // Apply error class
                    messageDiv.textContent = 'Unable to retrieve your location. Please ensure GPS is enabled and try again.';
                }
            }
        });

        document.getElementById('BackButton').addEventListener('click', function () {
            window.location.href = './';
        });

    </script>
</body>

</html>