<?php
 if ( ! defined( 'WPINC' ) ) {
	exit; // Exit if accessed directly
 }
/**
 * Admin page for library
 *
 * All admin page functionality is there
 *
 * @since      1.0.0
 * @package    WP_Library
 * @subpackage WP_Library/admin
 * @author     MD Hiron Mia <hironmd647@gmail.com>
 */

class WP_Library_Admin {
    public function admin_assets(){
        wp_enqueue_style( 'wp-library-style', LIBRARY_URL . '/admin/build/index.css', array(), time() );
        wp_enqueue_script( 'wp-library-react-script', LIBRARY_URL . '/admin/build/index.js', array(), time(), true );
    }

    public function admin_page(){
        add_menu_page( 
            __( 'WP Library', 'wp-library' ), 
            __( 'WP Library', 'wp-library' ), 
            'manage_options', 
            'wp-library', 
            array( $this, 'library_admin_page' ), 
            'dashicons-book-alt',
            30
        );
    }

    public function library_admin_page(){
        echo '<div id="library-admin-page">Loading...</div>';
    }
}