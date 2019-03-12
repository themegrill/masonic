jQuery(document).ready(function () {
    var hideSearchForm = function() {
        jQuery( '#masthead .masonic-search-toggle' ).hide( 'slow' );
    };
    jQuery('.sb-search').click(function () {
        jQuery('.masonic-search-toggle').slideToggle('slow');

        // focus after some time to fix conflict with toggle classs
        setTimeout( function (  ) {
            jQuery( '.masonic-search-toggle .masonic-search input' ).focus();

        }, 200 );

        // Hide search box on esc.
        jQuery( document ).on( 'keyup', function (e) {
            if ( 27 === e.keyCode ){

                if (  jQuery( '#masthead .masonic-search-toggle' ) .has( '.masonic-search')  ) {
                    hideSearchForm();
                }
            }
        } );

        jQuery( document ).on( 'click', function(e) {
            var container = jQuery( '#masthead .masonic-search-toggle, .searchform, .sb-search i, .masonic-search-toggle .masonic-search input' );
            if ( ! container.is( e.target ) ) {
                hideSearchForm();
            }
        } );
    });
});
jQuery(document).ready(function () {
    jQuery('.sb-search-res').click(function () {
        jQuery('.masonic-search-toggle').slideToggle('slow');
    });
});
