<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.webheroes.it
 * @since      1.0.0
 *
 * @package    Wh_Cc_Creator
 * @subpackage Wh_Cc_Creator/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	
	<?php
	    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'add_tax';
		if( isset( $_GET[ 'tab' ] ) ) {
		    $active_tab = $_GET[ 'tab' ];
		} // end if
	?>
	
	<?php if( isset( $_GET['settings-updated'] ) ) : ?>
		<div id="message" class="updated">
			<p><strong><?php _e( 'Settings saved.' ) ?></strong></p>
		</div>
	<?php endif; ?>
	
	<h3 class="nav-tab-wrapper">
		<a href="?page=wh-cc-creator&tab=add_tax" class="nav-tab <?php echo $active_tab == 'add_tax' ? 'nav-tab-active' : ''; ?>"><?php _e('Edit custom taxonomy', 'wh-cc-creator'); ?></a>
		<a href="?page=wh-cc-creator&tab=add_cpt" class="nav-tab <?php echo $active_tab == 'add_cpt' ? 'nav-tab-active' : ''; ?>"><?php _e('Edit custom post type', 'wh-cc-creator'); ?></a>
		<a href="?page=wh-cc-creator&tab=tax_edit" class="nav-tab <?php echo $active_tab == 'tax_edit' ? 'nav-tab-active' : ''; ?>"><?php _e('Taxonomies archives editor', 'wh-cc-creator'); ?></a>
		<a href="?page=wh-cc-creator&tab=term_edit" class="nav-tab <?php echo $active_tab == 'term_edit' ? 'nav-tab-active' : ''; ?>"><?php _e('Terms archives editor', 'wh-cc-creator'); ?></a>
		<a href="?page=wh-cc-creator&tab=cpt_edit" class="nav-tab <?php echo $active_tab == 'cpt_edit' ? 'nav-tab-active' : ''; ?>"><?php _e('CPT archives editor', 'wh-cc-creator'); ?></a>
	</h3>
	
	<form action="options.php" method="post" action="#">
        <?php
	        if( $active_tab == 'add_tax' ) {
	            settings_fields( $this->plugin_name . '_add_tax');
	            do_settings_sections( $this->plugin_name . '_add_tax' );
	        }
	        if( $active_tab == 'add_cpt' ) {
	            settings_fields( $this->plugin_name . '_add_cpt');
	            do_settings_sections( $this->plugin_name . '_add_cpt' );
	        }
	        if( $active_tab == 'tax_edit' ) {
		        settings_fields( $this->plugin_name . '_tax_edit');
	            do_settings_sections( $this->plugin_name . '_tax_edit' );
	        }
	        if( $active_tab == 'term_edit' ) {
		        settings_fields( $this->plugin_name . '_term_edit');
	            do_settings_sections( $this->plugin_name . '_term_edit' );
	        }
	        if( $active_tab == 'cpt_edit' ) {
	            settings_fields( $this->plugin_name . '_cpt_edit');
	            do_settings_sections( $this->plugin_name . '_cpt_edit' );
	        }
        ?>
    </form>
</div>
