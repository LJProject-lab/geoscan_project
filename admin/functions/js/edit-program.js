$(document).ready(function () {
  // Function to fetch program details
  function fetchProgramDetails(programIdToUpdate) {
    // Retrieve program details via AJAX
    $.ajax({
      url: "functions/get-program-details.php",
      method: "POST",
      data: { program_id: programIdToUpdate },
      dataType: "json",
      success: function (data) {
        // Populate edit modal with program details
        $("#edit_program_name").val(data.program_name);
        $("#edit_program_hour").val(data.program_hour);
      },
      error: function (xhr, status, error) {
        // Handle AJAX errors
        console.error(xhr.responseText);
      },
    });
  }

  // Edit button click event
  $(".edit-btn").click(function () {
    // Retrieve program_id from the edit button's data attribute
    programIdToUpdate = $(this).data("program-id");

    fetchProgramDetails(programIdToUpdate);
  });

  // Save changes button click event
  $("#saveChangesBtn").click(function () {
    var programId = programIdToUpdate;
    var programName = $("#edit_program_name").val();
    var programHour = $("#edit_program_hour").val();

    if (programId != "" && programName != "" && programHour != "") {
      // AJAX call to update program
      $.ajax({
        url: "functions/update-program.php",
        method: "POST",
        data: {
          program_id: programId,
          program_name: programName,
          program_hour: programHour,
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
              window.location.href = "program.php";
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
