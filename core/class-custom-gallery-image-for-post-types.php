<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'Custom_Gallery_Image_For_Post_Types' ) ) :

	/**
	 * Main Custom_Gallery_Image_For_Post_Types Class.
	 *
	 * @package		CGPT
	 * @subpackage	Classes/Custom_Gallery_Image_For_Post_Types
	 * @since		1.0.0
	 * @author		federico cadierno
	 */
	final class Custom_Gallery_Image_For_Post_Types {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Custom_Gallery_Image_For_Post_Types
		 */
		private static $instance;

		/**
		 * CGPT helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Custom_Gallery_Image_For_Post_Types_Helpers
		 */
		public $helpers;

		/**
		 * CGPT settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Custom_Gallery_Image_For_Post_Types_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'custom-gallery-image-for-post-types' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'custom-gallery-image-for-post-types' ), '1.0.0' );
		}

		/**
		 * Main Custom_Gallery_Image_For_Post_Types Instance.
		 *
		 * Insures that only one instance of Custom_Gallery_Image_For_Post_Types exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Custom_Gallery_Image_For_Post_Types	The one true Custom_Gallery_Image_For_Post_Types
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Custom_Gallery_Image_For_Post_Types ) ) {
				self::$instance					= new Custom_Gallery_Image_For_Post_Types;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Custom_Gallery_Image_For_Post_Types_Helpers();
				self::$instance->settings		= new Custom_Gallery_Image_For_Post_Types_Settings();

				//Fire the plugin logic
				new Custom_Gallery_Image_For_Post_Types_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'CGPT/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
			require_once CGPT_PLUGIN_DIR . 'core/includes/classes/class-custom-gallery-image-for-post-types-helpers.php';
			require_once CGPT_PLUGIN_DIR . 'core/includes/classes/class-custom-gallery-image-for-post-types-settings.php';

			require_once CGPT_PLUGIN_DIR . 'core/includes/classes/class-custom-gallery-image-for-post-types-run.php';
			require_once CGPT_PLUGIN_DIR . 'core/includes/classes/class-custom-gallery-image-for-post-types-db.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'custom-gallery-image-for-post-types', FALSE, dirname( plugin_basename( CGPT_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.