$(document).ready(function () {
    // Function to fetch program details
    function fetchDepartmentDetails(departmentIdToUpdate) {
      // Retrieve department_code details via AJAX
      $.ajax({
        url: "functions/get-department-details.php",
        method: "POST",
        data: { department_id: departmentIdToUpdate },
        dataType: "json",
        success: function (data) {
          // Populate edit modal with department_code details
          $("#edit_department_name").val(data.department_name);
          $("#edit_department_code").val(data.department_code);
        },
        error: function (xhr, status, error) {
          // Handle AJAX errors
          console.error(xhr.responseText);
        },
      });
    }
  
    // Edit button click event
    $(".edit-btn").click(function () {
      // Retrieve department_code_id from the edit button's data attribute
      departmentIdToUpdate = $(this).data("department-id");
  
      fetchDepartmentDetails(departmentIdToUpdate);
    });
  
    // Save changes button click event
    $("#saveChangesBtn").click(function () {
      var departmentId = departmentIdToUpdate;
      var departmentName = $("#edit_department_name").val();
      var departmentCode = $("#edit_department_code").val();
  
      if (departmentId != "" && departmentName != "" && departmentCode != "") {
        // AJAX call to update department_code
        $.ajax({
          url: "functions/update-department.php",
          method: "POST",
          data: {
            department_id: departmentId,
            department_name: departmentName,
            department_code: departmentCode,
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
                window.location.href = "department.php";
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
  