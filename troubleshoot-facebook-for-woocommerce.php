<?php
/*
Plugin name: Troubleshoot Facebook for WooCommerce
Plugin URI: https://solutiondesign.co.za/afpfeedreader/plugin-readme/
Description: Remove options stored by Facebook for WooCommerce
Author: nicw
Version: Beta
Author URI:
*/

namespace TFB4WC;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . '/admin/class-menu.php';
	require_once plugin_dir_path( __FILE__ ) . '/admin/class-delete-options.php';
	require_once plugin_dir_path( __FILE__ ) . '/admin/class-product-data-actions.php';
	require_once plugin_dir_path( __FILE__ ) . '/admin/class-clear-facebook-logs.php';
	require_once plugin_dir_path( __FILE__ ) . '/admin/class-test-products.php';
	require_once plugin_dir_path( __FILE__ ) . '/admin/class-validate-product.php';
	require_once plugin_dir_path( __FILE__ ) . '/admin/class-connection-test.php';
}

//require_once plugin_dir_path( __FILE__ ) . '/tests/test-data.php';


/**
 * Class Troubleshoot_FB4WC
 * @package TFB4WC
 */
class Troubleshoot_FB4WC {

	/*
	 * Version is a habit
	 */
	public $version = '1';

	protected static $instance = null;

	/**
	 * @return Troubleshoot_FB4WC|null
	 */
	public static function init(): ?Troubleshoot_FB4WC {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {

		add_action( 'admin_menu', array( 'TFB4WC\Menu', 'init' ) );
		add_action( 'admin_init', array( 'TFB4WC\Product_Data_Actions', 'init' ) );
		add_action( 'admin_init', array( 'TFB4WC\Clear_Facebook_Logs', 'init' ) );
		add_action( 'admin_notices', array( $this, 'check_plugins' ) );
		add_action( 'admin_init', array( 'TFB4WC\Delete_Options', 'init' ) );
		add_action( 'admin_init', array( 'TFB4WC\Connection_Test', 'init' ) );
		add_action( 'admin_init', array( 'TFB4WC\Test_Products', 'init' ) );
		add_action( 'admin_init', array( 'TFB4WC\Remove_Orphans', 'init' ) );

		if ( get_option( 'tsfb4wc_connection_test' ) === '1' ) {

			add_filter( 'wc_facebook_connection_proxy_url', function () {
				return 'https://wc-connect-test.skyverge.com/auth/facebook/';
			} );
		}
	}

	/**
	 * Add an admin warning if Facebook for WooCommerce is active
	 */
	public function check_plugins() {
		?>

        <div class="notice notice-error error-alt">
            <p>Your are troubleshooting Facebook for WooCommerce. Remember to deactivate the TroubleShooting Facebook
                for WooCommerce plugin when you are finished</p>
        </div>

		<?php if ( get_option( 'tsfb4wc_connection_test' ) === '1' ): ?>

            <div class="notice notice-error error-alt">
                <p>Facebook for WooCommerce is connecting through a test proxy. Remember to deactivate this when you
                    have finished troubleshooting.</p>
            </div>

		<?php endif;

	}

	/**
	 * On plugin deactivation we remove all the options
	 */
	public static function deactivated() {

		delete_option( 'tsfb4wc_connection_test' );
	}


}

add_action( 'init', array( 'TFB4WC\Troubleshoot_FB4WC', 'init' ) );
register_deactivation_hook( __FILE__, array( 'TFB4WC\Troubleshoot_FB4WC', 'deactivated' ) );