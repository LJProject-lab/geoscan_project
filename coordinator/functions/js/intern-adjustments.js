$(document).ready(function () {
  $("#ReqModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var studentId = button.data("student_id"); // Extract info from data-* attributes
    var records = button.data("records");
    var reason = button.data("reason");
    var id = button.data("id"); // New id attribute

    // Update the modal's content.
    var modal = $(this);
    modal.find("#student_id").val(studentId);
    modal.find("#adjustment_id").val(id); 
    modal.find("#intern-reason").text(reason);
    modal.find("#missing-dates").text(records);
  });

  $("#approveForm").on("submit", function (e) {
    e.preventDefault(); // Prevent the default form submission

    var formData = $(this).serialize(); // Serialize form data

    $.ajax({
      url: "functions/process_adjustments.php", // URL to your PHP script
      type: "POST",
      data: formData,
      success: function (response) {
        // Handle the success response here
        $("#ReqModal").modal("hide"); // Hide the modal
          location.reload(); // Reload the page to reflect changes
      },
      error: function (xhr, status, error) {
        // Log additional details about the error
        console.log("Status: " + status);
        console.log("Error: " + error);
        console.log("Response: " + xhr.responseText);
        alert("Failed to update adjustment request. Please try again.");
      },
    });
  });
});
