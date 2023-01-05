<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Custom_Gallery_Image_For_Post_Types_Settings
 *
 * This class contains all of the plugin settings.
 * Here you can configure the whole plugin data.
 *
 * @package		CGPT
 * @subpackage	Classes/Custom_Gallery_Image_For_Post_Types_Settings
 * @author		federico cadierno
 * @since		1.0.0
 */
class Custom_Gallery_Image_For_Post_Types_Settings{

	/**
	 * The plugin name
	 *
	 * @var		string
	 * @since   1.0.0
	 */
	private $plugin_name;
	public $dbClass;

	/**
	 * Our Custom_Gallery_Image_For_Post_Types_Settings constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){

		$this->plugin_name = CGPT_NAME;
		$this->dbClass = new Custom_Gallery_Image_For_Post_Types_Db;
		$this->add_settings_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### CALLABLE FUNCTIONS
	 * ###
	 * ######################
	 */

	/**
	 * Return the plugin name
	 *
	 * @access	public
	 * @since	1.0.0
	 * @return	string The plugin name
	 */
	public function get_plugin_name(){
		return apply_filters( 'CGPT/settings/get_plugin_name', $this->plugin_name );
	}

	
	/**
	 * Return the existing post types
	 *
	 * @access	public
	 * @since	1.0.0
	 * @return	array post types array
	 */
	function get_post_types() {
		
		$args = array(
			'public'   => true,
			'_builtin' => false
		);
  
		$output = 'names'; // 'names' or 'objects' (default: 'names')
		$operator = 'and'; // 'and' or 'or' (default: 'and')
  
		$post_types = get_post_types( $args, $output, $operator );

		return $post_types;
	}

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_settings_hooks(){	
		add_action( 'wp_ajax_save_post_types', array( &$this, 'save_post_types' ) );
	}

	public function save_post_types() {		    
		$this->dbClass->saveThePostTypeSeleted( $_POST["post_type_value"] );   		
	}




}
