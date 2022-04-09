<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Custom_Gallery_Image_For_Post_Types_Helpers
 *
 * This class contains repetitive functions that
 * are used globally within the plugin.
 *
 * @package		CGPT
 * @subpackage	Classes/Custom_Gallery_Image_For_Post_Types_Helpers
 * @author		federico cadierno
 * @since		1.0.0
 */
class Custom_Gallery_Image_For_Post_Types_Helpers{

	/**
	 * Our Custom_Gallery_Image_For_Post_Types_Helpers constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->add_hooks();
	}

	/**
	 * Registers all WordPress and plugin related functions
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */

	private function add_hooks(){
	
		add_action( 'cgpt_print_slider_template', array( $this, 'printSliderTemplate' ), 10 );
		

	}

	/**
	 * ######################
	 * ###
	 * #### CALLABLE FUNCTIONS
	 * ###
	 * ######################
	 */


	public function printSliderTemplate( $imgArray = null)
	{
		wp_enqueue_style( 'cgpt-backend-styles', CGPT_PLUGIN_URL . 'core/includes/templates/css/style-slider.css', array(), CGPT_VERSION, 'all' );
		wp_enqueue_script( 'cgpt-backend-scripts', CGPT_PLUGIN_URL . 'core/includes/templates/js/slider-scripts.js', array(), CGPT_VERSION, false );

		$hmtl_slider = '';
		$hmtl_slider .= '<div id="slider" class="cgpt_slider">';
		$hmtl_slider .= '<div class="nagigation-controls">
							<p class="control_next"><i class="fas fa-angle-right">></i></p>
							<p class="control_prev"><i class="fas fa-angle-left"><</i></p>
						</div>';
		$hmtl_slider .= '<ul class="slider-container">';
		foreach($imgArray as $index => $image) {
			$hmtl_slider .= '<li class="slide1">';
			$hmtl_slider .= '<img src="'.$image['image_url'].'" alt="" />';
			$hmtl_slider .= '</li>';
		}
		$hmtl_slider .= '</ul>';
		$hmtl_slider .= '</div>';
		
		echo $hmtl_slider; 
	} 


}
