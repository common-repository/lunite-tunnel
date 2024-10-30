<?php
/**
 * Lunite
 *
 * @package           Lunite Tunnel
 * @author            Keshan Sandeepa Perera
 * @copyright         Keshan
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Lunite Tunnel
 * Plugin URI:        https://github.com/keshansandeepa
 * Description:       Lunite Tunnel For SMS Gateways.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Keshan Sandeepa
 * Author URI:        https://github.com/keshansandeepa
 * Text Domain:       lunite-tunnel
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

    Copyright 2020 Lunite Tunnel.
*/


/**
 * Exit if accessed directly.
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Check if the Woocommerce Install
 */
if (! in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    echo 'Hi there!  You Need To Install WooCommerce In Order To Function Lunite Tunnel GateWay plugin.';
    exit();
}
/**
 * Defining Plugin Version Other Details
 */
define('LUNITE_PLUGIN_NAME', plugin_basename(__FILE__));
define('Lunite_VERSION', '1.0.0');
define('Lunite_SLUG', 'lunite_plugin_admin_slug');
define('Lunite_MINIMUM_WP_VERSION', '5.2');
define('Lunite__PLUGIN_DIR', plugin_dir_path(__FILE__));



/**
 * Requiring the Global Initialization Class
 */

require_once(Lunite__PLUGIN_DIR . 'inc/class.lunite-initialize.php');
require_once(Lunite__PLUGIN_DIR . 'inc/class.lunite-notification_trigger.php');
require_once(Lunite__PLUGIN_DIR . 'inc/class.lunite-ada-dialog.php');
require_once(Lunite__PLUGIN_DIR . 'inc/class.lunite-wordpress-api-wrapper.php');


/**
 * Checking if the lunite Initialize Class Exist
 */

if (class_exists('LuniteInitialize')) {
    $golub_plugin = new LuniteInitialize();
    $golub_plugin->luniteRegister();

    require_once(Lunite__PLUGIN_DIR . 'inc/class.lunite-plugin-activate.php');
    require_once(Lunite__PLUGIN_DIR . 'inc/class.lunite-plugin-deactivate.php');

    register_activation_hook(__FILE__, ['LunitePluginActivate','luniteActivate']);

    register_deactivation_hook(__FILE__, ['LunitePluginDeactivate','luniteDeactivate']);

    register_uninstall_hook(__FILE__, ['uninstall','luniteUninstall']);
} else {
    echo 'Hi there!  Issue With Lunite Tunnel , Immediately Contact the developer.';
    exit;
}
