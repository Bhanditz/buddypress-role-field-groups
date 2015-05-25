<?php
/**
 * Plugin Name: BuddyPress Conditional Field Groups
 * Plugin URI:  http://wordpress.org/extend/plugins
 * Description: Conditionally hide BuddyPress XProfile Field Groups based on user role
 * Version:     0.1.0
 * Author:      Tanner Moushey
 * Author URI:  http://tannermoushey.com
 * License:     GPLv2+
 * Text Domain: cfg
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2015 Tanner Moushey (email : tanner@iwitnessdesign.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using grunt-wp-plugin
 * Copyright (c) 2013 10up, LLC
 * https://github.com/10up/grunt-wp-plugin
 */

// Useful global constants
define( 'CFG_VERSION', '0.1.0' );
define( 'CFG_URL',     plugin_dir_url( __FILE__ ) );
define( 'CFG_PATH',    dirname( __FILE__ ) . '/' );

require_once( CFG_PATH . 'includes/setup.php' );

/**
 * Default initialization for the plugin:
 * - Registers the default textdomain.
 */
function cfg_init() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'cfg' );
	load_textdomain( 'cfg', WP_LANG_DIR . '/cfg/cfg-' . $locale . '.mo' );
	load_plugin_textdomain( 'cfg', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'cfg_init' );

/**
 * Activate the plugin
 */
function cfg_activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
	cfg_init();
}
register_activation_hook( __FILE__, 'cfg_activate' );