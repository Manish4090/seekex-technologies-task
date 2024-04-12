$(document).ready(function() {
    $('#bucketSuggestionsForm').submit(function(e) {
        e.preventDefault(); // Prevent the default form submission
        
        var formData = $(this).serialize(); // Serialize form data
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                // Handle success response
                console.log(response);
                // Here you can update the UI or perform further actions based on the response
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(error);
                // For example, you can display an error message
                alert('Failed to get bucket suggestions. Please try again.');
            }
        });
    });
});
