<?php
/**
 * Immediate Free Download for Easy Digital Downloads
 *
 * https://github.com/michaeluno/immediate-free-download-for-easy-digital-downloads
 * Copyright (c) 2019 Michael Uno
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
         * @callback    action  init
         */
        public function processDownload() {

            // Required information
            $iDownloadID   = $_GET[ 'id' ];
            $_aFiles       = edd_get_download_files( $iDownloadID );
            $_iFileIndex   = 0;
            foreach( $_aFiles as $_iIndex => $_aFile ) {
                 $_iFileIndex = $_iIndex ;
            }

            // Issue a payment

            /// Disable administrator notifications
            add_filter( 'edd_admin_notices_disabled', '__return_true' );
            remove_action( 'edd_complete_purchase', 'edd_trigger_purchase_receipt', 999 );
            remove_action( 'edd_admin_sale_notice', 'edd_admin_email_notice', 10 );

            /// Add a payment record
            $_iPayment     = $this->___addPayment( $iDownloadID );
            if ( ! $_iPayment ) {
                exit;
            }

            // Issue a download url
            $_sDownloadURL = edd_get_download_file_url(
                edd_get_payment_key( $_iPayment ),  // payment key
                edd_get_payment_user_email( $_iPayment ),  // email - not sure if it can be empty
                $_iFileIndex,   // file key
                $iDownloadID,
                false   // price id
            );

            // Process download
            exit( wp_redirect( $_sDownloadURL ) );

        }
            /**
             * @param $iDownloadID
             *
             * @return int
             */
            private function ___addPayment( $iDownloadID ) {

                $_oEDDDownload = new EDD_Download( $iDownloadID );
                $_iWPUserID    = get_current_user_id();
                $_oWPUser      = get_userdata( $_iWPUserID );
                $_sEmail       = false === $_oWPUser
                    ? null
                    : $_oWPUser->user_email;
                $_aPaymentData = array(
               		'price' 		=> 0,
               		'user_email' 	=> $_sEmail,
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
               		    'id'            => get_current_user_id(),
               		    'email'         => $_sEmail,
               		    'first_name'    => null,
               		    'last_name'     => null,
               		    'discount'      => null,
               		    'address'       => null,
                    ),
               		'cart_details' 	=> array(
               		    array(
               		        'name' => $_oEDDDownload->get_name(),
                            'id'    => $iDownloadID,
                            'item_number' => array(
                                'id'    => $iDownloadID,
                                'options' => array(),
                                'quantity' => 1,
                            ),
                            'item_price' => 0,
                            'quantity'   => 1,
                            'discount'   => 0,
                            'subtotal'   => 0,
                            'tax'        => 0,
                            'fees'       => array(),
                            'price'      => 0,
                        ),
                    ),
               		'status' 		=> 'publish'
               	);
               	return ( integer ) edd_insert_payment( $_aPaymentData );

            }


}