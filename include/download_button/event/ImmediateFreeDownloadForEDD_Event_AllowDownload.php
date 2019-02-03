<?php
/**
 * Immediate Free Download for Easy Digital Downloads
 *
 * [PROGRAM_URI]
 * Copyright (c) <COPYRIGHT_YEARS> Michael Uno
 *
 */

/**
 * Displays the free download button.
 *
 * @since       0.0.1
 */
class ImmediateFreeDownloadForEDD_Event_AllowDownload {

    /**
     * Allows download files if it is requested from the plugin download link.
     */
    public function __construct() {

        if ( ! isset( $_GET[ 'immediate' ], $_GET[ 'nonce' ], $_GET[ 'id' ], $_GET[ 'call_id' ] ) ) {
            return;
        }
        if ( ! wp_verify_nonce( $_GET[ 'nonce' ], 'edd-immediate-download-' . $_GET[ 'id' ] . $_GET[ 'call_id' ] ) ) {
             exit();
        }
        add_filter( 'edd_file_download_has_access', '__return_true' );

    }

}