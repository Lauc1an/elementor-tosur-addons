// Get Submit on Covid Form
jQuery( function( $ ) {
   
    $('.tosur-form-brevete').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        let formData = new FormData(this);
        
        $.ajax({
            url: wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'add_to_cart' ),
            data: formData,
            type: 'POST',
            processData: false,
            contentType: false,
            success: function( response ) {
                if ( !response ) {
                    return;
                }

                if ( response.error && response.product_url ) {
                    console.error('Error', response.error);
                    console.error('product_url', response.product_url);
                    return;
                }

                // Redirect to cart option
                if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {
                    window.location.href = "checkout/?"+ form.serialize();
                    return;
                }

                console.log('added_to_cart > cart_hash', response.cart_hash);
            },
            async: false
        });
    });

});
