
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'includes/top_include.php' ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script> <!-- Add your Google Maps API key here -->
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Make sure the path to styles.css is correct -->
    <script>
        function getLocationAndPicture() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError, {enableHighAccuracy: true});
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
            
            geocoder.geocode({'latLng': latLng}, function(results, status) {
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
            switch(error.code) {
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

            navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                video.srcObject = stream;
                video.play();
            });

            document.getElementById("captureButton").onclick = function() {
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                canvas.toBlob(function(blob) {
                    var formData = new FormData();
                    formData.append('photo', blob, 'photo.png');

                    fetch('upload_photo.php', {
                        method: 'POST',
                        body: formData
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            document.getElementById("photo").value = data.fileName;
                        } else {
                            alert("Failed to upload photo.");
                        }
                    }).catch(error => {
                        alert("Error: " + error.message);
                    });
                }, 'image/png');
            };
        }
    </script>
</head>
<body onload="getLocationAndPicture(); capturePhoto();">
    <div class="container">
        <div class="left-panel">
            <video id="videoElement" width="320" height="240" autoplay></video>
            <canvas id="canvasElement" width="320" height="240" style="display:none;"></canvas>
            <br>
            <button type="button" id="captureButton" class="button-secondary">Capture Photo</button>
        </div>
        <div class="right-panel">
            <h2>Time In/Out Recording</h2>
            <form action="time_record_conn.php" method="post">
                <label for="student_id">Student ID:</label>
                <input type="text" id="student_id" name="student_id" required><br><br>

                <label for="pin">4-digit PIN:</label>
                <input type="text" id="pin" name="pin" pattern="\d{4}" title="Please enter a 4-digit PIN" required><br><br>

                <label for="type">Type:</label>
                <select id="type" name="type" required>
                    <option value="time_in">Time In</option>
                    <option value="time_out">Time Out</option>
                </select>

                <input type="text" id="latitude" name="latitude" readonly required hidden>

                <input type="text" id="longitude" name="longitude" readonly required hidden><br><br>

                <input type="hidden" id="photo" name="photo">
                
                <button type="submit" class="button">Submit</button>
                <br>
                <br>
                <a href="./">Back</a>
            </form>
        </div>
    </div>
</body>
</html>
