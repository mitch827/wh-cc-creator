<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.webheroes.it
 * @since             1.0.0
 * @package           Wh_Cc_Creator
 *
 * @wordpress-plugin
 * Plugin Name:       WH Custom content creator
 * Plugin URI:        https://github.com/mitch827/wh-cc-creator
 * Description:       Custom content creator to add custom pot types and custom taxonomies. With this plugin you can also customize Archives page content such custom post type archives, custom taxonomies archives and custom taxonomy terms archives.
 * Version:           1.0.7a
 * Author:            Web Heroes
 * Author URI:        http://www.webheroes.it
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wh-cc-creator
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/mitch827/wh-cc-creator
 * GitHub Branch:     master
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wh-cc-creator-activator.php
 */
function activate_wh_cc_creator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wh-cc-creator-activator.php';
	$plugin_base = plugin_basename( __FILE__ );
	Wh_Cc_Creator_Activator::activate( $plugin_base );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wh-cc-creator-deactivator.php
 */
function deactivate_wh_cc_creator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wh-cc-creator-deactivator.php';
	Wh_Cc_Creator_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wh_cc_creator' );
register_deactivation_hook( __FILE__, 'deactivate_wh_cc_creator' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wh-cc-creator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wh_cc_creator() {

	$plugin = new Wh_Cc_Creator();
	$plugin->run();

}
run_wh_cc_creator();
