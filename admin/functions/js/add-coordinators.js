$(document).ready(function () {
    $("#AddCoordinator").click(function () {
      var department_id = $("#department_id").val();
      var username = $("#username").val();
      var firstname = $("#firstname").val();
      var lastname = $("#lastname").val();
      var email = $("#email").val();
      var password = $("#password").val();
  
      var coordinator_id = coordinator_id;
  
      // Make sure all fields are filled
      if (username != "" && firstname != "" && lastname != "" && email != "" && password != "" && department_id != "") {
        $.ajax({
          url: "functions/add-coordinators.php",
          method: "POST",
          data: {
            department_id: department_id,
            username: username,
            firstname: firstname,
            lastname: lastname,
            email: email,
            coordinator_id: coordinator_id,
            password: password
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
                window.location.href = "coordinators.php";
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
  