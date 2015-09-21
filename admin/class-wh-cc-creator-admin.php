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
	 * The options responsibile to contain cpt, taxonomies and terms
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     string      $option_name    Option name of this plugin
	 */
	private $cpt_selected = FALSE; 	//custom post type user selected
	private $cpt_name = FALSE; 		//cpt wp name
	private $cpt_label = FALSE; 	//cpt label (human readable)
	private $tax_selected = FALSE; 	//custom taxonomy post type user selected
	private $tax_name = FALSE; 		//custom taxonomy wp name
	private $tax_label = FALSE; 	//custom taxonomy label (human readable)
	private $term_selected = FALSE; //custom taxonomy term user selected
	private $term_name = FALSE; 	//custom taxonomy term wp slug
	private $term_label = FALSE; 	//custom taxonomy term name (human readable)

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
		$this->cpt_selected = get_option( $this->option_name . '_select_cpt' );
		$this->tax_selected = get_option( $this->option_name . '_select_tax' );
		$this->term_selected = get_option( $this->option_name . '_select_term' );
		if( isset( $this->cpt_selected ) && !empty( $this->cpt_selected ) )
			list( $this->cpt_name, $this->cpt_label ) = explode( '|', $this->cpt_selected );
		if ( isset( $this->term_selected ) && !empty( $this->term_selected ) )
			list( $this->term_name, $this->term_label  ) = explode( '|', $this->term_selected );
		if ( isset( $this->tax_selected ) && !empty( $this->tax_selected ) )
			list( $this->tax_name, $this->tax_label ) = explode( '|', $this->tax_selected );
		
		
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
		// Also adds a check to make sure `wp_enqueue_media` has only been called once.
		// @see: http://core.trac.wordpress.org/ticket/22843
		if ( ! did_action( 'wp_enqueue_media' ) )
			wp_enqueue_media();

	}
	
	/**
	 * This function provides simple check for the presence of WPML plugin.
	 * 
	 * @access public
	 * @return void
	 */
	public function wpml_check(){
		
		if (defined ( 'ICL_LANGUAGE_CODE' ) ){
			$wpml_check = ICL_LANGUAGE_CODE;
			if ( !empty( $wpml_check ) ){
				return TRUE;
			}
			return FALSE;
		}
		return FALSE;
	}
	
	/**
	 * wh_cc_creator_get_content function.
	 *
	 * Get varius content for creating options
	 * 
	 * @access public
	 * @param mixed $content_type -> type of content (post, taxonomy, term)
	 * @param mixed $taxonomies -> array of taxonomy used to get terms
	 * @return void
	 */
	public function wh_cc_creator_get_content( $content_type, $taxonomies ){
		
		$args = array (
			'public' 	=> true,
			'_builtin' 	=> false	
		);
		if ( $content_type === 'post')
			$result = get_post_types( $args, 'objects' );
			
		if ( $content_type === 'taxonomy')
			$result = get_taxonomies( $args, 'objects' );
		
		if ( $content_type === 'term'){
			foreach( $taxonomies as $tax ) :
				$taxes[] = $tax->name;
			endforeach;
			$result = get_terms( $taxes, array( 'hide_empty' => FALSE ) );
		}
		
		return $result;
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
		//Get varius content for creating the options
		$post_types = $this->wh_cc_creator_get_content( 'post', NULL );
		$taxonomies = $this->wh_cc_creator_get_content( 'taxonomy', NULL );
		$term = $this->wh_cc_creator_get_content( 'term', $taxonomies );
		$wpml = $this->wpml_check(); //check for the presence of WPML
		
		// Add sections
		add_settings_section(
		    $this->option_name . '_add_tax',
		    __( 'Custom taxonomy creator', 'wh-cc-creator' ),
		    array( $this, $this->option_name . '_general_cb' ),
		    $this->plugin_name . '_add_tax'
		);
		
		add_settings_section(
		    $this->option_name . '_add_cpt',
		    __( 'Custom post type creator', 'wh-cc-creator' ),
		    array( $this, $this->option_name . '_general_cb' ),
		    $this->plugin_name . '_add_cpt'
		);
		
		add_settings_section(
		    $this->option_name . '_tax_edit',
		    __( 'Taxonomy archive pages', 'wh-cc-creator' ),
		    array( $this, $this->option_name . '_general_cb' ),
		    $this->plugin_name . '_tax_edit'
		);
		
		add_settings_section(
		    $this->option_name . '_term_edit',
		    __( 'Taxonomy terms archive pages', 'wh-cc-creator' ),
		    array( $this, $this->option_name . '_general_cb' ),
		    $this->plugin_name . '_term_edit'
		);
		
		add_settings_section(
		    $this->option_name . '_cpt_edit',
		    __( 'Custom post type archive pages', 'wh-cc-creator' ),
		    array( $this, $this->option_name . '_general_cb' ),
		    $this->plugin_name . '_cpt_edit'
		);
		
		//CUSTOM TAXONOMY creator
		add_settings_field(
			$this->option_name . '_tax_creator',
			__( 'Taxonomy creator', 'wh-cc-creator' ),
			array( $this, $this->option_name . '_content_creator_cb'),
			$this->plugin_name . '_add_tax',
			$this->option_name . '_add_tax',
			$param = array(
				'label_for' => $this->option_name . '_tax_creator',
				'type'		=> 'tax'
			)
		);
		register_setting( $this->plugin_name . '_add_tax', $this->option_name . '_tax_creator' );
		
		//CUSTOM POST TYPE creator
		add_settings_field(
			$this->option_name . '_cpt_creator',
			__( 'Custom post type creator', 'wh-cc-creator' ),
			array( $this, $this->option_name . '_content_creator_cb'),
			$this->plugin_name . '_add_cpt',
			$this->option_name . '_add_cpt',
			$param = array(
				'label_for' => $this->option_name . '_cpt_creator',
				'type' 		=> 'cpt' 
			)
		);
		register_setting( $this->plugin_name . '_add_cpt', $this->option_name . '_cpt_creator' );
		
		//CUSTOM POST TYPE selector
		add_settings_field(
			$this->option_name . '_select_cpt',
			__( 'Select the custom post archive page you want to edit', 'wh-cc-creator' ),
			array( $this, $this->option_name . '_select_cb'),
			$this->plugin_name . '_cpt_edit',
			$this->option_name . '_cpt_edit',
			$param = array(
				'label_for' 	=> $this->option_name . '_select_cpt',
				'content'		=> 'cpt',
				'post_types' 	=> $post_types
			)
		);
		register_setting( $this->plugin_name . '_cpt_edit', $this->option_name . '_select_cpt' );
		
		//TAXONOMIES selector
		add_settings_field(
			$this->option_name . '_select_tax',
			__( 'Select the taxonomy archive page you want to edit', 'wh-cc-creator' ),
			array( $this, $this->option_name . '_select_cb'),
			$this->plugin_name . '_tax_edit',
			$this->option_name . '_tax_edit',
			$param = array(
				'label_for' 	=> $this->option_name . '_select_tax',
				'content'		=> 'tax',
				'taxonomies' 	=> $taxonomies
			)
		);
		register_setting( $this->plugin_name . '_tax_edit', $this->option_name . '_select_tax' );
		
		//TAXONOMY TERMS selector
		add_settings_field(
			$this->option_name . '_select_term',
			__( 'Select the taxonomy term archive page you want to edit', 'wh-cc-creator' ),
			array( $this, $this->option_name . '_select_cb'),
			$this->plugin_name . '_term_edit',
			$this->option_name . '_term_edit',
			$param = array(
				'label_for' 	=> $this->option_name . '_select_term',
				'content'		=> 'term',
				'tax_terms' 	=> $term
			)
		);
		register_setting( $this->plugin_name . '_term_edit', $this->option_name . '_select_term' );
		
		//CUSTOM POST TYPE editor
		if ( isset( $this->cpt_selected ) && !empty( $this->cpt_selected ) ){
			
			//Add image uploader
			add_settings_field(
			    $this->option_name . '_' . $this->cpt_name . '_content_img',
			    $this->cpt_label . ' ' .  __( 'image', 'wh-cc-creator' ),
			    array( $this, $this->option_name . '_content_img_cb' ),
			    $this->plugin_name . '_cpt_edit',
			    $this->option_name . '_cpt_edit',
			    $param = array( 
			    	'label_for' => $this->option_name . '_' . $this->cpt_name . '_content_img',
			    	'multilang' => false,
			    	'content' 	=> 'cpt'
			    )
			);
			register_setting( $this->plugin_name . '_cpt_edit', $this->option_name . '_' . $this->cpt_name . '_content_img' );
			
			//Add CPT content editor
			if ( TRUE === $wpml ){
				//Add custom text
				add_settings_field(
				    $this->option_name . '_' . $this->cpt_name . '_content_text_' . ICL_LANGUAGE_CODE,
				    $this->cpt_label . ' ' .  __( 'text', 'wh-cc-creator' ). ' ' . ICL_LANGUAGE_NAME,
				    array( $this, $this->option_name . '_content_text_cb' ),
				    $this->plugin_name . '_cpt_edit',
				    $this->option_name . '_cpt_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->cpt_name . '_content_text_' . ICL_LANGUAGE_CODE,
				    	'multilang' => true,
				    	'content' 	=> 'cpt'
				    )
				);
				add_settings_field(
				    $this->option_name . '_' . $this->cpt_name . '_content_' . ICL_LANGUAGE_CODE,
				    $this->cpt_label . ' ' .  __( 'content in', 'wh-cc-creator' ) . ' ' . ICL_LANGUAGE_NAME,
				    array( $this, $this->option_name . '_content_cb' ),
				    $this->plugin_name . '_cpt_edit',
				    $this->option_name . '_cpt_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->cpt_name . '_content_' . ICL_LANGUAGE_CODE,
				    	'multilang' => true,
				    	'content' 	=> 'cpt'
				    )
				);
				register_setting( $this->plugin_name . '_cpt_edit', $this->option_name . '_' . $this->cpt_name . '_content_text_' . ICL_LANGUAGE_CODE );
				register_setting( $this->plugin_name . '_cpt_edit', $this->option_name . '_' . $this->cpt_name . '_content_' . ICL_LANGUAGE_CODE );
			} else {
				//Add custom text
				add_settings_field(
				    $this->option_name . '_' . $this->cpt_name . '_content_text',
				    $this->cpt_label . ' ' .  __( 'text', 'wh-cc-creator' ),
				    array( $this, $this->option_name . '_content_text_cb' ),
				    $this->plugin_name . '_cpt_edit',
				    $this->option_name . '_cpt_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->cpt_name . '_content_text',
				    	'multilang' => false,
				    	'content' 	=> 'cpt'
				    )
				);
				add_settings_field(
				    $this->option_name . '_' . $this->cpt_name . '_content',
				    $this->cpt_label . ' ' .  __( 'content', 'wh-cc-creator' ),
				    array( $this, $this->option_name . '_content_cb' ),
				    $this->plugin_name . '_cpt_edit',
				    $this->option_name . '_cpt_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->cpt_name . '_content',
				    	'multilang' => false,
				    	'content' 	=> 'cpt'
				    )
				);
				register_setting( $this->plugin_name . '_cpt_edit', $this->option_name . '_' . $this->cpt_name . '_content_text' );
				register_setting( $this->plugin_name . '_cpt_edit', $this->option_name . '_' . $this->cpt_name . '_content' );
			}
		}
		
		//TAXONOMY editor
		if ( isset( $this->tax_selected ) && !empty( $this->tax_selected ) ){
			
			//Add image uploader
			add_settings_field(
			    $this->option_name . '_' . $this->tax_name . '_content_img',
			    $this->tax_label . ' ' .  __( 'image', 'wh-cc-creator' ),
			    array( $this, $this->option_name . '_content_img_cb' ),
			    $this->plugin_name . '_tax_edit',
			    $this->option_name . '_tax_edit',
			    $param = array( 
			    	'label_for' => $this->option_name . '_' . $this->tax_name . '_content_img',
			    	'multilang' => false,
			    	'content' 	=> 'tax'
			    )
			);
			register_setting( $this->plugin_name . '_tax_edit', $this->option_name . '_' . $this->tax_name . '_content_img' );
			
			//Add TAXONOMY content editor
			if ( TRUE === $wpml ){
				//Add custom text
				add_settings_field(
				    $this->option_name . '_' . $this->cpt_name . '_content_text' . ICL_LANGUAGE_CODE,
				    $this->tax_label . ' ' .  __( 'text', 'wh-cc-creator' ) . ' ' . ICL_LANGUAGE_NAME,
				    array( $this, $this->option_name . '_content_text_cb' ),
				    $this->plugin_name . '_tax_edit',
				    $this->option_name . '_tax_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->tax_name . '_content_text' . ICL_LANGUAGE_CODE,
				    	'multilang' => true,
				    	'content' 	=> 'tax'
				    )
				);
				add_settings_field(
				    $this->option_name . '_' . $this->tax_name . '_content_' . ICL_LANGUAGE_CODE,
				    $this->tax_label . ' ' .  __( 'content in', 'wh-cc-creator' ) . ' ' . ICL_LANGUAGE_NAME,
				    array( $this, $this->option_name . '_content_cb' ),
				    $this->plugin_name . '_tax_edit',
				    $this->option_name . '_tax_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->tax_name . '_content_' . ICL_LANGUAGE_CODE,
				    	'multilang' => true,
				    	'content' 	=> 'tax'
				    )
				);
				register_setting( $this->plugin_name . '_cpt_edit', $this->option_name . '_' . $this->tax_name . '_content_text' . ICL_LANGUAGE_CODE );
				register_setting( $this->plugin_name . '_tax_edit', $this->option_name . '_' . $this->tax_name . '_content_' . ICL_LANGUAGE_CODE );
			} else {
				add_settings_field(
				    $this->option_name . '_' . $this->tax_name . '_content_text',
				    $this->tax_label . ' ' .  __( 'text', 'wh-cc-creator' ),
				    array( $this, $this->option_name . '_content_text_cb' ),
				    $this->plugin_name . '_tax_edit',
				    $this->option_name . '_tax_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->tax_name . '_content_text',
				    	'multilang' => false,
				    	'content' 	=> 'tax'
				    )
				);
				add_settings_field(
				    $this->option_name . '_' . $this->tax_name . '_content',
				    $this->tax_label . ' ' .  __( 'content', 'wh-cc-creator' ),
				    array( $this, $this->option_name . '_content_cb' ),
				    $this->plugin_name . '_tax_edit',
				    $this->option_name . '_tax_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->tax_name . '_content',
				    	'multilang' => false,
				    	'content' 	=> 'tax'
				    )
				);
				register_setting( $this->plugin_name . '_cpt_edit', $this->option_name . '_' . $this->tax_name . '_content_text' );
				register_setting( $this->plugin_name . '_tax_edit', $this->option_name . '_' . $this->tax_name . '_content' );
			}
		}
		
		//TAXONOMY TERM editor
		if ( isset( $this->term_selected ) && !empty( $this->term_selected ) ){
			
			//Add image uploader
			add_settings_field(
			    $this->option_name . '_' . $this->term_name . '_content_img',
			    $this->term_label . ' ' .  __( 'image', 'wh-cc-creator' ),
			    array( $this, $this->option_name . '_content_img_cb' ),
			    $this->plugin_name . '_term_edit',
			    $this->option_name . '_term_edit',
			    $param = array( 
			    	'label_for' => $this->option_name . '_' . $this->term_name . '_content_img',
			    	'multilang' => false,
			    	'content' 	=> 'term'
			    )
			);
			register_setting( $this->plugin_name . '_term_edit', $this->option_name . '_' . $this->term_name . '_content_img' );
		
			if ( TRUE === $wpml ){
				//Add custom text
				add_settings_field(
				    $this->option_name . '_' . $this->term_name . '_content_text_' . ICL_LANGUAGE_CODE,
				    $this->term_label . ' ' .  __( 'text', 'wh-cc-creator' ) . ' ' . ICL_LANGUAGE_NAME,
				    array( $this, $this->option_name . '_content_text_cb' ),
				    $this->plugin_name . '_term_edit',
				    $this->option_name . '_term_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->cpt_name . '_content_text_' . ICL_LANGUAGE_CODE,
				    	'multilang' => true,
				    	'content' 	=> 'term'
				    )
				);
				add_settings_field(
				    $this->option_name . '_' . $this->term_name . '_content_' . ICL_LANGUAGE_CODE,
				    $this->term_label  . ' ' .  __( 'content in', 'wh-cc-creator' ) . ' ' . ICL_LANGUAGE_NAME,
				    array( $this, $this->option_name . '_content_cb' ),
				    $this->plugin_name . '_term_edit',
				    $this->option_name . '_term_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->term_name . '_content_' . ICL_LANGUAGE_CODE,
				    	'multilang' => true,
				    	'content' 	=> 'term'
				    )
				);
				register_setting( $this->plugin_name . '_term_edit', $this->option_name . '_' . $this->term_name . '_content_text_' . ICL_LANGUAGE_CODE );
				register_setting( $this->plugin_name . '_term_edit', $this->option_name . '_' . $this->term_name . '_content_' . ICL_LANGUAGE_CODE );
			} else {
				//Add custom text
				add_settings_field(
				    $this->option_name . '_' . $this->term_name . '_content_text',
				    $this->term_label . ' ' .  __( 'text', 'wh-cc-creator' ),
				    array( $this, $this->option_name . '_content_text_cb' ),
				    $this->plugin_name . '_term_edit',
				    $this->option_name . '_term_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->term_name . '_content_text',
				    	'multilang' => false,
				    	'content' 	=> 'term'
				    )
				);
				add_settings_field(
				    $this->option_name . '_' . $this->term_name . '_content',
				    $this->term_label  . ' ' .  __( 'content', 'wh-cc-creator' ),
				    array( $this, $this->option_name . '_content_cb' ),
				    $this->plugin_name . '_term_edit',
				    $this->option_name . '_term_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->term_name . '_content',
				    	'multilang' => false,
				    	'content' 	=> 'term'
				    )
				);
				register_setting( $this->plugin_name . '_term_edit', $this->option_name . '_' . $this->term_name . '_content_text' );
				register_setting( $this->plugin_name . '_term_edit', $this->option_name . '_' . $this->term_name . '_content' );
			}
		}
	}
	
	/**
	 * General section fuctions to print general messages and istructions.
	 * 
	 * @access public
	 * @return void
	 */
	public function wh_cc_creator_general_cb( $section_passed ) {
		$wpml = $this->wpml_check(); //check for the presence of WPML
		
		if ( TRUE === $wpml )
			$wpml_control = '<p class="wp-ui-notification"><strong>' . __('Attention!', 'wh-cc-creator' ) . '</strong> ' . __( 'WPML is not installed, content won\'t be multilingual.', 'wh-cc-creator' ) . '</p>';
			
		switch ( $section_passed['id'] ) :
			case  $this->option_name . '_add_tax':
				printf( __( '<p>%s: add custom taxonomies.</p>', 'wh-cc-creator' ), $section_passed['title'] );
				if ( isset( $wpml_control ) )
					echo $wpml_control;
				break;
			case  $this->option_name . '_add_cpt':
				printf( __( '<p>%s: add custom tpost types.</p>', 'wh-cc-creator' ), $section_passed['title'] );
				if (isset( $wpml_control ) )
					echo $wpml_control;
				break;
			case  $this->option_name . '_tax_edit':
				printf( __( '<p>%s: customize custom taxonomies archive page.</p>', 'wh-cc-creator' ), $section_passed['title'] );
				if ( isset( $wpml_control ) )
					echo $wpml_control;
				break;
			case  $this->option_name . '_term_edit':
				printf( __( '<p>%s: customize custom taxonomy terms archive page.</p>', 'wh-cc-creator' ), $section_passed['title'] );
				if ( isset( $wpml_control ) )
					echo $wpml_control;
				break;
			case  $this->option_name . '_cpt_edit':
				printf( __( '<p>%s: customize custom post types archive page.</p>', 'wh-cc-creator' ), $section_passed['title'] );
				if ( isset( $wpml_control ) )
					echo $wpml_control;
				break;
	    endswitch;
	}
	
	public function wh_cc_creator_content_creator_cb( $param ){
		$content = $param['type'];
		$type = get_option( $this->option_name . '_' . $content . '_creator' );
		?>
		
		<textarea cols="80" rows="30" name="<?php echo $this->option_name . '_' . $content . '_creator'; ?>" id="<?php echo $this->option_name . '_' . $content . '_creator'; ?>"><?php echo $type; ?></textarea>
		<p class="description">This file is used to create custom <?php echo ( ( 'tax' === $content ) ? 'taxonomies' : 'post types' ); ?>. In the future it will be a full functonal plugin.<br> For now use: <a href="https://generatewp.com/<?php echo ( ( 'tax' === $content ) ? 'taxonomy' : 'post-type' ); ?>/" target="_blank">https://generatewp.com/<?php echo ( ( 'tax' === $content ) ? 'taxonomy' : 'post-type' ); ?>/</a></p>
	 	
		<?php
			
	}
	
	public function wh_cc_creator_tax(){
		if ( stream_resolve_include_path( 'partials/wh-cc-creator-admin-tax.php' ) )
			include_once 'partials/wh-cc-creator-admin-tax.php';
	}
	
	public function wh_cc_creator_cpt(){
		if ( stream_resolve_include_path( 'partials/wh-cc-creator-admin-cpt.php' ) )
			include_once 'partials/wh-cc-creator-admin-cpt.php';
	}
	
	/**
	 * wh_cc_creator_select_cb function.
	 *
	 * Callback function to generate a select box with all custom post types created
	 * 
	 * @access public
	 * @param mixed $param -> content selected (cpt, taxonomies, terms)
	 * @return void
	 */
	public function wh_cc_creator_select_cb( $param ){
		$content = $param['content'];
		if ( $content === 'cpt' )
			$content_types = $param['post_types'];
		if ( $content === 'tax' )
			$content_types = $param['taxonomies'];
		if ( $content === 'term' )
			$content_types = $param['tax_terms'];
			
		echo '<select name="' . $this->option_name . '_select_' . $content .'" onchange=" this.form.submit(); ">';
		echo '<option selected="true" disabled>Select:</option>';
		if( $content_types ) :
			foreach( $content_types as $type ) :
        		$output = '<option value="' . ( ($content !== 'term') ? $type->name . '|' . $type->label : $type->slug . '|' . $type->name ) . '" ';
        		if ( $content === 'cpt' )
        			$output .= selected( $this->cpt_selected, $type->name . '|' . $type->label, false ) . '>' . $type->label . '</option>';
        		if ( $content === 'tax' )
        			$output .= selected( $this->tax_selected, $type->name . '|' . $type->label, false ) . '>' . $type->label . '</option>';
        		if ( $content === 'term' )
        			$output .= selected( $this->term_selected, $type->slug . '|' . $type->name, false ) . '>' . $type->name . '</option>';
        		echo $output;
			endforeach;
		else :
			echo '<option value="none" disabled>No' . $content . ' found</option>';
		endif;
		echo '</select>';
	}
	
	
	/**
	 * wh_cc_creator_content_img_cb function. Add image using WP media uploader
	 * 
	 * @access public
	 * @param mixed $param -> type of content (cpt, tax, term)
	 * @return void
	 */
	public function wh_cc_creator_content_img_cb( $param ){
		$multilang = $param['multilang'];
		$content = $param['content'];
		
		if ( $content === 'cpt' )
			$content_type = $this->cpt_name;
		if ( $content === 'tax' )
			$content_type = $this->tax_name;
		if ( $content === 'term' )
			$content_type = $this->term_name;
			
		$img_path = get_option( $this->option_name . '_' . $content_type . '_content_img' );
		
		?>
		
		<input type="text" name="<?php echo $this->option_name . '_' . $content_type . '_content_img'; ?>" class="image_path regular-text ltr" value="<?php echo $img_path; ?>" id="image_path">
		<input type="button" value="Choose image" class="button button-secondary" id="upload_image"/>
		<div id="show_upload_preview">
			<?php if( ! empty( $img_path ) ) : ?>
				<p class="description"><?php _e( 'Image preview' , 'wh-cc-creator' ); ?></p>
				<?php
					$img_attr = wp_get_attachment_image_src( attachment_url_to_postid( $img_path ), 'thumbnail' );
				?>
				<img src="<?php echo $img_attr[0]; ?>" width="<?php echo $img_attr[1]; ?>" height="<?php echo $img_attr[2]; ?>">
			<?php endif; ?>
		</div>
		<?php if( ! empty( $img_path ) ) : ?>
			<input type="submit" name="remove" value="Remove image" class="button-secondary button" id="remove_image"/>
		<?php else : ?>
			<input type="submit" name="image_submit" class="save_path button button-primary" id="image_submit" value="<?php _e('Save image', 'wh-cc-creator'); ?>">
		<?php endif; ?>

	<?php
	}
	
	/**
	 * wh_cc_creator_content_text_cb function. Add image using WP media uploader
	 * 
	 * @access public
	 * @param mixed $param -> type of content (cpt, tax, term)
	 * @return void
	 */
	 public function wh_cc_creator_content_text_cb( $param ){
		$multilang = $param['multilang'];
		$content = $param['content'];
		
		if ( $content === 'cpt' )
			$content_type = $this->cpt_name;
		if ( $content === 'tax' )
			$content_type = $this->tax_name;
		if ( $content === 'term' )
			$content_type = $this->term_name;
		
		if ( TRUE == $multilang ){
			$text = get_option( $this->option_name . '_' . $content_type . '_content_text_' . ICL_LANGUAGE_CODE );
			echo '<input class="widefat" type="text" name="' . $this->option_name . '_content_text_' . ICL_LANGUAGE_CODE . '" id="' . $this->option_name . '_content_text_' . ICL_LANGUAGE_CODE . '" value="' . $text . '" />' ;
		} else {
			$text = get_option( $this->option_name . '_' . $content_type . '_content_text');
			echo '<input class="widefat" type="text" name="' . $this->option_name . '_content_text' . '" id="' . $this->option_name . '_content_text' . '" value="' . $text . '" />';
		}
		echo '<p class="description">Insert custom text to be displayed in various part of the site. <b>Be short!</b></p>';
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
	public function wh_cc_creator_content_cb( $param ){
		$multilang = $param['multilang'];
		$content = $param['content'];
		
		if ( $content === 'cpt' )
			$content_type = $this->cpt_name;
		if ( $content === 'tax' )
			$content_type = $this->tax_name;
		if ( $content === 'term' )
			$content_type = $this->term_name;
		
		if( TRUE === $multilang ){
			$editor_content[ ICL_LANGUAGE_CODE ][ $content_type ] = apply_filters( 'the_editor_content', get_option( $this->option_name . '_' . $content_type . '_content_' . ICL_LANGUAGE_CODE ), $this->option_name . '_' . $content_type . '_content_' . ICL_LANGUAGE_CODE );
			wp_editor($editor_content[ ICL_LANGUAGE_CODE ][ $content_type ], $this->option_name . '_' . $content_type . '_content_' . ICL_LANGUAGE_CODE );
		} else {
			$editor_content[ $content_type ] = apply_filters( 'the_editor_content', get_option( $this->option_name . '_' . $content_type . '_content' ), $this->option_name . '_' . $content_type . '_content' );
			wp_editor($editor_content[ $content_type ], $this->option_name . '_' . $content_type . '_content' );
		}
		$name = 'editor_content_submit';
		submit_button( 'Publish', 'primary large', $name );	
	}
	
}
