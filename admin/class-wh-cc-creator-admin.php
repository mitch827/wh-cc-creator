<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.webheroes.it
 * @since      1.0.0
 *
 * @package    Wh_Cc_Creator
 * @subpackage Wh_Cc_Creator/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wh_Cc_Creator
 * @subpackage Wh_Cc_Creator/admin
 * @author     Web Heroes <diego@webheroes.it>
 */
class Wh_Cc_Creator_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	
	/**
	 * The options name to be used in this plugin
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     string      $option_name    Option name of this plugin
	 */
	private $option_name = 'wh_cc_creator';

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wh_Cc_Creator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wh_Cc_Creator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wh-cc-creator-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wh_Cc_Creator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wh_Cc_Creator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wh-cc-creator-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Create option page in a sub-menu page of Web Heroes Enchaments Plugin
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
	    
	    $this->plugin_screen_hook_suffix = add_submenu_page(
		    'web-heroes', 
		    __( 'Web Heroes Custom Content Creator', 'wh-cc-creator' ), 
		    __( 'Custom Content Creator', 'wh-cc-creator' ), 
		    'manage_options', 
		    $this->plugin_name, 
		    array( $this, 'display_options_page' ) 
	    );
	}
	
	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
	    include_once 'partials/wh-cc-creator-admin-display.php';
	}
	
	/**
	 * Register all related settings of this plugin
	 *
	 * @since  1.0.0
	 */
	public function register_setting() {
		
		// Add a General section
		add_settings_section(
		    $this->option_name . '_cpt_edit',
		    __( 'Custom post type archive pages', 'wh-cc-creator' ),
		    array( $this, $this->option_name . '_cpt_general_cb' ),
		    $this->plugin_name . '_cpt_edit'
		);
		
		add_settings_section(
		    $this->option_name . '_tax_edit',
		    __( 'Taxonomy archive pages', 'wh-cc-creator' ),
		    array( $this, $this->option_name . '_tax_general_cb' ),
		    $this->plugin_name . '_tax_edit'
		);
		
		add_settings_section(
		    $this->option_name . '_term_edit',
		    __( 'Taxonomy terms archive pages', 'wh-cc-creator' ),
		    array( $this, $this->option_name . '_term_general_cb' ),
		    $this->plugin_name . '_term_edit'
		);
		
		//CUSTOM POST TYPE selector
		$args = array (
			'public' 	=> true,
			'_builtin' 	=> false	
		);
		$post_types = get_post_types( $args, 'objects' );
		
		add_settings_field(
			$this->option_name . '_select_cpt',
			__( 'Select the custom post archive page you want to edit', 'wh-cc-creator' ),
			array( $this, $this->option_name . '_cpt_select_cb'),
			$this->plugin_name . '_cpt_edit',
			$this->option_name . '_cpt_edit',
			$param = array(
				'label_for' 	=> $this->option_name . '_select_cpt',
				'post_types' 	=> $post_types
			)
		);
		register_setting( $this->plugin_name . '_cpt_edit', $this->option_name . '_select_cpt' );
		
		//TAXONOMIES selector
		$args_tax = array (
			'public' 	=> true,
			'_builtin' 	=> false	
		);
		$taxonomies = get_taxonomies( $args, 'objects' );
		
		add_settings_field(
			$this->option_name . '_select_tax',
			__( 'Select the taxonomy archive page you want to edit', 'wh-cc-creator' ),
			array( $this, $this->option_name . '_tax_select_cb'),
			$this->plugin_name . '_tax_edit',
			$this->option_name . '_tax_edit',
			$param = array(
				'label_for' 	=> $this->option_name . '_select_tax',
				'taxonomies' 	=> $taxonomies
			)
		);
		register_setting( $this->plugin_name . '_tax_edit', $this->option_name . '_select_tax' );
		
		//TAXONOMY TERMS selector
		foreach( $taxonomies as $tax ) :
			$terms[$tax->label] = $tax->name;
		endforeach;
		$term = get_terms( $terms, array( 'hide_empty' => 0 ) );

		add_settings_field(
			$this->option_name . '_select_term',
			__( 'Select the taxonomy term archive page you want to edit', 'wh-cc-creator' ),
			array( $this, $this->option_name . '_term_select_cb'),
			$this->plugin_name . '_term_edit',
			$this->option_name . '_term_edit',
			$param = array(
				'label_for' 	=> $this->option_name . '_select_term',
				'tax_terms' 	=> $term
			)
		);
		register_setting( $this->plugin_name . '_term_edit', $this->option_name . '_select_term' );
		
		//CUSTOM POST TYPE editor
		$cpt_selected = get_option( $this->option_name . '_select_cpt' );
		if ( isset( $cpt_selected ) && !empty( $cpt_selected ) ){
			list( $cpt_name, $cpt_label ) = explode( '|', $cpt_selected );
			
			if ( defined( 'ICL_LANGUAGE_CODE' ) ){
				add_settings_field(
				    $this->option_name . '_' . $cpt_name . '_content_' . ICL_LANGUAGE_CODE,
				    $cpt_label . ' ' .  __( 'content in', 'wh-cc-creator' ) . ' ' . ICL_LANGUAGE_NAME,
				    array( $this, $this->option_name . '_cpt_content_cb' ),
				    $this->plugin_name . '_cpt_edit',
				    $this->option_name . '_cpt_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $cpt_name . '_content_' . ICL_LANGUAGE_CODE,
				    	'cpt_label'	=> $cpt_label,
				    	'cpt_name'	=> $cpt_name,
				    	'multilang' => true
				    )
				);
				register_setting( $this->plugin_name . '_cpt_edit', $this->option_name . '_cpt_content_' . ICL_LANGUAGE_CODE . '_' . $cpt_name );
			} else {
				add_settings_field(
				    $this->option_name . '_' . $cpt_name . '_content',
				    $cpt_label . ' ' .  __( 'content', 'wh-cc-creator' ),
				    array( $this, $this->option_name . '_cpt_content_cb' ),
				    $this->plugin_name . '_cpt_edit',
				    $this->option_name . '_cpt_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $cpt_name . '_content',
				    	'cpt_label'	=> $cpt_label,
				    	'cpt_name'	=> $cpt_name,
				    	'multilang' => false
				    )
				);
				register_setting( $this->plugin_name . '_cpt_edit', $this->option_name . '_cpt_content_' . $cpt_name );
			}
		}
		
		//TAXONOMY editor
		$tax_selected = get_option( $this->option_name . '_select_tax' );
		if ( isset( $tax_selected ) && !empty( $tax_selected ) ){
			list( $tax_name, $tax_label ) = explode( '|', $tax_selected );
		
			if ( defined( 'ICL_LANGUAGE_CODE' ) ){
				add_settings_field(
				    $this->option_name . '_' . $tax_name . '_content_' . ICL_LANGUAGE_CODE,
				    $tax_label . ' ' .  __( 'content in', 'wh-cc-creator' ) . ' ' . ICL_LANGUAGE_NAME,
				    array( $this, $this->option_name . '_tax_content_cb' ),
				    $this->plugin_name . '_tax_edit',
				    $this->option_name . '_tax_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $tax_name . '_content_' . ICL_LANGUAGE_CODE,
				    	'tax_label'	=> $tax_label,
				    	'tax_name'	=> $tax_name,
				    	'multilang' => true
				    )
				);
				register_setting( $this->plugin_name . '_tax_edit', $this->option_name . '_tax_content_' . ICL_LANGUAGE_CODE . '_' . $tax_name );
			} else {
				add_settings_field(
				    $this->option_name . '_' . $tax_name . '_content',
				    $tax_label . ' ' .  __( 'content', 'wh-cc-creator' ),
				    array( $this, $this->option_name . '_tax_content_cb' ),
				    $this->plugin_name . '_tax_edit',
				    $this->option_name . '_tax_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $tax_name . '_content',
				    	'tax_label'	=> $tax_label,
				    	'tax_name'	=> $tax_name,
				    	'multilang' => false
				    )
				);
				register_setting( $this->plugin_name . '_tax_edit', $this->option_name . '_tax_content_' . $tax_name );
			}
		}
		
		//TAXONOMY TERM editor
		$term_selected = get_option( $this->option_name . '_select_term' );
		if ( isset( $term_selected ) && !empty( $term_selected ) ){
			list( $term_name, $term_label ) = explode( '|', $term_selected );
		
			if ( defined( 'ICL_LANGUAGE_CODE' ) ){
				add_settings_field(
				    $this->option_name . '_' . $term_name . '_content_' . ICL_LANGUAGE_CODE,
				    $term_label . ' ' .  __( 'content in', 'wh-cc-creator' ) . ' ' . ICL_LANGUAGE_NAME,
				    array( $this, $this->option_name . '_term_content_cb' ),
				    $this->plugin_name . '_term_edit',
				    $this->option_name . '_term_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $term_name . '_content_' . ICL_LANGUAGE_CODE,
				    	'term_label'=> $term_label,
				    	'term_name'	=> $term_name,
				    	'multilang' => true
				    )
				);
				register_setting( $this->plugin_name . '_term_edit', $this->option_name . '_term_content_' . ICL_LANGUAGE_CODE . '_' . $term_name );
			} else {
				add_settings_field(
				    $this->option_name . '_' . $term_name . '_content',
				    $term_label . ' ' .  __( 'content', 'wh-cc-creator' ),
				    array( $this, $this->option_name . '_term_content_cb' ),
				    $this->plugin_name . '_term_edit',
				    $this->option_name . '_term_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $term_name . '_content',
				    	'term_label'=> $term_label,
				    	'term_name'	=> $term_name,
				    	'multilang' => false
				    )
				);
				register_setting( $this->plugin_name . '_term_edit', $this->option_name . '_term_content_' . $term_name );
			}
		}
	}
	
	/**
	 * General section fuctions to print general messages and istructions.
	 * 
	 * @access public
	 * @return void
	 */
	public function wh_cc_creator_cpt_general_cb() {
	    echo '<p>' . __( 'Insert custom text for the Custom Post Type archive pages.', 'wh-cc-creator' ) . '</p>';
	}
	public function wh_cc_creator_tax_general_cb() {
	    echo '<p>' . __( 'Insert custom text for the Taxonomies archive pages.', 'wh-cc-creator' ) . '</p>';
	}
	public function wh_cc_creator_term_general_cb() {
	    echo '<p>' . __( 'Insert custom text for the Taxonomy terms archive pages.', 'wh-cc-creator' ) . '</p>';
	}
	
	/**
	 * wh_cc_creator_cpt_select_cb function.
	 *
	 * Callback function to generate a select box with all custom post types created
	 * 
	 * @access public
	 * @param mixed $param -> custom post types
	 * @return void
	 */
	public function wh_cc_creator_cpt_select_cb( $param ){
		$post_types = $param['post_types'];

		echo '<select name="' . $this->option_name . '_select_cpt' .'" onchange=" this.form.submit(); ">';
		echo '<option selected="true" disabled>Select:</option>';
		if( $post_types ) :
			foreach( $post_types as $cpt ) :
        		echo '<option value="' . $cpt->name . '|' . $cpt->label . '">' . $cpt->label . '</option>';
			endforeach;
		else :
			echo '<option value="none" disabled>No CPT found</option>';
		endif;
		echo '</select>';
	}
	
	public function wh_cc_creator_tax_select_cb( $param ){
		$taxonomies = $param['taxonomies'];

		echo '<select name="' . $this->option_name . '_select_tax' .'" onchange=" this.form.submit(); ">';
		echo '<option selected="true" disabled>Select:</option>';
		if( $taxonomies ) :
			foreach(  $taxonomies as $tax ) :
        		echo '<option value="' . $tax->name . '|' . $tax->label . '">' . $tax->label . '</option>';
			endforeach;
		else :
			echo '<option value="none" disabled>No Taxonomies found</option>';
		endif;
		echo '</select>';
	}
	
	public function wh_cc_creator_term_select_cb( $param ){
		$terms = $param['tax_terms'];
		
		echo '<select name="' . $this->option_name . '_select_term' .'" onchange=" this.form.submit(); ">';
		echo '<option selected="true" disabled>Select:</option>';
		if( $terms ) :
			foreach(  $terms as $term ) :
        		echo '<option value="' . $term->slug . '|' . $term->name . '">' . $term->name . '</option>';
			endforeach;
		else :
			echo '<option value="none" disabled>No Taxonomies terms found</option>';
		endif;
		echo '</select>';
	}
	
	/**
	 * wh_cc_creator_cpt_content_cb function.
	 * 
	 * Callback function to create the wp_editor for custom post type archives page.
	 *
	 * @access public
	 * @param mixed $param -> custom post type name and label
	 * @return void
	 */
	public function wh_cc_creator_cpt_content_cb( $param ){
		$cpt_name = $param['cpt_name'];
		$cpt_label = $param['cpt_label'];
		$multilang = $param['multilang'];
		
		if( $multilang ){
			$cpt_content[ ICL_LANGUAGE_CODE ][ $cpt_name ] = apply_filters( 'the_editor_content', get_option( $this->option_name . '_cpt_content_' . ICL_LANGUAGE_CODE . '_' . $cpt_name ), $this->option_name . '_cpt_content_' . ICL_LANGUAGE_CODE . '_' . $cpt_name );
			wp_editor($cpt_content[ ICL_LANGUAGE_CODE ][ $cpt_name ], $this->option_name . '_cpt_content_' . ICL_LANGUAGE_CODE . '_' . $cpt_name );
		} else {
			echo '<p class="wp-ui-notification"><strong>' . __('Attention!', 'wh-cc-creator' ) . '</strong> ' . __( 'WPML is not installed, content won\'t be multilingual.', 'wh-cc-creator' ) . '</p><br>';
			$cpt_content[ $cpt_name ] = apply_filters( 'the_editor_content', get_option( $this->option_name . '_cpt_content_' . $cpt_name ), $this->option_name . '_cpt_content_' . $cpt_name );
			wp_editor($cpt_content[ $cpt_name ], $this->option_name . '_cpt_content_' . $cpt_name );
		}
		$name = 'cpt_content_submit';
		submit_button( 'Submit', 'primary', $name );	
	}
	
	public function wh_cc_creator_tax_content_cb( $param ){
		$tax_name = $param['tax_name'];
		$tax_label = $param['tax_label'];
		$multilang = $param['multilang'];
		
		if( $multilang ){
			$tax_content[ ICL_LANGUAGE_CODE ][ $tax_name ] = apply_filters( 'the_editor_content', get_option( $this->option_name . '_tax_content_' . ICL_LANGUAGE_CODE . '_' . $tax_name ), $this->option_name . '_tax_content_' . ICL_LANGUAGE_CODE . '_' . $tax_name );
			wp_editor($tax_content[ ICL_LANGUAGE_CODE ][ $tax_name ], $this->option_name . '_tax_content_' . ICL_LANGUAGE_CODE . '_' . $tax_name );
		} else {
			echo '<p class="wp-ui-notification"><strong>' . __('Attention!', 'wh-cc-creator' ) . '</strong> ' . __( 'WPML is not installed, content won\'t be multilingual.', 'wh-cc-creator' ) . '</p><br>';
			$tax_content[ $tax_name ] = apply_filters( 'the_editor_content', get_option( $this->option_name . '_tax_content_' . $tax_name ), $this->option_name . '_tax_content_' . $tax_name );
			wp_editor($tax_content[ $tax_name ], $this->option_name . '_tax_content_' . $tax_name );
		}
		$name = 'tax_content_submit';
		submit_button( 'Submit', 'primary', $name );	
	}
	
	public function wh_cc_creator_term_content_cb( $param ){
		$term_name = $param['term_name'];
		$term_label = $param['term_label'];
		$multilang = $param['multilang'];
		
		if( $multilang ){
			$term_content[ ICL_LANGUAGE_CODE ][ $term_name ] = apply_filters( 'the_editor_content', get_option( $this->option_name . '_term_content_' . ICL_LANGUAGE_CODE . '_' . $term_name ), $this->option_name . '_term_content_' . ICL_LANGUAGE_CODE . '_' . $term_name );
			wp_editor($term_content[ ICL_LANGUAGE_CODE ][ $term_name ], $this->option_name . '_term_content_' . ICL_LANGUAGE_CODE . '_' . $term_name );
		} else {
			echo '<p class="wp-ui-notification"><strong>' . __('Attention!', 'wh-cc-creator' ) . '</strong> ' . __( 'WPML is not installed, content won\'t be multilingual.', 'wh-cc-creator' ) . '</p><br>';
			$term_content[ $term_name ] = apply_filters( 'the_editor_content', get_option( $this->option_name . '_term_content_' . $term_name ), $this->option_name . '_term_content_' . $term_name );
			wp_editor($term_content[ $term_name ], $this->option_name . '_term_content_' . $term_name );
		}
		$name = 'term_content_submit';
		submit_button( 'Submit', 'primary', $name );	
	}

}
