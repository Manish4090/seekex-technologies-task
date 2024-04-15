$(document).ready(function() {
    $('#create-bucket-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {

                if (response.bucket_count >= 3) {
                    $('#createBucket').text('You can only create 3 buckets').prop('disabled', true);
                    $('#create-bucket-form')[0].reset();
                } else {
                   
                    $('#createBucket').text('Create Ball').prop('disabled', false);
                   
                    if(response.ball_count > 0 && response.bucket_count > 0){
                        $('#bucketSuggestionsWithBall').text('Get Bucket Suggestions').prop('disabled', false);
                    }
                    
                }
                 toastr.success(response.success);
                 var newBucketHtml = `
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">${response.bucket_name}</h5>
                                    <p class="card-text">Capacity: ${response.capacity} cubic inches</p>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#bucketsArea').append(newBucketHtml);
                    
                    $('#create-bucket-form')[0].reset();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
