<?php
/**
 * Custom Gallery Image For Post Types
 *
 * @package       CGPT
 * @author        federico cadierno
 * @license       gplv2
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Custom Gallery Image For Post Types
 * Plugin URI:    https://federicocadierno.com
 * Description:   Gallery image for custom post types
 * Version:       1.0.0
 * Author:        federico cadierno
 * Author URI:    https://federicocadierno.com
 * Text Domain:   custom-gallery-image-for-post-types
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with Custom Gallery Image For Post Types. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
// Plugin name
define( 'CGPT_NAME',			'Custom Gallery Image For Post Types' );

// Plugin version
define( 'CGPT_VERSION',		'1.0.0' );

// Plugin Root File
define( 'CGPT_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'CGPT_PLUGIN_BASE',	plugin_basename( CGPT_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'CGPT_PLUGIN_DIR',	plugin_dir_path( CGPT_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'CGPT_PLUGIN_URL',	plugin_dir_url( CGPT_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once CGPT_PLUGIN_DIR . 'core/class-custom-gallery-image-for-post-types.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  federico cadierno
 * @since   1.0.0
 * @return  object|Custom_Gallery_Image_For_Post_Types
 */
function CGPT() {
	return Custom_Gallery_Image_For_Post_Types::instance();
}

CGPT();
