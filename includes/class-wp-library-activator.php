<?php
 if ( ! defined( 'WPINC' ) ) {
	exit; // Exit if accessed directly
 }
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WP_Library
 * @subpackage WP_Library/includes
 * @author     MD Hiron Mia <hironmd647@gmail.com>
 */

class WP_Library_Activator {
    /**
	 * Runs during plugin activation
	 *  
     * Right now there is nothing to do during activation 
     * 
	 * @since    1.0.0
	 */
    public static function activate() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'library';
		// Check if the table already exists
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE {$table_name} (
				book_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				title VARCHAR(255) NOT NULL,
				author VARCHAR(255) NOT NULL, 
				publisher VARCHAR(255),
				isbn VARCHAR(255),
				publication_date DATE
			) {$charset_collate};";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			dbDelta( $sql );
		}
	}
}