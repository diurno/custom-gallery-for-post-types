<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Custom_Gallery_Image_For_Post_Types_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		CGPT
 * @subpackage	Classes/Custom_Gallery_Image_For_Post_Types_Run
 * @author		federico cadierno
 * @since		1.0.0
 */
class Custom_Gallery_Image_For_Post_Types_Run{

	/**
	 * Our Custom_Gallery_Image_For_Post_Types_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks(){
	
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_backend_scripts_and_styles' ), 20 );
		add_action( 'admin_menu', array( $this, 'settings_panel' ), 100, 1 );
		//add_action( 'admin_init', array( $this, 'add_custom_gallery_metabox' ), 30 );

		add_action( 'admin_init', array( $this,'custom_gallery_create_table'), 30 );
		add_action( 'admin_init', array( $this,'custom_gallery_add_metabox'), 40 );
	
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	 * Enqueue the backend related scripts and styles for this plugin.
	 * All of the added scripts andstyles will be available on every page within the backend.
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function enqueue_backend_scripts_and_styles() {
		wp_enqueue_style( 'cgpt-backend-styles', CGPT_PLUGIN_URL . 'core/includes/assets/css/backend-styles.css', array(), CGPT_VERSION, 'all' );
		wp_enqueue_script( 'cgpt-backend-scripts', CGPT_PLUGIN_URL . 'core/includes/assets/js/backend-scripts.js', array(), CGPT_VERSION, false );
		wp_localize_script( 'cgpt-backend-scripts', 'cgpt', array(
			'plugin_name'   	=> __( CGPT_NAME, 'custom-gallery-image-for-post-types' ),
		));
		wp_enqueue_script( 'fontawesome_cgpt', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js', null, null, true );
	}

	/**
	 * Add a new menu item to the WordPress topbar
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @param	object $admin_bar The WP_Admin_Bar object
	 *
	 * @return	void
	 */
	public function add_admin_bar_menu_items( $admin_bar ) {

		add_menu_page( 'ProjectileCRM', 'ProjectileCRM', 'manage_options', 'projectilecrm-plugin', 
                            array(&$this, 'procrm_api_key_setting_render')); 

	}


	public function custom_gallery_create_table( ) {
		//$this->dbClass = Procrm_DB_Class::get_instance();
		$DBClass = new Custom_Gallery_Image_For_Post_Types_Db;
		$DBClass->createTables();


	}

	public function settings_panel( $admin_bar ) {

		add_menu_page( 'Custom Gallery Settings', 'Settings', 'manage_options', 'CGPT-plugin', 
                            array(&$this, 'cgpt_setting_render')); 

	}

	public function cgpt_setting_render(  ) {
		$settingClass = new Custom_Gallery_Image_For_Post_Types_Settings;

		$postTypesNames =  $settingClass->get_post_types();
		$html_form = '<div class="wrapper-form">';
		$html_form .= '<form id="api-key-form">';
		$html_form .= '<div class="row">';
		$html_form .= '<h1>Custom Gallery Post Types</h1>';
		$html_form .= '<p>Select the Custom Post types you want to add the Custom Gallery.</p>';
		$html_form .= '</div>';
		$html_form .= '<div class="row">';
		$html_form .= '<select class="forms" name="CF7">';
        $html_form .= '<option value="0">Select a Form</option>';            
        foreach($postTypesNames as $item => $value) {
            $html_form .= '<option value="'.$value.'">'.$item.'</option>';            
        }
        $html_form .= '</select>';
		$html_form .= '</div>';
		$html_form .= '<div class="row">';
		$html_form .= '<input type="button" class="button" id="apikey-submit" value="Save" />';
		$html_form .= '</div>';
		$html_form .= '</form>';
		$html_form .= '<div class="overlay"></div>';
		$html_form .= '</div>';
		echo $html_form;

	}

	/**
	 * Create a metabox for the custom type choosen
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 *
	 * @return	void
	 */
	
	function custom_gallery_add_metabox() {
		add_meta_box(
			'custom_gallery_post_type',
			'Gallery',
			array(&$this, 'custom_gallery_add_metabox_callback'),
			'property', // replace this value with value saved on DB
			'normal',
			'high'
		);
	}
	
		
	function custom_gallery_add_metabox_callback() {
		wp_nonce_field( basename(__FILE__), 'sample_nonce' );
		global $post;
		$gallery_data = get_post_meta( $post->ID, 'gallery_data', true );
		//print_r($gallery_data);
		$gallery = '';
		$gallery .= '<div id="gallery_wrapper">
						<div id="img_box_container">';
						if ( isset( $gallery_data['image_url'] ) ){
							for( $i = 0; $i < count( $gallery_data['image_url'] ); $i++ ){
									
								$gallery .=  '<div class="gallery_single_row dolu">
												<div class="gallery_area image_container ">
													<img class="gallery_img_img" src="'. $gallery_data["image_url"][$i] .'" height="55" width="55" />
													<input type="hidden" class="meta_image_url" name="gallery[image_url][]" value="'. $gallery_data["image_url"][$i] .'" />
												</div>
												<span class="button cgpt-remove"  title="Remove"/><i class="fas fa-trash-alt"></i></span>											
											</div>';
			
							}
						} 
		$gallery .= '</div>';
		$gallery .=  '<div style="display:none" id="master_box">
					<div class="gallery_single_row">
						<div class="gallery_area image_container">
							<input class="meta_image_url" value="" type="hidden" name="gallery[image_url][]" />
						</div>
						<span class="button cgpt-remove"  title="Remove"/><i class="fas fa-trash-alt"></i></span>
					</div>
				</div>';
		$gallery .= '<div id="add_gallery_single_row">
						<input class="button cgpt-add-image" type="button" value="+"  title="Add image"/>
					</div>';
		$gallery .= '</div>';

		echo $gallery;
	}

}
