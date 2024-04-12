$(document).ready(function() {
    var isFormSubmitting = false;

    $('#createBallForm').submit(function(e) {
        e.preventDefault();

        // If form is already being submitted, ignore
        if (isFormSubmitting) {
            return;
        }

        // Set flag to true to indicate form submission is in progress
        isFormSubmitting = true;

        // Disable the submit button to prevent multiple submissions
        $('#createBallForm button[type="submit"]').prop('disabled', true);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                console.log(response.ball_name , response.ball_size);
                if (response && response.ball_name && response.ball_size) {
                    var circleBallHtml = `
                        <div class="circle-ball">
                            <p class="card-text">Ball: ${response.ball_name} cubic inches</p>
                            <p class="card-text">Size: ${response.ball_size}</p>
                        </div>`;
                
                    $('#circleBallContainer').html(circleBallHtml);
                   
                        $('#ballFields').append(response.ball_names_html);

                    $('form')[0].reset();
                } else {
                    console.error('Invalid response:', response);
                    alert('Failed to create ball. Invalid response received.');
                }
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(error);
                alert('Failed to create ball. Please try again.');
            },
            complete: function() {
                // Reset the flag and re-enable the submit button after AJAX request is complete
                isFormSubmitting = false;
                $('#createBallForm button[type="submit"]').prop('disabled', false);
            }
        });
    });
});
