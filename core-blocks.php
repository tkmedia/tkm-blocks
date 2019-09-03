<?php
/**
 * Plugin Name: TKM Blocks
 * Description: This contains all your site's core blocks so that it is theme independent. <strong>It should always be activated</strong>.
 * Version:     1.0.1
 * Text Domain: tkm-blocks
 * Author:      Tal Katz TKMedia.co.il
 * Domain Path: /languages/

 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 2, as published by the
 * Free Software Foundation.  You may NOT assume that you can use any other
 * version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.
 *
 * @package    CoreBlocks
 * @since      1.0.0
 * @copyright  Copyright (c) 2019, Tal Katz TKMedia.co.il
 * @license    GPL-2.0+
 */
// Plugin directory
define( 'TKM_DIR' , plugin_dir_path( __FILE__ ) );
require_once( TKM_DIR . '/inc/general.php' );
//require_once( TKM_DIR . '/inc/wordpress-cleanup.php' );
//require_once( TKM_DIR . '/inc/kill-trackbacks.php' );
//require_once( TKM_DIR . '/inc/acf-options.php' );
//require_once( TKM_DIR . '/inc/acf-blocks.php' );
//require_once( TKM_DIR . '/inc/acf-archive.php' );
//require_once( TKM_DIR . '/inc/acf-masthead.php' );


function tkmblocks_load_plugin_textdomain() {
    load_plugin_textdomain( 'tkm-blocks', FALSE, TKM_DIR . '/languages/' );
}
add_action( 'plugins_loaded', 'tkmblocks_load_plugin_textdomain' );

// Hide the ACF admin menu item.
//add_filter('acf/settings/show_admin', 'tkm_acf_settings_show_admin');
//function tkm_acf_settings_show_admin( $show_admin ) {
//    return false;
//}