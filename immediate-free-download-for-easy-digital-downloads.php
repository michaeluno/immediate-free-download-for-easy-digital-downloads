<?php
/**
 * Plugin Name:    Immediate Free Download for Easy Digital Downloads
 * Plugin URI:     [PROGRAM_URI]
 * Description:    Allows your site visitors to download free files right away without making them go to the checkout page.
 * Author:         Michael Uno
 * Author URI:     http://en.michaeluno.jp
 * Version:        1.0.0
 * Text Domain:   
 * Domain Path:    language
 */

/**
 * Provides the basic information about the plugin.
 * 
 * @since    0.0.1       
 */
class ImmediateFreeDownloadForEDD_Registry_Base {
 
    const VERSION        = '1.0.0';    // <--- DON'T FORGET TO CHANGE THIS AS WELL!!
    const NAME           = 'Immediate Free Download for Easy Digital Downloads';
    const DESCRIPTION    = 'Allows your site visitors to download free files right away without making them go to the checkout page.';
    const URI            = '[PROGRAM_URI]';
    const AUTHOR         = 'Michael Uno';
    const AUTHOR_URI     = 'http://en.michaeluno.jp';
    const PLUGIN_URI     = '[PROGRAM_URI]';
    const COPYRIGHT      = 'Copyright (c) <COPYRIGHT_YEARS>, Michael Uno';
    const LICENSE        = '<COPYRIGHT_TYPE>';
    const CONTRIBUTORS   = '';
 
}

/**
 * Provides the common data shared among plugin files.
 * 
 * To use the class, first call the setUp() method, which sets up the necessary properties.
 * 
 * @package     Immediate Free Download for Easy Digital Downloads
 * @since       0.0.1
*/
final class ImmediateFreeDownloadForEDD_Registry extends ImmediateFreeDownloadForEDD_Registry_Base {

    /**
     * 
     * @since       0.0.1
     */
    static public $sFilePath = __FILE__;
    
    /**
     * 
     * @since       0.0.1
     */    
    static public $sDirPath;    


    /**
     * Sets up class properties.
     * @return      void
     */
    static function setUp() {
        self::$sDirPath  = dirname( self::$sFilePath );  
    }    
    
    /**
     * @return      string
     */
    public static function getPluginURL( $sPath='', $bAbsolute=false ) {
        $_sRelativePath = $bAbsolute
            ? str_replace('\\', '/', str_replace( self::$sDirPath, '', $sPath ) )
            : $sPath;
        if ( isset( self::$_sPluginURLCache ) ) {
            return self::$_sPluginURLCache . $_sRelativePath;
        }
        self::$_sPluginURLCache = trailingslashit( plugins_url( '', self::$sFilePath ) );
        return self::$_sPluginURLCache . $_sRelativePath;
    }
        /**
         * @since    0.0.1
         */
        static private $_sPluginURLCache;

    /**
     * Requirements.
     * @since    0.0.1
     */    
    static public $aRequirements = array(
        'php' => array(
            'version'   => '5.2.4',
            'error'     => 'The plugin requires the PHP version %1$s or higher.',
        ),
        'wordpress'         => array(
            'version'   => '3.4',
            'error'     => 'The plugin requires the WordPress version %1$s or higher.',
        ),
        // 'mysql'             => array(
            // 'version'   => '5.0.3', // uses VARCHAR(2083) 
            // 'error'     => 'The plugin requires the MySQL version %1$s or higher.',
        // ),
        'functions'     => '', // disabled
        // array(
            // e.g. 'mblang' => 'The plugin requires the mbstring extension.',
        // ),
         'classes'       => array(
             'EDD_Download' => 'The plugin requires Easy Digital Downloads v2.2 or above.',
         ),
        'constants'     => '', // disabled
        // array(
            // e.g. 'THEADDONFILE' => 'The plugin requires the ... addon to be installed.',
            // e.g. 'APSPATH' => 'The script cannot be loaded directly.',
        // ),
        'files'         => '', // disabled
        // array(
            // e.g. 'home/my_user_name/my_dir/scripts/my_scripts.php' => 'The required script could not be found.',
        // ),
    );

    static public function setAdminNotice( $sMessage, $sType ) {
        self::$aAdminNotices[] = array( 'message' => $sMessage, 'type' => $sType );
        add_action( 'admin_notices', array( __CLASS__, 'replyToShowAdminNotices' ) );
    }
        static public $aAdminNotices = array();
        static public function replyToShowAdminNotices() {
            foreach( self::$aAdminNotices as $_aNotice ) {
                $_sType = esc_attr( $_aNotice[ 'type' ] );
                echo "<div class='{$_sType}'>"
                     . "<p>" . $_aNotice[ 'message' ] . "</p>"
                     . "</div>";
            }
        }

    static public function registerClasses( array $aClasses ) {
        self::$___aAutoLoadClasses = $aClasses + self::$___aAutoLoadClasses;
        spl_autoload_register( array( __CLASS__, 'replyToLoadClass' ) );
    }
        static private $___aAutoLoadClasses = array();
        static public function replyToLoadClass( $sCalledUnknownClassName ) {
            if ( ! isset( self::$___aAutoLoadClasses[ $sCalledUnknownClassName ] ) ) {
                return;
            }
            include( self::$___aAutoLoadClasses[ $sCalledUnknownClassName ] );
        }

}
ImmediateFreeDownloadForEDD_Registry::setUp();

// Do not load if accessed directly. Not exiting here because other scripts will load this main file such as uninstall.php and inclusion list generator
// and if it exists their scripts will not complete.
if ( ! defined( 'ABSPATH' ) ) {
    return;
}

if ( defined( 'DOING_TESTS' ) && DOING_TESTS ) {
    return;
}

function _loadImmediateFreeDownloadForEasyDigitalDownloads() {

    if ( ! class_exists( 'EDD_Download' ) ) {
        ImmediateFreeDownloadForEDD_Registry::setAdminNotice(
            ImmediateFreeDownloadForEDD_Registry::NAME . ' requires Easy Digital Downloads v2.2 or above.',
            'error'
        );
        return;
    }

    include( dirname( __FILE__ ) . '/include/class-list.php' );
    ImmediateFreeDownloadForEDD_Registry::registerClasses( $_aClassFiles );
    new ImmediateFreeDownloadForEDD_DownloadButton_Loader;
}
add_action( 'plugins_loaded', '_loadImmediateFreeDownloadForEasyDigitalDownloads' );