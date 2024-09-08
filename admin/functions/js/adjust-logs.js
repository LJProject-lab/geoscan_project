$(document).ready(function () {
  // Function to fetch missed time-outs for a student
  function fetchMissedTimeOuts(studentId) {
    $.ajax({
      url: "functions/get-logs.php",
      method: "POST",
      data: { student_id: studentId },
      dataType: "json",
      success: function (data) {
        // Clear existing content
        $("#editPackageForm").empty();

        if (data.success) {
          var missedTimeOuts = data.data;

          // Populate the modal with time-out inputs
          missedTimeOuts.forEach(function (entry) {
            $("#editPackageForm").append(`
                  <div class="form-group">
                    <label for="time_${entry.missing_date}">${entry.missing_date}:</label>
                    <input type="text" class="form-control" value="${entry.missing_date}" disabled>
                    <input type="time" class="form-control mt-2" id="time_${entry.missing_date}" name="time_${entry.missing_date}" data-longitude="${entry.longitude}" data-latitude="${entry.latitude}">
                  </div>
                `);
          });
        } else {
          $("#editPackageForm").append("<p>Error fetching time-out data.</p>");
        }
      },
      error: function (xhr, status, error) {
        // Handle AJAX errors
        console.error(xhr.responseText);
        $("#editPackageForm").append("<p>Error fetching time-out data.</p>");
      },
    });
  }

  $(".edit-btn").click(function () {
    var studentId = $(this).data("student-id");
    var adjustmentId = $(this).data("adjustment-id"); // Get the adjustment-id from the button

    // Set data attributes for save button
    $("#saveChangesBtn").data("student-id", studentId);
    $("#saveChangesBtn").data("adjustment-id", adjustmentId); // Set the adjustment-id for the save button

    // Fetch missed time-outs when the modal is shown
    fetchMissedTimeOuts(studentId);
  });

  $("#saveChangesBtn").click(function () {
    var studentId = $(this).data("student-id"); // Retrieve student ID from the button's data attribute
    var adjustmentId = $(this).data("adjustment-id"); // Retrieve adjustment ID
    var adjustments = {};

    // Collect all time adjustments from the form
    $("#editPackageForm .form-group").each(function () {
      var date = $(this).find('input[type="text"]').val();
      var time = $(this).find('input[type="time"]').val();
      var longitude = $(this).find('input[type="time"]').data("longitude");
      var latitude = $(this).find('input[type="time"]').data("latitude");
      if (time) {
        adjustments[date] = {
          time: time,
          longitude: longitude,
          latitude: latitude,
        };
      }
    });


    $.ajax({
      url: "functions/save-logs.php",
      method: "POST",
      data: {
        student_id: studentId,
        adjustments: adjustments,
        adjustment_id: adjustmentId, // Pass the adjustment_id if required
      },
      dataType: "json",
      success: function (data) {
        if (data.msg) {
          $("#editMessage").html(
            '<div class="alert alert-danger" role="alert">' +
              data.msg +
              "</div>"
          );
        } else if (data.success) {
          Toastify({
            text: data.message, // Use the message from the server
            backgroundColor: "rgba(31, 166, 49, 0.8)",
          }).showToast();

          setTimeout(function () {
            window.location.reload();
          }, 1000);
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
        $("#editMessage").html(
          '<div class="alert alert-danger" role="alert">' +
            "An error occurred while saving adjustments. Please try again." +
            "</div>"
        );
      },
    });
  });
});
