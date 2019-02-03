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
class ImmediateFreeDownloadForEDD_DownloadButton  {

    public function __construct() {
        add_filter( 'edd_purchase_download_form', array( $this, 'getOutput' ), 100, 2 );
    }

    /**
     * @param   string $sPurchaseForm
     * @param   array  $aArguments
     */
    public function getOutput( $sPurchaseForm, $aArguments ) {

        $_oEDDDownload = new EDD_Download( $aArguments[ 'download_id' ] );
        if ( ! $_oEDDDownload->is_free() ) {
            return $sPurchaseForm;
        }
        $_sCallID = uniqid();
        $_sURL    = add_query_arg(
            array(
                'nonce'   => wp_create_nonce( 'edd-immediate-download-' . $_oEDDDownload->ID . $_sCallID ),
                'action'  => 'download',
                'id'      => $_oEDDDownload->ID,
                'call_id' => $_sCallID,
            ),
            home_url( 'index.php' )
        );

        $_sButtonLabel = edd_get_option( 'free_checkout_label', '' );
        $_sButtonLabel = ! empty( $_sButtonLabel )
            ? $_sButtonLabel
            : __( 'Free Download', 'easy-digital-downloads' );
        $_sClass       = implode( ' ', array( $aArguments[ 'style' ], $aArguments[ 'color' ], trim( $aArguments[ 'class' ] ) ) );
        return '<a href="' . esc_url( $_sURL ) . '" class="edd_go_to_checkout ' . esc_attr( $_sClass ) . '">'
                   . $_sButtonLabel
               . '</a>';

    }

}