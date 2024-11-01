<?php 
/**
* Trigger this file on plugin uninstall
*
*@package: WebinaraPlugin
*/

if( !defined ('WP_UNINSTALL_PLUGIN'))
{
	exit();
}

$all_webinars = get_posts(array('post_type'=>'webinar', 'numberposts' => -1));
if(count($all_webinars) != 0)
{
	foreach($all_webinars as $all_webinar)
	{
		wp_delete_post($all_webinar->ID, true);
	}
}

$all_events = get_posts(array('post_type'=>'event', 'numberposts' => -1));
if(count($all_events) != 0)
{
	foreach($all_events as $all_event)
	{
		wp_delete_post($all_event->ID, true);
	}
}

global $wpdb;
// Prepare & excecute SQL, Delete Terms
$wpdb->query( "DELETE t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('event_categories')");

// Delete Taxonomy
$wpdb->delete( $wpdb->term_taxonomy, array( 'taxonomy' => 'event_categories' ) );

if(!empty(get_option('_webi_events_page_id')))
{
	wp_delete_post(get_option('_webi_events_page_id'), true);
}
if(!empty(get_option('_webi_webinars_page_id')))
{
	wp_delete_post(get_option('_webi_webinars_page_id'), true);
}	

$plugin_data_keys = array('_webi_webinarform_fields',
'_webi_eventform_fields',
'_webi_events_per_page',
'_webi_webinars_per_page',
'_webi_enable_webinars',
'_webi_enable_events',
'_webi_goto_refresh_token',
'_webi_goto_access_token',
'_webi_goto_account_key',
'_webi_goto_organizer_key',
'_webi_goto_connect',
'_webi_gotowebinar_secret',
'_webi_goto_refresh_token_expire_on',
'_webi_goto_access_token_expire_on',
'_webi_gotowebinar_key',
'_webi_onstream_password',
'_webi_onstream_username',
'_webi_onstream_connect',
'_webi_readytalk_passcode',
'_webi_readytalk_access_code',
'_webi_readytalk_access_number',
'_webi_readytalk_connect',
'_webi_zoom_refresh_token',
'_webi_zoom_access_token',
'_webi_zoom_connect',
'_webi_zoom_secret',
'_webi_zoom_key',
'_webi_zoom_refresh_token_expire_on',
'_webi_zoom_access_token_expire_on',
'_webi_events_page_id',
'_webi_webinars_page_id',
'_webi_banner_theme',
'_webi_publish_events',
'_webi_license_user_id',
'_webi_license_key');

foreach($plugin_data_keys as $plugin_data_key)
{
	delete_option($plugin_data_key);
}
