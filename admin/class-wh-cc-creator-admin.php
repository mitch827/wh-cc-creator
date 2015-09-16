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
	private $img_path = FALSE;		//img path container

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
		$this->img_path = get_option( $this->option_name . '_' . $this->cpt_name . '_content_img' );
		
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
		wp_enqueue_style('thickbox'); //Provides the styles needed for this window.

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
		wp_localize_script( $this->plugin_name, 'cpt', $this->cpt_name );
		wp_localize_script( $this->plugin_name, 'img_path', $this->img_path );
		// Also adds a check to make sure `wp_enqueue_media` has only been called once.
		// @see: http://core.trac.wordpress.org/ticket/22843
		if ( ! did_action( 'wp_enqueue_media' ) )
			wp_enqueue_media();

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
			array( $this, $this->option_name . '_tax_creator_cb'),
			$this->plugin_name . '_add_tax',
			$this->option_name . '_add_tax',
			$param = array(
				'label_for' 	=> $this->option_name . '_select_cpt',
			)
		);
		register_setting( $this->plugin_name . '_add_tax', $this->option_name . '_tax_creator' );
		
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
		if ( isset( $this->cpt_selected ) && !empty( $this->cpt_selected ) ){
			
			//Add image uploader
			add_settings_field(
			    $this->option_name . '_' . $this->cpt_name . '_content_img',
			    $this->cpt_label . ' ' .  __( 'image', 'wh-cc-creator' ),
			    array( $this, $this->option_name . '_cpt_content_img_cb' ),
			    $this->plugin_name . '_cpt_edit',
			    $this->option_name . '_cpt_edit',
			    $param = array( 
			    	'label_for' => $this->option_name . '_' . $this->cpt_name . '_content_img',
			    	'multilang' => false
			    )
			);
			register_setting( $this->plugin_name . '_cpt_edit', $this->option_name . '_' . $this->cpt_name . '_content_img' );
			
			//Add CPT content editor
			if ( defined( 'ICL_LANGUAGE_CODE' ) ){
				add_settings_field(
				    $this->option_name . '_' . $this->cpt_name . '_content_' . ICL_LANGUAGE_CODE,
				    $this->cpt_label . ' ' .  __( 'content in', 'wh-cc-creator' ) . ' ' . ICL_LANGUAGE_NAME,
				    array( $this, $this->option_name . '_cpt_content_cb' ),
				    $this->plugin_name . '_cpt_edit',
				    $this->option_name . '_cpt_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->cpt_name . '_content_' . ICL_LANGUAGE_CODE,
				    	'multilang' => true
				    )
				);
				register_setting( $this->plugin_name . '_cpt_edit', $this->option_name . '_' . $this->cpt_name . '_content_' . ICL_LANGUAGE_CODE );
			} else {
				add_settings_field(
				    $this->option_name . '_' . $this->cpt_name . '_content',
				    $this->cpt_label . ' ' .  __( 'content', 'wh-cc-creator' ),
				    array( $this, $this->option_name . '_cpt_content_cb' ),
				    $this->plugin_name . '_cpt_edit',
				    $this->option_name . '_cpt_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->cpt_name . '_content',
				    	'multilang' => false
				    )
				);
				register_setting( $this->plugin_name . '_cpt_edit', $this->option_name . '_' . $this->cpt_name . '_content' );
			}
		}
		
		//TAXONOMY editor
		if ( isset( $this->tax_selected ) && !empty( $this->tax_selected ) ){
		
			if ( defined( 'ICL_LANGUAGE_CODE' ) ){
				add_settings_field(
				    $this->option_name . '_' . $this->tax_name . '_content_' . ICL_LANGUAGE_CODE,
				    $this->tax_label . ' ' .  __( 'content in', 'wh-cc-creator' ) . ' ' . ICL_LANGUAGE_NAME,
				    array( $this, $this->option_name . '_tax_content_cb' ),
				    $this->plugin_name . '_tax_edit',
				    $this->option_name . '_tax_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->tax_name . '_content_' . ICL_LANGUAGE_CODE,
				    	'multilang' => true
				    )
				);
				register_setting( $this->plugin_name . '_tax_edit', $this->option_name . '_' . $this->tax_name . '_content_' . ICL_LANGUAGE_CODE );
			} else {
				add_settings_field(
				    $this->option_name . '_' . $this->tax_name . '_content',
				    $this->tax_label . ' ' .  __( 'content', 'wh-cc-creator' ),
				    array( $this, $this->option_name . '_tax_content_cb' ),
				    $this->plugin_name . '_tax_edit',
				    $this->option_name . '_tax_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->tax_name . '_content',
				    	'multilang' => false
				    )
				);
				register_setting( $this->plugin_name . '_tax_edit', $this->option_name . '_' . $this->tax_name . '_content' );
			}
		}
		
		//TAXONOMY TERM editor
		
		
		if ( isset( $this->term_selected ) && !empty( $this->term_selected ) ){	
		
			if ( defined( 'ICL_LANGUAGE_CODE' ) ){
				add_settings_field(
				    $this->option_name . '_' . $this->term_name . '_content_' . ICL_LANGUAGE_CODE,
				    $this->term_label  . ' ' .  __( 'content in', 'wh-cc-creator' ) . ' ' . ICL_LANGUAGE_NAME,
				    array( $this, $this->option_name . '_term_content_cb' ),
				    $this->plugin_name . '_term_edit',
				    $this->option_name . '_term_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->term_name . '_content_' . ICL_LANGUAGE_CODE,
				    	'multilang' => true
				    )
				);
				register_setting( $this->plugin_name . '_term_edit', $this->option_name . '_' . $this->term_name . '_content_' . ICL_LANGUAGE_CODE );
			} else {
				add_settings_field(
				    $this->option_name . '_' . $this->term_name . '_content',
				    $this->term_label  . ' ' .  __( 'content', 'wh-cc-creator' ),
				    array( $this, $this->option_name . '_term_content_cb' ),
				    $this->plugin_name . '_term_edit',
				    $this->option_name . '_term_edit',
				    $param = array( 
				    	'label_for' => $this->option_name . '_' . $this->term_name . '_content',
				    	'multilang' => false
				    )
				);
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
		switch ( $section_passed['id'] ) :
			case  $this->option_name . '_add_tax':
				printf( __( '<p>%s: add custom taxonomies.</p>', 'wh-cc-creator' ), $section_passed['title'] );
				break;
			case  $this->option_name . '_add_cpt':
				printf( __( '<p>%s: add custom tpost types.</p>', 'wh-cc-creator' ), $section_passed['title'] );
				break;
			case  $this->option_name . '_tax_edit':
				printf( __( '<p>%s: customize custom taxonomies archive page.</p>', 'wh-cc-creator' ), $section_passed['title'] );
				break;
			case  $this->option_name . '_term_edit':
				printf( __( '<p>%s: customize custom taxonomy terms archive page.</p>', 'wh-cc-creator' ), $section_passed['title'] );
				break;
			case  $this->option_name . '_cpt_edit':
				printf( __( '<p>%s: customize custom post types archive page.</p>', 'wh-cc-creator' ), $section_passed['title'] );
				break;
	    endswitch;
	}
	
	public function wh_cc_creator_tax_creator_cb(){
		?>
			
		<?php
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
		$cpt_select = get_option( $this->option_name . '_select_cpt' );

		echo '<select name="' . $this->option_name . '_select_cpt' .'" onchange=" this.form.submit(); ">';
		echo '<option selected="true" disabled>Select:</option>';
		if( $post_types ) :
			foreach( $post_types as $cpt ) :
        		echo '<option value="' . $cpt->name . '|' . $cpt->label . '" ' . selected( $cpt_select, $cpt->name . '|' . $cpt->label, false ) . '>' . $cpt->label . '</option>';
			endforeach;
		else :
			echo '<option value="none" disabled>No CPT found</option>';
		endif;
		echo '</select>';
	}
	
	public function wh_cc_creator_tax_select_cb( $param ){
		$taxonomies = $param['taxonomies'];
		$tax_select = get_option( $this->option_name . '_select_tax' );

		echo '<select name="' . $this->option_name . '_select_tax' .'" onchange=" this.form.submit(); ">';
		echo '<option selected="true" disabled>Select:</option>';
		if( $taxonomies ) :
			foreach(  $taxonomies as $tax ) :
        		echo '<option value="' . $tax->name . '|' . $tax->label . '" ' . selected( $tax_select, $tax->name . '|' . $tax->label, false ) . '>' . $tax->label . '</option>';
			endforeach;
		else :
			echo '<option value="none" disabled>No Taxonomies found</option>';
		endif;
		echo '</select>';
	}
	
	public function wh_cc_creator_term_select_cb( $param ){
		$terms = $param['tax_terms'];
		$term_select = get_option( $this->option_name . '_select_term' );
		
		echo '<select name="' . $this->option_name . '_select_term' .'" onchange=" this.form.submit(); ">';
		echo '<option selected="true" disabled>Select:</option>';
		if( $terms ) :
			foreach(  $terms as $term ) :
        		echo '<option value="' . $term->slug . '|' . $term->name . '" ' . selected( $term_select, $term->slug . '|' . $term->name, false ) . '>' . $term->name . '</option>';
			endforeach;
		else :
			echo '<option value="none" disabled>No Taxonomies terms found</option>';
		endif;
		echo '</select>';
	}
	
	/**
	 * wh_cc_creator_cpt_content_img_cb function. Add image using WP media uploader
	 * 
	 * @access public
	 * @param mixed $param
	 * @return void
	 */
	public function wh_cc_creator_cpt_content_img_cb( $param ){
		$multilang = $param['multilang'];
		
		?>
		
		<input type="text" name="<?php echo $this->option_name . '_' . $this->cpt_name . '_content_img'; ?>" class="image_path regular-text ltr" value="<?php echo $this->img_path; ?>" id="image_path">
		<input type="button" value="Choose image" class="button-primary" id="upload_image"/>
		<div id="show_upload_preview">
			<?php if( ! empty( $this->img_path ) ) : ?>
				<p class="description"><?php _e( 'Image preview' , 'wh-cc-creator' ); ?></p>
				<img src="<?php echo $this->img_path; ?>">
			<?php endif; ?>
		</div>
		<?php if( ! empty( $this->img_path ) ) : ?>
			<input type="submit" name="remove" value="Remove image" class="button-secondary" id="remove_image"/>
		<?php else : ?>
			<input type="submit" name="image_submit" class="save_path button-primary" id="image_submit" value="<?php _e('Save image', 'wh-cc-creator'); ?>">
		<?php endif; ?>

	<?php
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
		$multilang = $param['multilang'];
		
		if( $multilang ){
			$cpt_content[ ICL_LANGUAGE_CODE ][ $this->cpt_name ] = apply_filters( 'the_editor_content', get_option( $this->option_name . '_' . $this->cpt_name . '_content_' . ICL_LANGUAGE_CODE ), $this->option_name . '_' . $this->cpt_name . '_content_' . ICL_LANGUAGE_CODE );
			wp_editor($cpt_content[ ICL_LANGUAGE_CODE ][ $this->cpt_name ], $this->option_name . '_' . $this->cpt_name . '_content_' . ICL_LANGUAGE_CODE );
		} else {
			echo '<p class="wp-ui-notification"><strong>' . __('Attention!', 'wh-cc-creator' ) . '</strong> ' . __( 'WPML is not installed, content won\'t be multilingual.', 'wh-cc-creator' ) . '</p><br>';
			$cpt_content[ $this->cpt_name ] = apply_filters( 'the_editor_content', get_option( $this->option_name . '_' . $this->cpt_name . '_content' ), $this->option_name . '_' . $this->cpt_name . '_content' );
			wp_editor($cpt_content[ $this->cpt_name ], $this->option_name . '_' . $this->cpt_name . '_content' );
		}
		$name = 'cpt_content_submit';
		submit_button( 'Submit', 'primary', $name );	
	}
	
	public function wh_cc_creator_tax_content_cb( $param ){
		$multilang = $param['multilang'];
		
		if( $multilang ){
			$tax_content[ ICL_LANGUAGE_CODE ][ $this->tax_name ] = apply_filters( 'the_editor_content', get_option( $this->option_name . '_' . $this->tax_name . '_content_' . ICL_LANGUAGE_CODE ), $this->option_name . '_' . $this->tax_name . '_content_' . ICL_LANGUAGE_CODE );
			wp_editor($tax_content[ ICL_LANGUAGE_CODE ][ $this->tax_name ], $this->option_name . '_' . $this->tax_name . '_content_' . ICL_LANGUAGE_CODE );
		} else {
			echo '<p class="wp-ui-notification"><strong>' . __('Attention!', 'wh-cc-creator' ) . '</strong> ' . __( 'WPML is not installed, content won\'t be multilingual.', 'wh-cc-creator' ) . '</p><br>';
			$tax_content[ $this->tax_name ] = apply_filters( 'the_editor_content', get_option( $this->option_name . '_' . $this->tax_name . '_content' ), $this->option_name . '_' . $this->tax_name . '_content' );
			wp_editor($tax_content[ $this->tax_name ], $this->option_name . '_' . $this->tax_name . '_content' );
		}
		$name = 'tax_content_submit';
		submit_button( 'Submit', 'primary', $name );	
	}
	
	public function wh_cc_creator_term_content_cb( $param ){
		$multilang = $param['multilang'];
		
		if( $multilang ){
			$term_content[ ICL_LANGUAGE_CODE ][ $this->term_name ] = apply_filters( 'the_editor_content', get_option( $this->option_name . '_' . $this->term_name . '_content_' . ICL_LANGUAGE_CODE ), $this->option_name . '_' . $this->term_name . '_content_' . ICL_LANGUAGE_CODE );
			wp_editor($term_content[ ICL_LANGUAGE_CODE ][ $this->term_name ], $this->option_name . '_term_content_' . ICL_LANGUAGE_CODE . '_' . $this->term_name );
		} else {
			echo '<p class="wp-ui-notification"><strong>' . __('Attention!', 'wh-cc-creator' ) . '</strong> ' . __( 'WPML is not installed, content won\'t be multilingual.', 'wh-cc-creator' ) . '</p><br>';
			$term_content[ $this->term_name ] = apply_filters( 'the_editor_content', get_option( $this->option_name . '_' . $this->term_name . '_content' ), $this->option_name . '_' . $this->term_name . '_content' );
			wp_editor($term_content[ $this->term_name ], $this->option_name . '_' . $this->term_name . '_content' );
		}
		$name = 'term_content_submit';
		submit_button( 'Submit', 'primary', $name );	
	}
	
	
}
