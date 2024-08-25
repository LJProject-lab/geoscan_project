$(document).ready(function () {
    // Delete button click event
    $(".btn-get-del").click(function () {
      var coordinator_id = $(this).data("coordinator-id");
  
      // Set the coordinator id for deletion
      $("#confirmDeleteBtn").data("coordinator-id", coordinator_id);
  
      // Show delete confirmation modal
      $("#deleteConfirmationModal").modal("show");
    });
  
    // Confirm delete button click event
    $("#confirmDeleteBtn").click(function () {
      var coordinator_id = $(this).data("coordinator-id");
  
      // AJAX call to delete coordinator
      $.ajax({
        url: "functions/delete-coordinators.php",
        method: "POST",
        data: { coordinator_id: coordinator_id },
        dataType: "json",
        success: function (data) {
          // Handle success response
          if (data.msg) {
            // Display the message in the #message div
            $("#message").html(
              '<div class="alert alert-danger" role="alert">' +
                data.msg +
                "</div>"
            );
          } else if (data.success) {
            Toastify({
              text: data.success,
              backgroundColor: "rgba(31, 166, 49, 0.8)",
            }).showToast();
  
            setTimeout(function () {
              window.location.href = "coordinators.php";
            }, 1000);
          }
        },
        error: function (xhr, status, error) {
          // Handle AJAX errors
          console.error(xhr.responseText);
        },
      });
  
      // Hide delete confirmation modal
      $("#deleteConfirmationModal").modal("hide");
    });
  });
  