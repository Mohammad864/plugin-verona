(function($) {
    $(document).ready(function() {
        console.log("Book Info Plugin admin JS loaded.");
    });
})(jQuery);

jQuery(document).ready(function($) {
    $('#isbn_meta_box input[name="isbn"]').on('change', function() {
        var post_id = $('#post_ID').val();
        var isbn = $(this).val();
        var nonce = BooksInfoAjax.nonce;

        $.ajax({
            url: BooksInfoAjax.ajax_url,
            method: 'POST',
            data: {
                action: 'save_isbn',
                post_id: post_id,
                isbn: isbn,
                nonce: nonce
            },
            success: function(response) {
                if (response.success) {
                    alert('ISBN saved successfully.');
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('An error occurred while saving the ISBN.');
            }
        });
    });
});
