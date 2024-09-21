$(document).ready(function () {

    $("#RejectModal").on("show.bs.modal", function (event) {
        var button = $(event.relatedTarget);
        var studentId = button.data("student_id");
        var id = button.data("id");
  
        var modal = $(this);
        modal.find("#reject_student_id").val(studentId);
        modal.find("#reject_adjustment_id").val(id);
    });
  
    // Handle rejection form submission
    $("#rejectForm").on("submit", function (e) {
        e.preventDefault();
  
        var formData = {
            student_id: $("#reject_student_id").val(),
            adjustment_id: $("#reject_adjustment_id").val(),
            reject_reason: $("#reject-reason").val()
        };
  
        $.ajax({
            url: "functions/reject-adjustments.php", // PHP script for handling rejection
            type: "POST",
            data: formData,
            success: function (response) {
                $("#RejectModal").modal("hide");
                location.reload(); // Reload to reflect changes
            },
            error: function (xhr, status, error) {
                console.log("Status: " + status);
                console.log("Error: " + error);
                console.log("Response: " + xhr.responseText);
                alert("Failed to reject adjustment request. Please try again.");
            }
        });
      });
});