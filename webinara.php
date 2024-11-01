<?php
/*
Plugin Name: Webinara
Description: The easiest way to create powerful webinar management system with WordPress.
Version: 1.0.1
Requires at least: 4.1
Requires PHP: 5.6
Author: Webinara 
Author URI: https://www.webinara.com/
License: GPLv3
Text Domain: webinara
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit;
 
define( 'WEBINARA_VERSION', '1.0.0' );
define( 'WEBINARA_API_URL', 'https://www.webinara.com/moduleinfo.php' );

function webi_activate()
{		
	//flush_rewrite_rules();
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-webinara-activator.php';
	Webinara_Activator::webi_activate_action();
}

function webi_deactivate()
{
	//flush_rewrite_rules();
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-webinara-deactivator.php';
	Webinara_Deactivator::webi_deactivate_action();
}

//activation
register_activation_hook(__FILE__ , 'webi_activate');

//deactivation
register_deactivation_hook('__FILE__', 'webi_deactivate');

require_once plugin_dir_path(__FILE__) . 'includes/class-webinara.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-webinara-meta-box.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-webinara-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-webinara-field-editor.php';

add_filter('single_template', 'webi_event_template');

function webi_event_template($single) {

    global $post;

    /* Checks for single template by post type */
    if ( $post->post_type == 'webinar' ) {
        if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/single-webinar.php' ) ) {
            return plugin_dir_path( __FILE__ ) . 'templates/single-webinar.php';
        }
    }
	
	 if ( $post->post_type == 'event' ) {
        if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/single-event.php' ) ) {
            return plugin_dir_path( __FILE__ ) . 'templates/single-event.php';
        }
    }

    return $single;

}