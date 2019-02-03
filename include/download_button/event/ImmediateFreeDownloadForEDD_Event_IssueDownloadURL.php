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
class ImmediateFreeDownloadForEDD_Event_IssueDownloadURL {

    /**
     * Checks if a download is requested,
     */
    public function __construct() {

        if ( isset( $_GET[ 'immediate' ] ) ) {
            return;
        }
        if ( ! isset( $_GET[ 'action' ], $_GET[ 'nonce' ], $_GET[ 'id' ], $_GET[ 'call_id' ] ) ) {
            return;
        }
        if ( 'download' !== $_GET[ 'action' ] ) {
            return;
        }
        if ( ! wp_verify_nonce( $_GET[ 'nonce' ], 'edd-immediate-download-' . $_GET[ 'id' ] . $_GET[ 'call_id' ] ) ) {
             exit();
        }
        add_action( 'init', array( $this, 'processDownload' ) );

    }

        /**
         * @param $iDownloadID
         */
        public function processDownload() {

            $iDownloadID   = $_GET[ 'id' ];
            $_aFiles       = edd_get_download_files( $iDownloadID );
            $_iFileIndex   = 0;
            foreach( $_aFiles as $_iIndex => $_aFile ) {
                 $_iFileIndex = $_iIndex ;
            }
            add_filter( 'edd_admin_notices_disabled', '__return_true' );
            remove_action( 'edd_complete_purchase', 'edd_trigger_purchase_receipt', 999 );
            remove_action( 'edd_admin_sale_notice', 'edd_admin_email_notice', 10 );
            $_iPayment     = $this->___addPayment( $iDownloadID );
            if ( ! $_iPayment ) {
                exit;
            }
            $_oEDDDownload = new EDD_Download( $iDownloadID );
            $_oEDDDownload->increase_sales( 1 );

            $_sDownloadURL = edd_get_download_file_url(
                edd_get_payment_key( $_iPayment ),  // payment key
                edd_get_payment_user_email( $_iPayment ),             // email - not sure if it can be empty
                $_iFileIndex,   // file key
                $iDownloadID,
                false   // price id
            );
            $_sDownloadURL = add_query_arg(
                array(
                    'immediate' => true,
                ) + $_GET,
                $_sDownloadURL
            );

            wp_redirect( $_sDownloadURL );
            exit;

        }

            private function ___addPayment( $iDownloadID ) {

                $_aPaymentData = array(
               		'price' 		=> 0,
               		'user_email' 	=> null,
               		'purchase_key' 	=> strtolower( md5( uniqid() ) ),
               		'currency' 		=> edd_get_currency(),
               		'downloads' 	=> array(
               		    0 => array(
               		        'id'        => $iDownloadID,
                            'options'   => array(),
                            'quantity'  => 1,
                        )
                    ),
               		'user_info' 	=> array(
               		    'id'            => null,
               		    'email'         => null,
               		    'first_name'    => null,
               		    'last_name'     => null,
               		    'discount'      => null,
               		    'address'       => null,
                    ),
               		'cart_details' 	=> array(),
               		'status' 		=> 'publish'
               	);
               	return ( integer ) edd_insert_payment( $_aPaymentData );

            }


}