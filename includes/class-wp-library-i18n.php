<?php

 if ( ! defined( 'WPINC' ) ) {
	exit; // Exit if accessed directly
 }
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WP_Library
 * @subpackage WP_Library/includes
 * @author     MD Hiron Mia <hironmd647@gmail.com>
 */
class WP_Library_i18n {
    /**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
    public function load_plugin_textdomain(){
        load_plugin_textdomain(
            'wp-library',
            false,
            LIBRARY_URI . '/languages/'
        );
    }
}