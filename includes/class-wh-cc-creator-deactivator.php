<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.webheroes.it
 * @since      1.0.0
 *
 * @package    Wh_Cc_Creator
 * @subpackage Wh_Cc_Creator/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wh_Cc_Creator
 * @subpackage Wh_Cc_Creator/includes
 * @author     Web Heroes <diego@webheroes.it>
 */
class Wh_Cc_Creator_Deactivator {
	
	/**
	 * The options name to be used in this plugin
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     string      $option_name    Option name of this plugin
	 */
	private $option_name = 'wh_cc_creator';

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option($this->option_name . '_select_cpt' );
		delete_option($this->option_name . '_select_tax' );
		delete_option($this->option_name . '_select_term' );

	}

}
