<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time In/Out Recording</title>
    <script>
        function getLocationAndPicture() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            document.getElementById("location").value = "Latitude: " + position.coords.latitude + 
            " Longitude: " + position.coords.longitude;
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
                var dataURL = canvas.toDataURL("image/png");
                document.getElementById("photo").value = dataURL;
            };
        }
    </script>
</head>
<body onload="getLocationAndPicture(); capturePhoto();">
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
        </select><br><br>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" readonly required><br><br>

        <input type="hidden" id="photo" name="photo">
        <video id="videoElement" width="320" height="240" autoplay></video>
        <canvas id="canvasElement" width="320" height="240" style="display:none;"></canvas>
        <br>
        <button type="button" id="captureButton">Capture Photo</button><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
