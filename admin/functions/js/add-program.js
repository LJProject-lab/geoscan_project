$(document).ready(function () {
    $("#AddProgram").click(function (e) {
      e.preventDefault();
      var department_id = $("#department_id").val();
      var program_name = $("#program_name").val();
      var program_hour = $("#program_hour").val();
  
      // Make sure all fields are filled
      if (department_id != "" && program_name != "" && program_hour != ""  ) {
        $.ajax({
          url: "functions/add-program.php",
          method: "POST",
          data: {
            department_id:department_id,
            program_name: program_name,
            program_hour: program_hour
          },
          dataType: "json",
          success: function (data) {
            if (data.msg) {
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
                window.location.href = "program.php";
              }, 1000);
            }
          },
          error: function (xhr, status, error) {
            console.error(xhr.responseText);
          },
        });
      } else {
        $("#message").html(
          '<div class="alert alert-danger" role="alert">All fields are required</div>'
        );
      }
    });
  });
  