$(document).ready(function () {
    $("#AddDepartment").click(function (e) {
      e.preventDefault();
      var department_name = $("#department_name").val();
      var department_code = $("#department_code").val();
  
  
      // Make sure all fields are filled
      if (department_name != "" && department_code != "" ) {
        $.ajax({
          url: "functions/add-department.php",
          method: "POST",
          data: {
            department_name: department_name,
            department_code: department_code
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
                window.location.href = "department.php";
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
  