<?php
/**
 * Masonic Admin Class.
 *
 * @author  ThemeGrill
 * @package Masonic
 * @since   1.2.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Masonic_Admin' ) ) :

	/**
	 * Masonic_Admin Class.
	 */
	class Masonic_Admin {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		/**
		 * Localize array for import button AJAX request.
		 */
		public function enqueue_scripts() {
			wp_enqueue_style( 'masonic-admin-style', get_template_directory_uri() . '/inc/admin/css/admin.css', array(), MASONIC_THEME_VERSION );

			wp_enqueue_script( 'masonic-plugin-install-helper', get_template_directory_uri() . '/inc/admin/js/plugin-handle.js', array( 'jquery' ), MASONIC_THEME_VERSION, true );

			$welcome_data = array(
				'uri'      => esc_url( admin_url( '/themes.php?page=demo-importer&browse=all&masonic-hide-notice=welcome' ) ),
				'btn_text' => esc_html__( 'Processing...', 'masonic' ),
				'nonce'    => wp_create_nonce( 'masonic_demo_import_nonce' ),
			);

			wp_localize_script( 'masonic-plugin-install-helper', 'masonicRedirectDemoPage', $welcome_data );
		}
	}

endif;

return new Masonic_Admin();
