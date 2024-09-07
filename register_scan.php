<?php

require 'config.php';

// Redirect to login if student_id session is not active
if (!isset($_SESSION['student_id'])) {
    header('Location: login.php');
    exit;
}

$student_id = $_SESSION['student_id'];

// Fetch user data to check if the fingerprint is already registered
$sql = "SELECT credential_id, attestation_object, client_data_json FROM tbl_users WHERE student_id = :student_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':student_id' => $student_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && ($user['credential_id'] || $user['attestation_object'] || $user['client_data_json'])) {
    // User already has fingerprint data registered
    $message = 'Fingerprint already registered. You cannot register again.';
} else {
    $message = '';
}
?>

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
        <h1>Touch ID</h1>
    </div>
    <div class="container">
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php else: ?>
            <form id="registerForm">
                <div class="icon">
                <i class="fa-solid fa-fingerprint fa-4x"></i>
                </div>
                <p class="description">
                    Enabling Touch ID will speed up your time-record process. Just register your phone along with your
                    fingerprint scan for quicker access.
                </p>
                <div class="buttons">
                    <button id="BackButton" type="button" class="button-secondary">Back</button>
                    <button type="submit" class="button-main">Register</button>
                </div>
                <a href="#" class="learn-more">Learn more about Touch ID</a>
            </form>
            <br>
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

                    const messageDiv = document.getElementById('message');
                    messageDiv.textContent = '';

                    try {
                        // Fetch the challenge from the server
                        const challengeResponse = await fetch('generate_challenge.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            }
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
                                id: Uint8Array.from(window.btoa('<?php echo $student_id; ?>'), c => c.charCodeAt(0)),
                                name: '<?php echo $student_id; ?>',
                                displayName: '<?php echo $student_id; ?>'
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
                                student_id: '<?php echo $student_id; ?>',
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

                document.getElementById('BackButton').addEventListener('click', function () {
                    window.location.href = 'home.php';
                });
            </script>
        <?php endif; ?>
    </div>
</body>

</html>