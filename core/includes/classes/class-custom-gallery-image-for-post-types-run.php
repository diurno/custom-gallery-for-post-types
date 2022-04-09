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
	 * The dbc class
	 *
	 * @var		string
	 * @since   1.0.0
	 */
	public $dbClass;

	/**
	 * Our Custom_Gallery_Image_For_Post_Types_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->add_hooks();
		$this->dbClass = new Custom_Gallery_Image_For_Post_Types_Db;
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
		add_action( 'admin_init', array( $this,'custom_gallery_create_table'), 30 );
		add_action( 'admin_init', array( $this,'custom_gallery_add_metabox'), 40 );
		add_action('save_post', array( $this,'custom_gallery_save'), 50 ); 
		add_action('edit_form_after_title',array( $this,'CGPT_edit_form_after_title'), 60);
		add_shortcode( 'gallery_for_post_types', array( $this,'custom_gallery_shortcode'), 70 );

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
		wp_enqueue_script( 'cgpt-drag-srcript', CGPT_PLUGIN_URL . 'core/includes/assets/js/enableDragSort.js', array(), CGPT_VERSION, false );
		wp_localize_script( 'cgpt-backend-scripts', 'cgpt', array(
			'plugin_name'   	=> __( CGPT_NAME, 'custom-gallery-image-for-post-types' ),
		));
		wp_enqueue_script( 'fontawesome_cgpt', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js', null, null, true );

		$dataToBePassed = array(
			'ajax_url'            => admin_url('admin-ajax.php')
		);
		wp_localize_script( 'cgpt-backend-scripts', 'php_vars', $dataToBePassed );
	
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
		$this->dbClass->createTables();
	}

	public function settings_panel( $admin_bar ) {

		add_menu_page( 'Custom Gallery Settings', 'Settings', 'manage_options', 'CGPT-plugin', 
                            array(&$this, 'cgpt_setting_render')); 

	}

	public function cgpt_setting_render(  ) {
		$settingClass = new Custom_Gallery_Image_For_Post_Types_Settings;

		$postTypesNames =  $settingClass->get_post_types();
		$html_form = '<div class="wrapper-form">';
		$html_form .= '<form id="post-types-form">';
		$html_form .= '<div class="row">';
		$html_form .= '<h1>Custom Gallery Post Types</h1>';
		$html_form .= '<p>Select the Custom Post types you want to add the Custom Gallery.</p>';
		$html_form .= '</div>';
		$html_form .= '<div class="row">';
		$html_form .= '<select class="forms post-type-dropdown" name="postType">';
        $html_form .= '<option value="0">Select a Form</option>';            
        foreach($postTypesNames as $item => $value) {
            $html_form .= '<option value="'.$value.'">'.$item.'</option>';            
        }
        $html_form .= '</select>';
		$html_form .= '</div>';
		$html_form .= '<div class="row">';
		$html_form .= '<input type="button" class="button" id="post-type-submit" value="Save" />';
		$html_form .= '</div>';
		$html_form .= '</form>';
		$html_form .= '<div class="overlay"></div>';
		$html_form .= '</div>';
		echo $html_form;

	}


	public function getPostTypesSelected() {
		$postTypes = $this->dbClass->getThePostTypeSeleted();
		$postTypesArray = array();
		foreach($postTypes as $index => $value ) {
			$postTypesArray[$index] = $value->post_type;
		}
		
		return $postTypesArray;
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
			$this->getPostTypesSelected(), // how to set an array here???
			'normal',
			'high'
		);
	}

	function print_admin_gallery($gallery_images) {
		$gallery_html = '';
		$gallery_html .= '<div id="CGPT_gallery_wrapper">
						<div id="img_box_container" class="drag-sort-enable">';
						if($gallery_images) {
							foreach($gallery_images as $index => $image) {
									
								$gallery_html .=  '<div class="gallery_single_row ">
												<div class="gallery_area image_container ">
													<img class="gallery_img_img" src="'. $image['image_url'] .'" />
													<input type="hidden" class="meta_image_url" name="gallery[image_url][]" value="'. $image['image_url'] .'" />
													<input type="hidden" class="meta_image_id" name="gallery[image_id][]" value="'. $image['image_id'] .'" />
												</div>
												<span class="button cgpt-remove"  title="Remove"/><i class="fas fa-trash-alt"></i></span>											
											</div>';
							}
						}
						 
		$gallery_html .= '<div id="add_gallery_single_row">
							<input class="button cgpt-add-image" type="button" value="+"  title="Add image"/>
						</div>';
		$gallery_html .= '</div>';
		$gallery_html .= '<input type="hidden" class="image_count" name="image_count" value="'.count($gallery_images).'" />';
		$gallery_html .= '</div>';

		return $gallery_html;

	}
	
		
	function custom_gallery_add_metabox_callback() {
		wp_nonce_field( basename(__FILE__), 'sample_nonce' );
		global $post;

		$gallery_images = get_post_meta( $post->ID, 'gallery_data', true );
		echo $this->print_admin_gallery($gallery_images);
		
	}

	function custom_gallery_save($post_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		{
			return;
		}
		$is_autosave = wp_is_post_autosave($post_id);
		$is_revision = wp_is_post_revision($post_id);
		$is_valid_nonce = (isset($_POST['sample_nonce']) && wp_verify_nonce($_POST['sample_nonce'], basename(__FILE__))) ? 'true' : 'false';
	
		if ($is_autosave || $is_revision || !$is_valid_nonce)
		{
			return;
		}
		if (!current_user_can('edit_post', $post_id))
		{
			return;
		}
	
		if ( ! in_array($_POST['post_type'], $this->getPostTypesSelected()))
			return;
			
		if ($_POST['gallery'])
		{

			// Build array for saving post meta
			foreach($_POST['gallery']['image_url'] as $index => $value)	{
				$gallery_data[$index]['image_url'] = $_POST['gallery']['image_url'][$index];
				$gallery_data[$index]['image_id'] = $_POST['gallery']['image_id'][$index];
			}
	
			if ($gallery_data) 
			{
				update_post_meta($post_id, 'gallery_data', $gallery_data);
			} 
			else 
			{   
				delete_post_meta($post_id, 'gallery_data');
			}

		}
		// Nothing received, all fields are empty, delete option
		else
		{
			delete_post_meta($post_id, 'gallery_data');
		}
	}

	public function getImagesIds($imgArray) {
		$idsChain = '';
		foreach($imgArray as $index => $image) {
			$idsChain .= $image['image_id'].',';
		}
		return $idsChain;
	}

	public function custom_gallery_shortcode($atts) {
		$default_atts = array(
			'post_id' => ''
		);
		$params = shortcode_atts( $default_atts, $atts );
		$postImages = get_post_meta($params['post_id'],'gallery_data');
		
		ob_start();
		do_action( 'cgpt_print_slider_template', $postImages[0] );
		return ob_get_clean();

		// $gallery_shortcode = '[gallery ids="' . $this->getImagesIds($postImages[0]) . '" link="file" columns="4" itemtag="div" icontag="span" captiontag="p"]';
		// $gallery           = apply_filters( 'epl_property_gallery_shortcode', $gallery_shortcode, 1);
		// echo do_shortcode( $gallery ); // phpcs:ignore
	}

	// function to add meta_box after title, i think it dosent work
	public function CGPT_edit_form_after_title() {
		global $post, $wp_meta_boxes;
		do_meta_boxes(get_current_screen(), 'advanced', $post);
		unset($wp_meta_boxes[get_post_type($post)]['advanced']);
	}

} // end of the class
