$(document).ready(function() {
    var isFormSubmitting = false;
    $('#createBallForm').submit(function(e) {
        e.preventDefault();
        if (isFormSubmitting) {
            return;
        }
        isFormSubmitting = true;
        $('#createBallForm button[type="submit"]').prop('disabled', true);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                console.log(response);
                if (response.error) {
                    toastr.error(response.error);
                    $('#createBall').text(response.error).prop('disabled', true);
                } else {
                    toastr.success(response.success);
                    var ballName = response.ball_name.charAt(0).toUpperCase() + response.ball_name.slice(1);
                    var circleBallHtml = '<div class="col-md-3 mb-4"><div class="circle shadow d-flex flex-column align-items-center justify-content-center"><p>' + ballName + '</p><p>Size: ' + response.ball_size + '</p> </div></div>';
                    $('#circleBallContainer').append(circleBallHtml);
                    $('#ballFields').html(response.ball_names_html);
                    
                    
                    if (response.ball_count >= 4) {
                        $('#createBall').text('You can only create 4 balls.').prop('disabled', true);
                    } else {
                        $('#createBall').text('Create Ball').prop('disabled', false);
                    }
                }
                $('#createBallForm')[0].reset();

                if (response.ball_count > 0 && response.bucket_count > 0) {
                    $('#bucketSuggestionsWithBall').text('Get Bucket Suggestions').prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                alert('Failed to create ball. Please try again.');
                $('#circleBallContainer').html('<p>Error creating ball</p>');
            },
            complete: function() {
                isFormSubmitting = false;
                $('#createBallForm button[type="submit"]').prop('disabled', false);
            }
        });
    });
});

