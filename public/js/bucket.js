// Assume you have jQuery included in your project
$(document).ready(function() {
    // Handle form submission
    $('form').submit(function(e) {
        e.preventDefault(); // Prevent default form submission
        
        // Perform AJAX request to submit form data
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                // Build HTML for the new bucket
                var newBucketHtml = `
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">${response.bucket_name}</h5>
                                <p class="card-text">Capacity: ${response.capacity} cubic inches</p>
                                <p class="card-text">Balls: ${response.ball_count}</p>
                            </div>
                        </div>
                    </div>
                `;
                
                // Append the new bucket HTML to the container
                $('#bucketContainer').html(newBucketHtml);
                
                // Clear form fields
                $('form')[0].reset();
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(error);
            }
        });
    });
});
