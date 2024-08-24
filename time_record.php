<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'includes/top_include.php' ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
    <!-- Add your Google Maps API key here -->
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Make sure the path to styles.css is correct -->
    <script>
        function getLocationAndPicture() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError, { enableHighAccuracy: true });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            // Update latitude and longitude fields
            document.getElementById("latitude").value = latitude;
            document.getElementById("longitude").value = longitude;

            // Reverse geocode to get a human-readable address
            var geocoder = new google.maps.Geocoder();
            var latLng = new google.maps.LatLng(latitude, longitude);

            geocoder.geocode({ 'latLng': latLng }, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        document.getElementById("address").value = results[0].formatted_address;
                    } else {
                        alert("No results found");
                    }
                } else {
                    alert("Geocoder failed due to: " + status);
                }
            });
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }

        function capturePhoto() {
            var video = document.querySelector("#videoElement");
            var canvas = document.querySelector("#canvasElement");
            var context = canvas.getContext("2d");

            // Start the video stream
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function (stream) {
                    video.srcObject = stream;
                    video.play();
                });

            document.getElementById("captureButton").onclick = function () {
                // Draw the current video frame to the canvas
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Convert canvas to a Blob and upload the photo
                canvas.toBlob(function (blob) {
                    var formData = new FormData();
                    formData.append('photo', blob, 'photo.png');

                    fetch('upload_photo.php', {
                        method: 'POST',
                        body: formData
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            // Store the file name in the hidden input field
                            document.getElementById("photo").value = data.fileName;

                            // Stop the video stream
                            var stream = video.srcObject;
                            var tracks = stream.getTracks();

                            tracks.forEach(function (track) {
                                track.stop();
                            });

                            video.srcObject = null;

                            // Hide the video element and show the captured image
                            video.style.display = 'none';
                            canvas.style.display = 'block';

                            // Change button text to "Re-Capture"
                            var captureButton = document.getElementById("captureButton");
                            captureButton.textContent = "Re-Capture Photo";
                            captureButton.onclick = function () {
                                // Delete the previously captured photo from the server
                                deleteOldPhoto();

                                // Reset the capture process
                                resetCaptureProcess(video, canvas, context);
                            };
                        } else {
                            alert("Failed to upload photo.");
                        }
                    }).catch(error => {
                        alert("Error: " + error.message);
                    });
                }, 'image/png');
            };
        }

        function deleteOldPhoto() {
            var oldPhoto = document.getElementById("photo").value;
            if (oldPhoto) {
                fetch('delete_photo.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ fileName: oldPhoto })
                }).then(response => response.json()).then(data => {
                    if (!data.success) {
                        alert("Failed to delete old photo.");
                    }
                }).catch(error => {
                    alert("Error: " + error.message);
                });
            }
        }

        function resetCaptureProcess(video, canvas, context) {
            // Clear the canvas and reset the video stream
            context.clearRect(0, 0, canvas.width, canvas.height);
            canvas.style.display = 'none';
            video.style.display = 'block';

            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function (stream) {
                    video.srcObject = stream;
                    video.play();
                });

            // Reset the capture button functionality and text
            var captureButton = document.getElementById("captureButton");
            captureButton.textContent = "Capture Photo";
            captureButton.onclick = function () {
                // Re-assign the original capture functionality
                capturePhoto();
            };

            // Clear the hidden input value for photo
            document.getElementById("photo").value = "";
        }


        // Function to handle form submission via AJAX
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector("form").addEventListener("submit", function (e) {
                e.preventDefault(); // Prevent the default form submission

                var formData = new FormData(this); // Create a FormData object with the form data

                fetch('time_record_conn.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.text())
                    .then(data => {
                        // Display the response message below the form
                        document.querySelector("#response-message").innerHTML = data;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.querySelector("#response-message").innerHTML = "An unexpected error occurred.";
                    });
            });
        });

    </script>
</head>

<body onload="getLocationAndPicture(); capturePhoto();">
    <div class="container">
        <div class="left-panel">
            <!-- Left panel content remains the same -->
            <video id="videoElement" width="320" height="240" autoplay></video>
            <canvas id="canvasElement" width="320" height="240" style="display:none;"></canvas>
            <br>
            <input type="hidden" id="old_photo" name="old_photo" value="">
            <button type="button" id="captureButton" class="button-secondary">Capture Photo</button>
        </div>
        <div class="right-panel">
            <h2>Time In/Out Recording</h2>
            <form method="post" enctype="multipart/form-data">
                <!-- Form inputs -->
                <label for="student_id">Student ID:</label>
                <input type="text" id="student_id" name="student_id" required><br><br>

                <label for="pin">4-digit PIN:</label>
                <input type="password" id="pin" name="pin" pattern="\d{4}" title="Please enter a 4-digit PIN"
                    required><br><br>

                <label for="type">Type:</label>
                <select id="type" name="type" required>
                    <option value="time_in">Time In</option>
                    <option value="time_out">Time Out</option>
                </select>

                <input type="text" id="latitude" name="latitude" readonly required hidden>
                <input type="text" id="longitude" name="longitude" readonly required hidden><br><br>

                <input type="hidden" id="photo" name="photo">
                <button type="submit" class="button">Submit</button>
                <br><br>
                <a href="./">Back</a>
            </form>

            <!-- Div to display the response message -->
            <div id="response-message" style="color: red; margin-top: 20px;"></div>
        </div>
    </div>
</body>

</html>