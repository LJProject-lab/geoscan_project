$(document).ready(function () {
    $("#confirmAdjustment").click(function () {
        var reason = $("#reason").val();
        var dates = $("#dates").val(); // Fetch the hidden dates
        var student_id = studentId; // Use the global JS variable for student ID

        if (reason != "") {
            $.ajax({
                url: "functions/send-adjustments.php",
                method: "POST",
                data: {
                    reason: reason,
                    dates: dates,
                    student_id: student_id // Send the student ID
                },
                dataType: "json",
                success: function (data) {
                    if (data.msg) {
                        $("#message").html(
                            '<div class="alert alert-danger" role="alert">' + data.msg + "</div>"
                        );
                    } else if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.success,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "./";
                            }
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                },
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Kindly provide a reason.',
                confirmButtonText: 'OK'
            });
        }
    });
});
