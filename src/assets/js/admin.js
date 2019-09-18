( function( $ ) {

    $( document ).on( 'change', '#edd_show_active_vs_purchased_licenses_download', function() {

        $( '#edd-show-active-vs-purchased-licenses-results' ).contents().remove();

        if ( $( this ).val() == '' ) return;

        $.ajax( {
            type: 'POST',
            url: eddShowActiveVsPurchasedLicenses.ajaxURL,
            data: {
                action: 'edd_show_active_vs_purchased_licenses_get_data',
                nonce: $( '#edd-show-active-vs-purchased-licenses-nonce' ).val(),
                download_id: $( this ).val(),
            },
            success: function( response ) {

                $( '#edd-show-active-vs-purchased-licenses-results' ).append( response.data.response );

            },
            error: function( request, status, error ) {

                console.error( error );

            }
        } );

    } );

} )( jQuery );