<?php
/**
 * WP LIBRARY
 *
 * @package           WP LIBRARY
 * @author            Md Hiron Mia
 * @copyright         2024 Hirondev
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       WP LIBRARY
 * Plugin URI:        https://hirondev.com/wp-library
 * Description:       This plugin is a very light weight and help you manage your library system
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Md Hiron Mia
 * Author URI:        https://hirondev.com
 * Text Domain:       wp-library
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://hirondev.com/wp-library/
 * 
 *  WP LIBRARY is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * WP LIBRARY is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with WP LIBRARY. If not, see <http://www.gnu.org/licenses/>
 */

if ( ! defined( 'WPINC' ) ) {
	exit; // Exit if accessed directly
}

define( 'LIBRARY_VERSION', '1.0.0' );
define( 'TABLE_NAME', 'library' );
define( 'LIBRARY_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'LIBRARY_URI', rtrim( plugin_dir_path( __FILE__ ), '/' ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_wp_library(){
	require_once LIBRARY_URI . '/includes/class-wp-library-activator.php';
	WP_Library_Activator::activate();
}

function deactivate_wp_library(){
	require_once LIBRARY_URI . '/includes/class-wp-library-deactivator.php';
	WP_Library_Deactivator::Deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_library' );
register_deactivation_hook( __FILE__, 'deactivate_wp_library');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once LIBRARY_URI . '/includes/class-wp-library.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_library(){
	$plugin = new WP_Library();
	$plugin->run();
}


run_wp_library();