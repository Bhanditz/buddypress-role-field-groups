<?php
/**
 * Plugin Name: BuddyPress Role Field Groups
 * Plugin URI:  http://wordpress.org/extend/plugins
 * Description: Conditionally hide BuddyPress XProfile Field Groups based on the viewer's user role
 * Version:     0.1.0
 * Author:      Creative Commons, Tanner Moushey
 * Author URI:  https://github.com/creativecommons http://tannermoushey.com
 * License:     GPLv2+
 * Text Domain: rfg
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2015 Tanner Moushey (email : tanner@iwitnessdesign.com)
 * Modifications copyright (c) 2017 Creative Commons
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
define( 'RFG_VERSION', '0.2.0' );
define( 'RFG_URL',     plugin_dir_url( __FILE__ ) );
define( 'RFG_PATH',    dirname( __FILE__ ) . '/' );

require_once( RFG_PATH . 'includes/setup.php' );

/**
 * Default initialization for the plugin:
 * - Registers the default textdomain.
 */
function rfg_init() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'rfg' );
	load_textdomain( 'rfg', WP_LANG_DIR . '/rfg/rfg-' . $locale . '.mo' );
	load_plugin_textdomain( 'rfg', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'rfg_init' );

/**
 * Activate the plugin
 */
function rfg_activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
	rfg_init();
}

register_activation_hook( __FILE__, 'rfg_activate' );
