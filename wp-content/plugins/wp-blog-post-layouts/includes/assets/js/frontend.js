/**
 * Handles the events in the public facing window.
 */
jQuery(document).ready( function($) {

    "use strict";
    /**
     * Masonry layout for block.
     */
    $( '.cv-block' ).each( function() {
        var Pid = $(this).attr('id');
        if( Pid === 'cv-masonry-post-layout' ) {
            $(this).find( '.cv-post-wrapper' ).masonry();
        }
    });
});