$(document).ready(function () {
    $("#AddCourses").click(function () {
      var course_name = $("#course_name").val();
  
      var coordinator_id = coordinator_id;
  
      // Make sure all fields are filled
      if (course_name != "" ) {
        $.ajax({
          url: "functions/add-course.php",
          method: "POST",
          data: {
            course_name: course_name
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
                window.location.href = "course.php";
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
  