jQuery('body').on( 'click', '.btn-fb-login', event => {

    jQuery.ajax({
        method     : 'GET',
        cache      : false,
        data       : { action : 'get_facebook_login_url' },
        url        : my_ajax_object.ajax_url,
        beforeSend :  xhr => {
            jQuery('.facebook-login-loader', event.currentTarget).show();
        },
        success    :  ( result, status, xhr ) => {
            const response = JSON.parse(result);
            if ( response.status === 'success' ) {
                window.open( response.login_url, 'Authenticate', 'width=800, height=800' );
            } else {
                console.warn( response.message );
            }
        },
        error : ( xhr, status, error ) => {
            console.warn(xhr);
            console.warn(status);
            console.warn(error);
        },
        complete : ( xhr, status ) => {
            jQuery('.facebook-login-loader', event.currentTarget).hide();
        }
    });
});