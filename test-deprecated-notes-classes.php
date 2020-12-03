<?php
/**
 * Plugin Name: test-deprecated-notes-classes
 *
 * @package WooCommerce\Admin
 */

use \Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes;
use \Automattic\WooCommerce\Admin\Notes\WC_Admin_Note;

/**
 * Register the JS.
 */
function tdnc_admin_enqueue_scripts() {
	if ( ! class_exists( 'Automattic\WooCommerce\Admin\Loader' ) || ! \Automattic\WooCommerce\Admin\Loader::is_admin_or_embed_page() ) {
		return;
	}
	
	$script_path       = '/build/index.js';
	$script_asset_path = dirname( __FILE__ ) . '/build/index.asset.php';
	$script_asset      = file_exists( $script_asset_path )
		? require( $script_asset_path )
		: array( 'dependencies' => array(), 'version' => filemtime( $script_path ) );
	$script_url = plugins_url( $script_path, __FILE__ );

	wp_register_script(
		'test-deprecated-notes-classes',
		$script_url,
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);

	wp_register_style(
		'test-deprecated-notes-classes',
		plugins_url( '/build/index.css', __FILE__ ),
		// Add any dependencies styles may have, such as wp-components.
		array(),
		filemtime( dirname( __FILE__ ) . '/build/index.css' )
	);

	wp_enqueue_script( 'test-deprecated-notes-classes' );
	wp_enqueue_style( 'test-deprecated-notes-classes' );
}

add_action( 'admin_enqueue_scripts', 'tdnc_admin_enqueue_scripts' );


function tdnc_plugins_loaded() {
	// trigger a warning on static call
	$count = WC_Admin_Notes::get_notes_count();

	// trigger a warning on instance call
	$note = new WC_Admin_Note();
	$note->set_title( 'Test' );		
}
add_action( 'plugins_loaded', 'tdnc_plugins_loaded' );



