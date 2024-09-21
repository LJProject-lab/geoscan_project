$(document).ready(function () {
    // Function to fetch company details
    function fetchCompanyDetails(companyIdToUpdate) {
      // Retrieve company details via AJAX
      $.ajax({
        url: "functions/get-company-details.php",
        method: "POST",
        data: { company_id: companyIdToUpdate },
        dataType: "json",
        success: function (data) {
          // Populate edit modal with company details
          $("#edit_company_name").val(data.company_name);
        },
        error: function (xhr, status, error) {
          // Handle AJAX errors
          console.error(xhr.responseText);
        },
      });
    }
  
    // Edit button click event
    $(".edit-btn").click(function () {
      // Retrieve company_id from the edit button's data attribute
      companyIdToUpdate = $(this).data("company-id");
  
      fetchCompanyDetails(companyIdToUpdate);
    });
  
    // Save changes button click event
    $("#saveChangesBtn").click(function () {
      var companyId = companyIdToUpdate;
      var companyName = $("#edit_company_name").val();
  
      if (companyId != "" && companyName != "" ) {
        // AJAX call to update company
        $.ajax({
          url: "functions/update-company.php",
          method: "POST",
          data: {
            company_id: companyId,
            company_name: companyName
          },
          dataType: "json",
          success: function (data) {
            // Handle success response
            if (data.msg) {
              // Display the error message in the #editMessage div
              $("#editMessage").html(
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
      } else {
        // Display a danger alert if any field is empty
        $("#editMessage").html(
          '<div class="alert alert-danger" role="alert">All fields are required</div>'
        );
      }
    });
  });
  