jQuery(document).ready(function($){
    $('#grant-search-form').on('submit', function(e) {
        e.preventDefault();
        var searchQuery = $('#grant-search-input').val();

        $.ajax({
            url: grants_ajax_obj.ajax_url,
            method: 'POST',
            data: {
                action: 'search_grants',
                query: searchQuery
            },
            beforeSend: function() {
                // Optional: show a loading indicator
                $('#grant-results').html('<p>Loading...</p>');
            },
            success: function(response) {
                // Replace the results container with the new HTML.
                $('#grant-results').html(response);
            },
            error: function(err) {
                $('#grant-results').html('<p>An error occurred.</p>');
                console.log('Error:', err);
            }
        });
    });
});
