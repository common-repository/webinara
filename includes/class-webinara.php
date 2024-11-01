<?php 
/**
*
*@package: WebinaraPlugin
*/
if(!class_exists('Webinara'))
{
	class Webinara{
		
		public $plugin;
		
		function __construct() {
			$this->plugin = plugin_basename(__FILE__);
			self::webi_check_schedule_crons();
			add_action('gotowebinar_refresh_token', array($this, 'webi_gotowebinar_token_refresh'));	
			add_action( 'admin_notices', array($this,'webi_admin_notice_success') );	
		}		
		
		function webi_register()
		{
			add_action('admin_enqueue_scripts', array($this,'webi_admin_scripts'));
			add_action('wp_enqueue_scripts', array($this,'webi_front_scripts'));	
			add_action('admin_menu', array($this,'webi_admin_pages'));
			add_action('add_meta_boxes', array($this, 'webi_add_pageinfo'));
		}
		
		function webi_add_pageinfo()
		{
			global $post;

			if(!empty($post))
			{				
				if($post->ID == get_option('_webi_events_page_id') || $post->ID == get_option('_webi_webinars_page_id'))
				{
					add_meta_box(
						'product_meta', // $id
						__('Information','webinara'), // $title
						array($this, 'webi_display_webinara_information'), // $callback
						'page', // $page
						'normal', // $context
						'high'); // $priority
				}
			}
		}
		
		function webi_display_webinara_information(){
			echo "<p class='webi_pageinfo'>'.__('Do not change anything on this page, it is controlled by the Webinara plugin.','webinara').'</p>";
		}				
		
		function webi_admin_pages(){
			if(get_option('_webi_enable_webinars') != 1){
				add_menu_page( __('Events', 'webinara'), __('Events', 'webinara'), 'manage_options', 'edit.php?post_type=event', '', plugin_dir_url( __FILE__ ) . 'assets/images/logo-webinara-20x20.png', 40 );
				add_submenu_page('edit.php?post_type=event', __('All Events', 'webinara'), __('All Events', 'webinara'), 'manage_options', 'edit.php?post_type=event');
				add_submenu_page('edit.php?post_type=event', __('Add New Event', 'webinara'), __('Add New Event', 'webinara'), 'manage_options', 'post-new.php?post_type=event');
				add_submenu_page('edit.php?post_type=event', __('Event Categories', 'webinara'), __('Event Categories', 'webinara'), 'manage_options', 'edit-tags.php?taxonomy=event_categories&post_type=event');
				add_submenu_page('edit.php?post_type=event', __('Event tags', 'webinara'), __('Event tags', 'webinara'), 'manage_options', 'edit-tags.php?taxonomy=event_tag&post_type=event');
				add_submenu_page('edit.php?post_type=event', __('Settings', 'webinara'), __('Settings', 'webinara'), 'manage_options', 'webinara_plugin', array($this, 'webi_admin_settings') ,'' ,null);
			}
			else
			{
				add_menu_page( __('Events', 'webinara'), __('Events', 'webinara'), 'manage_options', 'edit.php?post_type=webinar', '', plugin_dir_url( __FILE__ ) . 'assets/images/logo-webinara-20x20.png', 40 );
				add_submenu_page('edit.php?post_type=webinar', __('All Webinars', 'webinara'), __('All Webinars', 'webinara'), 'manage_options', 'edit.php?post_type=webinar');
				add_submenu_page('edit.php?post_type=webinar', __('Add New Webinar', 'webinara'), __('Add New Webinar', 'webinara'), 'manage_options', 'post-new.php?post_type=webinar');			
				if(get_option('_webi_enable_events') == 1){
					add_submenu_page('edit.php?post_type=webinar', __('All Events', 'webinara'), __('All Events', 'webinara'), 'manage_options', 'edit.php?post_type=event');
					add_submenu_page('edit.php?post_type=webinar', __('Add New Event', 'webinara'), __('Add New Event', 'webinara'), 'manage_options', 'post-new.php?post_type=event');
				}
				add_submenu_page('edit.php?post_type=webinar', __('Event Categories', 'webinara'), __('Event Categories', 'webinara'), 'manage_options', 'edit-tags.php?taxonomy=event_categories&post_type=webinar');
				add_submenu_page('edit.php?post_type=webinar', __('Event tags', 'webinara'), __('Event tags', 'webinara'), 'manage_options', 'edit-tags.php?taxonomy=event_tag&post_type=webinar');
				add_submenu_page('edit.php?post_type=webinar', __('Dashboard', 'webinara'), __('Dashboard', 'webinara'), 'manage_options', 'webinara_dashboard', array($this, 'webi_admin_dashboard') ,'' ,null);
				add_submenu_page('edit.php?post_type=webinar', __('Settings', 'webinara'), __('Settings', 'webinara'), 'manage_options', 'webinara_plugin', array($this, 'webi_admin_settings') ,'' ,null);
			}												
		}				
		
		function webi_admin_dashboard(){
			?>
			<div class="wrap">  
				<div id="icon-themes" class="icon32"></div>  
				<h2><?php esc_html_e('Welcome to Webinara Wordpress Plugin', 'webinara') ?></h2>
				<p><?php printf( __( 'Since 2015 <a href="%s" target="_blank">Webinara.com</a> has promoted webinars from all over the world to thousands for potential attendees. Webinars from all kinds of industries and companies, from one-man band companies to large enterprises.', 'webinara' ), esc_attr('https://www.webinara.com') ); ?></p>	
					
				<p><?php printf( __('With your Webinara Event Plugin you can easily promote you own events, webinars and in-persons event on your own website.','webinara') ) ?></p>
				
				<p><?php printf( __( 'If you are using it to promote your webinars, and using one of the integrated webinars solutions, like GoToWebinar, Zoom or Readytalk, you can make the registration process seamless by upgrading to the Pro version of the plugin. 
				With Pro version enables you can also decide if you like to send your webinar to <a href="%s" target="_blank">Webinara.com</a> for increase awareness and more registrations.', 'webinara' ), esc_attr('https://www.webinara.com') ); ?></p>	

				<p><?php printf( __( 'Shot us an email <a href="mailto:%s">support@webinara.com</a> if you have new ideas or questions about the plugin.', 'webinara' ), 'support@webinara.com' ); ?></p>	
				
				<p><?php printf( __( 'Good Luck with your events.','webinara')); ?></p>
				
				<p><?php printf( __( 'Team Webinara','webinara')); ?></p>								
			</div>		
			<?php
		}
		
		function webi_admin_notice_success() {
			if(isset($_POST['active_tab']))
			{
				if($_POST['active_tab'] == 'general')
				{
					$refresh_required = 0;
					$adminurl = admin_url('edit.php?post_type=webinar&page=webinara_plugin');
					if(isset($_POST['_webi_enable_webinars']))
					{
						$enable_webinars = sanitize_text_field($_POST['_webi_enable_webinars']);
						if(get_option('_webi_enable_webinars') != $enable_webinars)
						{
							$refresh_required = 1;
						}
						update_option('_webi_enable_webinars', $enable_webinars);					
					}
					else
					{
						if(get_option('_webi_enable_webinars') == 1)
						{
							$refresh_required = 1;
						}
						delete_option('_webi_enable_webinars');
						$adminurl = admin_url('edit.php?post_type=event&page=webinara_plugin');					
					}
					if(isset($_POST['_webi_enable_events']))
					{
						$enable_events = sanitize_text_field($_POST['_webi_enable_events']);
						if(get_option('_webi_enable_events') != $enable_events)
						{
							$refresh_required = 1;
						}
						update_option('_webi_enable_events', $enable_events);					
					}
					else
					{
						if(get_option('_webi_enable_events') == 1)
						{
							$refresh_required = 1;
						}
						delete_option('_webi_enable_events');
						$refresh_required = 1;
					}
					if(isset($_POST['_webi_events_per_page']))
					{
						update_option('_webi_events_per_page',sanitize_text_field($_POST['_webi_events_per_page']));
					}
					if(isset($_POST['_webi_banner_theme']))
					{
						update_option('_webi_banner_theme',sanitize_text_field($_POST['_webi_banner_theme']));
					}
					if(isset($_POST['_webi_webinars_per_page']))
					{
						update_option('_webi_webinars_per_page',sanitize_text_field($_POST['_webi_webinars_per_page']));
					}
					
					if(isset($_POST['_webi_publish_events']))
					{
						update_option('_webi_publish_events', sanitize_text_field($_POST['_webi_publish_events']));
					}
					else
					{
						delete_option('_webi_publish_events');
					}
					if(!isset($_POST['_webi_enable_webinars']) && !isset($_POST['_webi_enable_events']))
					{
						update_option('_webi_enable_webinars', 1);
					}								
					?>
					<div class="notice notice-success is-dismissible">
						<p>
						<?php 
						if($refresh_required == 1)
						{
							printf( __( 'Saved successfully! Please click <a href="%s">here</a> for refresh menu options.', 'webinara' ), esc_attr($adminurl) );						
						}
						else
						{
							_e( 'Saved successfully!', 'webinara' );
						}
						?></p>
					</div>
					<?php					
				}
				else if($_POST['active_tab'] == 'license')
				{
					if(isset($_POST['_webi_license_key']))
					{
						update_option('_webi_license_key', sanitize_text_field($_POST['_webi_license_key']));
						$success_text = sprintf(__('Saved successfully!','webinara'));
					}
					if(isset($_POST['downgrade_license']))
					{
						delete_option('_webi_license_key');
						$success_text = sprintf(__('Downgraded successfully!','webinara'));
					}
					?>
					<div class="notice notice-success is-dismissible">
						<p>
						<?php _e( $success_text, 'webinara' ); ?></p>
					</div>
					<?php
				}
			}				
		}		
		
		function webi_admin_settings(){
			//stuff for settings								
			?>
			<div class="wrap">  
				<div id="icon-themes" class="icon32"></div>  
				<h2><?php esc_html_e('Settings', 'webinara'); ?></h2>   
				<?php  
					if ( isset( $_GET['tab'] ) ) {
						$active_tab = trim( $_GET['tab'] );
						if ( ! empty( $active_tab ) ) {
							if ( ! in_array( $active_tab, array( 'general', 'profile', 'license', 'webinara-connect' ) ) ) {
								$active_tab = 'general';
							}
						}
					} else {
						$active_tab = 'general';
					}	
																									
					$webi_license_key = get_option('_webi_license_key');
				?>  

				<h2 class="nav-tab-wrapper">  
					<a href="<?php echo admin_url('edit.php?post_type=webinar&page=webinara_plugin&tab=general') ?>" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('General', 'webinara') ?></a>  
					<?php
					$webi_license_key = get_option('_webi_license_key');
					if(!empty($webi_license_key))
					{
					?>
						<a href="<?php echo admin_url('edit.php?post_type=webinar&page=webinara_plugin&tab=profile') ?>" class="nav-tab <?php echo $active_tab == 'profile' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Profile', 'webinara') ?></a>
					<?php
					}
					?>
					<a href="<?php echo admin_url('edit.php?post_type=webinar&page=webinara_plugin&tab=license') ?>" class="nav-tab <?php echo $active_tab == 'license' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Upgrade to premium', 'webinara') ?></a>  
					<a href="<?php echo admin_url('edit.php?post_type=webinar&page=webinara_plugin&tab=webinara-connect') ?>" class="nav-tab <?php echo $active_tab == 'webinara-connect' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Connect your webinar solution', 'webinara') ?></a>  										
				</h2>
				<div class="wrap webinara-form-field-editor">									
						<?php 						
						if($active_tab == 'general')
						{																								
							$webi_enable_webinars = get_option('_webi_enable_webinars');
							$webi_enable_events = get_option('_webi_enable_events');
							$args = array(
								'sort_order' => 'asc',
								'sort_column' => 'post_title',
								'hierarchical' => 1,
								'exclude' => '',
								'include' => '',
								'meta_key' => '',
								'meta_value' => '',
								'authors' => '',
								'child_of' => 0,
								'parent' => -1,
								'exclude_tree' => '',
								'number' => '',
								'offset' => 0,
								'post_type' => 'page',
								'post_status' => 'publish'
							); 
							$webi_pages = get_pages($args);
							if(get_option('_webi_enable_webinars') != 1){
								$ptype = 'event';
							}
							else
							{
								$ptype = 'webinar';
							}
							?>
						<form method="post">										
							<table class="form-table">
								<tbody>									
									<tr valign="top" class="">
										<th scope="row"><label for="setting-webi_enable_webinars"><?php esc_html_e('Enable Webinars', 'webinara'); ?></label></th>
										<td>
											<input type="checkbox" name="_webi_enable_webinars" id="enable_webinars" value="1" <?php if(get_option('_webi_enable_webinars') == 1){ echo "checked"; } ?>>
											<p class="description"><?php esc_html_e('Ticked option to enable webinars (At least one option should be checked(either \'webinars\' or \'events\') always for running plugin properly.)', 'webinara'); ?></p>
										</td>
									</tr>
									<tr valign="top" class="">
										<th scope="row"><label for="setting-webi_enable_events"><?php esc_html_e('Enable Events', 'webinara'); ?></label></th>
										<td>
											<input type="checkbox" name="_webi_enable_events" id="enable_events" value="1" <?php if(get_option('_webi_enable_events') == 1){ echo "checked"; } ?>>
											<p class="description"><?php esc_html_e('Ticked option to enable events (At least one option should be checked(either \'webinars\' or \'events\') always for running plugin properly.)', 'webinara'); ?></p>
										</td>
									</tr>
									<?php 
									if(get_option('_webi_enable_webinars') == 1)
									{
										?>
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_per_page"><?php esc_html_e('How many webinars do you want to display in /webinars page before pagination', 'webinara'); ?></label></th>
											<td>
												<select name="_webi_webinars_per_page">
													<?php for($count1 = 1; $count1 <= 30; $count1++ ){
														if($count1%3 == 0){
															if(get_option('_webi_webinars_per_page') == $count1){
																echo '<option value="'.$count1.'" selected>'.$count1.'</option>';
															}
															else
															{
																echo '<option value="'.$count1.'">'.$count1.'</option>';
															}														
														}
													}?>
												</select>
											</td>
										</tr>
										<?php
									}
									if(get_option('_webi_enable_events') == 1)
									{
										?>	
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_events_per_page"><?php esc_html_e('How many events do you want to display in /events page before pagination', 'webinara'); ?></label></th>
											<td>
												<select name="_webi_events_per_page">
													<?php for($count1 = 1; $count1 <= 30; $count1++ ){
														if($count1%3 == 0){
															if(get_option('_webi_events_per_page') == $count1){
																echo '<option value="'.$count1.'" selected>'.$count1.'</option>';
															}
															else
															{
																echo '<option value="'.$count1.'">'.$count1.'</option>';
															}
														}
													}?>
												</select>
											</td>
										</tr>
									<?php
									}
									?>									
									<tr valign="top" class="">
										<th scope="row"><label for="setting-webi_events_per_page"><?php esc_html_e('Select event/webinar page banner and theme color', 'webinara'); ?></label></th>
										<td>
											<input type="text" name="_webi_banner_theme" class="webi_banner_theme" value="<?php if(!empty(get_option('_webi_banner_theme'))){ echo get_option('_webi_banner_theme'); } else { echo "#48335C"; } ?>">
										</td>
									</tr>
									<tr valign="top" class="">
										<th scope="row"><label for="setting-webi_publish_events"><?php echo '' .sprintf( __('Publish webinars to <a href="%1$s" target="_blank">%2$s</a>','webinara'), esc_attr( 'https://www.webinara.com' ), esc_html( 'Webinara.com.' ) ) ?></label></th>
										<td>													
											<?php 											
											if(empty(get_option('_webi_license_key')))
											{												
												echo '<a href="'.admin_url('edit.php?post_type=webinar&page=webinara_plugin&tab=license&action=register').'" class="button-primary">'.__('Upgrade to the Premium version to enable','webinara').'</a>';																												printf('<p class="description">'.__( 'Enable option to publish webinar on <a href="%s" target="_blank">Webinara.com</a> server. You can able to choose which webinar want to post or not.', 'webinara' ).'</p>', esc_attr('https://www.webinara.com') );																				
											}
											else
											{
												if(date('Y-m-d') > get_option('_webi_license_x'))
												{
													?>
													<a href="javascript:void(0);" class="button-primary renew_button" id="renew_license">Renew License</a>
													<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>	
													<p class="up_response"></p>
													<input type="hidden" name="_webi_license_key" id="license_key" value="<?php echo get_option('_webi_license_key'); ?>">
													<?php
												}
												else
												{		
													if(get_option('_webi_publish_events') == 1){ $checked_publish = "checked"; } else { $checked_publish = ""; }
													echo '<input type="checkbox" name="_webi_publish_events" id="publish_events" value="1" '.$checked_publish.'>';	
													printf('<p class="description">'.__( 'Select this option to publish your webinars to <a href="%s" target="_blank">Webinara.com</a> server. You can choose which webinars you like to publish.', 'webinara' ).'</p>', esc_attr('https://www.webinara.com') );	
												}
											}											
											?>
										</td>
									</tr>	
								</tbody>
							</table>
							<input type="hidden" name="active_tab" value="general">
							<?php submit_button(); 
						echo '</form>';
						}
						if($active_tab == 'profile')
						{
							$license_downgrade_request = wp_remote_get( add_query_arg( array(								
								'site_action' => 'retrieve_data',					
								), WEBINARA_API_URL ), array(
									'timeout' => 10,
									'headers' => array(
										'Accept' => 'application/json'
									)
								)
							);
							$license_downgrade_response = @json_decode(wp_remote_retrieve_body($license_downgrade_request), true);	
							?>
							<div class="webi_profile_section">
								<table class="form-table loading_state">															
									<tbody class="">
										<tr valign="top" class="">
											<td><a href="javascript:void(0);" id="webi_pp_button" target="_blank" class="button-primary"><?php esc_html_e('Public Profile', 'webinara'); ?></a></td>
											<td id="webi_update_mp">
												<a href="javascript:void(0);" id="update_webi_prof" class="button-primary" title="<?php esc_html_e('Update additional fields like profile image, company logo etc.', 'webinara'); ?>"><?php esc_html_e('Update public Profile', 'webinara'); ?></a>
												<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>	
												<p class="acc_response"></p>
											</td>	
										</tr>
										<tr valign="top" class="">
											<th scope="row" colspan="2"><label for="setting-webi_webinars_license"><?php esc_html_e('Profile Information', 'webinara'); ?></label></th>												
										</tr>											 
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('First Name*', 'webinara'); ?></label></th>
											<td>
												<input type="text" name="webiprofile[_webireg_fname]" class="webireg_fld" id="webireg_fname">										
											</td>
										</tr>
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Last Name*', 'webinara'); ?></label></th>
											<td>
												<input type="text" name="webiprofile[_webireg_lname]" class="webireg_fld" id="webireg_lname">										
											</td>
										</tr>											
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Job title*', 'webinara'); ?></label></th>
											<td>
												<input type="text" name="webiprofile[_webireg_jobtitle]" class="webireg_fld" id="webireg_jobtitle">										
											</td>
										</tr>
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Job level*', 'webinara'); ?></label></th>
											<td>
												<select name="webiprofile[_webireg_joblevel]" id="webireg_joblevel" class="input-select">
													<option disabled="" selected=""></option>
													<option value="Independent Consultant"><?php _e('Independent Consultant','webinara'); ?>
													</option>
													<option value="Employee (not supervisor)"><?php _e('Employee (not supervisor)','webinara'); ?></option>
													<option value="Employee (supervisor)"><?php _e('Employee (supervisor)','webinara'); ?></option>
													<option value="Manager"><?php _e('Manager','webinara'); ?></option>
													<option value="Director"><?php _e('Director','webinara'); ?></option>
													<option value="Executive/V.P."><?php _e('Executive/V.P.','webinara'); ?></option>
													<option value="Senior Executive"><?php _e('Senior Executive','webinara'); ?></option>
													<option value="CEO / President / Owner"><?php _e('CEO / President / Owner','webinara'); ?></option>
												</select>
											</td>
										</tr>
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Job function*', 'webinara'); ?></label></th>
											<td>
												<select name="webiprofile[_webireg_jobfunction]" id="webireg_jobfunction" class="input-select">
													<option disabled="" selected=""></option>
													<option value="Accounting"><?php _e('Accounting','webinara'); ?></option>
													<option value="Business Development"><?php _e('Business Development','webinara'); ?></option>
													<option value="Communication"><?php _e('Communication','webinara'); ?></option>
													<option value="Consulting"><?php _e('Consulting','webinara'); ?></option>
													<option value="Customer Service"><?php _e('Customer Service','webinara'); ?></option>
													<option value="Engineering"><?php _e('Engineering','webinara'); ?></option>
													<option value="Finance"><?php _e('Finance','webinara'); ?></option>
													<option value="Human Resources"><?php _e('Human Resources','webinara'); ?></option>
													<option value="IT"><?php _e('IT','webinara'); ?></option>
													<option value="Management"><?php _e('Management','webinara'); ?></option>
													<option value="Manufacturing/Production"><?php _e('Manufacturing/Production','webinara'); ?>
													</option>
													<option value="Marketing"><?php _e('Marketing','webinara'); ?></option>
													<option value="Operations"><?php _e('Operations','webinara'); ?></option>
													<option value="Research and Development"><?php _e('Research and Development','webinara'); ?>
													</option>
													<option value="Sales"><?php _e('Sales','webinara'); ?></option>
													<option value="Other"><?php _e('Other','webinara'); ?></option>
												</select>
											</td>
										</tr>
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Country*', 'webinara'); ?></label></th>
											<td>
												<select id="webireg_country" name="webiprofile[_webireg_country]" class="input-select" id="webireg_country">
													<option disabled="" selected=""></option>
													<option value="Afghanistan"><?php _e('Afghanistan','webinara'); ?></option>
													<option value="Aland Islands"><?php _e('Aland Islands','webinara'); ?></option>
													<option value="Albania"><?php _e('Albania','webinara'); ?></option>
													<option value="Algeria"><?php _e('Algeria','webinara'); ?></option>
													<option value="American Samoa"><?php _e('American Samoa','webinara'); ?></option>
													<option value="Andorra"><?php _e('Andorra','webinara'); ?></option>
													<option value="Angola"><?php _e('Angola','webinara'); ?></option>
													<option value="Anguilla"><?php _e('Anguilla','webinara'); ?></option>
													<option value="Antarctica"><?php _e('Antarctica','webinara'); ?></option>
													<option value="Antigua and Barbuda"><?php _e('Antigua and Barbuda','webinara'); ?></option>
													<option value="Argentina"><?php _e('Argentina','webinara'); ?></option>
													<option value="Armenia"><?php _e('Armenia','webinara'); ?></option>
													<option value="Aruba"><?php _e('Aruba','webinara'); ?></option>
													<option value="Australia"><?php _e('Australia','webinara'); ?></option>
													<option value="Austria"><?php _e('Austria','webinara'); ?></option>
													<option value="Azerbaijan"><?php _e('Azerbaijan','webinara'); ?></option>
													<option value="Bahamas"><?php _e('Bahamas','webinara'); ?></option>
													<option value="Bahrain"><?php _e('Bahrain','webinara'); ?></option>
													<option value="Bangladesh"><?php _e('Bangladesh','webinara'); ?></option>
													<option value="Barbados"><?php _e('Barbados','webinara'); ?></option>
													<option value="Belarus"><?php _e('Belarus','webinara'); ?></option>
													<option value="Belgium"><?php _e('Belgium','webinara'); ?></option>
													<option value="Belize"><?php _e('Belize','webinara'); ?></option>
													<option value="Benin"><?php _e('Benin','webinara'); ?></option>
													<option value="Bermuda"><?php _e('Bermuda','webinara'); ?></option>
													<option value="Bhutan"><?php _e('Bhutan','webinara'); ?></option>
													<option value="Bolivia, Plurinational State of"><?php _e('Bolivia, Plurinational State
													of
													','webinara'); ?></option>
													<option value="Bosnia and Herzegovina"><?php _e('Bosnia and Herzegovina','webinara'); ?></option>
													<option value="Botswana"><?php _e('Botswana','webinara'); ?></option>
													<option value="Bouvet Island"><?php _e('Bouvet Island','webinara'); ?></option>
													<option value="Brazil"><?php _e('Brazil','webinara'); ?></option>
													<option value="British Indian Ocean Territory"><?php _e('British Indian Ocean
													Territory
													','webinara'); ?></option>
													<option value="Brunei Darussalam"><?php _e('Brunei Darussalam','webinara'); ?></option>
													<option value="Bulgaria"><?php _e('Bulgaria','webinara'); ?></option>
													<option value="Burkina Faso"><?php _e('Burkina Faso','webinara'); ?></option>
													<option value="Burundi"><?php _e('Burundi','webinara'); ?></option>
													<option value="Cambodia"><?php _e('Cambodia','webinara'); ?></option>
													<option value="Cameroon"><?php _e('Cameroon','webinara'); ?></option>
													<option value="Canada"><?php _e('Canada','webinara'); ?></option>
													<option value="Cape Verde"><?php _e('Cape Verde','webinara'); ?></option>
													<option value="Cayman Islands"><?php _e('Cayman Islands','webinara'); ?></option>
													<option value="Central African Republic"><?php _e('Central African Republic','webinara'); ?></option>
													<option value="Chad"><?php _e('Chad','webinara'); ?></option>
													<option value="Chile"><?php _e('Chile','webinara'); ?></option>
													<option value="China"><?php _e('China','webinara'); ?></option>
													<option value="Christmas Island"><?php _e('Christmas Island','webinara'); ?></option>
													<option value="Cocos (Keeling) Islands"><?php _e('Cocos (Keeling) Islands','webinara'); ?></option>
													<option value="Colombia"><?php _e('Colombia','webinara'); ?></option>
													<option value="Comoros"><?php _e('Comoros','webinara'); ?></option>
													<option value="Congo"><?php _e('Congo','webinara'); ?></option>
													<option value="Congo, the Democratic Republic of the"><?php _e('Congo, the Democratic
													Republic of the
													','webinara'); ?></option>
													<option value="Cook Islands"><?php _e('Cook Islands','webinara'); ?></option>
													<option value="Costa Rica"><?php _e('Costa Rica','webinara'); ?></option>
													<option value="Côte d’Ivoire"><?php _e('Côte d’Ivoire','webinara'); ?></option>
													<option value="Croatia"><?php _e('Croatia','webinara'); ?></option>
													<option value="Cuba"><?php _e('Cuba','webinara'); ?></option>
													<option value="Cyprus"><?php _e('Cyprus','webinara'); ?></option>
													<option value="Czech Republic"><?php _e('Czech Republic','webinara'); ?></option>
													<option value="Denmark"><?php _e('Denmark','webinara'); ?></option>
													 <option value="Djibouti"><?php _e('Djibouti','webinara'); ?></option>
													<option value="Dominica"><?php _e('Dominica','webinara'); ?></option>
													<option value="Dominican Republic"><?php _e('Dominican Republic','webinara'); ?></option>
													<option value="Ecuador"><?php _e('Ecuador','webinara'); ?></option>
													<option value="Egypt"><?php _e('Egypt','webinara'); ?></option>
													<option value="El Salvador"><?php _e('El Salvador','webinara'); ?></option>
													<option value="Equatorial Guinea"><?php _e('Equatorial Guinea','webinara'); ?></option>
													<option value="Eritrea"><?php _e('Eritrea','webinara'); ?></option>
													<option value="Estonia"><?php _e('Estonia','webinara'); ?></option>
													<option value="Ethiopia"><?php _e('Ethiopia','webinara'); ?></option>
													<option value="Falkland Islands (Malvinas)"><?php _e('Falkland Islands (Malvinas)
													','webinara'); ?></option>
													<option value="Faroe Islands"><?php _e('Faroe Islands','webinara'); ?></option>
													<option value="Fiji"><?php _e('Fiji','webinara'); ?></option>
													<option value="Finland"><?php _e('Finland','webinara'); ?></option>
													<option value="France"><?php _e('France','webinara'); ?></option>
													<option value="French Guiana"><?php _e('French Guiana','webinara'); ?></option>
													<option value="French Polynesia"><?php _e('French Polynesia','webinara'); ?></option>
													<option value="French Southern Territories"><?php _e('French Southern Territories
													','webinara'); ?></option>
													<option value="Gabon"><?php _e('Gabon','webinara'); ?></option>
													<option value="Gambia"><?php _e('Gambia','webinara'); ?></option>
													<option value="Georgia"><?php _e('Georgia','webinara'); ?></option>
													<option value="Germany"><?php _e('Germany','webinara'); ?></option>
													<option value="Ghana"><?php _e('Ghana','webinara'); ?></option>
													<option value="Gibraltar"><?php _e('Gibraltar','webinara'); ?></option>
													<option value="Greece"><?php _e('Greece','webinara'); ?></option>
													<option value="Greenland"><?php _e('Greenland','webinara'); ?></option>
													<option value="Grenada"><?php _e('Grenada','webinara'); ?></option>
													<option value="Guadeloupe"><?php _e('Guadeloupe','webinara'); ?></option>
													<option value="Guam"><?php _e('Guam','webinara'); ?></option>
													<option value="Guatemala"><?php _e('Guatemala','webinara'); ?></option>
													<option value="Guernsey"><?php _e('Guernsey','webinara'); ?></option>
													<option value="Guinea"><?php _e('Guinea','webinara'); ?></option>
													<option value="Guinea-Bissau"><?php _e('Guinea-Bissau','webinara'); ?></option>
													<option value="Guyana"><?php _e('Guyana','webinara'); ?></option>
													<option value="Haiti"><?php _e('Haiti','webinara'); ?></option>
													<option value="Heard Island and McDonald Islands"><?php _e('Heard Island and McDonald
													Islands
													','webinara'); ?></option>
													<option value="Holy See (Vatican City State)"><?php _e('Holy See (Vatican City
													State)
													','webinara'); ?></option>
													<option value="Honduras"><?php _e('Honduras','webinara'); ?></option>
													<option value="Hong Kong"><?php _e('Hong Kong','webinara'); ?></option>
													<option value="Hungary"><?php _e('Hungary','webinara'); ?></option>
													<option value="Iceland"><?php _e('Iceland','webinara'); ?></option>
													<option value="India"><?php _e('India','webinara'); ?></option>
													 <option value="Indonesia"><?php _e('Indonesia','webinara'); ?></option>
													<option value="Iran, Islamic Republic of"><?php _e('Iran, Islamic Republic of','webinara'); ?></option>
													<option value="Iraq"><?php _e('Iraq','webinara'); ?></option>
													<option value="Ireland"><?php _e('Ireland','webinara'); ?></option>
													<option value="Isle of Man"><?php _e('Isle of Man','webinara'); ?></option>
													<option value="Israel"><?php _e('Israel','webinara'); ?></option>
													<option value="Italy"><?php _e('Italy','webinara'); ?></option>
													<option value="Jamaica"><?php _e('Jamaica','webinara'); ?></option>
													<option value="Japan"><?php _e('Japan','webinara'); ?></option>
													<option value="Jersey"><?php _e('Jersey','webinara'); ?></option>
													<option value="Jordan"><?php _e('Jordan','webinara'); ?></option>
													<option value="Kazakhstan"><?php _e('Kazakhstan','webinara'); ?></option>
													<option value="Kenya"><?php _e('Kenya','webinara'); ?></option>
													<option value="Kiribati"><?php _e('Kiribati','webinara'); ?></option>
													<option value="Korea, Democratic People's Republic of"><?php _e('Korea, Democratic
													People\'s Republic of
													','webinara'); ?></option>
													<option value="Korea, Republic of"><?php _e('Korea, Republic of','webinara'); ?></option>
													<option value="Kuwait"><?php _e('Kuwait','webinara'); ?></option>
													<option value="Kyrgyzstan"><?php _e('Kyrgyzstan','webinara'); ?></option>
													<option value="Lao People's Democratic Republic"><?php _e('Lao People\'s Democratic
													Republic
													','webinara'); ?></option>
													<option value="Latvia"><?php _e('Latvia','webinara'); ?></option>
													<option value="Lebanon"><?php _e('Lebanon','webinara'); ?></option>
													<option value="Lesotho"><?php _e('Lesotho','webinara'); ?></option>
													<option value="Liberia"><?php _e('Liberia','webinara'); ?></option>
													<option value="Libyan Arab Jamahiriya"><?php _e('Libyan Arab Jamahiriya','webinara'); ?></option>
													<option value="Liechtenstein"><?php _e('Liechtenstein','webinara'); ?></option>
													<option value="Lithuania"><?php _e('Lithuania','webinara'); ?></option>
													<option value="Luxembourg"><?php _e('Luxembourg','webinara'); ?></option>
													<option value="Macao"><?php _e('Macao','webinara'); ?></option>
													<option value="Macedonia, the former Yugoslav Republic of"><?php _e('Macedonia, the
													former Yugoslav Republic of
													','webinara'); ?></option>
													<option value="Madagascar"><?php _e('Madagascar','webinara'); ?></option>
													<option value="Malawi"><?php _e('Malawi','webinara'); ?></option>
													<option value="Malaysia"><?php _e('Malaysia','webinara'); ?></option>
													<option value="Maldives"><?php _e('Maldives','webinara'); ?></option>
													<option value="Mali"><?php _e('Mali','webinara'); ?></option>
													<option value="Malta"><?php _e('Malta','webinara'); ?></option>
													<option value="Marshall Islands"><?php _e('Marshall Islands','webinara'); ?></option>
													<option value="Martinique"><?php _e('Martinique','webinara'); ?></option>
													<option value="Mauritania"><?php _e('Mauritania','webinara'); ?></option>
													<option value="Mauritius"><?php _e('Mauritius','webinara'); ?></option>
													<option value="Mayotte"><?php _e('Mayotte','webinara'); ?></option>
													<option value="Mexico"><?php _e('Mexico','webinara'); ?></option>
													 <option value="Micronesia, Federated States of"><?php _e('Micronesia, Federated States of','webinara'); ?></option>
													<option value="Moldova, Republic of"><?php _e('Moldova, Republic of','webinara'); ?></option>
													<option value="Monaco"><?php _e('Monaco','webinara'); ?></option>
													<option value="Mongolia"><?php _e('Mongolia','webinara'); ?></option>
													<option value="Montenegro"><?php _e('Montenegro','webinara'); ?></option>
													<option value="Montserrat"><?php _e('Montserrat','webinara'); ?></option>
													<option value="Morocco"><?php _e('Morocco','webinara'); ?></option>
													<option value="Mozambique"><?php _e('Mozambique','webinara'); ?></option>
													<option value="Myanmar"><?php _e('Myanmar','webinara'); ?></option>
													<option value="Namibia"><?php _e('Namibia','webinara'); ?></option>
													<option value="Nauru"><?php _e('Nauru','webinara'); ?></option>
													<option value="Nepal"><?php _e('Nepal','webinara'); ?></option>
													<option value="Netherlands"><?php _e('Netherlands','webinara'); ?></option>
													<option value="Netherlands Antilles"><?php _e('Netherlands Antilles','webinara'); ?></option>
													<option value="New Caledonia"><?php _e('New Caledonia','webinara'); ?></option>
													<option value="New Zealand"><?php _e('New Zealand','webinara'); ?></option>
													<option value="Nicaragua"><?php _e('Nicaragua','webinara'); ?></option>
													<option value="Niger"><?php _e('Niger','webinara'); ?></option>
													<option value="Nigeria"><?php _e('Nigeria','webinara'); ?></option>
													<option value="Niue"><?php _e('Niue','webinara'); ?></option>
													<option value="Norfolk Island"><?php _e('Norfolk Island','webinara'); ?></option>
													<option value="Northern Mariana Islands"><?php _e('Northern Mariana Islands','webinara'); ?></option>
													<option value="Norway"><?php _e('Norway','webinara'); ?></option>
													<option value="Oman"><?php _e('Oman','webinara'); ?></option>
													<option value="Pakistan"><?php _e('Pakistan','webinara'); ?></option>
													<option value="Palau"><?php _e('Palau','webinara'); ?></option>
													<option value="Palestine"><?php _e('Palestine','webinara'); ?></option>
													<option value="Panama"><?php _e('Panama','webinara'); ?></option>
													<option value="Papua New Guinea"><?php _e('Papua New Guinea','webinara'); ?></option>
													<option value="Paraguay"><?php _e('Paraguay','webinara'); ?></option>
													<option value="Peru"><?php _e('Peru','webinara'); ?></option>
													<option value="Philippines"><?php _e('Philippines','webinara'); ?></option>
													<option value="Pitcairn"><?php _e('Pitcairn','webinara'); ?></option>
													<option value="Poland"><?php _e('Poland','webinara'); ?></option>
													<option value="Portugal"><?php _e('Portugal','webinara'); ?></option>
													<option value="Puerto Rico"><?php _e('Puerto Rico','webinara'); ?></option>
													<option value="Qatar"><?php _e('Qatar','webinara'); ?></option>
													<option value="Runion"><?php _e('Runion','webinara'); ?></option>
													<option value="Romania"><?php _e('Romania','webinara'); ?></option>
													<option value="Russian Federation"><?php _e('Russian Federation','webinara'); ?></option>
													<option value="Rwanda"><?php _e('Rwanda','webinara'); ?></option>
													<option value="Saint Barthélemy"><?php _e('Saint Barthélemy','webinara'); ?></option>
													<option value="Saint Helena"><?php _e('Saint Helena','webinara'); ?></option>
													<option value="Saint Kitts and Nevis"><?php _e('Saint Kitts and Nevis','webinara'); ?></option>
													 <option value="Saint Lucia"><?php _e('Saint Lucia','webinara'); ?></option>
													<option value="Saint Martin (French part)"><?php _e('Saint Martin (French part)
													','webinara'); ?></option>
													<option value="Saint Pierre and Miquelon"><?php _e('Saint Pierre and Miquelon','webinara'); ?></option>
													<option value="Saint Vincent and the Grenadines"><?php _e('Saint Vincent and the
													Grenadines
													','webinara'); ?></option>
													<option value="Samoa"><?php _e('Samoa','webinara'); ?></option>
													<option value="San Marino"><?php _e('San Marino','webinara'); ?></option>
													<option value="Sao Tome and Principe"><?php _e('Sao Tome and Principe','webinara'); ?></option>
													<option value="Saudi Arabia"><?php _e('Saudi Arabia','webinara'); ?></option>
													<option value="Senegal"><?php _e('Senegal','webinara'); ?></option>
													<option value="Serbia"><?php _e('Serbia','webinara'); ?></option>
													<option value="Seychelles"><?php _e('Seychelles','webinara'); ?></option>
													<option value="Sierra Leone"><?php _e('Sierra Leone','webinara'); ?></option>
													<option value="Singapore"><?php _e('Singapore','webinara'); ?></option>
													<option value="Slovakia"><?php _e('Slovakia','webinara'); ?></option>
													<option value="Slovenia"><?php _e('Slovenia','webinara'); ?></option>
													<option value="Solomon Islands"><?php _e('Solomon Islands','webinara'); ?></option>
													<option value="Somalia"><?php _e('Somalia','webinara'); ?></option>
													<option value="South Africa"><?php _e('South Africa','webinara'); ?></option>
													<option value="South Georgia and the South Sandwich Islands"><?php _e('South Georgia
													and the South Sandwich Islands
													','webinara'); ?></option>
													<option value="Spain"><?php _e('Spain','webinara'); ?></option>
													<option value="Sri Lanka"><?php _e('Sri Lanka','webinara'); ?></option>
													<option value="Sudan"><?php _e('Sudan','webinara'); ?></option>
													<option value="Suriname"><?php _e('Suriname','webinara'); ?></option>
													<option value="Svalbard and Jan Mayen"><?php _e('Svalbard and Jan Mayen','webinara'); ?></option>
													<option value="Swaziland"><?php _e('Swaziland','webinara'); ?></option>
													<option value="Sweden"><?php _e('Sweden','webinara'); ?></option>
													<option value="Switzerland"><?php _e('Switzerland','webinara'); ?></option>
													<option value="Syrian Arab Republic"><?php _e('Syrian Arab Republic','webinara'); ?></option>
													<option value="Taiwan, Province of China"><?php _e('Taiwan, Province of China','webinara'); ?></option>
													<option value="Tajikistan"><?php _e('Tajikistan','webinara'); ?></option>
													<option value="Tanzania, United Republic of"><?php _e('Tanzania, United Republic of
													','webinara'); ?></option>
													<option value="Thailand"><?php _e('Thailand','webinara'); ?></option>
													<option value="Timor-Leste"><?php _e('Timor-Leste','webinara'); ?></option>
													<option value="Togo"><?php _e('Togo','webinara'); ?></option>
													<option value="Tokelau"><?php _e('Tokelau','webinara'); ?></option>
													<option value="Tonga"><?php _e('Tonga','webinara'); ?></option>
													<option value="Trinidad and Tobago"><?php _e('Trinidad and Tobago','webinara'); ?></option>
													<option value="Tunisia"><?php _e('Tunisia','webinara'); ?></option>
													<option value="Turkey"><?php _e('Turkey','webinara'); ?></option>
													<option value="Turkmenistan"><?php _e('Turkmenistan','webinara'); ?></option>
													<option value="Turks and Caicos Islands"><?php _e('Turks and Caicos Islands','webinara'); ?></option>
													<option value="Tuvalu"><?php _e('Tuvalu','webinara'); ?></option>
													<option value="Uganda"><?php _e('Uganda','webinara'); ?></option>
													<option value="Ukraine"><?php _e('Ukraine','webinara'); ?></option>
													<option value="United Arab Emirates"><?php _e('United Arab Emirates','webinara'); ?></option>
													<option value="United Kingdom"><?php _e('United Kingdom','webinara'); ?></option>
													<option value="United States"><?php _e('United States','webinara'); ?></option>
													<option value="United States Minor Outlying Islands"><?php _e('United States Minor
													Outlying Islands
													','webinara'); ?></option>
													<option value="Uruguay"><?php _e('Uruguay','webinara'); ?></option>
													<option value="Uzbekistan"><?php _e('Uzbekistan','webinara'); ?></option>
													<option value="Vanuatu"><?php _e('Vanuatu','webinara'); ?></option>
													<option value="Venezuela, Bolivarian Republic of"><?php _e('Venezuela, Bolivarian
													Republic of
													','webinara'); ?></option>
													<option value="Viet Nam"><?php _e('Viet Nam','webinara'); ?></option>
													<option value="Virgin Islands, British"><?php _e('Virgin Islands, British','webinara'); ?></option>
													<option value="Virgin Islands, U.S."><?php _e('Virgin Islands, U.S.','webinara'); ?></option>
													<option value="Wallis and Futuna"><?php _e('Wallis and Futuna','webinara'); ?></option>
													<option value="Western Sahara"><?php _e('Western Sahara','webinara'); ?></option>
													<option value="Yemen"><?php _e('Yemen','webinara'); ?></option>
													<option value="Zambia"><?php _e('Zambia','webinara'); ?></option>
													<option value="Zimbabwe"><?php _e('Zimbabwe','webinara'); ?></option>
												</select>
											</td>
										</tr>
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Timezone*', 'webinara'); ?></label></th>
											<td>
												<select id="webireg_prefzone" name="webiprofile[_webireg_prefzone]" class="input-select">
													<option disabled="" selected=""></option>
													<option value="Pacific/Samoa"><?php _e('Midway Island, Samoa','webinara'); ?></option>
													<option value="Pacific/Honolulu"><?php _e('Hawaii','webinara'); ?></option>
													<option value="America/Anchorage"><?php _e('Alaska','webinara'); ?></option>
													<option value="America/Los_Angeles"><?php _e('Pacific Time (US and Canada), Tijuana','webinara'); ?></option>
													<option value="America/Phoenix"><?php _e('Arizona','webinara'); ?></option>
													<option value="America/Denver"><?php _e('Mountain Time (US and Canada)','webinara'); ?></option>
													<option value="America/Mexico_City"><?php _e('Mexico City','webinara'); ?></option>
													<option value="America/Chicago"><?php _e('Central Time (US and Canada)','webinara'); ?></option>
													<option value="Canada/Saskatchewan"><?php _e('Regina','webinara'); ?></option>
													<option value="America/Bogota"><?php _e('Bogota, Lima, Quito','webinara'); ?></option>
													<option value="America/Indianapolis"><?php _e('Indiana (East)','webinara'); ?></option>
													<option value="America/New_York"><?php _e('Eastern Time (US and Canada)','webinara'); ?></option>
													<option value="America/Caracas"><?php _e('Caracas, La Paz','webinara'); ?></option>
													<option value="America/Halifax"><?php _e('Atlantic Time (Canada)','webinara'); ?></option>
													<option value="America/Guyana"><?php _e('Georgetown','webinara'); ?></option>
													<option value="America/St_Johns"><?php _e('Newfoundland','webinara'); ?></option>
													<option value="America/Buenos_Aires"><?php _e('Buenos Aires','webinara'); ?></option>
													<option value="America/Santiago"><?php _e('Santiago','webinara'); ?></option>
													<option value="America/Sao_Paulo"><?php _e('Brasilia','webinara'); ?></option>
													<option value="Atlantic/Azores"><?php _e('Azores','webinara'); ?></option>
													<option value="Atlantic/Cape_Verde"><?php _e('Cape Verde Is.','webinara'); ?></option>
													<option value="GMT"><?php _e('Greenwich Mean Time','webinara'); ?></option>
													<option value="Africa/Casablanca"><?php _e('Casablanca, Monrovia','webinara'); ?></option>
													<option value="Europe/London"><?php _e('Dublin, Edinburgh, Lisbon, London','webinara'); ?></option>
													<option value="Europe/Prague"><?php _e('Belgrade, Bratislava, Budapest, Ljubljana, Prague','webinara'); ?></option>
													<option value="Africa/Malabo"><?php _e('West Central Africa','webinara'); ?></option>
													<option value="Europe/Warsaw"><?php _e('Sarajevo, Skopje, Sofija, Vilnius, Warsaw, Zagreb','webinara'); ?></option>
													<option value="Europe/Brussels"><?php _e('Brussels, Copenhagen, Madrid, Paris','webinara'); ?></option>
													<option value="Europe/Amsterdam"><?php _e('Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna','webinara'); ?></option>
													<option value="Africa/Harare"><?php _e('Harare, Pretoria','webinara'); ?></option>
													<option value="Europe/Helsinki"><?php _e('Helsinki, Riga, Tallinn','webinara'); ?></option>
													<option value="Europe/Athens"><?php _e('Athens, Istanbul','webinara'); ?></option>
													<option value="Asia/Jerusalem"><?php _e('Jerusalem','webinara'); ?></option>
													<option value="Africa/Cairo"><?php _e('Cairo','webinara'); ?></option>
													<option value="Europe/Bucharest"><?php _e('Bucharest','webinara'); ?></option>
													<option value="Asia/Kuwait"><?php _e('Kuwait, Riyadh','webinara'); ?></option>
													<option value="Europe/Minsk"><?php _e('Minsk','webinara'); ?></option>
													<option value="Africa/Nairobi"><?php _e('Nairobi','webinara'); ?></option>
													<option value="Asia/Baghdad"><?php _e('Baghdad','webinara'); ?></option>
													<option value="Europe/Moscow"><?php _e('Moscow, St. Petersburg, Volgograd','webinara'); ?></option>
													<option value="Asia/Tehran"><?php _e('Tehran','webinara'); ?></option>
													<option value="Asia/Tbilisi"><?php _e('Baku,Tbilisi, Yerevan','webinara'); ?></option>
													<option value="Asia/Muscat"><?php _e('Abu Dhabi, Muscat','webinara'); ?></option>
													<option value="Asia/Kabul"><?php _e('Kabul','webinara'); ?></option>
													<option value="Asia/Yekaterinburg"><?php _e('Yekaterinburg','webinara'); ?></option>
													<option value="Asia/Karachi"><?php _e('Islamabad, Karachi, Tashkent','webinara'); ?></option>
													<option value="Asia/Kolkata"><?php _e('Calcutta, Chennai, Mumbai, New Delhi','webinara'); ?></option>
													<option value="Asia/Colombo"><?php _e('SriJayawardenepura','webinara'); ?></option>
													<option value="Asia/Katmandu"><?php _e('Kathmandu','webinara'); ?></option>
													<option value="Asia/Novosibirsk"><?php _e('Almaty, Novosibirsk','webinara'); ?></option>
													<option value="Asia/Dhaka"><?php _e('Astana, Dhaka','webinara'); ?></option>
													<option value="Asia/Rangoon"><?php _e('Rangoon','webinara'); ?></option>
													<option value="Asia/Bangkok"><?php _e('Bangkok','webinara'); ?></option>
													<option value="Asia/Krasnoyarsk"><?php _e('Krasnoyarsk','webinara'); ?></option>
													<option value="Asia/Jakarta"><?php _e('Hanoi, Jakarta','webinara'); ?></option>
													<option value="Asia/Hong_Kong"><?php _e('Hong Kong','webinara'); ?></option>
													<option value="Asia/Shanghai"><?php _e('Beijing, Chongqing, Urumqi, Taipei','webinara'); ?></option>
													<option value="Australia/Perth"><?php _e('Perth','webinara'); ?></option>
													<option value="Asia/Taipei"><?php _e('Taipei','webinara'); ?></option>
													<option value="Asia/Singapore"><?php _e('Kuala Lumpur, Singapore','webinara'); ?></option>
													<option value="Asia/Irkutsk"><?php _e('Irkutsk, Ulaan Bataar','webinara'); ?></option>
													<option value="Asia/Seoul"><?php _e('Seoul','webinara'); ?></option>
													<option value="Asia/Tokyo"><?php _e('Osaka, Sapporo, Tokyo','webinara'); ?></option>
													<option value="Asia/Yakutsk"><?php _e('Yakutsk','webinara'); ?></option>
													<option value="Australia/Darwin"><?php _e('Darwin','webinara'); ?></option>
													<option value="Asia/Vladivostok"><?php _e('Vladivostok','webinara'); ?></option>
													<option value="Pacific/Guam"><?php _e('Guam, Port Moresby','webinara'); ?></option>
													<option value="Asia/Magadan"><?php _e('Magadan, Solomon Is., New Caledonia','webinara'); ?></option>
													<option value="Australia/Brisbane"><?php _e('Brisbane','webinara'); ?></option>
													<option value="Australia/Adelaide"><?php _e('Adelaide','webinara'); ?></option>
													<option value="Australia/Sydney"><?php _e('Canberra, Melbourne, Sydney','webinara'); ?></option>
													<option value="Australia/Hobart"><?php _e('Hobart','webinara'); ?></option>
													<option value="Pacific/Fiji"><?php _e('Fiji, Kamchatka, Marshall Is.','webinara'); ?></option>
													<option value="Pacific/Tongatapu"><?php _e('Nukualofa','webinara'); ?></option>
													<option value="Pacific/Auckland"><?php _e('Auckland, Wellington','webinara'); ?></option> 
												</select>										
											</td>
										</tr>
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Webinar solution*', 'webinara'); ?></label></th>
											<td>
												<select id="webireg_websolution" name="webiprofile[_webireg_websolution]" class="input-select">
													<option disabled="" selected=""></option>
													<option value="Adobe Connect"><?php _e('Adobe Connect','webinara'); ?></option>
													<option value="AnyMeeting"><?php _e('AnyMeeting','webinara'); ?></option>
													<option value="BeaconLive"><?php _e('BeaconLive','webinara'); ?></option>
													<option value="Bigmarker"><?php _e('Bigmarker','webinara'); ?></option>
													<option value="Cisco WebEx"><?php _e('Cisco WebEx','webinara'); ?></option>
													 <option value="ClickWebinar"><?php _e('ClickWebinar','webinara'); ?></option>
													<option value="EasyWebinar"><?php _e('EasyWebinar','webinara'); ?></option>
													<option value="FuzeBox"><?php _e('FuzeBox','webinara'); ?></option>
													<option value="GlobalMeet"><?php _e('GlobalMeet','webinara'); ?></option>
													<option value="Google Hangout"><?php _e('Google Hangout','webinara'); ?></option>
													<option value="GoToMeeting"><?php _e('GoToMeeting','webinara'); ?></option>
													<option value="GoToWebinar"><?php _e('GoToWebinar','webinara'); ?></option>
													<option value="iLinc"><?php _e('iLinc','webinara'); ?></option>
													<option value="Infinite"><?php _e('Infinite','webinara'); ?></option>
													<option value="InterCall"><?php _e('InterCall','webinara'); ?></option>
													<option value="join. me"><?php _e('join. me','webinara'); ?></option>
													<option value="MeetingBurner"><?php _e('MeetingBurner','webinara'); ?></option>
													<option value="MegaMeeting"><?php _e('MegaMeeting','webinara'); ?></option>
													<option value="Microsoft Lync"><?php _e('Microsoft Lync','webinara'); ?></option>
													<option value="Mikogo"><?php _e('Mikogo','webinara'); ?></option>
													<option value="ON24"><?php _e('ON24','webinara'); ?></option>
													<option value="Onstream Webinars"><?php _e('Onstream Webinars','webinara'); ?></option>
													<option value="Rally Point"><?php _e('Rally Point','webinara'); ?></option>
													<option value="ReadyTalk"><?php _e('ReadyTalk','webinara'); ?></option>
													<option value="Skype"><?php _e('Skype','webinara'); ?></option>
													<option value="VIA3"><?php _e('VIA3','webinara'); ?></option>
													<option value="VoiceBoxer"><?php _e('VoiceBoxer','webinara'); ?></option>
													<option value="Web Conferencing Central"><?php _e('Web Conferencing
													Central
													','webinara'); ?></option>
													<option value="Yugma"><?php _e('Yugma','webinara'); ?></option>
													<option value="Yuuguu"><?php _e('Yuuguu','webinara'); ?></option>
													<option value="XPOCAST"><?php _e('XPOCAST','webinara'); ?></option>
													<option value="Zoho Meeting"><?php _e('Zoho Meeting','webinara'); ?></option>
													<option value="Zoom"><?php _e('Zoom','webinara'); ?></option>
													<option value="Other"><?php _e('Other','webinara'); ?></option>
												</select>										
											</td>
										</tr>
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Company*', 'webinara'); ?></label></th>
											<td>
												<input type="text" name="webiprofile[_webireg_cname]" class="webireg_fld" id="webireg_cname">										
											</td>
										</tr>
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Company Industry*', 'webinara'); ?></label></th>
											<td>
												<select id="webireg_cindustry" name="webiprofile[_webireg_cindustry]" class="input-select">
													<option disabled="" selected=""></option>
													<option value="Accounting"><?php _e('Accounting','webinara'); ?></option>
													<option value="Airlines/Aviation"><?php _e('Airlines/Aviation','webinara'); ?></option>
													<option value="Alternative Dispute Resolution"><?php _e('Alternative Dispute
													Resolution
													','webinara'); ?></option>
													<option value="Alternative Medicine"><?php _e('Alternative Medicine
													','webinara'); ?></option>
													<option value="Animation"><?php _e('Animation','webinara'); ?></option>
													<option value="Apparel &amp; Fashion"><?php _e('Apparel &amp; Fashion','webinara'); ?></option>
													<option value="Architecture &amp; Planning"><?php _e('Architecture &amp; Planning
													','webinara'); ?></option>
													<option value="Arts and Crafts"><?php _e('Arts and Crafts','webinara'); ?></option>
													<option value="Automotive"><?php _e('Automotive','webinara'); ?></option>
													<option value="Aviation &amp; Aerospace"><?php _e('Aviation &amp; Aerospace','webinara'); ?></option>
													<option value="Banking"><?php _e('Banking','webinara'); ?></option>
													<option value="Biotechnology"><?php _e('Biotechnology','webinara'); ?></option>
													<option value="Broadcast Media"><?php _e('Broadcast Media','webinara'); ?></option>
													<option value="Building Materials"><?php _e('Building Materials','webinara'); ?></option>
													<option value="Business Supplies and Equipment"><?php _e('Business Supplies and
													Equipment
													','webinara'); ?></option>
													<option value="Capital Markets"><?php _e('Capital Markets','webinara'); ?></option>
													<option value="Chemicals"><?php _e('Chemicals','webinara'); ?></option>
													<option value="Civic &amp; Social Organization"><?php _e('Civic &amp; Social
													Organization
													','webinara'); ?></option>
													<option value="Civil Engineering"><?php _e('Civil Engineering','webinara'); ?></option>
													<option value="Commercial Real Estate"><?php _e('Commercial Real Estate','webinara'); ?></option>
													<option value="Computer &amp; Network Security"><?php _e('Computer &amp; Network
													Security
													','webinara'); ?></option>
													<option value="Computer Games"><?php _e('Computer Games','webinara'); ?></option>
													<option value="Computer Hardware"><?php _e('Computer Hardware','webinara'); ?></option>
													<option value="Computer Networking"><?php _e('Computer Networking','webinara'); ?></option>
													<option value="Computer Software"><?php _e('Computer Software','webinara'); ?></option>
													<option value="Construction"><?php _e('Construction','webinara'); ?></option>
													<option value="Consumer Electronics"><?php _e('Consumer Electronics','webinara'); ?></option>
													<option value="Consumer Goods"><?php _e('Consumer Goods','webinara'); ?></option>
													<option value="Consumer Services"><?php _e('Consumer Services','webinara'); ?></option>
													<option value="Cosmetics"><?php _e('Cosmetics','webinara'); ?></option>
													<option value="Dairy"><?php _e('Dairy','webinara'); ?></option>
													<option value="Defense &amp; Space"><?php _e('Defense &amp; Space','webinara'); ?></option>
													<option value="Design"><?php _e('Design','webinara'); ?></option>
													<option value="Education Management"><?php _e('Education Management','webinara'); ?></option>
													<option value="E-Learning"><?php _e('E-Learning','webinara'); ?></option>
													<option value="Electrical/Electronic Manufacturing"><?php _e('Electrical/Electronic
													Manufacturing
													','webinara'); ?></option>
													<option value="Entertainment"><?php _e('Entertainment','webinara'); ?></option>
													<option value="Environmental Services"><?php _e('Environmental Services','webinara'); ?></option>
													<option value="Events Services"><?php _e('Events Services','webinara'); ?></option>
													<option value="Executive Office"><?php _e('Executive Office','webinara'); ?></option>
													<option value="Facilities Services"><?php _e('Facilities Services','webinara'); ?></option>
													<option value="Farming"><?php _e('Farming','webinara'); ?></option>
													<option value="Financial Services"><?php _e('Financial Services','webinara'); ?></option>
													<option value="Fine Art"><?php _e('Fine Art','webinara'); ?></option>
													<option value="Fishery"><?php _e('Fishery','webinara'); ?></option>
													<option value="Food &amp; Beverages"><?php _e('Food &amp; Beverages','webinara'); ?></option>
													<option value="Food Production"><?php _e('Food Production','webinara'); ?></option>
													<option value="Fund-Raising"><?php _e('Fund-Raising','webinara'); ?></option>
													<option value="Furniture"><?php _e('Furniture','webinara'); ?></option>
													<option value="Gambling &amp; Casinos"><?php _e('Gambling &amp; Casinos','webinara'); ?></option>
													<option value="Glass, Ceramics &amp; Concrete"><?php _e('Glass, Ceramics &amp;
													Concrete
													','webinara'); ?></option>
													<option value="Government Administration"><?php _e('Government Administration','webinara'); ?></option>
													<option value="Government Relations"><?php _e('Government Relations','webinara'); ?></option>
													<option value="Graphic Design"><?php _e('Graphic Design','webinara'); ?></option>
													<option value="Health, Wellness and Fitness"><?php _e('Health, Wellness and Fitness
													','webinara'); ?></option>
													<option value="Higher Education"><?php _e('Higher Education','webinara'); ?></option>
													<option value="Hospital &amp; Health Care"><?php _e('Hospital &amp; Health Care
													','webinara'); ?></option>
													<option value="Hospitality"><?php _e('Hospitality','webinara'); ?></option>
													<option value="Human Resources"><?php _e('Human Resources','webinara'); ?></option>
													<option value="Import and Export"><?php _e('Import and Export','webinara'); ?></option>
													<option value="Individual &amp; Family Services"><?php _e('Individual &amp; Family
													Services
													','webinara'); ?></option>
													<option value="Industrial Automation"><?php _e('Industrial Automation','webinara'); ?></option>
													<option value="Information Services"><?php _e('Information Services','webinara'); ?></option>
													<option value="Information Technology and Services"><?php _e('Information Technology
													and Services
													','webinara'); ?></option>
													<option value="Insurance"><?php _e('Insurance','webinara'); ?></option>
													<option value="International Affairs"><?php _e('International Affairs','webinara'); ?></option>
													<option value="International Trade and Development"><?php _e('International Trade and
													Development
													','webinara'); ?></option>
													<option value="Internet"><?php _e('Internet','webinara'); ?></option>
													<option value="Investment Banking"><?php _e('Investment Banking','webinara'); ?></option>
													<option value="Investment Management"><?php _e('Investment Management','webinara'); ?></option>
													<option value="Judiciary"><?php _e('Judiciary','webinara'); ?></option>
													<option value="Law Enforcement"><?php _e('Law Enforcement','webinara'); ?></option>
													<option value="Law Practice"><?php _e('Law Practice','webinara'); ?></option>
													<option value="Legal Services"><?php _e('Legal Services','webinara'); ?></option>
													<option value="Legislative Office"><?php _e('Legislative Office','webinara'); ?></option>
													<option value="Leisure, Travel &amp; Tourism"><?php _e('Leisure, Travel &amp;
													Tourism
													','webinara'); ?></option>
													<option value="Libraries"><?php _e('Libraries','webinara'); ?></option>
													<option value="Logistics and Supply Chain"><?php _e('Logistics and Supply Chain
													','webinara'); ?></option>
													<option value="Luxury Goods &amp; Jewelry"><?php _e('Luxury Goods &amp; Jewelry
													','webinara'); ?></option>
													<option value="Machinery"><?php _e('Machinery','webinara'); ?></option>
													 <option value="Management Consulting"><?php _e('Management Consulting','webinara'); ?></option>
													<option value="Maritime"><?php _e('Maritime','webinara'); ?></option>
													<option value="Marketing and Advertising"><?php _e('Marketing and Advertising','webinara'); ?></option>
													<option value="Market Research"><?php _e('Market Research','webinara'); ?></option>
													<option value="Mechanical or Industrial Engineering"><?php _e('Mechanical or
													Industrial Engineering
													','webinara'); ?></option>
													<option value="Media Production"><?php _e('Media Production','webinara'); ?></option>
													<option value="Medical Devices"><?php _e('Medical Devices','webinara'); ?></option>
													<option value="Medical Practice"><?php _e('Medical Practice','webinara'); ?></option>
													<option value="Mental Health Care"><?php _e('Mental Health Care','webinara'); ?></option>
													<option value="Military"><?php _e('Military','webinara'); ?></option>
													<option value="Mining &amp; Metals"><?php _e('Mining &amp; Metals','webinara'); ?></option>
													<option value="Motion Pictures and Film"><?php _e('Motion Pictures and Film','webinara'); ?></option>
													<option value="Museums and Institutions"><?php _e('Museums and Institutions','webinara'); ?></option>
													<option value="Music"><?php _e('Music','webinara'); ?></option>
													<option value="Nanotechnology"><?php _e('Nanotechnology','webinara'); ?></option>
													<option value="Newspapers"><?php _e('Newspapers','webinara'); ?></option>
													<option value="Nonprofit Organization Management"><?php _e('Nonprofit Organization
													Management
													','webinara'); ?></option>
													<option value="Oil &amp; Energy"><?php _e('Oil &amp; Energy','webinara'); ?></option>
													<option value="Online Media"><?php _e('Online Media','webinara'); ?></option>
													<option value="Outsourcing/Offshoring"><?php _e('Outsourcing/Offshoring','webinara'); ?></option>
													<option value="Package/Freight Delivery"><?php _e('Package/Freight Delivery','webinara'); ?></option>
													<option value="Packaging and Containers"><?php _e('Packaging and Containers','webinara'); ?></option>
													<option value="Paper &amp; Forest Products"><?php _e('Paper &amp; Forest Products
													','webinara'); ?></option>
													<option value="Performing Arts"><?php _e('Performing Arts','webinara'); ?></option>
													<option value="Pharmaceuticals"><?php _e('Pharmaceuticals','webinara'); ?></option>
													<option value="Philanthropy"><?php _e('Philanthropy','webinara'); ?></option>
													<option value="Photography"><?php _e('Photography','webinara'); ?></option>
													<option value="Plastics"><?php _e('Plastics','webinara'); ?></option>
													<option value="Political Organization"><?php _e('Political Organization','webinara'); ?></option>
													<option value="Primary/Secondary Education"><?php _e('Primary/Secondary Education
													','webinara'); ?></option>
													<option value="Printing"><?php _e('Printing','webinara'); ?></option>
													<option value="Professional Training &amp; Coaching"><?php _e('Professional Training
													&amp; Coaching
													','webinara'); ?></option>
													<option value="Program Development"><?php _e('Program Development','webinara'); ?></option>
													<option value="Public Policy"><?php _e('Public Policy','webinara'); ?></option>
													<option value="Public Relations and Communications"><?php _e('Public Relations and
													Communications
													','webinara'); ?></option>
													<option value="Public Safety"><?php _e('Public Safety','webinara'); ?></option>
													<option value="Publishing"><?php _e('Publishing','webinara'); ?></option>
													<option value="Railroad Manufacture"><?php _e('Railroad Manufacture','webinara'); ?></option>
													<option value="Ranching"><?php _e('Ranching','webinara'); ?></option>
													<option value="Real Estate"><?php _e('Real Estate','webinara'); ?></option>
													<option value="Recreational Facilities and Services"><?php _e('Recreational Facilities
													and Services
													','webinara'); ?></option>
													<option value="Religious Institutions"><?php _e('Religious Institutions','webinara'); ?></option>
													<option value="Renewables &amp; Environment"><?php _e('Renewables &amp; Environment
													','webinara'); ?></option>
													<option value="Research"><?php _e('Research','webinara'); ?></option>
													<option value="Restaurants"><?php _e('Restaurants','webinara'); ?></option>
													<option value="Retail"><?php _e('Retail','webinara'); ?></option>
													<option value="Security and Investigations"><?php _e('Security and Investigations
													','webinara'); ?></option>
													<option value="Semiconductors"><?php _e('Semiconductors','webinara'); ?></option>
													<option value="Shipbuilding"><?php _e('Shipbuilding','webinara'); ?></option>
													<option value="Sporting Goods"><?php _e('Sporting Goods','webinara'); ?></option>
													<option value="Sports"><?php _e('Sports','webinara'); ?></option>
													<option value="Staffing and Recruiting"><?php _e('Staffing and Recruiting','webinara'); ?></option>
													<option value="Supermarkets"><?php _e('Supermarkets','webinara'); ?></option>
													<option value="Telecommunications"><?php _e('Telecommunications','webinara'); ?></option>
													<option value="Textiles"><?php _e('Textiles','webinara'); ?></option>
													<option value="Think Tanks"><?php _e('Think Tanks','webinara'); ?></option>
													<option value="Tobacco"><?php _e('Tobacco','webinara'); ?></option>
													<option value="Translation and Localization"><?php _e('Translation and Localization
													','webinara'); ?></option>
													<option value="Transportation/Trucking/Railroad"><?php _e('
													Transportation/Trucking/Railroad
													','webinara'); ?></option>
													<option value="Utilities"><?php _e('Utilities','webinara'); ?></option>
													<option value="Venture Capital &amp; Private Equity"><?php _e('Venture Capital &amp;
													Private Equity
													','webinara'); ?></option>
													<option value="Veterinary"><?php _e('Veterinary','webinara'); ?></option>
													<option value="Warehousing"><?php _e('Warehousing','webinara'); ?></option>
													<option value="Wholesale"><?php _e('Wholesale','webinara'); ?></option>
													<option value="Wine and Spirits"><?php _e('Wine and Spirits','webinara'); ?></option>
													<option value="Wireless"><?php _e('Wireless','webinara'); ?></option>
													<option value="Writing and Editing"><?php _e('Writing and Editing','webinara'); ?></option>
												</select>										
											</td>
										</tr>
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Website*', 'webinara'); ?></label></th>
											<td>
												<input type="text" name="webiprofile[_webireg_website]" class="webireg_fld" id="webireg_website">										
											</td>
										</tr>
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Company Size*', 'webinara'); ?></label></th>
											<td>
												<select name="_webireg_csize" id="webireg_csize" class="input-select">
													<option disabled="" selected=""></option>
													<option value="Less than 5 Employees"><?php _e('Less than 5 Employees','webinara'); ?></option>
													<option value="5 - 9 Employees"><?php _e('5 - 9 Employees','webinara'); ?></option>
													<option value="10 - 19 Employees"><?php _e('10 - 19 Employees','webinara'); ?></option>
													<option value="20 - 29 Employees"><?php _e('20 - 29 Employees
													','webinara'); ?></option>
													<option value="30 - 49 Employees"><?php _e('30 - 49 Employees','webinara'); ?></option>
													<option value="50 - 99 Employees"><?php _e('50 - 99 Employees','webinara'); ?></option>
													<option value="100 - 249 Employees"><?php _e('100 - 249 Employees','webinara'); ?></option>
													<option value="250 - 499 Employees"><?php _e('250 - 499 Employees','webinara'); ?></option>
													<option value="500 - 999 Employees"><?php _e('500 - 999 Employees','webinara'); ?></option>
													<option value="1000 - 4999 Employees"><?php _e('1000 - 4999 Employees','webinara'); ?></option>
													<option value="5000 - 9999 Employees"><?php _e('5000 - 9999 Employees','webinara'); ?></option>
													<option value="10000 - 24999 Employees"><?php _e('10000 - 24999 Employees
													','webinara'); ?></option>
													<option value="25000+ Employees"><?php _e('25000+ Employees','webinara'); ?></option>
													<option value="Prefer Not to Answer"><?php _e('Prefer Not to Answer','webinara'); ?></option>
												</select>										
											</td>
										</tr>
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('About company(will be displayed for your company profile)*', 'webinara'); ?></label></th>
											<td>
												<textarea name="webiprofile[_webireg_cdesc]" class="webireg_fld" id="webireg_cdesc" rows="8"></textarea>										
											</td>
										</tr>
									</tbody>
								
									<tbody>									
										<tr valign="top" class="">
											<th scope="row"></th>
											<td id="webi_profile_section">
												<a href="javascript:void(0);" class="button-primary" id="webi_update_profile"><?php esc_html_e('Update', 'webinara'); ?></a>
												<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>	
												<p class="acc_response"></p>
												<input type="hidden" id="cur_tab" value="p">
											</td>
										</tr>	
									</tbody>
								</table>
							</div>	
							<?php
						}
						if($active_tab == 'license')
						{							
							$webi_license_key = get_option('_webi_license_key'); ?>
							<form method="post" id="webinara_licensekey_form">											
								<table class="form-table">
									<tbody>								
										<tr valign="top" class="">
											<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('License Key', 'webinara'); ?></label></th>
											<td>
												<input type="text" name="_webi_license_key" id="license_key" value="<?php if(!empty($webi_license_key)){ echo $webi_license_key; } ?>" class="license_fld" <?php if(!empty($webi_license_key)){ echo "readonly"; } ?>>										
											</td>
										</tr>					
									</tbody>
								</table>
								<input type="hidden" id="license_tab_url" value="<?php echo admin_url('edit.php?post_type=webinar&page=webinara_plugin&tab=license'); ?>">	
								<input type="hidden" name="active_tab" value="license">
								<?php 
								if(!empty($webi_license_key)){
									if(date('Y-m-d') > get_option('_webi_license_x'))
									{
										?>
										<p class="license_info"><strong><?php esc_html_e('License Status:', 'webinara'); ?> </strong>
											<span style="color:#FF0000 !important"><?php esc_html_e('Premium License Expired', 'webinara'); ?>												
											</span>
										</p>
										<?php
									}
									else
									{
										?>
										<p class="license_info"><strong><?php esc_html_e('License Status:', 'webinara'); ?> </strong>
											<span><?php esc_html_e('Premium License Active', 'webinara'); ?> <b class="license_exp_message"></b>												
											</span>
										</p>
										<?php
									}
									?>									
									<div class="webidowngrade_sec">
										<a href="javascript:void(0);" class="button-primary" id="downgrade_license"><?php esc_html_e('Downgrade', 'webinara'); ?></a>
										<a href="javascript:void(0);" class="button-primary renew_button" id="renew_license"><?php esc_html_e('Renew License', 'webinara'); ?></a>
										<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>	
										<p class="up_response"></p>
										<input type="hidden" id="cur_tab" value="l">
									</div>	
									<?php					
								}
								else
								{
									?>
									<p class="license_info"><strong><?php esc_html_e('License Status:', 'webinara'); ?> </strong><?php esc_html_e('Free License Active', 'webinara'); ?> <b>(<?php echo '' .sprintf( __('To unlock more features consider <a href="%1$s" id="webi_upgrade_btn">%2$s</a>','webinara'), esc_attr( 'javascript:void(0);' ), esc_html( 'upgrading to PRO' ) ) ?>)</b></p>
									<div class="webi_fremium_sec">
										<a href="javascript:void(0);" class="button-primary" id="webi_save_license"><?php esc_html_e('Save', 'webinara'); ?></a>
										<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>	
										<p class="up_response"></p>
									</div>	
									<?php
								}	
							echo '</form>';	
							if(empty($webi_license_key)){
							?>
								<div class="webi_upgrade_section" id="webi_upgrade_section" <?php if(isset($_GET['action']) && trim($_GET['action']) == 'register'){ echo 'style="display:block"'; }?>>
									<h2><?php esc_html_e('Fill form for upgrade plugin', 'webinara'); ?></h2>
									<table class="form-table">
										<tbody>	
											<tr valign="top" class="">
												<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Email*', 'webinara'); ?></label></th>
												<td>
													<input type="text" name="webiprofile[_webireg_email]" class="webireg_fld" id="webireg_email">										
												</td>
											</tr>
										</tbody>
									
										<tbody class="webi_addi_field">
											<tr valign="top" class="">
												<th scope="row" colspan="2"><label for="setting-webi_webinars_license"><?php esc_html_e('Please fill some more information as you new user (we use this information to create account on webinara.com. You can update this any time.)', 'webinara'); ?></label></th>												
											</tr>											 
											<tr valign="top" class="">
												<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('First Name*','webinara'); ?></label></th>
												<td>
													<input type="text" name="webiprofile[_webireg_fname]" class="webireg_fld" id="webireg_fname">										
												</td>
											</tr>
											<tr valign="top" class="">
												<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Last Name*','webinara'); ?></label></th>
												<td>
													<input type="text" name="webiprofile[_webireg_lname]" class="webireg_fld" id="webireg_lname">										
												</td>
											</tr>											
											<tr valign="top" class="">
												<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Job title*','webinara'); ?></label></th>
												<td>
													<input type="text" name="webiprofile[_webireg_jobtitle]" class="webireg_fld" id="webireg_jobtitle">										
												</td>
											</tr>
											<tr valign="top" class="">
												<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Job level*','webinara'); ?></label></th>
												<td>
													<select name="webiprofile[_webireg_joblevel]" id="webireg_joblevel" class="input-select">
														<option disabled="" selected=""></option>
														<option value="Independent Consultant"><?php _e('Independent Consultant
														','webinara'); ?></option>
														<option value="Employee (not supervisor)"><?php _e('Employee (not supervisor)','webinara'); ?></option>
														<option value="Employee (supervisor)"><?php _e('Employee (supervisor)','webinara'); ?></option>
														<option value="Manager"><?php _e('Manager','webinara'); ?></option>
														<option value="Director"><?php _e('Director','webinara'); ?></option>
														<option value="Executive/V.P."><?php _e('Executive/V.P.','webinara'); ?></option>
														<option value="Senior Executive"><?php _e('Senior Executive','webinara'); ?></option>
														<option value="CEO / President / Owner"><?php _e('CEO / President / Owner','webinara'); ?></option>
													</select>
												</td>
											</tr>
											<tr valign="top" class="">
												<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Job function*','webinara'); ?></label></th>
												<td>
													<select name="webiprofile[_webireg_jobfunction]" id="webireg_jobfunction" class="input-select">
														<option disabled="" selected=""></option>
														<option value="Accounting"><?php _e('Accounting','webinara'); ?></option>
														<option value="Business Development"><?php _e('Business Development','webinara'); ?></option>
														<option value="Communication"><?php _e('Communication','webinara'); ?></option>
														<option value="Consulting"><?php _e('Consulting','webinara'); ?></option>
														<option value="Customer Service"><?php _e('Customer Service','webinara'); ?></option>
														<option value="Engineering"><?php _e('Engineering','webinara'); ?></option>
														<option value="Finance"><?php _e('Finance','webinara'); ?></option>
														<option value="Human Resources"><?php _e('Human Resources','webinara'); ?></option>
														<option value="IT"><?php _e('IT','webinara'); ?></option>
														<option value="Management"><?php _e('Management','webinara'); ?></option>
														<option value="Manufacturing/Production"><?php _e('Manufacturing/Production
														','webinara'); ?></option>
														<option value="Marketing"><?php _e('Marketing','webinara'); ?></option>
														<option value="Operations"><?php _e('Operations','webinara'); ?></option>
														<option value="Research and Development"><?php _e('Research and Development
														','webinara'); ?></option>
														<option value="Sales"><?php _e('Sales','webinara'); ?></option>
														<option value="Other"><?php _e('Other','webinara'); ?></option>
													</select>
												</td>
											</tr>
											<tr valign="top" class="">
												<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Country*','webinara'); ?></label></th>
												<td>
													<select id="webireg_country" name="webiprofile[_webireg_country]" class="input-select" id="webireg_country">
														<option disabled="" selected=""></option>
														<option value="Afghanistan"><?php _e('Afghanistan','webinara'); ?></option>
														<option value="Aland Islands"><?php _e('Aland Islands','webinara'); ?></option>
														<option value="Albania"><?php _e('Albania','webinara'); ?></option>
														<option value="Algeria"><?php _e('Algeria','webinara'); ?></option>
														<option value="American Samoa"><?php _e('American Samoa','webinara'); ?></option>
														<option value="Andorra"><?php _e('Andorra','webinara'); ?></option>
														<option value="Angola"><?php _e('Angola','webinara'); ?></option>
														<option value="Anguilla"><?php _e('Anguilla','webinara'); ?></option>
														<option value="Antarctica"><?php _e('Antarctica','webinara'); ?></option>
														<option value="Antigua and Barbuda"><?php _e('Antigua and Barbuda','webinara'); ?></option>
														<option value="Argentina"><?php _e('Argentina','webinara'); ?></option>
														<option value="Armenia"><?php _e('Armenia','webinara'); ?></option>
														<option value="Aruba"><?php _e('Aruba','webinara'); ?></option>
														<option value="Australia"><?php _e('Australia','webinara'); ?></option>
														<option value="Austria"><?php _e('Austria','webinara'); ?></option>
														<option value="Azerbaijan"><?php _e('Azerbaijan','webinara'); ?></option>
														<option value="Bahamas"><?php _e('Bahamas','webinara'); ?></option>
														<option value="Bahrain"><?php _e('Bahrain','webinara'); ?></option>
														<option value="Bangladesh"><?php _e('Bangladesh','webinara'); ?></option>
														<option value="Barbados"><?php _e('Barbados','webinara'); ?></option>
														<option value="Belarus"><?php _e('Belarus','webinara'); ?></option>
														<option value="Belgium"><?php _e('Belgium','webinara'); ?></option>
														<option value="Belize"><?php _e('Belize','webinara'); ?></option>
														<option value="Benin"><?php _e('Benin','webinara'); ?></option>
														<option value="Bermuda"><?php _e('Bermuda','webinara'); ?></option>
														<option value="Bhutan"><?php _e('Bhutan','webinara'); ?></option>
														<option value="Bolivia, Plurinational State of"><?php _e('Bolivia, Plurinational State
														of
														','webinara'); ?></option>
														<option value="Bosnia and Herzegovina"><?php _e('Bosnia and Herzegovina','webinara'); ?></option>
														<option value="Botswana"><?php _e('Botswana','webinara'); ?></option>
														<option value="Bouvet Island"><?php _e('Bouvet Island','webinara'); ?></option>
														<option value="Brazil"><?php _e('Brazil','webinara'); ?></option>
														<option value="British Indian Ocean Territory"><?php _e('British Indian Ocean
														Territory
														','webinara'); ?></option>
														<option value="Brunei Darussalam"><?php _e('Brunei Darussalam','webinara'); ?></option>
														<option value="Bulgaria"><?php _e('Bulgaria','webinara'); ?></option>
														<option value="Burkina Faso"><?php _e('Burkina Faso','webinara'); ?></option>
														<option value="Burundi"><?php _e('Burundi','webinara'); ?></option>
														<option value="Cambodia"><?php _e('Cambodia','webinara'); ?></option>
														<option value="Cameroon"><?php _e('Cameroon','webinara'); ?></option>
														<option value="Canada"><?php _e('Canada','webinara'); ?></option>
														<option value="Cape Verde"><?php _e('Cape Verde','webinara'); ?></option>
														<option value="Cayman Islands"><?php _e('Cayman Islands','webinara'); ?></option>
														<option value="Central African Republic"><?php _e('Central African Republic','webinara'); ?></option>
														<option value="Chad"><?php _e('Chad','webinara'); ?></option>
														<option value="Chile"><?php _e('Chile','webinara'); ?></option>
														<option value="China"><?php _e('China','webinara'); ?></option>
														<option value="Christmas Island"><?php _e('Christmas Island','webinara'); ?></option>
														<option value="Cocos (Keeling) Islands"><?php _e('Cocos (Keeling) Islands','webinara'); ?></option>
														<option value="Colombia"><?php _e('Colombia','webinara'); ?></option>
														<option value="Comoros"><?php _e('Comoros','webinara'); ?></option>
														<option value="Congo"><?php _e('Congo','webinara'); ?></option>
														<option value="Congo, the Democratic Republic of the"><?php _e('Congo, the Democratic
														Republic of the
														','webinara'); ?></option>
														<option value="Cook Islands"><?php _e('Cook Islands','webinara'); ?></option>
														<option value="Costa Rica"><?php _e('Costa Rica','webinara'); ?></option>
														<option value="Côte d’Ivoire"><?php _e('Côte d’Ivoire','webinara'); ?></option>
														<option value="Croatia"><?php _e('Croatia','webinara'); ?></option>
														<option value="Cuba"><?php _e('Cuba','webinara'); ?></option>
														<option value="Cyprus"><?php _e('Cyprus','webinara'); ?></option>
														<option value="Czech Republic"><?php _e('Czech Republic','webinara'); ?></option>
														<option value="Denmark"><?php _e('Denmark','webinara'); ?></option>
														 <option value="Djibouti"><?php _e('Djibouti','webinara'); ?></option>
														<option value="Dominica"><?php _e('Dominica','webinara'); ?></option>
														<option value="Dominican Republic"><?php _e('Dominican Republic','webinara'); ?></option>
														<option value="Ecuador"><?php _e('Ecuador','webinara'); ?></option>
														<option value="Egypt"><?php _e('Egypt','webinara'); ?></option>
														<option value="El Salvador"><?php _e('El Salvador','webinara'); ?></option>
														<option value="Equatorial Guinea"><?php _e('Equatorial Guinea','webinara'); ?></option>
														<option value="Eritrea"><?php _e('Eritrea','webinara'); ?></option>
														<option value="Estonia"><?php _e('Estonia','webinara'); ?></option>
														<option value="Ethiopia"><?php _e('Ethiopia','webinara'); ?></option>
														<option value="Falkland Islands (Malvinas)"><?php _e('Falkland Islands (Malvinas)
														','webinara'); ?></option>
														<option value="Faroe Islands"><?php _e('Faroe Islands','webinara'); ?></option>
														<option value="Fiji"><?php _e('Fiji','webinara'); ?></option>
														<option value="Finland"><?php _e('Finland','webinara'); ?></option>
														<option value="France"><?php _e('France','webinara'); ?></option>
														<option value="French Guiana"><?php _e('French Guiana','webinara'); ?></option>
														<option value="French Polynesia"><?php _e('French Polynesia','webinara'); ?></option>
														<option value="French Southern Territories"><?php _e('French Southern Territories
														','webinara'); ?></option>
														<option value="Gabon"><?php _e('Gabon','webinara'); ?></option>
														<option value="Gambia"><?php _e('Gambia','webinara'); ?></option>
														<option value="Georgia"><?php _e('Georgia','webinara'); ?></option>
														<option value="Germany"><?php _e('Germany','webinara'); ?></option>
														<option value="Ghana"><?php _e('Ghana','webinara'); ?></option>
														<option value="Gibraltar"><?php _e('Gibraltar','webinara'); ?></option>
														<option value="Greece"><?php _e('Greece','webinara'); ?></option>
														<option value="Greenland"><?php _e('Greenland','webinara'); ?></option>
														<option value="Grenada"><?php _e('Grenada','webinara'); ?></option>
														<option value="Guadeloupe"><?php _e('Guadeloupe','webinara'); ?></option>
														<option value="Guam"><?php _e('Guam','webinara'); ?></option>
														<option value="Guatemala"><?php _e('Guatemala','webinara'); ?></option>
														<option value="Guernsey"><?php _e('Guernsey','webinara'); ?></option>
														<option value="Guinea"><?php _e('Guinea','webinara'); ?></option>
														<option value="Guinea-Bissau"><?php _e('Guinea-Bissau','webinara'); ?></option>
														<option value="Guyana"><?php _e('Guyana','webinara'); ?></option>
														<option value="Haiti"><?php _e('Haiti','webinara'); ?></option>
														<option value="Heard Island and McDonald Islands"><?php _e('Heard Island and McDonald
														Islands
														','webinara'); ?></option>
														<option value="Holy See (Vatican City State)"><?php _e('Holy See (Vatican City
														State)
														','webinara'); ?></option>
														<option value="Honduras"><?php _e('Honduras','webinara'); ?></option>
														<option value="Hong Kong"><?php _e('Hong Kong','webinara'); ?></option>
														<option value="Hungary"><?php _e('Hungary','webinara'); ?></option>
														<option value="Iceland"><?php _e('Iceland','webinara'); ?></option>
														<option value="India"><?php _e('India','webinara'); ?></option>
														 <option value="Indonesia"><?php _e('Indonesia','webinara'); ?></option>
														<option value="Iran, Islamic Republic of"><?php _e('Iran, Islamic Republic of','webinara'); ?></option>
														<option value="Iraq"><?php _e('Iraq','webinara'); ?></option>
														<option value="Ireland"><?php _e('Ireland','webinara'); ?></option>
														<option value="Isle of Man"><?php _e('Isle of Man','webinara'); ?></option>
														<option value="Israel"><?php _e('Israel','webinara'); ?></option>
														<option value="Italy"><?php _e('Italy','webinara'); ?></option>
														<option value="Jamaica"><?php _e('Jamaica','webinara'); ?></option>
														<option value="Japan"><?php _e('Japan','webinara'); ?></option>
														<option value="Jersey"><?php _e('Jersey','webinara'); ?></option>
														<option value="Jordan"><?php _e('Jordan','webinara'); ?></option>
														<option value="Kazakhstan"><?php _e('Kazakhstan','webinara'); ?></option>
														<option value="Kenya"><?php _e('Kenya','webinara'); ?></option>
														<option value="Kiribati"><?php _e('Kiribati','webinara'); ?></option>
														<option value="Korea, Democratic People's Republic of"><?php _e('Korea, Democratic
														People\'s Republic of
														','webinara'); ?></option>
														<option value="Korea, Republic of"><?php _e('Korea, Republic of','webinara'); ?></option>
														<option value="Kuwait"><?php _e('Kuwait','webinara'); ?></option>
														<option value="Kyrgyzstan"><?php _e('Kyrgyzstan','webinara'); ?></option>
														<option value="Lao People's Democratic Republic"><?php _e('Lao People\'s Democratic
														Republic
														','webinara'); ?></option>
														<option value="Latvia"><?php _e('Latvia','webinara'); ?></option>
														<option value="Lebanon"><?php _e('Lebanon','webinara'); ?></option>
														<option value="Lesotho"><?php _e('Lesotho','webinara'); ?></option>
														<option value="Liberia"><?php _e('Liberia','webinara'); ?></option>
														<option value="Libyan Arab Jamahiriya"><?php _e('Libyan Arab Jamahiriya','webinara'); ?></option>
														<option value="Liechtenstein"><?php _e('Liechtenstein','webinara'); ?></option>
														<option value="Lithuania"><?php _e('Lithuania','webinara'); ?></option>
														<option value="Luxembourg"><?php _e('Luxembourg','webinara'); ?></option>
														<option value="Macao"><?php _e('Macao','webinara'); ?></option>
														<option value="Macedonia, the former Yugoslav Republic of"><?php _e('Macedonia, the
														former Yugoslav Republic of
														','webinara'); ?></option>
														<option value="Madagascar"><?php _e('Madagascar','webinara'); ?></option>
														<option value="Malawi"><?php _e('Malawi','webinara'); ?></option>
														<option value="Malaysia"><?php _e('Malaysia','webinara'); ?></option>
														<option value="Maldives"><?php _e('Maldives','webinara'); ?></option>
														<option value="Mali"><?php _e('Mali','webinara'); ?></option>
														<option value="Malta"><?php _e('Malta','webinara'); ?></option>
														<option value="Marshall Islands"><?php _e('Marshall Islands','webinara'); ?></option>
														<option value="Martinique"><?php _e('Martinique','webinara'); ?></option>
														<option value="Mauritania"><?php _e('Mauritania','webinara'); ?></option>
														<option value="Mauritius"><?php _e('Mauritius','webinara'); ?></option>
														<option value="Mayotte"><?php _e('Mayotte','webinara'); ?></option>
														<option value="Mexico"><?php _e('Mexico','webinara'); ?></option>
														 <option value="Micronesia, Federated States of"><?php _e('Micronesia, Federated States
														of
														','webinara'); ?></option>
														<option value="Moldova, Republic of"><?php _e('Moldova, Republic of','webinara'); ?></option>
														<option value="Monaco"><?php _e('Monaco','webinara'); ?></option>
														<option value="Mongolia"><?php _e('Mongolia','webinara'); ?></option>
														<option value="Montenegro"><?php _e('Montenegro','webinara'); ?></option>
														<option value="Montserrat"><?php _e('Montserrat','webinara'); ?></option>
														<option value="Morocco"><?php _e('Morocco','webinara'); ?></option>
														<option value="Mozambique"><?php _e('Mozambique','webinara'); ?></option>
														<option value="Myanmar"><?php _e('Myanmar','webinara'); ?></option>
														<option value="Namibia"><?php _e('Namibia','webinara'); ?></option>
														<option value="Nauru"><?php _e('Nauru','webinara'); ?></option>
														<option value="Nepal"><?php _e('Nepal','webinara'); ?></option>
														<option value="Netherlands"><?php _e('Netherlands','webinara'); ?></option>
														<option value="Netherlands Antilles"><?php _e('Netherlands Antilles','webinara'); ?></option>
														<option value="New Caledonia"><?php _e('New Caledonia','webinara'); ?></option>
														<option value="New Zealand"><?php _e('New Zealand','webinara'); ?></option>
														<option value="Nicaragua"><?php _e('Nicaragua','webinara'); ?></option>
														<option value="Niger"><?php _e('Niger','webinara'); ?></option>
														<option value="Nigeria"><?php _e('Nigeria','webinara'); ?></option>
														<option value="Niue"><?php _e('Niue','webinara'); ?></option>
														<option value="Norfolk Island"><?php _e('Norfolk Island','webinara'); ?></option>
														<option value="Northern Mariana Islands"><?php _e('Northern Mariana Islands','webinara'); ?></option>
														<option value="Norway"><?php _e('Norway','webinara'); ?></option>
														<option value="Oman"><?php _e('Oman','webinara'); ?></option>
														<option value="Pakistan"><?php _e('Pakistan','webinara'); ?></option>
														<option value="Palau"><?php _e('Palau','webinara'); ?></option>
														<option value="Palestine"><?php _e('Palestine','webinara'); ?></option>
														<option value="Panama"><?php _e('Panama','webinara'); ?></option>
														<option value="Papua New Guinea"><?php _e('Papua New Guinea','webinara'); ?></option>
														<option value="Paraguay"><?php _e('Paraguay','webinara'); ?></option>
														<option value="Peru"><?php _e('Peru','webinara'); ?></option>
														<option value="Philippines"><?php _e('Philippines','webinara'); ?></option>
														<option value="Pitcairn"><?php _e('Pitcairn','webinara'); ?></option>
														<option value="Poland"><?php _e('Poland','webinara'); ?></option>
														<option value="Portugal"><?php _e('Portugal','webinara'); ?></option>
														<option value="Puerto Rico"><?php _e('Puerto Rico','webinara'); ?></option>
														<option value="Qatar"><?php _e('Qatar','webinara'); ?></option>
														<option value="Runion"><?php _e('Runion','webinara'); ?></option>
														<option value="Romania"><?php _e('Romania','webinara'); ?></option>
														<option value="Russian Federation"><?php _e('Russian Federation','webinara'); ?></option>
														<option value="Rwanda"><?php _e('Rwanda','webinara'); ?></option>
														<option value="Saint Barthélemy"><?php _e('Saint Barthélemy','webinara'); ?></option>
														<option value="Saint Helena"><?php _e('Saint Helena','webinara'); ?></option>
														<option value="Saint Kitts and Nevis"><?php _e('Saint Kitts and Nevis','webinara'); ?></option>
														 <option value="Saint Lucia"><?php _e('Saint Lucia','webinara'); ?></option>
														<option value="Saint Martin (French part)"><?php _e('Saint Martin (French part)
														','webinara'); ?></option>
														<option value="Saint Pierre and Miquelon"><?php _e('Saint Pierre and Miquelon','webinara'); ?></option>
														<option value="Saint Vincent and the Grenadines"><?php _e('Saint Vincent and the
														Grenadines
														','webinara'); ?></option>
														<option value="Samoa"><?php _e('Samoa','webinara'); ?></option>
														<option value="San Marino"><?php _e('San Marino','webinara'); ?></option>
														<option value="Sao Tome and Principe"><?php _e('Sao Tome and Principe','webinara'); ?></option>
														<option value="Saudi Arabia"><?php _e('Saudi Arabia','webinara'); ?></option>
														<option value="Senegal"><?php _e('Senegal','webinara'); ?></option>
														<option value="Serbia"><?php _e('Serbia','webinara'); ?></option>
														<option value="Seychelles"><?php _e('Seychelles','webinara'); ?></option>
														<option value="Sierra Leone"><?php _e('Sierra Leone','webinara'); ?></option>
														<option value="Singapore"><?php _e('Singapore','webinara'); ?></option>
														<option value="Slovakia"><?php _e('Slovakia','webinara'); ?></option>
														<option value="Slovenia"><?php _e('Slovenia','webinara'); ?></option>
														<option value="Solomon Islands"><?php _e('Solomon Islands','webinara'); ?></option>
														<option value="Somalia"><?php _e('Somalia','webinara'); ?></option>
														<option value="South Africa"><?php _e('South Africa','webinara'); ?></option>
														<option value="South Georgia and the South Sandwich Islands"><?php _e('South Georgia
														and the South Sandwich Islands
														','webinara'); ?></option>
														<option value="Spain"><?php _e('Spain','webinara'); ?></option>
														<option value="Sri Lanka"><?php _e('Sri Lanka','webinara'); ?></option>
														<option value="Sudan"><?php _e('Sudan','webinara'); ?></option>
														<option value="Suriname"><?php _e('Suriname','webinara'); ?></option>
														<option value="Svalbard and Jan Mayen"><?php _e('Svalbard and Jan Mayen','webinara'); ?></option>
														<option value="Swaziland"><?php _e('Swaziland','webinara'); ?></option>
														<option value="Sweden"><?php _e('Sweden','webinara'); ?></option>
														<option value="Switzerland"><?php _e('Switzerland','webinara'); ?></option>
														<option value="Syrian Arab Republic"><?php _e('Syrian Arab Republic','webinara'); ?></option>
														<option value="Taiwan, Province of China"><?php _e('Taiwan, Province of China','webinara'); ?></option>
														<option value="Tajikistan"><?php _e('Tajikistan','webinara'); ?></option>
														<option value="Tanzania, United Republic of"><?php _e('Tanzania, United Republic of
														','webinara'); ?></option>
														<option value="Thailand"><?php _e('Thailand','webinara'); ?></option>
														<option value="Timor-Leste"><?php _e('Timor-Leste','webinara'); ?></option>
														<option value="Togo"><?php _e('Togo','webinara'); ?></option>
														<option value="Tokelau"><?php _e('Tokelau','webinara'); ?></option>
														<option value="Tonga"><?php _e('Tonga','webinara'); ?></option>
														<option value="Trinidad and Tobago"><?php _e('Trinidad and Tobago','webinara'); ?></option>
														<option value="Tunisia"><?php _e('Tunisia','webinara'); ?></option>
														<option value="Turkey"><?php _e('Turkey','webinara'); ?></option>
														<option value="Turkmenistan"><?php _e('Turkmenistan','webinara'); ?></option>
														<option value="Turks and Caicos Islands"><?php _e('Turks and Caicos Islands','webinara'); ?></option>
														<option value="Tuvalu"><?php _e('Tuvalu','webinara'); ?></option>
														<option value="Uganda"><?php _e('Uganda','webinara'); ?></option>
														<option value="Ukraine"><?php _e('Ukraine','webinara'); ?></option>
														<option value="United Arab Emirates"><?php _e('United Arab Emirates','webinara'); ?></option>
														<option value="United Kingdom"><?php _e('United Kingdom','webinara'); ?></option>
														<option value="United States"><?php _e('United States','webinara'); ?></option>
														<option value="United States Minor Outlying Islands"><?php _e('United States Minor
														Outlying Islands
														','webinara'); ?></option>
														<option value="Uruguay"><?php _e('Uruguay','webinara'); ?></option>
														<option value="Uzbekistan"><?php _e('Uzbekistan','webinara'); ?></option>
														<option value="Vanuatu"><?php _e('Vanuatu','webinara'); ?></option>
														<option value="Venezuela, Bolivarian Republic of"><?php _e('Venezuela, Bolivarian
														Republic of
														','webinara'); ?></option>
														<option value="Viet Nam"><?php _e('Viet Nam','webinara'); ?></option>
														<option value="Virgin Islands, British"><?php _e('Virgin Islands, British','webinara'); ?></option>
														<option value="Virgin Islands, U.S."><?php _e('Virgin Islands, U.S.','webinara'); ?></option>
														<option value="Wallis and Futuna"><?php _e('Wallis and Futuna','webinara'); ?></option>
														<option value="Western Sahara"><?php _e('Western Sahara','webinara'); ?></option>
														<option value="Yemen"><?php _e('Yemen','webinara'); ?></option>
														<option value="Zambia"><?php _e('Zambia','webinara'); ?></option>
														<option value="Zimbabwe"><?php _e('Zimbabwe','webinara'); ?></option>
													</select>
												</td>
											</tr>
											<tr valign="top" class="">
												<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Timezone*','webinara'); ?></label></th>
												<td>
													<select id="webireg_prefzone" name="webiprofile[_webireg_prefzone]" class="input-select">
														<option disabled="" selected=""></option>
														<option value="Pacific/Samoa"><?php _e('Midway Island, Samoa','webinara'); ?></option>
														<option value="Pacific/Honolulu"><?php _e('Hawaii','webinara'); ?></option>
														<option value="America/Anchorage"><?php _e('Alaska','webinara'); ?></option>
														<option value="America/Los_Angeles"><?php _e('Pacific Time (US and Canada), Tijuana','webinara'); ?></option>
														<option value="America/Phoenix"><?php _e('Arizona','webinara'); ?></option>
														<option value="America/Denver"><?php _e('Mountain Time (US and Canada)','webinara'); ?></option>
														<option value="America/Mexico_City"><?php _e('Mexico City','webinara'); ?></option>
														<option value="America/Chicago"><?php _e('Central Time (US and Canada)','webinara'); ?></option>
														<option value="Canada/Saskatchewan"><?php _e('Regina','webinara'); ?></option>
														<option value="America/Bogota"><?php _e('Bogota, Lima, Quito','webinara'); ?></option>
														<option value="America/Indianapolis"><?php _e('Indiana (East)','webinara'); ?></option>
														<option value="America/New_York"><?php _e('Eastern Time (US and Canada)','webinara'); ?></option>
														<option value="America/Caracas"><?php _e('Caracas, La Paz','webinara'); ?></option>
														<option value="America/Halifax"><?php _e('Atlantic Time (Canada)','webinara'); ?></option>
														<option value="America/Guyana"><?php _e('Georgetown','webinara'); ?></option>
														<option value="America/St_Johns"><?php _e('Newfoundland','webinara'); ?></option>
														<option value="America/Buenos_Aires"><?php _e('Buenos Aires','webinara'); ?></option>
														<option value="America/Santiago"><?php _e('Santiago','webinara'); ?></option>
														<option value="America/Sao_Paulo"><?php _e('Brasilia','webinara'); ?></option>
														<option value="Atlantic/Azores"><?php _e('Azores','webinara'); ?></option>
														<option value="Atlantic/Cape_Verde"><?php _e('Cape Verde Is.','webinara'); ?></option>
														<option value="GMT"><?php _e('Greenwich Mean Time','webinara'); ?></option>
														<option value="Africa/Casablanca"><?php _e('Casablanca, Monrovia','webinara'); ?></option>
														<option value="Europe/London"><?php _e('Dublin, Edinburgh, Lisbon, London','webinara'); ?></option>
														<option value="Europe/Prague"><?php _e('Belgrade, Bratislava, Budapest, Ljubljana, Prague','webinara'); ?></option>
														<option value="Africa/Malabo"><?php _e('West Central Africa','webinara'); ?></option>
														<option value="Europe/Warsaw"><?php _e('Sarajevo, Skopje, Sofija, Vilnius, Warsaw, Zagreb','webinara'); ?></option>
														<option value="Europe/Brussels"><?php _e('Brussels, Copenhagen, Madrid, Paris','webinara'); ?></option>
														<option value="Europe/Amsterdam"><?php _e('Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna','webinara'); ?></option>
														<option value="Africa/Harare"><?php _e('Harare, Pretoria','webinara'); ?></option>
														<option value="Europe/Helsinki"><?php _e('Helsinki, Riga, Tallinn','webinara'); ?></option>
														<option value="Europe/Athens"><?php _e('Athens, Istanbul','webinara'); ?></option>
														<option value="Asia/Jerusalem"><?php _e('Jerusalem','webinara'); ?></option>
														<option value="Africa/Cairo"><?php _e('Cairo','webinara'); ?></option>
														<option value="Europe/Bucharest"><?php _e('Bucharest','webinara'); ?></option>
														<option value="Asia/Kuwait"><?php _e('Kuwait, Riyadh','webinara'); ?></option>
														<option value="Europe/Minsk"><?php _e('Minsk','webinara'); ?></option>
														<option value="Africa/Nairobi"><?php _e('Nairobi','webinara'); ?></option>
														<option value="Asia/Baghdad"><?php _e('Baghdad','webinara'); ?></option>
														<option value="Europe/Moscow"><?php _e('Moscow, St. Petersburg, Volgograd','webinara'); ?></option>
														<option value="Asia/Tehran"><?php _e('Tehran','webinara'); ?></option>
														<option value="Asia/Tbilisi"><?php _e('Baku,Tbilisi, Yerevan','webinara'); ?></option>
														<option value="Asia/Muscat"><?php _e('Abu Dhabi, Muscat','webinara'); ?></option>
														<option value="Asia/Kabul"><?php _e('Kabul','webinara'); ?></option>
														<option value="Asia/Yekaterinburg"><?php _e('Yekaterinburg','webinara'); ?></option>
														<option value="Asia/Karachi"><?php _e('Islamabad, Karachi, Tashkent','webinara'); ?></option>
														<option value="Asia/Kolkata"><?php _e('Calcutta, Chennai, Mumbai, New Delhi','webinara'); ?></option>
														<option value="Asia/Colombo"><?php _e('SriJayawardenepura','webinara'); ?></option>
														<option value="Asia/Katmandu"><?php _e('Kathmandu','webinara'); ?></option>
														<option value="Asia/Novosibirsk"><?php _e('Almaty, Novosibirsk','webinara'); ?></option>
														<option value="Asia/Dhaka"><?php _e('Astana, Dhaka','webinara'); ?></option>
														<option value="Asia/Rangoon"><?php _e('Rangoon','webinara'); ?></option>
														<option value="Asia/Bangkok"><?php _e('Bangkok','webinara'); ?></option>
														<option value="Asia/Krasnoyarsk"><?php _e('Krasnoyarsk','webinara'); ?></option>
														<option value="Asia/Jakarta"><?php _e('Hanoi, Jakarta','webinara'); ?></option>
														<option value="Asia/Hong_Kong"><?php _e('Hong Kong','webinara'); ?></option>
														<option value="Asia/Shanghai"><?php _e('Beijing, Chongqing, Urumqi, Taipei','webinara'); ?></option>
														<option value="Australia/Perth"><?php _e('Perth','webinara'); ?></option>
														<option value="Asia/Taipei"><?php _e('Taipei','webinara'); ?></option>
														<option value="Asia/Singapore"><?php _e('Kuala Lumpur, Singapore','webinara'); ?></option>
														<option value="Asia/Irkutsk"><?php _e('Irkutsk, Ulaan Bataar','webinara'); ?></option>
														<option value="Asia/Seoul"><?php _e('Seoul','webinara'); ?></option>
														<option value="Asia/Tokyo"><?php _e('Osaka, Sapporo, Tokyo','webinara'); ?></option>
														<option value="Asia/Yakutsk"><?php _e('Yakutsk','webinara'); ?></option>
														<option value="Australia/Darwin"><?php _e('Darwin','webinara'); ?></option>
														<option value="Asia/Vladivostok"><?php _e('Vladivostok','webinara'); ?></option>
														<option value="Pacific/Guam"><?php _e('Guam, Port Moresby','webinara'); ?></option>
														<option value="Asia/Magadan"><?php _e('Magadan, Solomon Is., New Caledonia','webinara'); ?></option>
														<option value="Australia/Brisbane"><?php _e('Brisbane','webinara'); ?></option>
														<option value="Australia/Adelaide"><?php _e('Adelaide','webinara'); ?></option>
														<option value="Australia/Sydney"><?php _e('Canberra, Melbourne, Sydney','webinara'); ?></option>
														<option value="Australia/Hobart"><?php _e('Hobart','webinara'); ?></option>
														<option value="Pacific/Fiji"><?php _e('Fiji, Kamchatka, Marshall Is.','webinara'); ?></option>
														<option value="Pacific/Tongatapu"><?php _e('Nukualofa','webinara'); ?></option>
														<option value="Pacific/Auckland"><?php _e('Auckland, Wellington','webinara'); ?></option> 
													</select>										
												</td>
											</tr>
											<tr valign="top" class="">
												<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Webinar solution*','webinara'); ?></label></th>
												<td>
													<select id="webireg_websolution" name="webiprofile[_webireg_websolution]" class="input-select">
														<option disabled="" selected=""></option>
														<option value="Adobe Connect"><?php _e('Adobe Connect','webinara'); ?></option>
														<option value="AnyMeeting"><?php _e('AnyMeeting','webinara'); ?></option>
														<option value="BeaconLive"><?php _e('BeaconLive','webinara'); ?></option>
														<option value="Bigmarker"><?php _e('Bigmarker','webinara'); ?></option>
														<option value="Cisco WebEx"><?php _e('Cisco WebEx','webinara'); ?></option>
														 <option value="ClickWebinar"><?php _e('ClickWebinar','webinara'); ?></option>
														<option value="EasyWebinar"><?php _e('EasyWebinar','webinara'); ?></option>
														<option value="FuzeBox"><?php _e('FuzeBox','webinara'); ?></option>
														<option value="GlobalMeet"><?php _e('GlobalMeet','webinara'); ?></option>
														<option value="Google Hangout"><?php _e('Google Hangout','webinara'); ?></option>
														<option value="GoToMeeting"><?php _e('GoToMeeting','webinara'); ?></option>
														<option value="GoToWebinar"><?php _e('GoToWebinar','webinara'); ?></option>
														<option value="iLinc"><?php _e('iLinc','webinara'); ?></option>
														<option value="Infinite"><?php _e('Infinite','webinara'); ?></option>
														<option value="InterCall"><?php _e('InterCall','webinara'); ?></option>
														<option value="join. me"><?php _e('join. me','webinara'); ?></option>
														<option value="MeetingBurner"><?php _e('MeetingBurner','webinara'); ?></option>
														<option value="MegaMeeting"><?php _e('MegaMeeting','webinara'); ?></option>
														<option value="Microsoft Lync"><?php _e('Microsoft Lync','webinara'); ?></option>
														<option value="Mikogo"><?php _e('Mikogo','webinara'); ?></option>
														<option value="ON24"><?php _e('ON24','webinara'); ?></option>
														<option value="Onstream Webinars"><?php _e('Onstream Webinars','webinara'); ?></option>
														<option value="Rally Point"><?php _e('Rally Point','webinara'); ?></option>
														<option value="ReadyTalk"><?php _e('ReadyTalk','webinara'); ?></option>
														<option value="Skype"><?php _e('Skype','webinara'); ?></option>
														<option value="VIA3"><?php _e('VIA3','webinara'); ?></option>
														<option value="VoiceBoxer"><?php _e('VoiceBoxer','webinara'); ?></option>
														<option value="Web Conferencing Central"><?php _e('Web Conferencing
														Central
														','webinara'); ?></option>
														<option value="Yugma"><?php _e('Yugma','webinara'); ?></option>
														<option value="Yuuguu"><?php _e('Yuuguu','webinara'); ?></option>
														<option value="XPOCAST"><?php _e('XPOCAST','webinara'); ?></option>
														<option value="Zoho Meeting"><?php _e('Zoho Meeting','webinara'); ?></option>
														<option value="Other"><?php _e('Other','webinara'); ?></option>
													</select>										
												</td>
											</tr>
											<tr valign="top" class="">
												<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Company*','webinara'); ?></label></th>
												<td>
													<input type="text" name="webiprofile[_webireg_cname]" class="webireg_fld" id="webireg_cname">										
												</td>
											</tr>
											<tr valign="top" class="">
												<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Company Industry*','webinara'); ?></label></th>
												<td>
													<select id="webireg_cindustry" name="webiprofile[_webireg_cindustry]" class="input-select">
														<option disabled="" selected=""></option>
														<option value="Accounting"><?php _e('Accounting','webinara'); ?></option>
														<option value="Airlines/Aviation"><?php _e('Airlines/Aviation','webinara'); ?></option>
														<option value="Alternative Dispute Resolution"><?php _e('Alternative Dispute
														Resolution
														','webinara'); ?></option>
														<option value="Alternative Medicine"><?php _e('Alternative Medicine
														','webinara'); ?></option>
														<option value="Animation"><?php _e('Animation','webinara'); ?></option>
														<option value="Apparel &amp; Fashion"><?php _e('Apparel &amp; Fashion','webinara'); ?></option>
														<option value="Architecture &amp; Planning"><?php _e('Architecture &amp; Planning
														','webinara'); ?></option>
														<option value="Arts and Crafts"><?php _e('Arts and Crafts','webinara'); ?></option>
														<option value="Automotive"><?php _e('Automotive','webinara'); ?></option>
														<option value="Aviation &amp; Aerospace"><?php _e('Aviation &amp; Aerospace','webinara'); ?></option>
														<option value="Banking"><?php _e('Banking','webinara'); ?></option>
														<option value="Biotechnology"><?php _e('Biotechnology','webinara'); ?></option>
														<option value="Broadcast Media"><?php _e('Broadcast Media','webinara'); ?></option>
														<option value="Building Materials"><?php _e('Building Materials','webinara'); ?></option>
														<option value="Business Supplies and Equipment"><?php _e('Business Supplies and
														Equipment
														','webinara'); ?></option>
														<option value="Capital Markets"><?php _e('Capital Markets','webinara'); ?></option>
														<option value="Chemicals"><?php _e('Chemicals','webinara'); ?></option>
														<option value="Civic &amp; Social Organization"><?php _e('Civic &amp; Social
														Organization
														','webinara'); ?></option>
														<option value="Civil Engineering"><?php _e('Civil Engineering','webinara'); ?></option>
														<option value="Commercial Real Estate"><?php _e('Commercial Real Estate','webinara'); ?></option>
														<option value="Computer &amp; Network Security"><?php _e('Computer &amp; Network
														Security
														','webinara'); ?></option>
														<option value="Computer Games"><?php _e('Computer Games','webinara'); ?></option>
														<option value="Computer Hardware"><?php _e('Computer Hardware','webinara'); ?></option>
														<option value="Computer Networking"><?php _e('Computer Networking','webinara'); ?></option>
														<option value="Computer Software"><?php _e('Computer Software','webinara'); ?></option>
														<option value="Construction"><?php _e('Construction','webinara'); ?></option>
														<option value="Consumer Electronics"><?php _e('Consumer Electronics','webinara'); ?></option>
														<option value="Consumer Goods"><?php _e('Consumer Goods','webinara'); ?></option>
														<option value="Consumer Services"><?php _e('Consumer Services','webinara'); ?></option>
														<option value="Cosmetics"><?php _e('Cosmetics','webinara'); ?></option>
														<option value="Dairy"><?php _e('Dairy','webinara'); ?></option>
														<option value="Defense &amp; Space"><?php _e('Defense &amp; Space','webinara'); ?></option>
														<option value="Design"><?php _e('Design','webinara'); ?></option>
														<option value="Education Management"><?php _e('Education Management','webinara'); ?></option>
														<option value="E-Learning"><?php _e('E-Learning','webinara'); ?></option>
														<option value="Electrical/Electronic Manufacturing"><?php _e('Electrical/Electronic
														Manufacturing
														','webinara'); ?></option>
														<option value="Entertainment"><?php _e('Entertainment','webinara'); ?></option>
														<option value="Environmental Services"><?php _e('Environmental Services','webinara'); ?></option>
														<option value="Events Services"><?php _e('Events Services','webinara'); ?></option>
														<option value="Executive Office"><?php _e('Executive Office','webinara'); ?></option>
														<option value="Facilities Services"><?php _e('Facilities Services','webinara'); ?></option>
														<option value="Farming"><?php _e('Farming','webinara'); ?></option>
														<option value="Financial Services"><?php _e('Financial Services','webinara'); ?></option>
														<option value="Fine Art"><?php _e('Fine Art','webinara'); ?></option>
														<option value="Fishery"><?php _e('Fishery','webinara'); ?></option>
														<option value="Food &amp; Beverages"><?php _e('Food &amp; Beverages','webinara'); ?></option>
														<option value="Food Production"><?php _e('Food Production','webinara'); ?></option>
														<option value="Fund-Raising"><?php _e('Fund-Raising','webinara'); ?></option>
														<option value="Furniture"><?php _e('Furniture','webinara'); ?></option>
														<option value="Gambling &amp; Casinos"><?php _e('Gambling &amp; Casinos','webinara'); ?></option>
														<option value="Glass, Ceramics &amp; Concrete"><?php _e('Glass, Ceramics &amp;
														Concrete
														','webinara'); ?></option>
														<option value="Government Administration"><?php _e('Government Administration','webinara'); ?></option>
														<option value="Government Relations"><?php _e('Government Relations','webinara'); ?></option>
														<option value="Graphic Design"><?php _e('Graphic Design','webinara'); ?></option>
														<option value="Health, Wellness and Fitness"><?php _e('Health, Wellness and Fitness
														','webinara'); ?></option>
														<option value="Higher Education"><?php _e('Higher Education','webinara'); ?></option>
														<option value="Hospital &amp; Health Care"><?php _e('Hospital &amp; Health Care
														','webinara'); ?></option>
														<option value="Hospitality"><?php _e('Hospitality','webinara'); ?></option>
														<option value="Human Resources"><?php _e('Human Resources','webinara'); ?></option>
														<option value="Import and Export"><?php _e('Import and Export','webinara'); ?></option>
														<option value="Individual &amp; Family Services"><?php _e('Individual &amp; Family
														Services
														','webinara'); ?></option>
														<option value="Industrial Automation"><?php _e('Industrial Automation','webinara'); ?></option>
														<option value="Information Services"><?php _e('Information Services','webinara'); ?></option>
														<option value="Information Technology and Services"><?php _e('Information Technology
														and Services
														','webinara'); ?></option>
														<option value="Insurance"><?php _e('Insurance','webinara'); ?></option>
														<option value="International Affairs"><?php _e('International Affairs','webinara'); ?></option>
														<option value="International Trade and Development"><?php _e('International Trade and
														Development
														','webinara'); ?></option>
														<option value="Internet"><?php _e('Internet','webinara'); ?></option>
														<option value="Investment Banking"><?php _e('Investment Banking','webinara'); ?></option>
														<option value="Investment Management"><?php _e('Investment Management','webinara'); ?></option>
														<option value="Judiciary"><?php _e('Judiciary','webinara'); ?></option>
														<option value="Law Enforcement"><?php _e('Law Enforcement','webinara'); ?></option>
														<option value="Law Practice"><?php _e('Law Practice','webinara'); ?></option>
														<option value="Legal Services"><?php _e('Legal Services','webinara'); ?></option>
														<option value="Legislative Office"><?php _e('Legislative Office','webinara'); ?></option>
														<option value="Leisure, Travel &amp; Tourism"><?php _e('Leisure, Travel &amp;
														Tourism
														','webinara'); ?></option>
														<option value="Libraries"><?php _e('Libraries','webinara'); ?></option>
														<option value="Logistics and Supply Chain"><?php _e('Logistics and Supply Chain
														','webinara'); ?></option>
														<option value="Luxury Goods &amp; Jewelry"><?php _e('Luxury Goods &amp; Jewelry
														','webinara'); ?></option>
														<option value="Machinery"><?php _e('Machinery','webinara'); ?></option>
														 <option value="Management Consulting"><?php _e('Management Consulting','webinara'); ?></option>
														<option value="Maritime"><?php _e('Maritime','webinara'); ?></option>
														<option value="Marketing and Advertising"><?php _e('Marketing and Advertising','webinara'); ?></option>
														<option value="Market Research"><?php _e('Market Research','webinara'); ?></option>
														<option value="Mechanical or Industrial Engineering"><?php _e('Mechanical or
														Industrial Engineering
														','webinara'); ?></option>
														<option value="Media Production"><?php _e('Media Production','webinara'); ?></option>
														<option value="Medical Devices"><?php _e('Medical Devices','webinara'); ?></option>
														<option value="Medical Practice"><?php _e('Medical Practice','webinara'); ?></option>
														<option value="Mental Health Care"><?php _e('Mental Health Care','webinara'); ?></option>
														<option value="Military"><?php _e('Military','webinara'); ?></option>
														<option value="Mining &amp; Metals"><?php _e('Mining &amp; Metals','webinara'); ?></option>
														<option value="Motion Pictures and Film"><?php _e('Motion Pictures and Film','webinara'); ?></option>
														<option value="Museums and Institutions"><?php _e('Museums and Institutions','webinara'); ?></option>
														<option value="Music"><?php _e('Music','webinara'); ?></option>
														<option value="Nanotechnology"><?php _e('Nanotechnology','webinara'); ?></option>
														<option value="Newspapers"><?php _e('Newspapers','webinara'); ?></option>
														<option value="Nonprofit Organization Management"><?php _e('Nonprofit Organization
														Management
														','webinara'); ?></option>
														<option value="Oil &amp; Energy"><?php _e('Oil &amp; Energy','webinara'); ?></option>
														<option value="Online Media"><?php _e('Online Media','webinara'); ?></option>
														<option value="Outsourcing/Offshoring"><?php _e('Outsourcing/Offshoring','webinara'); ?></option>
														<option value="Package/Freight Delivery"><?php _e('Package/Freight Delivery','webinara'); ?></option>
														<option value="Packaging and Containers"><?php _e('Packaging and Containers','webinara'); ?></option>
														<option value="Paper &amp; Forest Products"><?php _e('Paper &amp; Forest Products
														','webinara'); ?></option>
														<option value="Performing Arts"><?php _e('Performing Arts','webinara'); ?></option>
														<option value="Pharmaceuticals"><?php _e('Pharmaceuticals','webinara'); ?></option>
														<option value="Philanthropy"><?php _e('Philanthropy','webinara'); ?></option>
														<option value="Photography"><?php _e('Photography','webinara'); ?></option>
														<option value="Plastics"><?php _e('Plastics','webinara'); ?></option>
														<option value="Political Organization"><?php _e('Political Organization','webinara'); ?></option>
														<option value="Primary/Secondary Education"><?php _e('Primary/Secondary Education
														','webinara'); ?></option>
														<option value="Printing"><?php _e('Printing','webinara'); ?></option>
														<option value="Professional Training &amp; Coaching"><?php _e('Professional Training
														&amp; Coaching
														','webinara'); ?></option>
														<option value="Program Development"><?php _e('Program Development','webinara'); ?></option>
														<option value="Public Policy"><?php _e('Public Policy','webinara'); ?></option>
														<option value="Public Relations and Communications"><?php _e('Public Relations and
														Communications
														','webinara'); ?></option>
														<option value="Public Safety"><?php _e('Public Safety','webinara'); ?></option>
														<option value="Publishing"><?php _e('Publishing','webinara'); ?></option>
														<option value="Railroad Manufacture"><?php _e('Railroad Manufacture','webinara'); ?></option>
														<option value="Ranching"><?php _e('Ranching','webinara'); ?></option>
														<option value="Real Estate"><?php _e('Real Estate','webinara'); ?></option>
														<option value="Recreational Facilities and Services"><?php _e('Recreational Facilities
														and Services
														','webinara'); ?></option>
														<option value="Religious Institutions"><?php _e('Religious Institutions','webinara'); ?></option>
														<option value="Renewables &amp; Environment"><?php _e('Renewables &amp; Environment
														','webinara'); ?></option>
														<option value="Research"><?php _e('Research','webinara'); ?></option>
														<option value="Restaurants"><?php _e('Restaurants','webinara'); ?></option>
														<option value="Retail"><?php _e('Retail','webinara'); ?></option>
														<option value="Security and Investigations"><?php _e('Security and Investigations
														','webinara'); ?></option>
														<option value="Semiconductors"><?php _e('Semiconductors','webinara'); ?></option>
														<option value="Shipbuilding"><?php _e('Shipbuilding','webinara'); ?></option>
														<option value="Sporting Goods"><?php _e('Sporting Goods','webinara'); ?></option>
														<option value="Sports"><?php _e('Sports','webinara'); ?></option>
														<option value="Staffing and Recruiting"><?php _e('Staffing and Recruiting','webinara'); ?></option>
														<option value="Supermarkets"><?php _e('Supermarkets','webinara'); ?></option>
														<option value="Telecommunications"><?php _e('Telecommunications','webinara'); ?></option>
														<option value="Textiles"><?php _e('Textiles','webinara'); ?></option>
														<option value="Think Tanks"><?php _e('Think Tanks','webinara'); ?></option>
														<option value="Tobacco"><?php _e('Tobacco','webinara'); ?></option>
														<option value="Translation and Localization"><?php _e('Translation and Localization
														','webinara'); ?></option>
														<option value="Transportation/Trucking/Railroad"><?php _e('
														Transportation/Trucking/Railroad
														','webinara'); ?></option>
														<option value="Utilities"><?php _e('Utilities','webinara'); ?></option>
														<option value="Venture Capital &amp; Private Equity"><?php _e('Venture Capital &amp;
														Private Equity
														','webinara'); ?></option>
														<option value="Veterinary"><?php _e('Veterinary','webinara'); ?></option>
														<option value="Warehousing"><?php _e('Warehousing','webinara'); ?></option>
														<option value="Wholesale"><?php _e('Wholesale','webinara'); ?></option>
														<option value="Wine and Spirits"><?php _e('Wine and Spirits','webinara'); ?></option>
														<option value="Wireless"><?php _e('Wireless','webinara'); ?></option>
														<option value="Writing and Editing"><?php _e('Writing and Editing','webinara'); ?></option>
													</select>										
												</td>
											</tr>
											<tr valign="top" class="">
												<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Website*','webinara'); ?></label></th>
												<td>
													<input type="text" name="webiprofile[_webireg_website]" class="webireg_fld" id="webireg_website">										
												</td>
											</tr>
											<tr valign="top" class="">
												<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('Company Size*','webinara'); ?></label></th>
												<td>
													<select name="_webireg_csize" id="webireg_csize" class="input-select">
														<option disabled="" selected=""></option>
														<option value="Less than 5 Employees"><?php _e('Less than 5 Employees','webinara'); ?></option>
														<option value="5 - 9 Employees"><?php _e('5 - 9 Employees','webinara'); ?></option>
														<option value="10 - 19 Employees"><?php _e('10 - 19 Employees','webinara'); ?></option>
														<option value="20 - 29 Employees"><?php _e('20 - 29 Employees
														','webinara'); ?></option>
														<option value="30 - 49 Employees"><?php _e('30 - 49 Employees','webinara'); ?></option>
														<option value="50 - 99 Employees"><?php _e('50 - 99 Employees','webinara'); ?></option>
														<option value="100 - 249 Employees"><?php _e('100 - 249 Employees','webinara'); ?></option>
														<option value="250 - 499 Employees"><?php _e('250 - 499 Employees','webinara'); ?></option>
														<option value="500 - 999 Employees"><?php _e('500 - 999 Employees','webinara'); ?></option>
														<option value="1000 - 4999 Employees"><?php _e('1000 - 4999 Employees','webinara'); ?></option>
														<option value="5000 - 9999 Employees"><?php _e('5000 - 9999 Employees','webinara'); ?></option>
														<option value="10000 - 24999 Employees"><?php _e('10000 - 24999 Employees
														','webinara'); ?></option>
														<option value="25000+ Employees"><?php _e('25000+ Employees','webinara'); ?></option>
														<option value="Prefer Not to Answer"><?php _e('Prefer Not to Answer','webinara'); ?></option>
													</select>										
												</td>
											</tr>
											<tr valign="top" class="">
												<th scope="row"><label for="setting-webi_webinars_license"><?php esc_html_e('About company(will be displayed for your company profile)*','webinara'); ?></label></th>
												<td>
													<textarea name="webiprofile[_webireg_cdesc]" class="webireg_fld" id="webireg_cdesc" rows="8"></textarea>										
												</td>
											</tr>
										</tbody>
									
										<tbody>									
											<tr valign="top" class="">
												<th scope="row"></th>
												<td>
													<a href="javascript:void(0);" class="button-primary" id="webi_upgrade_license"><?php esc_html_e('Upgrade','webinara'); ?></a>
													<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>	
													<p class="acc_response"></p>
													<input type="hidden" id="ustep" value="1">
												</td>
											</tr>	
										</tbody>
									</table>
								</div>	
							<?php
							}
						} 
						if($active_tab == 'webinara-connect')
						{
							echo '<input type="hidden" id="webi_url" value="'.admin_url( 'edit.php?post_type=webinar&page=webinara_plugin').'">';
							echo '<input type="hidden" id="zoom_url" value="'.urlencode(admin_url( 'edit.php?post_type=webinar&page=webinara_plugin&tab=webinara-connect&zoom')).'">';
							$webi_license_key = get_option('_webi_license_key');
							echo '<h2>'.__('Webinar platforms','webinara').'</h2>';
								$webi_gotowebinar_key = get_option('_webi_gotowebinar_key');
								$webi_gotowebinar_secret = get_option('_webi_gotowebinar_secret');
								$webi_zoom_key = get_option('_webi_zoom_key');
								$webi_zoom_secret = get_option('_webi_zoom_secret');						
								if(isset($_GET['code']) && isset($_GET['goto'])){
									$code = trim($_GET['code']);
									$response = wp_remote_post( 'https://api.getgo.com/oauth/v2/token', array(
										'headers' => array(
											'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
											'Authorization' => 'Basic '.base64_encode($webi_gotowebinar_key.':'.$webi_gotowebinar_secret),
											'Accept' => 'application/json',
										),
										'body' => array(
											'code' => $code,
											'grant_type' => 'authorization_code',
										),
									) );
									
									if ( ! is_wp_error( $response ) ) {
										// The request went through successfully, check the response code against
										// what we're expecting
										if ( 200 == wp_remote_retrieve_response_code( $response ) ) {
											
											//lets get the response and decode it
											$jsondata = json_decode($response['body'],true); 
											
											//lets pull our key variables from the response
											update_option('_webi_goto_connect',1);
											update_option('_webi_goto_organizer_key',$jsondata['organizer_key']);
											update_option('_webi_goto_account_key',$jsondata['account_key']);
											update_option('_webi_goto_access_token',$jsondata['access_token']);
											update_option('_webi_goto_refresh_token',$jsondata['refresh_token']);
											update_option('_webi_goto_refresh_token_expire_on',date('Y-m-d', strtotime("+28 days")));
											update_option('_webi_goto_access_token_expire_on',date('Y-m-d H:i:s', strtotime("+50 minutes")));										
											set_transient('_webi_goto_access_token',$jsondata['access_token'],60*50);
										}
									}																											
								}
								else
								{
									if(empty(get_option('_webi_goto_connect')))
									{
										delete_option('_webi_gotowebinar_key');
										delete_option('_webi_gotowebinar_secret');
									}
								}
								
								if(isset($_GET['code']) && isset($_GET['zoom'])){
									$code = trim($_GET['code']);
									$response = wp_remote_post( 'https://api.zoom.us/oauth/token', array(
										'headers' => array(
											'Content-Type' => 'application/x-www-form-urlencoded',
											'Authorization' => 'Basic '.base64_encode($webi_zoom_key.':'.$webi_zoom_secret),
											'Accept' => 'application/json',
										),
										'body' => array(
											'code' => $code,
											'grant_type' => 'authorization_code',
											'redirect_uri' =>admin_url( 'edit.php?post_type=webinar&page=webinara_plugin&tab=webinara-connect&zoom'),
										),
									) );
									
									if ( ! is_wp_error( $response ) ) {
										// The request went through successfully, check the response code against
										// what we're expecting
										if ( 200 == wp_remote_retrieve_response_code( $response ) ) {
											
											//lets get the response and decode it
											$jsondata = json_decode($response['body'],true); 
											
											update_option('_webi_zoom_connect',1);								
											update_option('_webi_zoom_access_token',$jsondata['access_token']);
											update_option('_webi_zoom_refresh_token',$jsondata['refresh_token']);
											update_option('_webi_zoom_refresh_token_expire_on',date('Y-m-d', strtotime("+15 years")));
											update_option('_webi_zoom_access_token_expire_on',date('Y-m-d H:i:s', strtotime("+50 minutes")));
											set_transient('_webi_zoom_access_token',$jsondata['access_token'],60*50);
										}
									}																		
								}
								else
								{
									if(empty(get_option('_webi_zoom_connect')))
									{
										delete_option('_webi_zoom_key');
										delete_option('_webi_zoom_secret');
									}
								}
								?>
								
								<div class="platform_section">								
									<!-- <a href="javascript:void(0);" class="webi_enable_platform" id="webigotowebinar_opt"> -->
										<table class="form-table">
											<tbody>
												<tr valign="top" class="">
													<th scope="row"><label for=""><?php esc_html_e('GoToWebinar platform','webinara'); ?></label></th>
													<td>
														<!-- <span id="goto_status" class="<?php if(get_option('_webi_goto_connect') != 1){ echo "err"; } ?>"><?php if(empty($webi_license_key)){ } else { if(get_option('_webi_goto_connect') == 1){ esc_html_e('Connected','webinara'); } else { esc_html_e('Not connected','webinara'); } } ?></span> -->
														<?php 
														if(empty($webi_license_key)){
															echo '<a href="'.admin_url('edit.php?post_type=webinar&page=webinara_plugin&tab=license&action=register').'" class="button-primary">'.__('Upgrade to the Premium version to enable','webinara').'</a>';
														}
														else
														{
															?>
															<div id="webigotowebinar_top" class="connectsec_top">
																<div class="sec_1" style="<?php if(get_option('_webi_goto_connect') == 1){ echo "display:block"; } else { echo "display:none"; } ?>" >
																	<a href="javascript:void(0);" class="disconnect_btn disconnect_platform " id="dis_gotowebinar"><?php esc_html_e('Disconnect','webinara'); ?></a>
																	<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>
																</div>	
																<div class="sec_2" style="<?php if(get_option('_webi_goto_connect') != 1){ echo "display:block"; } else { echo "display:none"; } ?>">																									
																	<a href="javascript:void(0);" class="button-primary webi_enable_platform" id="webigotowebinar_opt"><?php esc_html_e('Connect','webinara'); ?></a>																	
																</div>
																<p class="webi_con_response" id="gotowebinar_topmsg" <?php if(isset($_GET['code']) && isset($_GET['goto'])){ echo 'style="display:block"'; } ?>><?php esc_html_e('Successfully connected','webinara'); ?></p>															
															</div>
															<?php		
														}
														?>														
													</td>
												</tr>					
											</tbody>
										</table>
									<!-- </a> -->
									<div class="webi_connect_section" id="webigotowebinar">
										<?php
										$currentPage = admin_url( 'edit.php?post_type=webinar&page=webinara_plugin&tab=webinara-connect&goto');
										$currentPageEncoded = rawurlencode($currentPage);
										?>	
										<div class="goto_notice notice notice-info inline" style="<?php if(get_option('_webi_goto_connect') == 1){ echo "display:none"; } else { echo "display:block"; } ?>"><p><?php echo __('To connect your website to your GoToWebinar account, please go to: <a target="_blank" href="https://goto-developer.logmeininc.com/">https://goto-developer.logmeininc.com/</a> and click on "My Apps" to sign in with your GoToWebinar credentials. Then click on the "Add a new App" button. You can give any name to your application and add any description you like. Please select "GoToWebinar" as the Product API. For the redirect URL please enter: <code>'.$currentPage.'</code> and then click on "Create App". Now get your Consumer Key and Secret and add them to two settings fields below. Then click on the "Save All Settings" button at the bottom of this page. Just below this message you will see that the button will now say "Authenticate". Please click on that and you should be authenticated with your own app!','webinara') ?></p></div>
										<table class="form-table">
											<tbody>
												<tr valign="top" class="">
													<th scope="row"><label for=""><?php esc_html_e('GoToWebinar Consumer Key','webinara'); ?></label></th>
													<td>
														<input type="text" name="_webi_gotowebinar_key" value="<?php echo $webi_gotowebinar_key; ?>" class="" id="webi_gotowebinar_key">										
													</td>
												</tr>
												<tr valign="top" class="">
													<th scope="row"><label for=""><?php esc_html_e('GoToWebinar Consumer Secret','webinara'); ?></label></th>
													<td>
														<input type="text" name="_webi_gotowebinar_secret" value="<?php echo $webi_gotowebinar_secret; ?>" class="" id="webi_gotowebinar_secret">										
													</td>
												</tr>
												<tr valign="top" class="">
													<th scope="row"></th>
													<td>
														<div class="sec_2" style="<?php if(get_option('_webi_goto_connect') != 1){ echo "display:block"; } else { echo "display:none"; } ?>">																									
															<a href="javascript:void(0);" class="button-primary connect_platform" id="auth_gotowebinar"><?php esc_html_e('Authenticate','webinara'); ?></a>			
															<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>	
														</div>														
													</td>
												</tr>	
											</tbody>
										</table>																														
									</div>
								</div>
								<div class="platform_section">
									<!-- <a href="javascript:void(0);" class="webi_enable_platform" id="webizoom_opt"> -->
										<table class="form-table">
											<tbody>
												<tr valign="top" class="">
													<th scope="row"><label for=""><?php esc_html_e('Zoom platform','webinara'); ?></label></th>
													<td>
														<?php
														if(empty($webi_license_key)){
															echo '<a href="'.admin_url('edit.php?post_type=webinar&page=webinara_plugin&tab=license&action=register').'" class="button-primary">'.__('Upgrade to the Premium version to enable','webinara').'</a>';
														}
														else
														{
															?>
															<div id="zoomwebinar_top" class="connectsec_top">
																<div class="sec_1" style="<?php if(get_option('_webi_zoom_connect') == 1){ echo "display:block"; } else { echo "display:none"; } ?>" >
																	<a href="javascript:void(0);" class="disconnect_btn disconnect_platform" id="dis_zoom"><?php esc_html_e('Disconnect','webinara') ?></a>
																	<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>
																</div>	
																<div class="sec_2" style="<?php if(get_option('_webi_zoom_connect') != 1){ echo "display:block"; } else { echo "display:none"; } ?>">																									
																	<a href="javascript:void(0);" class="button-primary webi_enable_platform" id="webizoom_opt"><?php esc_html_e('Connect','webinara') ?></a>																	
																</div>
																<p class="webi_con_response" id="zoom_topmsg" <?php if(isset($_GET['code']) && isset($_GET['zoom'])){ echo 'style="display:block"'; } ?>><?php esc_html_e('Successfully connected','webinara') ?></p>																
															</div>
															<?php
														}															
														?>
													</td>
												</tr>					
											</tbody>
										</table>
									<!-- </a> -->
									<div class="webi_connect_section" id="webizoom">
										<?php
										$currentPage = admin_url( 'edit.php?post_type=webinar&page=webinara_plugin&tab=webinara-connect&zoom');
										$currentPageEncoded = rawurlencode($currentPage);
										?>	
										<div class="zoom_notice notice notice-info inline" style="<?php if(get_option('_webi_zoom_connect') == 1){ echo "display:none"; } else { echo "display:block"; } ?>"><p><?php echo __('To connect your website to your Zoom account, please go to: <a target="_blank" href="https://marketplace.zoom.us/">https://marketplace.zoom.us/</a>, click on "Build App" and sign in with your Zoom credentials. You can give any name to your application and add any description you like. Then please select "Account-level app" option and click on "Create" button. For the redirect URL please enter: <code>'.$currentPage.'</code> and then click on "Continue". Now get your Consumer Key and Secret and add them to two settings fields below. Then click on the "Connect" button.','webinara') ?></p></div>
										<table class="form-table">
											<tbody>
												<tr valign="top" class="">
													<th scope="row"><label for=""><?php esc_html_e('Zoom Consumer Key','webinara') ?></label></th>
													<td>
														<input type="text" name="_webi_zoom_key" value="<?php echo $webi_zoom_key; ?>" class="" id="webi_zoom_key">										
													</td>
												</tr>
												<tr valign="top" class="">
													<th scope="row"><label for=""><?php esc_html_e('Zoom Consumer Secret','webinara') ?></label></th>
													<td>
														<input type="text" name="_webi_zoom_secret" value="<?php echo $webi_zoom_secret; ?>" class="" id="webi_zoom_secret">										
													</td>
												</tr>
												<tr valign="top" class="">
													<th scope="row"></th>
													<td>														
														<div class="sec_2" style="<?php if(get_option('_webi_zoom_connect') != 1){ echo "display:block"; } else { echo "display:none"; } ?>">																									
															<a href="javascript:void(0);" class="button-primary connect_platform" id="auth_zoom"><?php esc_html_e('Authenticate','webinara') ?></a>			
															<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>	
														</div>												
													</td>													
												</tr>	
											</tbody>
										</table>																														
									</div>
								</div>
								<div class="platform_section">
									<!-- <a href="javascript:void(0);" class="webi_enable_platform" id="webionstream_opt"> -->
										<table class="form-table">
											<tbody>
												<tr valign="top" class="">
													<th scope="row"><label for=""><?php esc_html_e('Onstream platform','webinara') ?></label></th>
													<td>
														<?php
														if(empty($webi_license_key)){
															echo '<a href="'.admin_url('edit.php?post_type=webinar&page=webinara_plugin&tab=license&action=register').'" class="button-primary">'.__('Upgrade to the Premium version to enable','webinara').'</a>';
														}
														else
														{
															?>
															<div id="onstreamwebinar_top" class="connectsec_top">
																<div class="sec_1" style="<?php if(get_option('_webi_onstream_connect') == 1){ echo "display:block"; } else { echo "display:none"; } ?>" >
																	<a href="javascript:void(0);" class="disconnect_btn disconnect_platform" id="dis_onstream"><?php esc_html_e('Disconnect','webinara') ?></a>
																	<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>
																</div>	
																<div class="sec_2" style="<?php if(get_option('_webi_onstream_connect') != 1){ echo "display:block"; } else { echo "display:none"; } ?>">																									
																	<a href="javascript:void(0);" class="button-primary webi_enable_platform" id="webionstream_opt"><?php esc_html_e('Connect','webinara') ?></a>			
																	<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>	
																</div>
																<p class="webi_con_response" id="onstream_topmsg"><?php esc_html_e('Successfully connected','webinara') ?></p>
															</div>																
															<?php
														}
														?>
													</td>
												</tr>					
											</tbody>
										</table>
									<!-- </a> -->
									<div class="webi_connect_section" id="webionstream">
										<table class="form-table">
											<tbody>
												<tr valign="top" class="">
													<th scope="row"><label for=""><?php esc_html_e('API Username','webinara') ?></label></th>
													<td>
														<input type="text" name="_webi_onstream_username" value="<?php echo get_option('_webi_onstream_username'); ?>" class="" id="webi_onstream_username">										
													</td>
												</tr>
												<tr valign="top" class="">
													<th scope="row"><label for=""><?php esc_html_e('Password','webinara') ?></label></th>
													<td>
														<input type="text" name="_webi_onstream_password" value="<?php echo get_option('_webi_onstream_password'); ?>" class="" id="webi_onstream_password">										
													</td>
												</tr>
												<tr valign="top" class="">
													<th scope="row"></th>
													<td>
														<div class="sec_1" style="<?php if(get_option('_webi_onstream_connect') == 1){ echo "display:block"; } else { echo "display:none"; } ?>" >
															<a href="javascript:void(0);" class="disconnect_btn disconnect_platform" id="dis_onstream"><?php esc_html_e('Disconnect','webinara') ?></a>
															<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>
														</div>	
														<div class="sec_2" style="<?php if(get_option('_webi_onstream_connect') != 1){ echo "display:block"; } else { echo "display:none"; } ?>">																									
															<a href="javascript:void(0);" class="button-primary connect_platform" id="auth_onstream"><?php esc_html_e('Authenticate','webinara') ?></a>			
															<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>	
														</div>	
														<p class="webi_con_response" id="onstream_msg"><?php esc_html_e('Successfully connected','webinara') ?></p>	
													</td>
												</tr>	
											</tbody>
										</table>
									</div>
								</div>						
								<div class="platform_section">
									<!-- <a href="javascript:void(0);" class="webi_enable_platform" id="webireadytalk_opt"> -->
										<table class="form-table">
											<tbody>
												<tr valign="top" class="">
													<th scope="row"><label for=""><?php esc_html_e('ReadyTalk platform','webinara') ?></label></th>
													<td>
														<?php
														if(empty($webi_license_key)){
															echo '<a href="'.admin_url('edit.php?post_type=webinar&page=webinara_plugin&tab=license&action=register').'" class="button-primary">'.__('Upgrade to the Premium version to enable','webinara').'</a>';
														}
														else
														{
															?>
															<div id="readytalkwebinar_top" class="connectsec_top">
																<div class="sec_1" style="<?php if(get_option('_webi_readytalk_connect') == 1){ echo "display:block"; } else { echo "display:none"; } ?>" >
																	<a href="javascript:void(0);" class="disconnect_btn disconnect_platform" id="dis_readytalk"><?php esc_html_e('Disconnect','webinara') ?></a>
																	<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>
																</div>	
																<div class="sec_2" style="<?php if(get_option('_webi_readytalk_connect') != 1){ echo "display:block"; } else { echo "display:none"; } ?>">																									
																	<a href="javascript:void(0);" class="button-primary webi_enable_platform" id="webireadytalk_opt"><?php esc_html_e('Connect','webinara') ?></a>			
																	<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>	
																</div>	
																<p class="webi_con_response" id="readytalk_topmsg"><?php esc_html_e('Successfully connected','webinara') ?></p>
															</div>
															<?php
														}
														?>
													</td>
												</tr>					
											</tbody>
										</table>
									<!-- </a> -->	
									<div class="webi_connect_section" id="webireadytalk">
										<table class="form-table">
											<tbody>
												<tr valign="top" class="">
													<th scope="row"><label for=""><?php esc_html_e('Toll-Free Number','webinara') ?> </label></th>
													<td>
														<input type="text" name="_webi_rt_number" value="<?php echo get_option('_webi_readytalk_access_number'); ?>" class="" id="webi_rt_number">										
													</td>
												</tr>
												<tr valign="top" class="">
													<th scope="row"><label for=""><?php esc_html_e('Access code','webinara') ?></label></th>
													<td>
														<input type="text" name="_webi_rt_code" value="<?php echo get_option('_webi_readytalk_access_code'); ?>" class="" id="webi_rt_code">										
													</td>
												</tr>
												<tr valign="top" class="">
													<th scope="row"><label for=""><?php esc_html_e('Passcode','webinara') ?></label></th>
													<td>
														<input type="text" name="_webi_rt_passcode" value="<?php echo get_option('_webi_readytalk_passcode'); ?>" class="" id="webi_rt_passcode">										
													</td>
												</tr>
												<tr valign="top" class="">
													<th scope="row"></th>
													<td>
														<div class="sec_1" style="<?php if(get_option('_webi_readytalk_connect') == 1){ echo "display:block"; } else { echo "display:none"; } ?>" >
															<a href="javascript:void(0);" class="disconnect_btn disconnect_platform" id="dis_readytalk"><?php esc_html_e('Disconnect','webinara') ?></a>
															<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>
														</div>	
														<div class="sec_2" style="<?php if(get_option('_webi_readytalk_connect') != 1){ echo "display:block"; } else { echo "display:none"; } ?>">																									
															<a href="javascript:void(0);" class="button-primary connect_platform" id="auth_readytalk"><?php esc_html_e('Autheticate','webinara') ?></a>			
															<span class="webi_loader"><img src="<?php echo esc_url( plugins_url( '/assets/images/1.gif', __FILE__ ) ); ?>"></span>	
														</div>	
														<p class="webi_con_response" id="readytalk_msg"><?php esc_html_e('Successfully connected','webinara') ?></p>		
													</td>
												</tr>		
											</tbody>
										</table>
									</div>
								</div>
							<?php
							
						}
						?>
					</form>
				</div>
			</div>
			<?php			
		}
		
		function webi_create_custom_post()
		{
			add_action('init', array($this, 'webi_custom_post_type'));
		}							
		
		function webi_custom_post_type()
		{

			  $labels = array(
				'name'               => __( 'Webinars','webinara' ),
				'singular_name'      => __( 'Webinar','webinara' ),
				'add_new'            => __( 'Add New Webinar','webinara' ),
				'add_new_item'       => __( 'Add New Webinar','webinara' ),
				'edit_item'          => __( 'Edit Webinar','webinara' ),
				'new_item'           => __( 'New Webinar','webinara' ),
				'all_items'          => __( 'All Webinars','webinara' ),
				'view_item'          => __( 'View Webinar','webinara' ),
				'search_items'       => __( 'Search Webinars','webinara' ),
				'featured_image'     => __('Event Image Banner','webinara'),
				'set_featured_image' => __('Add Event Image Banner','webinara')
			  );
			 
			  // The arguments for our post type, to be entered as parameter 2 of register_post_type()
			  $args = array(
				'labels'            => $labels,
				'description'       => __('Holds our Webinars and webinar specific data', 'webinara'),
				'public'            => true,
				'menu_position'     => 5,
				'supports'          => array( 'title', 'editor', 'thumbnail'),
				'has_archive'       => true,
				'show_in_admin_bar' => true,
				'show_in_nav_menus' => true,
				'has_archive'       => false,
				'menu_icon'			=> plugin_dir_url( __FILE__ ) . 'assets/images/logo-webinara-20x20.png',
				'rewrite'           => array( 'slug' => 'webinars' ),
				'show_in_menu'  	=> false,
			  );
	 
			register_post_type( 'webinar', $args );

			
			if(get_option('_webi_enable_events') == 1){
			
				$labels = array(
					'name'               => __( 'Events','webinara' ),
					'singular_name'      => __( 'Event','webinara' ),
					'add_new'            => __( 'Add New Event','webinara' ),
					'add_new_item'       => __( 'Add New Event','webinara' ),
					'edit_item'          => __( 'Edit Event','webinara' ),
					'new_item'           => __( 'New Event','webinara' ),
					'all_items'          => __( 'All Events','webinara' ),
					'view_item'          => __( 'View Event','webinara' ),
					'search_items'       => __( 'Search Events','webinara' ),
					'featured_image'     => __('Event Image Banner','webinara'),
					'set_featured_image' => __('Add Event Image Banner','webinara')
				  );
				 
				  // The arguments for our post type, to be entered as parameter 2 of register_post_type()
				  $args = array(
					'labels'            => $labels,
					'description'       => __('Holds our Events and event specific data','webinara'),
					'public'            => true,
					'menu_position'     => 5,
					'supports'          => array( 'title', 'editor', 'thumbnail'),
					'has_archive'       => true,
					'show_in_admin_bar' => true,
					'show_in_nav_menus' => true,
					'has_archive'       => false,
					'menu_icon'			=> plugin_dir_url( __FILE__ ) . 'assets/images/logo-webinara-20x20.png',
					'rewrite'           => array( 'slug' => 'events' ),
					'show_in_menu'  	=>	false,
				  );
			 
				register_post_type( 'event', $args );
			}
			
			if(get_option('_webi_enable_events') == 1 || get_option('_webi_enable_webinars') == 1){
				$app_post_type = array();
				if(get_option('_webi_enable_events') == 1){
					$app_post_type[] = 'event';
				}
				if(get_option('_webi_enable_webinars') == 1){
					$app_post_type[] = 'webinar';
				}
			
				$labels = array(
					"name" => __( 'Event Categories','webinara' ),
					"singular_name" => __( 'Event Category','webinara' ),
					);

					$args = array(
						"label" => __( 'Event Categories','webinara' ),
						"labels" => $labels,
						"public" => true,
						"hierarchical" => true,
						"label" => __('Event Categories','webinara'),
						"show_ui" => true,
						"query_var" => true,					
						"show_admin_column" => false,
						"show_in_rest" => false,
						"rest_base" => "",
						"show_in_quick_edit" => false,
						"show_in_menu" => true,	
					);
					register_taxonomy( "event_categories", $app_post_type, $args );											
					
					$labels = array(
						'name' => __( 'Event Tags','webinara' ),
						'singular_name' => __( 'Tag','webinara' ),
						'search_items' =>  __( 'Search Tags','webinara' ),
						'popular_items' => __( 'Popular Tags','webinara' ),
						'all_items' => __( 'All Tags','webinara' ),
						'parent_item' => null,
						'parent_item_colon' => null,
						'edit_item' => __( 'Edit Tag','webinara' ), 
						'update_item' => __( 'Update Tag','webinara' ),
						'add_new_item' => __( 'Add New Tag','webinara' ),
						'new_item_name' => __( 'New Tag Name','webinara' ),
						'separate_items_with_commas' => __( 'Separate tags with commas','webinara' ),
						'add_or_remove_items' => __( 'Add or remove tags','webinara' ),
						'choose_from_most_used' => __( 'Choose from the most used tags','webinara' ),
						'menu_name' => __( 'Event Tags','webinara' ),					
					  ); 						

				  register_taxonomy('event_tag',$app_post_type,array(
					'hierarchical' => false,
					'labels' => $labels,
					'show_ui' => true,
					'update_count_callback' => '_update_post_term_count',
					'query_var' => true,
					'rewrite' => array( 'slug' => 'event_tag' ),
					'show_in_menu' => true,
				  ));
			}
			
			flush_rewrite_rules();
								
			add_filter( 'admin_post_thumbnail_html', array($this, 'webi_admin_post_thumbnail_add_label'), 10, 3);			
			add_action( 'wp_ajax_webi_authgotowebinar', array($this,'webi_authgotowebinar') );
			add_action( 'wp_ajax_nopriv_webi_authgotowebinar', array($this,'webi_authgotowebinar') );	
			add_action( 'wp_ajax_webi_disconnect_platform', array($this,'webi_disconnect_platform') );
			add_action( 'wp_ajax_nopriv_webi_disconnect_platform', array($this,'webi_disconnect_platform') );
			add_action( 'wp_ajax_webi_downgrade_license', array($this,'webi_downgrade_license') );
			add_action( 'wp_ajax_nopriv_webi_downgrade_license', array($this,'webi_downgrade_license') );	
			add_action( 'wp_ajax_webi_check_account', array($this,'webi_check_account') );
			add_action( 'wp_ajax_nopriv_webi_check_account', array($this,'webi_check_account') );	
			add_action( 'wp_ajax_webi_check_license', array($this,'webi_check_license') );
			add_action( 'wp_ajax_nopriv_webi_check_license', array($this,'webi_check_license') );
			add_action( 'wp_ajax_webi_get_info', array($this,'webi_get_info') );
			add_action( 'wp_ajax_nopriv_webi_get_info', array($this,'webi_get_info') );
			add_action( 'wp_ajax_webi_update_profile', array($this,'webi_update_profile') );
			add_action( 'wp_ajax_nopriv_webi_update_profile', array($this,'webi_update_profile') );
			add_action( 'wp_ajax_webi_send_profilelink', array($this,'webi_send_profilelink') );
			add_action( 'wp_ajax_nopriv_webi_send_profilelink', array($this,'webi_send_profilelink') );
			add_action( 'wp_ajax_webi_renew_license', array($this,'webi_renew_license') );
			add_action( 'wp_ajax_nopriv_webi_renew_license', array($this,'webi_renew_license') );			
			add_action( 'wp_ajax_webi_register_user', array($this,'webi_register_user') );
			add_action( 'wp_ajax_nopriv_webi_register_user', array($this,'webi_register_user') );					
		}
		
		function webi_admin_post_thumbnail_add_label($content, $post_id, $thumbnail_id)
		{
			$post = get_post($post_id);
			if ($post->post_type == 'webinar' || $post->post_type == 'event') {
				$content .= '<i>(Recommeded size 1024 x 683 px)</i>';
				return $content;
			}

			return $content;
		}
		
		function webi_register_user(){
			if(isset($_POST['rf_fname']) && isset($_POST['rf_lname']) && isset($_POST['rf_email']) && isset($_POST['rf_platform']))
			{
				if($_POST['rf_platform'] == 1)
				{
					$organizer_key = get_post_meta(sanitize_text_field($_POST['rf_number']), '_webi_goto_organizer_key', true);	
					$gotowebinar_id = str_replace("-","",get_post_meta(sanitize_text_field($_POST['rf_number']), '_webi_gotowebinar_id', true));
					$gotowebinar_access_token = $this->webi_get_gotowebinar_accesstoken();
					$fields = array(
						'firstName' => sanitize_text_field($_POST['rf_fname']),
						'lastName' => sanitize_text_field($_POST['rf_lname']),
						'email' => sanitize_email($_POST['rf_email']),
					);
					
					$response = wp_remote_post( 'https://api.getgo.com/G2W/rest/organizers/'.$organizer_key.'/webinars/'.$gotowebinar_id.'/registrants', array(
						'headers' => array(
							'Content-Type' => 'application/json',
							'Authorization' => $gotowebinar_access_token,							
						),
						'body' => json_encode($fields),
					));

					if (! is_wp_error($response)) {
						echo wp_remote_retrieve_response_code( $response );
					}
					else
					{
						echo "Error";
					}						
				}
				else if($_POST['rf_platform'] == 2)
				{
					$username = get_post_meta(sanitize_text_field($_POST['rf_number']), '_webi_onstream_username', true);
					$password = get_post_meta(sanitize_text_field($_POST['rf_number']), '_webi_onstream_password', true);
					$parameters = array( 
						'session_id' => sanitize_text_field($_POST['rf_info']), 
						'email' => sanitize_email($_POST['rf_email']),  
						'first_name' => sanitize_text_field($_POST['rf_fname']), 
						'last_name' => sanitize_text_field($_POST['rf_lname']),
						'role' => 2 
					);																				
																																
					$response = wp_remote_post( WEBINARA_API_URL, array(
						'headers' => array(),
						'body' => array('register_user' => 'onstream', 'register_data' => $parameters, 'auth_data' => base64_encode($username.':'.$password))
					));	

					if ( is_wp_error( $response ) ) {
						$error_message = $response->get_error_message();
						echo "Something went wrong: $error_message";
					} else {						
						$register_user_response = @json_decode(wp_remote_retrieve_body($response), true);
						echo $register_user_response['reg_user_status'];						
					}																								
				}
				else if($_POST['rf_platform'] == 4)
				{
					$readytalk_accessnumber = get_post_meta(sanitize_text_field($_POST['rf_number']), '_webi_readytalk_accessnumber', true);
					$readytalk_accesscode = get_post_meta(sanitize_text_field($_POST['rf_number']), '_webi_readytalk_accesscode', true);
					$readytalk_passcode = get_post_meta(sanitize_text_field($_POST['rf_number']), '_webi_readytalk_passcode', true);
					
					$fields = array(
						'meetingId' => urlencode(sanitize_text_field($_POST['rf_info'])),
						'firstName' => urlencode(sanitize_text_field($_POST['rf_fname'])),
						'lastName' => urlencode(sanitize_text_field($_POST['rf_lname'])),
						'email' => urlencode(sanitize_email($_POST['rf_email'])),
						'sendConfirmationEmail' => true,
					);

					$response = wp_remote_post( WEBINARA_API_URL, array(
						'headers' => array(),
						'body' => array('register_user' => 'readytalk', 'register_data' => $fields, 'auth_data' => base64_encode($readytalk_accessnumber.':'.$readytalk_accesscode.':'.$readytalk_passcode))
					));	

					if ( is_wp_error( $response ) ) {
						$error_message = $response->get_error_message();
						echo "Something went wrong: $error_message";
					} else {						
						$register_user_response = @json_decode(wp_remote_retrieve_body($response), true);
						echo $register_user_response['reg_user_status'];						
					}	
				}				
				else if($_POST['rf_platform'] == 7)
				{
					$zoom_access_token = $this->webi_get_zoom_accesstoken();
					$registraint_data = array(
						"email" => sanitize_email($_POST['rf_email']),
						"first_name" => sanitize_text_field($_POST['rf_fname']),
						"last_name" => sanitize_text_field($_POST['rf_lname']),						
					);
					$rf_info = sanitize_text_field($_POST['rf_info']);
					
					$response = wp_remote_post( 'https://api.zoom.us/v2/webinars/'.$rf_info.'/registrants', array(
						'headers' => array(
							'Content-Type' => 'application/json',
							'Authorization' => 'Bearer '.$zoom_access_token,							
						),
						'body' => json_encode($registraint_data),
					));

					if (! is_wp_error($response)) {
						echo wp_remote_retrieve_response_code( $response );
					}
					else
					{
						echo "Error";
					}															
				}				
			}
			wp_die();
		}				
		
		function webi_renew_license(){
			if(isset($_POST['license_key']) && !empty($_POST['license_key']))
			{
				$renew_license_req = wp_remote_post( WEBINARA_API_URL, array(					
					'headers'     => array(),
					'body'        => array(
						'renew_license' => urlencode('yes'),
						'prokey' => urlencode(get_option('_webi_license_user_id')),
						'lk' => urlencode(get_option('_webi_license_key')),
					),
					'cookies'     => array()
					)
				);
				
				if ( is_wp_error( $renew_license_req ) ) {
					$error_message = $response->get_error_message();
					echo "Something went wrong: $error_message";
				} else {
					$renew_license_response = @json_decode(wp_remote_retrieve_body($renew_license_req), true);
					echo $renew_license_response['renewlink_response'];
				}
				wp_die();
			}
		}
		
		function webi_send_profilelink(){			 			
			$send_profilelink_req = wp_remote_post( WEBINARA_API_URL, array(				
				'headers'     => array(),
				'body'        => array(
					'sendprofile' => urlencode('yes'),
					'prokey' => urlencode(get_option('_webi_license_user_id')),
				),
				'cookies'     => array()
				)
			);
			
			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				echo "Something went wrong: $error_message";
			} else {
				$send_profilelink_response = @json_decode(wp_remote_retrieve_body($send_profilelink_req), true);
				echo $send_profilelink_response['profilesend_response'];
			}
			wp_die();
		}
		
		function webi_get_info(){
			if(isset($_POST['fetch_data']) && $_POST['fetch_data'] == 1)
			{
				$get_info_request = wp_remote_get( add_query_arg( array(
					'datakey' =>  urlencode(get_option('_webi_license_user_id')),				
					), WEBINARA_API_URL ), array(
						'timeout' => 10,
						'headers' => array(
							'Accept' => 'application/json'
						)
					)
				);
				
				$get_info_response = @json_decode(wp_remote_retrieve_body($get_info_request), true);
				if($get_info_response['data_valid'] == 1)
				{
					echo $get_info_response['first_name'].'|+|'.$get_info_response['last_name'].'|+|'.$get_info_response['company'].'|+|'.$get_info_response['title'].'|+|'.$get_info_response['job_level'].'|+|'.$get_info_response['job_function'].'|+|'.$get_info_response['country'].'|+|'.$get_info_response['prefered_timezone'].'|+|'.$get_info_response['webinar_solution'].'|+|'.$get_info_response['company_industry'].'|+|'.$get_info_response['URL_company'].'|+|'.$get_info_response['company_size'].'|+|'.$get_info_response['information_about_company'].'|+|'.$get_info_response['public_profile_url'].'|+|'.$get_info_response['profile_url'];
					update_option('webi_organizer_logo',$get_info_response['company_logo']);
				}
				else
				{				
					echo "0";	
				}
			}
			else if(isset($_POST['fetch_data']) && $_POST['fetch_data'] == 2)
			{
				$get_info_request = wp_remote_get( add_query_arg( array(
					'lickey' =>  urlencode(get_option('_webi_license_key')),				
					), WEBINARA_API_URL ), array(
						'timeout' => 10,
						'headers' => array(
							'Accept' => 'application/json'
						)
					)
				);
				
				$get_info_response = @json_decode(wp_remote_retrieve_body($get_info_request), true);
				if($get_info_response['valid'] == 0)
				{
					echo "0++0";					
				}	
				else if($get_info_response['valid'] == 2)
				{					
					delete_option('_webi_license_key');
					delete_option('_webi_license_user_id');
					delete_option('_webi_license_x');
					echo "2++2";
				}					
				else
				{				
					echo $get_info_response['exp_date'].'++'.$get_info_response['need_update'];	
				}
			}
			wp_die();
		}
		
		function webi_check_license(){
			if(isset($_POST['license_key']) && !empty($_POST['license_key'])){
				$license_check_request = wp_remote_get( add_query_arg( array(
					'license_key' =>  urlencode(sanitize_text_field($_POST['license_key'])),					
					'site_info' => urlencode(site_url().'|*|'.$_SERVER['SERVER_ADDR']),					
					), WEBINARA_API_URL ), array(
						'timeout' => 10,
						'headers' => array(
							'Accept' => 'application/json'
						)
					)
				);
				
				$license_check_response = @json_decode(wp_remote_retrieve_body($license_check_request), true);	
				if($license_check_response['license_valid'] == 1)
				{
					update_option('_webi_license_key',sanitize_text_field($_POST['license_key']));
					if(isset($license_check_response['license_user_id']))
					{
						update_option('_webi_license_user_id', $license_check_response['license_user_id']);
						update_option('_webi_license_x', $license_check_response['expire_on']);
					}
				}					
				echo $license_check_response['license_valid'];
			}							
			wp_die();
		}
		
		function webi_update_profile(){
			$account_check_request = wp_remote_get( add_query_arg( array(				
				'userkey' =>  urlencode(get_option('_webi_license_user_id')),
				'user_info' => urlencode(sanitize_text_field($_POST['webireg_cname']).'|*|'.sanitize_text_field($_POST['webireg_fname']).'|*|'.sanitize_text_field($_POST['webireg_lname']).'|*|'.sanitize_text_field($_POST['webireg_jobtitle']).'|*|'.sanitize_text_field($_POST['webireg_joblevel']).'|*|'.sanitize_text_field($_POST['webireg_jobfunction']).'|*|'.sanitize_text_field($_POST['webireg_country']).'|*|'.sanitize_text_field($_POST['webireg_prefzone']).'|*|'.sanitize_text_field($_POST['webireg_websolution']).'|*|'.sanitize_text_field($_POST['webireg_cindustry']).'|*|'.sanitize_text_field($_POST['webireg_website']).'|*|'.sanitize_text_field($_POST['webireg_csize']).'|*|'.sanitize_text_field($_POST['webireg_cdesc'])),
				), WEBINARA_API_URL ), array(
					'timeout' => 10,
					'headers' => array(
						'Accept' => 'application/json'
					)
				)
			);
			$account_check_response = @json_decode(wp_remote_retrieve_body($account_check_request), true);			
			echo $account_check_response['status'];
			wp_die();
		}
		 
		function webi_check_account(){
			if(isset($_POST['webireg_email']) && !empty($_POST['webireg_email'])){
				if($_POST['us'] == 1)
				{
					$account_check_request = wp_remote_get( add_query_arg( array(
						'webireg_email' => urlencode( sanitize_email($_POST['webireg_email']) ),					
						'site_info' => urlencode(site_url().'|*|'.$_SERVER['SERVER_ADDR']),
						'user_info' => urlencode('none|*|none|*|none'),
						'us' => 1,
						), WEBINARA_API_URL ), array(
							'timeout' => 10,
							'headers' => array(
								'Accept' => 'application/json'
							)
						)
					);
				}
				else if($_POST['us'] == 2)
				{
					$account_check_request = wp_remote_get( add_query_arg( array(
						'webireg_email' => urlencode( sanitize_email($_POST['webireg_email'] )),					
						'site_info' => urlencode(site_url().'|*|'.$_SERVER['SERVER_ADDR']),
						'user_info' => urlencode(sanitize_text_field($_POST['webireg_cname']).'|*|'.sanitize_text_field($_POST['webireg_fname']).'|*|'.sanitize_text_field($_POST['webireg_lname']).'|*|'.sanitize_text_field($_POST['webireg_jobtitle']).'|*|'.sanitize_text_field($_POST['webireg_joblevel']).'|*|'.sanitize_text_field($_POST['webireg_jobfunction']).'|*|'.sanitize_text_field($_POST['webireg_country']).'|*|'.sanitize_text_field($_POST['webireg_prefzone']).'|*|'.sanitize_text_field($_POST['webireg_websolution']).'|*|'.sanitize_text_field($_POST['webireg_cindustry']).'|*|'.sanitize_text_field($_POST['webireg_website']).'|*|'.sanitize_text_field($_POST['webireg_csize']).'|*|'.sanitize_text_field($_POST['webireg_cdesc'])),
						'us' => 2,
						), WEBINARA_API_URL ), array(
							'timeout' => 10,
							'headers' => array(
								'Accept' => 'application/json'
							)
						)
					);					
				}					
				
				
				$account_check_response = @json_decode(wp_remote_retrieve_body($account_check_request), true);			
				echo $account_check_response['account_available_response'];
			}							
			wp_die();
		}
		
		function webi_downgrade_license(){
			$license_downgrade_request = wp_remote_get( add_query_arg( array(
				'license_key' =>  urlencode(get_option('_webi_license_key')),					
				'site_info' => urlencode(site_url().'|*|'.$_SERVER['SERVER_ADDR']),
				'site_action' => 'downgrade',					
				), WEBINARA_API_URL ), array(
					'timeout' => 10,
					'headers' => array(
						'Accept' => 'application/json'
					)
				)
			);
			
			$license_downgrade_response = @json_decode(wp_remote_retrieve_body($license_downgrade_request), true);	
			if($license_downgrade_response['license_downgrade'] == 1)
			{
				delete_option('_webi_license_key');
				delete_option('_webi_license_user_id');
				delete_option('_webi_license_x');
			}					
			echo $license_downgrade_response['license_downgrade'];			
			wp_die();
		}
		
		function webi_authgotowebinar()
		{
			if(isset($_POST['webi_gotowebinar_key']) && !empty($_POST['webi_gotowebinar_key']))
			{
				$webi_gotowebinar_key = sanitize_text_field($_POST['webi_gotowebinar_key']);
				$webi_gotowebinar_secret = sanitize_text_field($_POST['webi_gotowebinar_secret']);
				update_option('_webi_gotowebinar_key', $webi_gotowebinar_key);
				update_option('_webi_gotowebinar_secret', $webi_gotowebinar_secret);				
				echo "Success";
			}
			
			if(isset($_POST['webi_zoom_key']) && !empty($_POST['webi_zoom_key']))
			{
				$webi_zoom_key = sanitize_text_field($_POST['webi_zoom_key']);
				$webi_zoom_secret = sanitize_text_field($_POST['webi_zoom_secret']);
				update_option('_webi_zoom_key', $webi_zoom_key);
				update_option('_webi_zoom_secret', $webi_zoom_secret);				
				echo "Success";
			}
			
			if(isset($_POST['webi_onstream_username']) && !empty($_POST['webi_onstream_username']))
			{
				$username = sanitize_text_field($_POST['webi_onstream_username']);
				$password = sanitize_text_field($_POST['webi_onstream_password']);
				
				
				$response = wp_remote_post( WEBINARA_API_URL, array(
					'headers' => array(),
					'body' => array('auth_platform' => 'onstream', 'auth_data' => base64_encode($username.':'.$password))
				));	

				if ( is_wp_error( $response ) ) {
					$error_message = $response->get_error_message();
					echo "Something went wrong: $error_message";
				} else {						
					$register_user_response = @json_decode(wp_remote_retrieve_body($response), true);
					if($register_user_response['platform_status'] == 1)
					{
						update_option('_webi_onstream_connect',1);
						update_option('_webi_onstream_username',$username);
						update_option('_webi_onstream_password',$password);	
						echo "Success";					
					}					
					else
					{
						echo "Error";
					}
				}														
			}
			
			if(isset($_POST['webi_rt_number']) && !empty($_POST['webi_rt_number']))
			{
				$readytalk_access_number = sanitize_text_field($_POST['webi_rt_number']);
				$readytalk_access_code = sanitize_text_field($_POST['webi_rt_code']);
				$readytalk_user_pass = sanitize_text_field($_POST['webi_rt_passcode']);
				
				$response = wp_remote_post( WEBINARA_API_URL, array(
					'headers' => array(),
					'body' => array('auth_platform' => 'readytalk', 'auth_data' => base64_encode($readytalk_access_number.':'.$readytalk_access_code.':'.$readytalk_user_pass))
				));	

				if ( is_wp_error( $response ) ) {
					$error_message = $response->get_error_message();
					echo "Something went wrong: $error_message";
				} else {						
					$register_user_response = @json_decode(wp_remote_retrieve_body($response), true);
					if($register_user_response['platform_status'] == 1)
					{
						update_option('_webi_readytalk_connect',1);
						update_option('_webi_readytalk_access_number',$readytalk_access_number);
						update_option('_webi_readytalk_access_code',$readytalk_access_code);
						update_option('_webi_readytalk_passcode',$readytalk_user_pass);
						echo "Success";					
					}					
					else
					{
						echo "Error";
					}
				}	
			}
			wp_die();
		}	

		function webi_disconnect_platform(){
			if(isset($_POST['platform']) && $_POST['platform'] == 'goto'){
				delete_option('_webi_gotowebinar_key');
				delete_option('_webi_gotowebinar_secret');				
				delete_option('_webi_goto_connect');
				delete_option('_webi_goto_organizer_key');
				delete_option('_webi_goto_account_key');
				delete_option('_webi_goto_access_token');
				delete_option('_webi_goto_refresh_token');
				delete_option('_webi_goto_refresh_token_expire_on');
				delete_option('_webi_goto_access_token_expire_on');	
				echo "Success";
			}
			
			if(isset($_POST['platform']) && $_POST['platform'] == 'zoom'){
				delete_option('_webi_zoom_key');
				delete_option('_webi_zoom_secret');				
				delete_option('_webi_zoom_connect');
				delete_option('_webi_zoom_access_token');
				delete_option('_webi_zoom_refresh_token');
				delete_option('_webi_zoom_refresh_token_expire_on');
				delete_option('_webi_zoom_access_token_expire_on');				
				echo "Success";
			}
			
			if(isset($_POST['platform']) && $_POST['platform'] == 'onstream'){
				delete_option('_webi_onstream_connect');
				delete_option('_webi_onstream_username');				
				delete_option('_webi_onstream_password');				
				echo "Success";
			}
			
			if(isset($_POST['platform']) && $_POST['platform'] == 'readytalk'){
				delete_option('_webi_readytalk_connect');
				delete_option('_webi_readytalk_access_number');				
				delete_option('_webi_readytalk_access_code');
				delete_option('_webi_readytalk_passcode');	
				echo "Success";
			}
			wp_die();
		}			
		
		function webi_admin_scripts()
		{
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');			
			wp_enqueue_script('webi_timepicker_script', plugins_url('/assets/js/jquery.timepicker.js',__FILE__),array(),'',true);
			wp_enqueue_script('webi_datepicker_script', plugins_url('/assets/js/bootstrap-datepicker.js',__FILE__),array(),'',true);			
			wp_enqueue_script('webi_datepair_script', plugins_url('/assets/js/jquery-datepair/datepair.min.js',__FILE__),array(),'',true);
			wp_enqueue_script('webi_jquery_datepair_script', plugins_url('/assets/js/jquery-datepair/jquery.datepair.min.js',__FILE__),array(),'',true);
			wp_enqueue_script('webi_chosen_script', plugins_url('/assets/js/jquery-chosen/chosen.jquery.min.js',__FILE__),array(),'',true);			
			wp_enqueue_style('webi_datepicker_style', plugins_url('/assets/css/bootstrap-datepicker.standalone.css',__FILE__));
			wp_enqueue_style('webi_timepicker_style', plugins_url('/assets/css/jquery.timepicker.css',__FILE__));
			wp_enqueue_style('webi_choosen_style', plugins_url('/assets/css/chosen.css',__FILE__));
			wp_enqueue_style('webi_style', plugins_url('/assets/css/webi_style.css',__FILE__));			
			wp_enqueue_script('webi_script', plugins_url('/assets/js/webi_script.js',__FILE__),array(),'',true);
			wp_localize_script('webi_script', 'ajax_var', array('url' => admin_url('admin-ajax.php')));			
		}
		
		function webi_front_scripts(){
			wp_enqueue_script('jquery');
			wp_enqueue_style('model_style', plugins_url('/assets/css/jquery.modal.min.css',__FILE__));
			wp_enqueue_script('model_script', plugins_url('/assets/js/jquery.modal.min.js',__FILE__));				
			wp_enqueue_style('webi_front_style', plugins_url('/assets/css/webi_front_style.css',__FILE__));
			wp_enqueue_script('webi_front_script', plugins_url('/assets/js/webi_front_script.js',__FILE__));
			wp_localize_script('webi_front_script', 'ajax_var', array('url' => admin_url('admin-ajax.php')));	
		}				
		
		// here's the function we'd like to call with our cron job
		function webi_gotowebinar_token_refresh() {
			if(get_option('_webi_goto_connect') == 1)
			{
				$webi_gotowebinar_key = get_option('_webi_gotowebinar_key');
				$webi_gotowebinar_secret = get_option('_webi_gotowebinar_secret');
				$webi_goto_refresh_token = get_option('_webi_goto_refresh_token');
				$webi_goto_refresh_token_expire = get_option('_webi_goto_refresh_token_expire_on');
				
				if(strtotime($webi_goto_refresh_token_expire) < strtotime())
				{
					delete_option('_webi_goto_connect');
					delete_option('_webi_goto_organizer_key');
					delete_option('_webi_goto_account_key');
					delete_option('_webi_goto_access_token');
					delete_option('_webi_goto_refresh_token');
					delete_option('_webi_goto_refresh_token_expire_on');	
					delete_option('_webi_goto_access_token_expire_on');	
					delete_transient('_webi_goto_access_token');
				}
				else
				{
					
					$response = wp_remote_post( 'https://api.getgo.com/oauth/v2/token', array(
						'headers' => array(
							'Content-Type:application/x-www-form-urlencoded',
							'Authorization: Basic '.base64_encode($webi_gotowebinar_key.':'.$webi_gotowebinar_secret),
							'Accept:application/json',
						),
						'body' => array(
							'refresh_token' => $webi_goto_refresh_token,
							'grant_type' => 'refresh_token',
						)
					) );
									
					if ( ! is_wp_error( $response ) ) {
						// The request went through successfully, check the response code against
						// what we're expecting
						if ( 200 == wp_remote_retrieve_response_code( $response ) ) {
							
							$jsondata = json_decode($response['body'],true);
							
							update_option('_webi_goto_access_token',$jsondata['access_token']);
							update_option('_webi_goto_refresh_token',$jsondata['refresh_token']);	
							update_option('_webi_goto_refresh_token_expire_on',date('Y-m-d', strtotime("+28 days")));
							update_option('_webi_goto_access_token_expire_on',date('Y-m-d H:i:s', strtotime("+50 minutes")));			
							set_transient('_webi_goto_access_token',$jsondata['access_token'],60*50);
						}
					}														
				}							
			}								
		}
		
		public function webi_check_schedule_crons(){
			if ( ! wp_next_scheduled( 'gotowebinar_refresh_token' ) ) {
				wp_schedule_event( time(), 'hourly', 'gotowebinar_refresh_token' );
			}			
		}

		public function webi_get_zoom_accesstoken(){
			$getTransient = get_transient('_webi_zoom_access_token');
			//if the transient exists
			if ($getTransient != false){
				return $getTransient;
			}			
			else
			{
				$webi_zoom_key = get_option('_webi_zoom_key');
				$webi_zoom_secret = get_option('_webi_zoom_secret');
				$webi_zoom_refresh_token = get_option('_webi_zoom_refresh_token');
				
				$response = wp_remote_post( 'https://zoom.us/oauth/token', array(
					'headers' => array(
						'Content-Type:application/x-www-form-urlencoded',
						'Authorization: Basic '.base64_encode($webi_zoom_key.':'.$webi_zoom_secret),
						'Accept:application/json',
					),
					'body' => array(
						'grant_type' => 'refresh_token',
						'refresh_token' => $webi_zoom_refresh_token,
					),
				) );
				
				if ( ! is_wp_error( $response ) ) {
					// The request went through successfully, check the response code against
					// what we're expecting
					if ( 200 == wp_remote_retrieve_response_code( $response ) ) {
						
						//lets get the response and decode it
						$jsondata = json_decode($response['body'],true); 
						
						update_option('_webi_zoom_access_token',$jsondata['access_token']);
						update_option('_webi_zoom_refresh_token',$jsondata['refresh_token']);
						update_option('_webi_zoom_refresh_token_expire_on',date('Y-m-d', strtotime("+15 years")));
						update_option('_webi_zoom_access_token_expire_on',date('Y-m-d H:i:s', strtotime("+50 minutes")));
						set_transient('_webi_zoom_access_token',$jsondata['access_token'],60*50);
						return $jsondata['access_token'];
					}
				}															
			}
		}
		
		public function webi_get_gotowebinar_accesstoken()
		{
			$getTransient = get_transient('_webi_goto_access_token');
			//if the transient exists
			if ($getTransient != false){
				return $getTransient;
			}			
			else
			{
				$webi_gotowebinar_key = get_option('_webi_gotowebinar_key');
				$webi_gotowebinar_secret = get_option('_webi_gotowebinar_secret');				
				$webi_goto_refresh_token = get_option('_webi_goto_refresh_token');
				$webi_goto_refresh_token_expire = get_option('_webi_goto_refresh_token_expire_on');	


				$response = wp_remote_post( 'https://api.getgo.com/oauth/v2/token', array(
					'headers' => array(
						'Content-Type:application/x-www-form-urlencoded',
						'Authorization: Basic '.base64_encode($webi_gotowebinar_key.':'.$webi_gotowebinar_secret),
						'Accept:application/json',
					),
					'body' => array(
						'refresh_token' => $webi_goto_refresh_token,
						'grant_type' => 'refresh_token',
					)
				) );
								
				if ( ! is_wp_error( $response ) ) {
					// The request went through successfully, check the response code against
					// what we're expecting
					if ( 200 == wp_remote_retrieve_response_code( $response ) ) {
						
						$jsondata = json_decode($response['body'],true);
						
						update_option('_webi_goto_access_token',$jsondata['access_token']);
						update_option('_webi_goto_refresh_token',$jsondata['refresh_token']);	
						update_option('_webi_goto_refresh_token_expire_on',date('Y-m-d', strtotime("+28 days")));
						update_option('_webi_goto_access_token_expire_on',date('Y-m-d H:i:s', strtotime("+50 minutes")));
						set_transient('_webi_goto_access_token',$jsondata['access_token'],60*50);
						return $jsondata['access_token'];
					}
				}													
			}
		}			
	}			
}
$webinaraPlugin = new Webinara();
$webinaraPlugin->webi_create_custom_post();
$webinaraPlugin->webi_register();