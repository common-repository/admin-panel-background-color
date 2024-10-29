<?php

/**
 * @since             1.0.0
 * @package           Admin_Panel_Background_Color
 *
 * @wordpress-plugin
 * Plugin Name:       Admin Panel Background Color
 * Description:       Plugin that makes possible to customize background color of the admin panel. Option for that will appear under profile page (Path from    * admin panel main menu is Users -> Your Profile).
 * Version:           1.0.0
 * Author:            castellar120
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       admin-panel-background-color
 * Domain Path:       /languages
 */
session_start();

require "vendor/autoload.php";

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define('PLUGIN_NAME_VERSION', '1.0.0');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_admin_panel_background_color()
{
    $plugin = new Main();
    $plugin->run();
}
run_admin_panel_background_color();
