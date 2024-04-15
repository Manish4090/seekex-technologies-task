$(document).ready(function() {
    //$('#releaseSpacesAllBuckets').removeClass('d-block').addClass('d-none');
    $('#bucketSuggestionsForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                toastr.success(response.message);
                $('#bucketSuggestionsForm')[0].reset();
                $('#bucketsArea').html(response.finalBacketHtml);
                $('#backetsBallResult').html(response.backetsBallResult);
                if(response.empty_space == true){
                    $('#releaseSpacesAllBuckets').removeClass('d-none').addClass('d-block');
                    $('#bucketSuggestionsForm').removeClass('d-block').addClass('d-none');
                }else{
                    $('#releaseSpacesAllBuckets').removeClass('d-block').addClass('d-none');
                    $('#bucketSuggestionsForm').removeClass('d-none').addClass('d-block');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
    $('#releaseSpacesAllBuckets').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).data('action'), 
            type: 'POST',
            success: function(response) {
                toastr.success(response.message);
                if(response.empty_space == true){
                    $('#releaseSpacesAllBuckets').removeClass('d-none').addClass('d-block');
                    $('#bucketSuggestionsForm').removeClass('d-block').addClass('d-none');
                }else{
                    $('#releaseSpacesAllBuckets').removeClass('d-block').addClass('d-none');
                    $('#bucketSuggestionsForm').removeClass('d-none').addClass('d-block');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
