$(document).ready(function () {
    // Delete button click event
    $(".btn-get-del").click(function () {
      var company_id = $(this).data("company-id");
  
      // Set the company id for deletion
      $("#confirmDeleteBtn").data("company-id", company_id);
  
      // Show delete confirmation modal
      $("#deleteConfirmationModal").modal("show");
    });
  
    // Confirm delete button click event
    $("#confirmDeleteBtn").click(function () {
      var company_id = $(this).data("company-id");
  
      // AJAX call to delete company
      $.ajax({
        url: "functions/delete-company.php",
        method: "POST",
        data: { company_id: company_id },
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
              window.location.href = "companies.php";
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
  