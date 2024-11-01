<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
*@package: WebinaraPlugin
*/

class Webinara_Meta_Box
{
    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     */
    public function __construct() {
        
        // Action -> Post Type -> Webinar/Event -> Create Meta Box.
        add_action('add_meta_boxes', array($this, 'webi_create_meta_box'));
        
        // Action -> Post Type -> Webinar/Event -> Save Meta Box.
        add_action('save_post', array($this, 'webi_save_meta_box'));
    }

    /**
     * create_meta_box function.
     *
     * @since   1.0.0
     * 
     * @return  void
     */
    public function webi_create_meta_box() {
        $this->webi_add_meta_box('webi_webinar_options', __('Webinar Details (fields marked * are recommended for good webinar)','webinara'), 'webinar');
		$this->webi_add_meta_box('webi_event_options', __('Event Details (fields marked * are recommended for good event)','webinara'), 'event');
    }

    /**
     * add_meta_box function.
     *
     * @since   1.0.0
     *
     * @return  void
     */
    public function webi_add_meta_box($id, $label, $post_type) {
        add_meta_box('_' . $id, $label, array($this, $id), $post_type);
    }
        
    public function webi_webinar_options() {
		global $post;			
		if(get_post_meta($post->ID,'_webi_event_type',true) == 'seminar'){ $show_vanue = 'style="display:block"'; } else { $show_vanue = ''; }
		$webinara_form_fields = get_option('_webi_webinarform_fields',true);
		$con_platform_arr = array();
		$goto_status = (get_option('_webi_goto_connect') == 1) ? sprintf(__('(Connected)','webinara')) : sprintf(__('(Not connected)','webinara'));
		$onstream_status = (get_option('_webi_onstream_connect') == 1) ? sprintf(__('(Connected)','webinara')) : sprintf(__('(Not connected)','webinara'));
		$readytalk_status = (get_option('_webi_readytalk_connect') == 1) ? sprintf(__('(Connected)','webinara')) : sprintf(__('(Not connected)','webinara'));
		$zoom_status = (get_option('_webi_zoom_connect') == 1) ? sprintf(__('(Connected)','webinara')) : sprintf(__('(Not connected)','webinara'));	
		$con_platform_arr[] = 0;	
		$con_platform_arr[] = (get_option('_webi_goto_connect') == 1) ? 1 : 0;
		$con_platform_arr[] = (get_option('_webi_onstream_connect') == 1) ? 1 : 0;
		$con_platform_arr[] = 1;
		$con_platform_arr[] = (get_option('_webi_readytalk_connect') == 1) ? 1 : 0;
		$con_platform_arr[] = 0;
		$con_platform_arr[] = 0;
		$con_platform_arr[] = (get_option('_webi_zoom_connect') == 1) ? 1 : 0;
		if(get_post_meta($post->ID,'_webi_event_type',true) == 'webinar' && get_post_meta($post->ID,'_webi_platform',true) != 3){ $show_fld = 'style="display:none"'; } else { $show_fld = ''; }	
		if(get_post_meta($post->ID,'_webi_event_type',true) == 'webinar' && get_post_meta($post->ID,'_webi_platform',true) == 1){ $show_fld_goto = 'style="display:block"'; } else { $show_fld_goto = ''; }		
        echo '<div class="webi-metabox">';					
		echo '<input type="hidden" name="_webi_event_type" value="webinar">';
		if(get_option('_webi_publish_events') == 1 && (date('Y-m-d') < get_option('_webi_license_x')))
		{
			$checked_status = $checked_class = $checked_msg = $show_msg = '';
			if(get_post_meta($post->ID,'_webi_sync',true) == 1)
			{
				$checked_status = "checked";
				$checked_class = " evt_shared";
				$checked_msg = sprintf(__('This webinar is published on Webinara.com server.','webinara'));
				$show_msg = ' style="display:block"';
			}
			echo '<p class="form-field form-field-checkbox webi_publish_checkbox">
					<label for="_webi_publish_event" class="featured_opt publish_opt">
					'.sprintf( __('Publish this webinar on <a href="%1$s" target="_blank">%2$s</a>','webinara'), esc_attr( 'https://www.webinara.com' ), esc_html( 'Webinara.com' ) ).'					
					<input type="checkbox" class="checkbox'.$checked_class.'" name="_webi_publish_event" id="_webi_publish_event" value="1" '.$checked_status.'></label>';
					echo '<label for="_webi_publish_event_msg" class="webi_publish_event_msg"'.$show_msg.'>'.$checked_msg.'</label>';
			echo '</p>';	
		}
		$this->webi_input_text('_webi_title', array('label'=>__('Title','webinara'), 'placeholder' => $webinara_form_fields['general']['title']['label'],'readonly' => 'readonly'));
		if(isset($webinara_form_fields['general']['subtitle']['enable'])){
			$this->webi_input_text('_webi_subtitle', array('label'=>$webinara_form_fields['general']['subtitle']['label'], 'placeholder' => $webinara_form_fields['general']['subtitle']['label']));
		}
		echo '<div class="" id="webi_datetime">';
			$this->webi_input_text('_webi_start_date', array('label'=>__('Start date*','webinara'), 'sec' => 'sec_3 webi_require', 'class' => 'date start', 'placeholder' => ''));
			$this->webi_input_text('_webi_start_time', array('label'=>__('Start time*','webinara'), 'sec' => 'sec_3 webi_require', 'class' => 'time start', 'placeholder' => ''));
			$this->webi_input_checkbox('_webi_featured', array('label'=>__('Featured event','webinara'), 'sec' => 'sec_3', 'label_class' => 'featured_opt', 'field_class' => 'webi_featured', 'option_count' => 1, 'placeholder' => ''));								
			$this->webi_input_text('_webi_end_date', array('label'=>__('End date*','webinara'), 'sec' => 'sec_3 webi_require', 'class' => 'date end', 'placeholder' => ''));
			$this->webi_input_text('_webi_end_time', array('label'=>__('End time*','webinara'), 'sec' => 'sec_3 webi_require', 'class' => 'time end', 'placeholder' => ''));						
			$this->webi_input_checkbox('_webi_all_day', array('label'=>__('All day','webinara'), 'sec' => 'sec_3', 'label_class' => 'allday_opt', 'field_class' => 'webi_all_day', 'option_count' => 1, 'placeholder' => ''));			
			echo '<div style="clear:both"></div>
		</div>';
		echo '<div class="normal_sec">';
			if(isset($webinara_form_fields['general']['timezone']['enable'])){	
				$this->webi_input_timezone('_webi_timezone', array('label' => $webinara_form_fields['general']['timezone']['label'].'*', 'default' => '', 'additional_class' => 'webi_tz'));
				$this->webi_input_timezone_zoom('_webi_timezone', array('label' => $webinara_form_fields['general']['timezone']['label'].'*', 'default' => '', 'additional_class' => 'webi_tz_zoom'));
				$webi_timezone_status = 1;
			}
			else
			{
				$webi_timezone_status = 0;
			}
			$this->webi_input_select('_webi_platform', array('label' => __('Platform*','webinara'),'sec' => 'webi_platform_chooser', 'options' => array(3 => __('Other','webinara'), 1 => __('GoToWebinar','webinara').' '.$goto_status, 7 => __('Zoom','webinara').' '.$zoom_status, 2 => __('Onstream','webinara').' '.$onstream_status, 4 => __('ReadyTalk','webinara').' '.$readytalk_status)));
			
			echo '<div style="clear:both"></div>';			
		echo '</div>';	
		echo '<input type="hidden" id="webi_timezone_status" value="'.$webi_timezone_status.'">';	
		$this->webi_input_text('_webi_registration_url', array('label'=>__('Registration URL (the link to your webinar registration page)*','webinara'), 'placeholder' => __('Registration URL','webinara'), 'sec' => 'webi_require other_url_section', 'show_fld' => $show_fld));
		$this->webi_input_text('_webi_gotowebinar_id', array('label'=>__('GoToWebinar ID*','webinara'), 'placeholder' => 'XXX-XXX-XXX', 'sec' => 'webi_require gotowebinar_id_section', 'show_fld_goto' => $show_fld_goto));		
		$this->webi_show_label(array('class' => 'onstream_msg_section','message' => 'For onstream platform you must select timezone same as your connected account.'));
		if(isset($webinara_form_fields['general']['whyattend']['enable'])){
			if($webinara_form_fields['general']['whyattend']['type'] == 'textarea'){
				$this->webi_input_textarea('_webi_why_attened', array('label'=>$webinara_form_fields['general']['whyattend']['label']));
			} elseif($webinara_form_fields['general']['whyattend']['type'] == 'wp-editor'){
				$this->webi_input_wp_editor('_webi_why_attened', array('label'=>$webinara_form_fields['general']['whyattend']['label']));
			}
			else{
				$this->webi_input_text('_webi_why_attened', array('label'=>$webinara_form_fields['general']['whyattend']['label'], 'placeholder'=>$webinara_form_fields['general']['whyattend']['label']));
			}
		}
		if(isset($webinara_form_fields['general']['whoattened']['enable'])){
			if($webinara_form_fields['general']['whoattened']['type'] == 'textarea'){
				$this->webi_input_textarea('_webi_who_attened', array('label'=>$webinara_form_fields['general']['whoattened']['label']));
			} elseif($webinara_form_fields['general']['whoattened']['type'] == 'wp-editor'){
				$this->webi_input_wp_editor('_webi_who_attened', array('label'=>$webinara_form_fields['general']['whoattened']['label']));
			}
			else{
				$this->webi_input_text('_webi_who_attened', array('label'=>$webinara_form_fields['general']['whoattened']['label'], 'placeholder'=>$webinara_form_fields['general']['whoattened']['label']));
			}
		}		
		$this->webi_speaker_section();
		if(isset($webinara_form_fields['additional']['attachments']['enable'])){
			$this->webi_attachment_section();
		}		
		if(isset($webinara_form_fields['additional']['video']['enable'])){
			$this->webi_video_section();
		}
		if(isset($webinara_form_fields['additional']['sponsor']['enable'])){
			$this->webi_sponsor_section($webinara_form_fields['additional']['sponsor']['label']);
		}
		echo "<input type='hidden' id='webi_current_section'>";
		echo "<input type='hidden' id='webi_con_platform' value='".implode(",",$con_platform_arr)."'>";
        echo '</div>';
    }
	
	public function webi_event_options() {
		global $post;
		$webinara_form_fields = get_option('_webi_eventform_fields',true);
		$con_platform_arr = array();
		$con_platform_arr[] = (get_option('_webi_goto_connect') == 1) ? 1 : 0;
		$con_platform_arr[] = (get_option('_webi_onstream_connect') == 1) ? 1 : 0;
		$con_platform_arr[] = 1;
		$con_platform_arr[] = (get_option('_webi_readytalk_connect') == 1) ? 1 : 0;
		$con_platform_arr[] = 0;
		$con_platform_arr[] = 0;
		$con_platform_arr[] = (get_option('_webi_zoom_connect') == 1) ? 1 : 0;
        echo '<div class="webi-metabox">';
		echo '<input type="hidden" name="_webi_event_type" value="event">';
		$this->webi_input_text('_webi_title', array('label'=>__('Title','webinara'), 'placeholder' => $webinara_form_fields['general']['title']['label'],'readonly' => 'readonly'));
		if(isset($webinara_form_fields['general']['subtitle']['enable'])){
			if($webinara_form_fields['general']['subtitle']['type'] == 'textarea'){
				$this->webi_input_textarea('_webi_subtitle', array('label'=>$webinara_form_fields['general']['subtitle']['label'], 'placeholder' => $webinara_form_fields['general']['subtitle']['label']));
			} elseif($webinara_form_fields['general']['subtitle']['type'] == 'wp-editor'){
				$this->webi_input_wp_editor('_webi_subtitle', array('label'=>$webinara_form_fields['general']['subtitle']['label'], 'placeholder' => $webinara_form_fields['general']['subtitle']['label']));
			}
			else{
				$this->webi_input_text('_webi_subtitle', array('label'=>$webinara_form_fields['general']['subtitle']['label'], 'placeholder' => $webinara_form_fields['general']['subtitle']['label']));
			}
		}
		echo '<div class="" id="webi_datetime">';
			$this->webi_input_text('_webi_start_date', array('label'=>__('Start date*','webinara'), 'sec' => 'sec_3 webi_require', 'class' => 'date start', 'placeholder' => ''));
			$this->webi_input_text('_webi_start_time', array('label'=>__('Start time*','webinara'), 'sec' => 'sec_3 webi_require', 'class' => 'time start', 'placeholder' => ''));
			$this->webi_input_checkbox('_webi_featured', array('label'=>__('Featured event','webinara'), 'sec' => 'sec_3', 'label_class' => 'featured_opt', 'field_class' => 'webi_featured', 'option_count' => 1, 'placeholder' => ''));								
			$this->webi_input_text('_webi_end_date', array('label'=>__('End date*','webinara'), 'sec' => 'sec_3 webi_require', 'class' => 'date end', 'placeholder' => ''));
			$this->webi_input_text('_webi_end_time', array('label'=>__('End time*','webinara'), 'sec' => 'sec_3 webi_require', 'class' => 'time end', 'placeholder' => ''));						
			$this->webi_input_checkbox('_webi_all_day', array('label'=>__('All day','webinara'), 'sec' => 'sec_3', 'label_class' => 'allday_opt', 'field_class' => 'webi_all_day', 'option_count' => 1, 'placeholder' => ''));			
			echo '<div style="clear:both"></div>
		</div>';
		if(isset($webinara_form_fields['general']['timezone']['enable'])){	
			$this->webi_input_timezone('_webi_timezone', array('label' => $webinara_form_fields['general']['timezone']['label'].'*', 'default' => ''));			
		}			
		echo '<div class="webi_vanue">
			<p class="form-field"><label class="webi_label">'.__('Venue:','webinara').'</label></p>
			<div class="venue_section">';				
				$this->webi_input_text('_webi_address', array('label'=>__('Address*','webinara'), 'placeholder' => '', 'sec' => 'webi_require'));
				$this->webi_input_text('_webi_city', array('label'=>__('City*','webinara'), 'placeholder' => '', 'sec' => 'sec_2 webi_require'));				
				$this->webi_input_text('_webi_state', array('label'=>__('State*','webinara'), 'placeholder' => '', 'sec' => 'sec_2 webi_require'));
				$this->webi_input_text('_webi_zipcode', array('label'=>__('Zipcode*','webinara'), 'placeholder' => '', 'sec' => 'sec_2 webi_require'));
				$this->webi_input_text('_webi_country', array('label'=>__('Country*','webinara'), 'placeholder' => '', 'sec' => 'sec_2 webi_require'));				
			echo '</div>
		</div>';		
		$this->webi_input_text('_webi_registration_url', array('label'=>__('Registration URL (the link to your event registration page)*','webinara'), 'placeholder' => __('Registration URL','webinara'), 'sec' => 'webi_require'));		
		if(isset($webinara_form_fields['general']['whyattend']['enable'])){
			if($webinara_form_fields['general']['whyattend']['type'] == 'textarea'){
				$this->webi_input_textarea('_webi_why_attened', array('label'=>$webinara_form_fields['general']['whyattend']['label']));
			} elseif($webinara_form_fields['general']['whyattend']['type'] == 'wp-editor'){
				$this->webi_input_wp_editor('_webi_why_attened', array('label'=>$webinara_form_fields['general']['whyattend']['label']));
			}
			else{
				$this->webi_input_text('_webi_why_attened', array('label'=>$webinara_form_fields['general']['whyattend']['label'], 'placeholder'=>$webinara_form_fields['general']['whyattend']['label']));
			}
		}
		if(isset($webinara_form_fields['general']['whoattened']['enable'])){
			if($webinara_form_fields['general']['whoattened']['type'] == 'textarea'){
				$this->webi_input_textarea('_webi_who_attened', array('label'=>$webinara_form_fields['general']['whoattened']['label']));
			} elseif($webinara_form_fields['general']['whoattened']['type'] == 'wp-editor'){
				$this->webi_input_wp_editor('_webi_who_attened', array('label'=>$webinara_form_fields['general']['whoattened']['label']));
			}
			else{
				$this->webi_input_text('_webi_who_attened', array('label'=>$webinara_form_fields['general']['whoattened']['label'], 'placeholder'=>$webinara_form_fields['general']['whoattened']['label']));
			}
		}		
		$this->webi_speaker_section();
		if(isset($webinara_form_fields['additional']['attachments']['enable'])){
			$this->webi_attachment_section();
		}		
		if(isset($webinara_form_fields['additional']['video']['enable'])){
			$this->webi_video_section();
		}
		if(isset($webinara_form_fields['additional']['sponsor']['enable'])){
			$this->webi_sponsor_section($webinara_form_fields['additional']['sponsor']['label']);
		}
		echo "<input type='hidden' id='webi_current_section'>";
		echo "<input type='hidden' id='webi_con_platform' value='".implode(",",$con_platform_arr)."'>";
        echo '</div>';
    }
	
	public function webi_speaker_section(){	
		global $post;				
		$speaker_firstname = get_post_meta($post->ID, '_webi_speaker_first_name', true);
		$speaker_lastname = get_post_meta($post->ID, '_webi_speaker_last_name', true);		
		$speaker_company = get_post_meta($post->ID, '_webi_speaker_company', true);
		$speaker_title = get_post_meta($post->ID, '_webi_speaker_title', true);
		$speaker_website = get_post_meta($post->ID, '_webi_speaker_website', true);
		$speaker_twitter = get_post_meta($post->ID, '_webi_speaker_twitter', true);
		$speaker_facebook = get_post_meta($post->ID, '_webi_speaker_facebook', true);
		$speaker_linkedin = get_post_meta($post->ID, '_webi_speaker_linkedin', true);
		$speaker_bio = get_post_meta($post->ID, '_webi_speaker_bio', true);		
		$speaker_image = get_post_meta($post->ID, '_webi_speaker_image', true);			
		$webinara_form_fields = get_option('_webi_webinarform_fields',true);	
		if(empty(get_post_meta($post->ID, '_webi_speaker_first_name', true)))
		{
			$speaker_firstname = $speaker_lastname = $speaker_lastname = $speaker_company = $speaker_title = $speaker_website = $speaker_twitter = $speaker_facebook = $speaker_linkedin = $speaker_bio = $speaker_image = array('');
		}
		if(count($webinara_form_fields))
		{
			foreach($webinara_form_fields  as $group_key => $group_fields){
				if($group_key == 'speaker')
				{
					?>
					<div class="webi_speakers">
						<label class="webi_label"><?php esc_html_e('Add Speakers', 'webinara'); ?></label>
						<div class="speaker_section">
							<?php 
							for($speaker_count = 0; $speaker_count < count($speaker_firstname); $speaker_count++)
							{
								if(isset($group_fields))
								{
									echo '<div class="repeated_speaker_section">';
										foreach ( $group_fields as $field_key => $field ) {	
											if(isset($field['enable']) && $field['enable'] == 1)
											{
												if($field['type'] == 'text'){
													$result_fld = get_post_meta($post->ID, '_webi_'.$field_key , true);	
													?>
													<p class="form-field sec_2 <?php if(isset($field['required']) && $field['required'] == 1) { echo 'webi_require'; }?>">
														<label for=""><?php if(isset($field['label']) && !empty($field['label'])) { echo $field['label']; } if(isset($field['required']) && $field['required'] == 1) { echo '*'; }?> </label>
														<input type="text" name="_webi_<?php echo $field_key; ?>[]" placeholder="<?php if(isset($field['description']) && !empty($field['description'])) { echo $field['description']; }?>" value="<?php if(is_array($result_fld)){ if(count($result_fld) != 0){ echo $result_fld[$speaker_count]; } }?>">
													</p>
													<?php
												}
												
												if($field['type'] == 'textarea'){
													$result_fld = get_post_meta($post->ID, '_webi_'.$field_key , true);	
													?>
													<p class="form-field <?php if(isset($field['required']) && $field['required'] == 1) { echo 'webi_require'; }?>">
														<label><?php if(isset($field['label']) && !empty($field['label'])) { esc_html_e($field['label'], 'webinara'); } if(isset($field['required']) && $field['required'] == 1) { echo '*'; }?> </label>
														<textarea name="_webi_<?php echo $field_key; ?>[]" placeholder="<?php if(isset($field['description']) && !empty($field['description'])) { echo $field['description']; }?>" rows="5"><?php if(is_array($result_fld)){ if(count($result_fld) != 0){ echo $result_fld[$speaker_count]; } }?></textarea>
													</p>
													<?php
												}
												
												if($field['type'] == 'wp-editor'){
													$result_data = '';
													$result_fld = get_post_meta($post->ID, '_webi_'.$field_key , true);
													if(is_array($result_fld)){ 
														if(count($result_fld) != 0){ 
															$result_data = $result_fld[$speaker_count]; 
														} 
													}											
													?>
													<p class="form-field <?php if(isset($field['required']) && $field['required'] == 1) { echo 'webi_require'; }?>">
														<label><?php if(isset($field['label']) && !empty($field['label'])) { esc_html_e($field['label'], 'webinara'); } if(isset($field['required']) && $field['required'] == 1) { echo '*'; }?> </label>													
														<?php wp_editor( $result_data, '_webi_'.$field_key, array("media_buttons" => false, "wpautop" => false, "textarea_name" => '_webi_'.$field_key.'[]', "textarea_rows" => 12) ); ?>
													</p>
													<?php
												}
												
												if($field['type'] == 'image'){
													$result_fld = get_post_meta($post->ID, '_webi_'.$field_key , true);	
													?>
													<p class="form-field <?php if(isset($field['required']) && $field['required'] == 1) { echo 'webi_require'; }?>">
														<label for=""><?php if(isset($field['label']) && !empty($field['label'])) { echo $field['label']; } if(isset($field['required']) && $field['required'] == 1) { echo '* <span class="req_label"> Required</span>'; }?> </label>							
														<span class="webi_speakerimg">								
															<img src="<?php if(count($result_fld) != 0){ echo $result_fld[$speaker_count]; }?>" style="<?php if(empty($result_fld[$speaker_count])){ echo 'display:none;'; } ?> max-width: 120px;">
															<input type="hidden" name="_webi_<?php echo $field_key; ?>[]" class="webi_upload_file_url" value="<?php if(count($result_fld) != 0){ echo $result_fld[$speaker_count]; }?>">
															<a href="javascript:void(0);" class="button-primary webi_upload_file_button add_speaker_img"><?php esc_html_e('Upload', 'webinara'); ?></a>
															<a href="javascript:void(0);" class="button-primary webi_remove_image" style="<?php if(empty($result_fld[$speaker_count])){ echo 'display:none'; } ?>"><?php esc_html_e('Remove', 'webinara'); ?></a>
															<span class="clr"></span>
														</span>																
													</p>												
													<?php
												}
											}
										}
										?>
										<p class="form-field"><a href="javascript:void(0);" class="webi_remove_speaker" style="<?php if(count($speaker_firstname) > 1 ){ echo 'display:block'; } ?>"><?php esc_html_e('Remove', 'webinara'); ?></a></p>		
										<?php
									echo '</div>';	
								}	
							}													
							?>
							
						</div>	
						<p class="form-field" style="text-align: right"><a href="javascript:void(0);" class="webi_add_speaker button-primary"><?php esc_html_e('Add New Speaker', 'webinara'); ?></a></p>		
					</div>
					<?php
				}							
			}
		}
		?>
		
		<?php
	}	
		
	public function webi_attachment_section(){
		global $post;				
		$webi_attachment = get_post_meta($post->ID, '_webi_attachment', true);
		if(empty($webi_attachment))
		{
			$webi_attachment = array('');
		}
		echo '<div class="webi_attachments">
			<label class="webi_label">'.__('Attachments','webinara').'</label>
			<div class="attachment_section">';
				if(count($webi_attachment) != 0)
				{
					$show_remove = '';
					foreach($webi_attachment as $attachment)
					{						
						if(count($webi_attachment) > 1){ $show_remove = ' style="display:inline"'; }
						echo '<div class="repeated_attachment_section">
							<p class="form-field sec_2 webi_attach_fld">
								<input type="text" name="_webi_attachment[]" placeholder="'.__('http://','webinara').'" value="'.$attachment.'">
							</p>																	
							<p class="form-field sec_2 webi_attach_action">
								<a href="javascript:void(0);" class="button-primary webi_upload_file_button add_file_attachment">'.__('Upload','webinara').'</a>
								<a href="javascript:void(0);" class="webi_remove_attachment"'.$show_remove.'>'.__('Remove','webinara').'</a>
							</p>		
						</div>';
					}
				}
			echo '</div>	
			<p class="form-field" style="text-align: right"><a href="javascript:void(0);" class="webi_add_attachment button-primary">'.__('Add New Attachment','webinara').'</a></p>		
		</div>';
	}	

	public function webi_carosel_section(){
		global $post;				
		$webi_attachment_car = get_post_meta($post->ID, '_webi_carosel_img', true);
		if(empty($webi_attachment_car))
		{
			$webi_attachment_car = array('');
		}
		echo '<div class="webi_carosel">
			<label class="webi_label">'.__('Webinar slider images','webinara').'</label>
			<div class="caroselimg_section">';
				if(count($webi_attachment_car) != 0)
				{
					$show_remove = '';
					foreach($webi_attachment_car as $attachment)
					{
						$hide_image = '';
						if(empty($attachment)){ $hide_image=' style="display:none"'; }	
						if(count($webi_attachment_car) > 1){ $show_remove = ' style="display:inline"'; }
						echo '<div class="repeated_caroselimg_section">
							<p class="form-field sec_2 webi_imgcarosel_fld">
								<input type="hidden" name="_webi_carosel_img[]" value="'.$attachment.'">
								<img src="'.$attachment.'" '.$hide_image.'>
							</p>
							<p class="form-field sec_2 webi_caro_action">
								<a href="javascript:void(0);" class="button-primary webi_upload_file_button add_imgcarosel_attachment">'.__('Upload','webinara').'</a>
								<a href="javascript:void(0);" class="webi_remove_imgcarosel"'.$show_remove.'>'.__('Remove','webinara').'</a>
							</p>
																																
						</div>';
					}
				}
			echo '</div>	
			<p class="form-field" style="text-align: right"><a href="javascript:void(0);" class="webi_add_carselimg button-primary">'.__('Add New Image','webinara').'</a></p>		
		</div>';
	}
	
	public function webi_video_section(){
		global $post;				
		$webi_attachment_car = get_post_meta($post->ID, '_webi_promotional_video', true);	
		echo '<p class="form-field video_fld">
				<label for="youtube_input">'.__('Promotional video (Youtube OR Vimeo)','webinara').'</label>
				<input class="video-input" type="text" placeholder="'.__('https://www.youtube.com/?gl=UA&amp;hl=ru','webinara').'" value="'.$webi_attachment_car.'" name="_webi_promotional_video" id="youtube_input">
		</p>';
		
	}

	public function webi_sponsor_section($label){
		global $post;				
		$webi_attachment_car = get_post_meta($post->ID, '_webi_sponser', true);
		if(empty($webi_attachment_car))
		{
			$webi_attachment_car = array('');
		}
		echo '<div class="webi_sponsor">
			<label class="webi_label">'.esc_html_e($label,'webinara').'</label>
			<div class="sponsorimg_section">';
				if(count($webi_attachment_car) != 0)
				{
					$show_remove = '';
					foreach($webi_attachment_car as $attachment)
					{
						$hide_image = '';
						if(empty($attachment)){ $hide_image=' style="display:none"'; }	
						if(count($webi_attachment_car) > 1){ $show_remove = ' style="display:inline"'; }
						echo '<div class="repeated_sponsorimg_section">
							<p class="form-field sec_2 webi_imgsponsor_fld">
								<input type="hidden" name="_webi_sponser[]" value="'.$attachment.'">
								<img src="'.$attachment.'" '.$hide_image.'>
							</p>
							<p class="form-field sec_2 webi_sponsor_action">
								<a href="javascript:void(0);" class="button-primary webi_upload_file_button add_imgsponsor_attachment">'.__('Upload','webinara').'</a>
								<a href="javascript:void(0);" class="webi_remove_imgsponsor"'.$show_remove.'>'.__('Remove','webinara').'</a>
							</p>
																																
						</div>';
					}
				}
			echo '</div>	
			<p class="form-field" style="text-align: right"><a href="javascript:void(0);" class="webi_add_sponsorimg button-primary">'.__('Add New Logo','webinara').'</a></p>		
		</div>';
	}

    /**
	 * input_text function.
	 *
	 * @param mixed $key
	 * @par6am mixed $field
	 */
	public function webi_input_text( $key, $field ) {
		global $post;
		if ( ! isset( $field['value'] ) ) {	
			if($key == '_webi_title'){
				if(get_the_title($post->ID) == 'Auto Draft'){
					$field['value'] = '';
				}
				else
				{
					$field['value'] = get_the_title($post->ID);
				}
			}
			else
			{
				$field['value'] = get_post_meta( $post->ID, $key, true );
			}
		}
		if ( ! empty( $field['name'] ) ) {
			$name = $field['name'];
		} else {
			$name = $key;
		}
		$disbled = $readonly = '';
		if($name == '_webi_start_time' || $name == '_webi_end_time'){
			if(get_post_meta( $post->ID, '_webi_all_day', true ) == 1){
				$disbled = " disabled";
			}
		}
		
		if(isset($field['readonly']) && $field['readonly'] == 'readonly'){
			$readonly = " readonly='readonly'";
		}
		?>	
		<p class="form-field <?php if ( ! empty( $field['sec'] ) ) : echo $field['sec']; endif; ?>" <?php if ( ! empty( $field['show_fld'] ) ) : echo $field['show_fld']; endif; ?> <?php if ( ! empty( $field['show_fld_goto'] ) ) : echo $field['show_fld_goto']; endif; ?>>
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?> <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>" <?php if ( ! empty( $field['class'] ) ) : echo "class='".$field['class']."'"; endif; ?> placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" <?php echo $disbled.''.$readonly ?> />
		</p>
		<?php
	}
	
	/**
	 * input_wp_editor function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 * @since 2.8
	 */
	public function webi_input_wp_editor( $key, $field ) {
		global $post;
		if ( ! isset( $field['value'] ) ) {
			$field['value'] = get_post_meta( $post->ID, $key, true );
		}
		if ( ! empty( $field['name'] ) ) {
			$name = $field['name'];
		} else {
			$name = $key;
			}?>
			<p class="form-field">
				<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?> <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
			
	
			<?php
			wp_editor( $field['value'], $name, array("media_buttons" => false, "wpautop" => false, "textarea_rows" => 12) );
			?>
			</p>
			<?php
		}
	
	/**
	 * input_date function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public function webi_input_date( $key, $field ) {
	    global $post;
	    if ( ! isset( $field['value'] ) ) {
	        $date = get_post_meta( $post->ID, $key, true );
	        if(!empty($date)){	        	
				$php_date_format = 'Y-m-d';
				$date = date($php_date_format,strtotime($date));
				$field['value'] = $date;
	        }
	    }
	    if ( ! empty( $field['name'] ) ) {
	        $name = $field['name'];
	    } else {
	        $name = $key;
	    }
	    ?>
		<p class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?> <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php if( isset($field['value']) ) echo esc_attr( $field['value'] ); ?>" data-picker="datepicker" />
		</p>
		<?php
	}

	/**
	 * input_text function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public function webi_input_textarea( $key, $field ) {
		global $post;
		if ( ! isset( $field['value'] ) ) {
			$field['value'] = get_post_meta( $post->ID, $key, true );
		}
		if ( ! empty( $field['name'] ) ) {
			$name = $field['name'];
		} else {
			$name = $key;
		}
	?>
		<p class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?> <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
			<textarea name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"><?php echo esc_html( $field['value'] ); ?></textarea>
		</p>
		<?php
	}

	/**
	 * input_select function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public function webi_input_select( $key, $field ) {	   
		global $post;
		if ( ! isset( $field['value'] ) ) {
			$field['value'] = get_post_meta( $post->ID, $key, true );
		}
		if ( ! empty( $field['name'] ) ) {
			$name = $field['name'];
		} else {
			$name = $key;
		}
		?>

		<p class="form-field sec_2 <?php if ( ! empty( $field['sec'] ) ) : echo $field['sec']; endif; ?>">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?> <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
			<select name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>">
				<?php foreach ( $field['options'] as $key => $value ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php if ( isset( $field['value'] ) ) selected( $field['value'], $key ); ?>><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

	/**
	 * input_select function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public function webi_input_multiselect( $key, $field ) {
		global $post;
		if ( ! isset( $field['value'] ) ) {
			$field['value'] = get_post_meta( $post->ID, $key, true );
		}
		if ( ! empty( $field['name'] ) ) {
			$name = $field['name'];
		} else {
			$name = $key;
		}
		?>
		<p class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?> <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
			<select multiple="multiple" name="<?php echo esc_attr( $name ); ?>[]" id="<?php echo esc_attr( $key ); ?>">
				<?php foreach ( $field['options'] as $key => $value ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php if ( ! empty( $field['value'] ) && is_array( $field['value'] ) ) selected( in_array( $key, $field['value'] ), true ); ?>><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

	/**
	 * input_checkbox function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public function webi_input_checkbox( $key, $field ) {
		global $post;
		if ( empty( $field['value'] ) ) {
			$field['value'] = get_post_meta( $post->ID, $key, true );
		}
		if ( ! empty( $field['name'] ) ) {
			$name = $field['name'];
		} else {
			$name = $key;
		}
		?>
		<p class="form-field form-field-checkbox <?php if ( ! empty( $field['sec'] ) ) : echo $field['sec']; endif; ?>">
			<?php 
			if($field['option_count'] == 1)
			{
				?>
				<label for="<?php echo esc_attr( $key ); ?>" <?php if ( ! empty( $field['label_class'] ) ) : echo "class='".$field['label_class']."'"; endif; ?>>
				<input type="checkbox" class="checkbox" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>" <?php if ( ! empty( $field['field_class'] ) ) : echo "class='".$field['field_class']."'"; endif; ?> value="1" <?php checked( $field['value'], 1 ); ?> />
				<?php echo esc_html( $field['label'] ) ; ?></label>
				<?php if ( ! empty( $field['description'] ) ) : ?><span class="description"><?php echo $field['description']; ?></span><?php endif;
			}
			else
			{
				?>
				<label for="<?php echo esc_attr( $key ); ?>" <?php if ( ! empty( $field['label_class'] ) ) : echo "class='".$field['label_class']."'"; endif; ?>><?php echo esc_html( $field['label'] ) ; ?></label>
				<input type="checkbox" class="checkbox" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>" <?php if ( ! empty( $field['field_class'] ) ) : echo "class='".$field['field_class']."'"; endif; ?> value="1" <?php checked( $field['value'], 1 ); ?> />
				<?php if ( ! empty( $field['description'] ) ) : ?><span class="description"><?php echo $field['description']; ?></span><?php endif;
			}
			?>
			
		</p>
		<?php
	}

	/**
	 * input_time function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public function webi_input_time( $key, $field ) {
		global $post;
		if ( ! isset( $field['value'] ) ) {
			$field['value'] = get_post_meta( $post->ID, $key, true );
		}
		if ( ! empty( $field['name'] ) ) {
			$name = $field['name'];
		} else {
			$name = $key;
		}
		?>
		<p class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?> <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
			<input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" data-picker="timepicker" />
		</p>
			<?php
		}
		
		/**
		 * input_timezone function.
		 *
		 * @param mixed $key
		 * @param mixed $field
		 */
		public function webi_input_timezone( $key, $field ) {
			global $post;
			if ( ! isset( $field['value'] ) ) {
				$field['value'] = get_post_meta( $post->ID, $key, true );
			}
			if ( ! empty( $field['name'] ) ) {
				$name = $field['name'];
			} else {
				$name = $key;
			}
			?>
				<p class="form-field sec_2 <?php echo esc_attr( isset( $field['additional_class'] ) ? $field['additional_class'] : '' ); ?>" <?php if(get_post_meta($post->ID,'_webi_platform',true) == 7){ echo 'style="display:none"'; } ?>>
					<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?> <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
					 <select name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>" id="<?php echo isset( $field['id'] ) ? esc_attr( $field['id'] ) :  esc_attr( $key ); ?>" class="input-select <?php echo esc_attr( isset( $field['class'] ) ? $field['class'] : $key ); ?>">
		 			<?php 
		 			$value = isset($field['value']) ? $field['value'] : $field['default'];			 								
		 			?>										
					<option value="Pacific/Samoa" <?php if($value == 'Pacific/Samoa'){ echo "selected"; }?>><?php _e('(GMT -11:00) Midway Island, Samoa','webinara'); ?></option>
					<option value="Pacific/Honolulu" <?php if($value == 'Pacific/Honolulu'){ echo "selected"; }?>><?php _e('(GMT -10:00) Hawaii','webinara'); ?></option>
					<option value="America/Anchorage" <?php if($value == 'America/Anchorage'){ echo "selected"; }?>><?php _e('(GMT -08:00) Alaska','webinara'); ?></option>
					<option value="America/Los_Angeles" <?php if($value == 'America/Los_Angeles'){ echo "selected"; }?>><?php _e('(GMT -07:00) Pacific Time (US and Canada), Tijuana','webinara'); ?></option>
					<option value="America/Phoenix" <?php if($value == 'America/Phoenix'){ echo "selected"; }?>><?php _e('(GMT -07:00) Arizona','webinara'); ?></option>
					<option value="America/Denver" <?php if($value == 'America/Denver'){ echo "selected"; }?>><?php _e('(GMT -06:00) Mountain Time (US and Canada)','webinara'); ?></option>
					<option value="America/Mexico_City" <?php if($value == 'America/Mexico_City'){ echo "selected"; }?>><?php _e('(GMT -05:00) Mexico City','webinara'); ?></option>
					<option value="America/Chicago" <?php if($value == 'America/Chicago'){ echo "selected"; }?>><?php _e('(GMT -05:00) Central Time (US and Canada)','webinara'); ?></option>
					<option value="Canada/Saskatchewan" <?php if($value == 'Canada/Saskatchewan'){ echo "selected"; }?>><?php _e('(GMT -06:00) Regina','webinara'); ?></option>
					<option value="America/Bogota" <?php if($value == 'America/Bogota'){ echo "selected"; }?>><?php _e('(GMT -05:00) Bogota, Lima, Quito','webinara'); ?></option>
					<option value="America/Indianapolis" <?php if($value == 'America/Indianapolis'){ echo "selected"; }?>><?php _e('(GMT -04:00) Indiana (East)','webinara'); ?></option>
					<option value="America/New_York" <?php if($value == 'America/New_York'){ echo "selected"; }?>><?php _e('(GMT -04:00) Eastern Time (US and Canada)','webinara'); ?></option>
					<option value="America/Caracas" <?php if($value == 'America/Caracas'){ echo "selected"; }?>><?php _e('(GMT -04:00)  Caracas, La Paz','webinara'); ?></option>
					<option value="America/Halifax" <?php if($value == 'America/Halifax'){ echo "selected"; }?>><?php _e('(GMT -03:00) Atlantic Time (Canada)','webinara'); ?></option>
					<option value="America/Guyana" <?php if($value == 'America/Guyana'){ echo "selected"; }?>><?php _e('(GMT -04:00) Georgetown','webinara'); ?></option>
					<option value="America/St_Johns" <?php if($value == 'America/St_Johns'){ echo "selected"; }?>><?php _e('(GMT -02:30)  Newfoundland','webinara'); ?></option>
					<option value="America/Buenos_Aires" <?php if($value == 'America/Buenos_Aires'){ echo "selected"; }?>><?php _e('(GMT -03:00) Buenos Aires','webinara'); ?></option>
					<option value="America/Santiago" <?php if($value == 'America/Santiago'){ echo "selected"; }?>><?php _e('(GMT -04:00) Santiago','webinara'); ?></option>
					<option value="America/Sao_Paulo" <?php if($value == 'America/Sao_Paulo'){ echo "selected"; }?>><?php _e('(GMT -03:00) Brasilia','webinara'); ?></option>
					<option value="Atlantic/Azores" <?php if($value == 'Atlantic/Azores'){ echo "selected"; }?>><?php _e('(GMT) Azores','webinara'); ?></option>
					<option value="Atlantic/Cape_Verde" <?php if($value == 'Atlantic/Cape_Verde'){ echo "selected"; }?>><?php _e('(GMT -01:00) Cape Verde Is.','webinara'); ?></option>
					<option value="GMT" <?php if($value == 'GMT'){ echo "selected"; }?>><?php _e('(GMT)  Greenwich Mean Time','webinara'); ?></option>
					<option value="Africa/Casablanca" <?php if($value == 'Africa/Casablanca'){ echo "selected"; }?>><?php _e('(GMT +01:00)  Casablanca, Monrovia','webinara'); ?></option>
					<option value="Europe/London" <?php if($value == 'Europe/London'){ echo "selected"; }?>><?php _e('(GMT +01:00)  Dublin, Edinburgh, Lisbon, London','webinara'); ?></option>
					<option value="Europe/Prague" <?php if($value == 'Europe/Prague'){ echo "selected"; }?>><?php _e('(GMT +02:00)  Belgrade, Bratislava, Budapest, Ljubljana, Prague','webinara'); ?></option>
					<option value="Africa/Malabo" <?php if($value == 'Africa/Malabo'){ echo "selected"; }?>><?php _e('(GMT +01:00)  West Central Africa','webinara'); ?></option>
					<option value="Europe/Warsaw" <?php if($value == 'Europe/Warsaw'){ echo "selected"; }?>><?php _e('(GMT +02:00)  Sarajevo, Skopje, Sofija, Vilnius, Warsaw, Zagreb','webinara'); ?></option>
					<option value="Europe/Brussels" <?php if($value == 'Europe/Brussels'){ echo "selected"; }?>><?php _e('(GMT +02:00)  Brussels, Copenhagen, Madrid, Paris','webinara'); ?></option>
					<option value="Europe/Amsterdam" <?php if($value == 'Europe/Amsterdam'){ echo "selected"; }?>><?php _e('(GMT +02:00)  Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna','webinara'); ?></option>
					<option value="Africa/Harare" <?php if($value == 'Africa/Harare'){ echo "selected"; }?>><?php _e('(GMT +02:00)  Harare, Pretoria','webinara'); ?></option>
					<option value="Europe/Helsinki" <?php if($value == 'Europe/Helsinki'){ echo "selected"; }?>><?php _e('(GMT +03:00)  Helsinki, Riga, Tallinn','webinara'); ?></option>
					<option value="Europe/Athens" <?php if($value == 'Europe/Athens'){ echo "selected"; }?>><?php _e('(GMT +03:00)  Athens, Istanbul','webinara'); ?></option>
					<option value="Asia/Jerusalem" <?php if($value == 'Asia/Jerusalem'){ echo "selected"; }?>><?php _e('(GMT +03:00)  Jerusalem','webinara'); ?></option>
					<option value="Africa/Cairo" <?php if($value == 'Africa/Cairo'){ echo "selected"; }?>><?php _e('(GMT +02:00)  Cairo','webinara'); ?></option>
					<option value="Europe/Bucharest" <?php if($value == 'Europe/Bucharest'){ echo "selected"; }?>><?php _e('(GMT +03:00)  Bucharest','webinara'); ?></option>
					<option value="Asia/Kuwait" <?php if($value == 'Asia/Kuwait'){ echo "selected"; }?>><?php _e('(GMT +03:00)  Kuwait, Riyadh','webinara'); ?></option>
					<option value="Europe/Minsk" <?php if($value == 'Europe/Minsk'){ echo "selected"; }?>><?php _e('(GMT +03:00)  Minsk','webinara'); ?></option>
					<option value="Africa/Nairobi" <?php if($value == 'Africa/Nairobi'){ echo "selected"; }?>><?php _e('(GMT +03:00)  Nairobi','webinara'); ?></option>
					<option value="Asia/Baghdad" <?php if($value == 'Asia/Baghdad'){ echo "selected"; }?>><?php _e('(GMT +03:00)  Baghdad','webinara'); ?></option>
					<option value="Europe/Moscow" <?php if($value == 'Europe/Moscow'){ echo "selected"; }?>><?php _e('(GMT +03:00)  Moscow, St. Petersburg, Volgograd','webinara'); ?></option>
					<option value="Asia/Tehran" <?php if($value == 'Asia/Tehran'){ echo "selected"; }?>><?php _e('(GMT +04:30)  Tehran','webinara'); ?></option>
					<option value="Asia/Tbilisi" <?php if($value == 'Asia/Tbilisi'){ echo "selected"; }?>><?php _e('(GMT +04:00)  Baku,Tbilisi, Yerevan','webinara'); ?></option>
					<option value="Asia/Muscat" <?php if($value == 'Asia/Muscat'){ echo "selected"; }?>><?php _e('(GMT +04:00)  Abu Dhabi, Muscat','webinara'); ?></option>
					<option value="Asia/Kabul" <?php if($value == 'Asia/Kabul'){ echo "selected"; }?>><?php _e('(GMT +04:30)  Kabul','webinara'); ?></option>
					<option value="Asia/Yekaterinburg" <?php if($value == 'Asia/Yekaterinburg'){ echo "selected"; }?>><?php _e('(GMT +05:00)  Yekaterinburg','webinara'); ?></option>
					<option value="Asia/Karachi" <?php if($value == 'Asia/Karachi'){ echo "selected"; }?>><?php _e('(GMT +05:00)  Islamabad, Karachi, Tashkent','webinara'); ?></option>
					<option value="Asia/Kolkata" <?php if($value == 'Asia/Kolkata'){ echo "selected"; }?>><?php _e('(GMT +05:30)  Calcutta, Chennai, Mumbai, New Delhi','webinara'); ?></option>
					<option value="Asia/Colombo" <?php if($value == 'Asia/Colombo'){ echo "selected"; }?>><?php _e('(GMT +05:30)  SriJayawardenepura','webinara'); ?></option>
					<option value="Asia/Katmandu" <?php if($value == 'Asia/Katmandu'){ echo "selected"; }?>><?php _e('(GMT +05:45)  Kathmandu','webinara'); ?></option>
					<option value="Asia/Novosibirsk" <?php if($value == 'Asia/Novosibirsk'){ echo "selected"; }?>><?php _e('(GMT +07:00)  Almaty, Novosibirsk','webinara'); ?></option>
					<option value="Asia/Dhaka" <?php if($value == 'Asia/Dhaka'){ echo "selected"; }?>><?php _e('(GMT +06:00)  Astana, Dhaka','webinara'); ?></option>
					<option value="Asia/Rangoon" <?php if($value == 'Asia/Rangoon'){ echo "selected"; }?>><?php _e('(GMT +06:30)  Rangoon','webinara'); ?></option>
					<option value="Asia/Bangkok" <?php if($value == 'Asia/Bangkok'){ echo "selected"; }?>><?php _e('(GMT +07:00)  Bangkok','webinara'); ?></option>
					<option value="Asia/Krasnoyarsk" <?php if($value == 'Asia/Krasnoyarsk'){ echo "selected"; }?>><?php _e('(GMT +07:00)  Krasnoyarsk','webinara'); ?></option>
					<option value="Asia/Jakarta" <?php if($value == 'Asia/Jakarta'){ echo "selected"; }?>><?php _e('(GMT +07:00)  Hanoi, Jakarta','webinara'); ?></option>
					<option value="Asia/Hong_Kong" <?php if($value == 'Asia/Hong_Kong'){ echo "selected"; }?>><?php _e('(GMT +08:00)  Hong Kong','webinara'); ?></option>
					<option value="Asia/Shanghai" <?php if($value == 'Asia/Shanghai'){ echo "selected"; }?>><?php _e('(GMT +08:00)  Beijing, Chongqing, Urumqi, Taipei','webinara'); ?></option>
					<option value="Australia/Perth" <?php if($value == 'Australia/Perth'){ echo "selected"; }?>><?php _e('(GMT +08:00)  Perth','webinara'); ?></option>
					<option value="Asia/Taipei" <?php if($value == 'Asia/Taipei'){ echo "selected"; }?>><?php _e('(GMT +08:00)  Taipei','webinara'); ?></option>
					<option value="Asia/Singapore" <?php if($value == 'Asia/Singapore'){ echo "selected"; }?>><?php _e('(GMT +08:00)  Kuala Lumpur, Singapore','webinara'); ?></option>
					<option value="Asia/Irkutsk" <?php if($value == 'Asia/Irkutsk'){ echo "selected"; }?>><?php _e('(GMT +08:00)  Irkutsk, Ulaan Bataar','webinara'); ?></option>
					<option value="Asia/Seoul" <?php if($value == 'Asia/Seoul'){ echo "selected"; }?>><?php _e('(GMT +09:00)  Seoul','webinara'); ?></option>
					<option value="Asia/Tokyo" <?php if($value == 'Asia/Tokyo'){ echo "selected"; }?>><?php _e('(GMT +09:00)  Osaka, Sapporo, Tokyo','webinara'); ?></option>
					<option value="Asia/Yakutsk" <?php if($value == 'Asia/Yakutsk'){ echo "selected"; }?>><?php _e('(GMT +09:00)  Yakutsk','webinara'); ?></option>
					<option value="Australia/Darwin" <?php if($value == 'Australia/Darwin'){ echo "selected"; }?>><?php _e('(GMT +09:30)  Darwin','webinara'); ?></option>
					<option value="Asia/Vladivostok" <?php if($value == 'Asia/Vladivostok'){ echo "selected"; }?>><?php _e('(GMT +10:00)  Vladivostok','webinara'); ?></option>
					<option value="Pacific/Guam" <?php if($value == 'Pacific/Guam'){ echo "selected"; }?>><?php _e('(GMT +10:00)  Guam, Port Moresby','webinara'); ?></option>
					<option value="Asia/Magadan" <?php if($value == 'Asia/Magadan'){ echo "selected"; }?>><?php _e('(GMT +11:00)  Magadan, Solomon Is., New Caledonia','webinara'); ?></option>
					<option value="Australia/Brisbane" <?php if($value == 'Australia/Brisbane'){ echo "selected"; }?>><?php _e('(GMT +10:00)  Brisbane','webinara'); ?></option>
					<option value="Australia/Adelaide" <?php if($value == 'Australia/Adelaide'){ echo "selected"; }?>><?php _e('(GMT +09:30)  Adelaide','webinara'); ?></option>
					<option value="Australia/Sydney" <?php if($value == 'Australia/Sydney'){ echo "selected"; }?>><?php _e('(GMT +10:00)  Canberra, Melbourne, Sydney','webinara'); ?></option>
					<option value="Australia/Hobart" <?php if($value == 'Australia/Hobart'){ echo "selected"; }?>><?php _e('(GMT +10:00)  Hobart','webinara'); ?></option>
					<option value="Pacific/Fiji" <?php if($value == 'Pacific/Fiji'){ echo "selected"; }?>><?php _e('(GMT +12:00)  Fiji, Kamchatka, Marshall Is.','webinara'); ?></option>
					<option value="Pacific/Tongatapu" <?php if($value == 'Pacific/Tongatapu'){ echo "selected"; }?>><?php _e('(GMT +13:00)  Nukualofa','webinara'); ?></option>
					<option value="Pacific/Auckland" <?php if($value == 'Pacific/Auckland'){ echo "selected"; }?>><?php _e('(GMT +12:00)  Auckland, Wellington','webinara'); ?></option>
		 			</select>
				</p>
		<?php
		}
		
		public function webi_input_timezone_zoom( $key, $field ) {
			global $post;
			if ( ! isset( $field['value'] ) ) {
				$field['value'] = get_post_meta( $post->ID, $key, true );
			}
			if ( ! empty( $field['name'] ) ) {
				$name = $field['name'];
			} else {
				$name = $key;
			}
			?>
				<p class="form-field <?php echo $wplatform; ?> sec_2 <?php echo esc_attr( isset( $field['additional_class'] ) ? $field['additional_class'] : '' ); ?>" <?php if(get_post_meta($post->ID,'_webi_platform',true) == 7){ echo 'style="display:block"'; } ?>>
					<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?> <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
					 <select name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>" id="<?php echo isset( $field['id'] ) ? esc_attr( $field['id'] ) :  esc_attr( $key ); ?>" class="input-select <?php echo esc_attr( isset( $field['class'] ) ? $field['class'] : $key ); ?>">
		 			<?php 
		 			$value = isset($field['value']) ? $field['value'] : $field['default'];			 										
		 			?>										
					<option value="Pacific/Midway" <?php if($value == 'Pacific/Midway'){ echo "selected"; }?>><?php _e('Midway Island, Samoa','webinara'); ?></option>
					<option value="Pacific/Pago_Pago" <?php if($value == 'Pacific/Pago_Pago'){ echo "selected"; }?>><?php _e('Pago Pago','webinara'); ?></option>
					<option value="Pacific/Honolulu" <?php if($value == 'Pacific/Honolulu'){ echo "selected"; }?>><?php _e('Hawaii','webinara'); ?></option>
					<option value="America/Anchorage" <?php if($value == 'America/Anchorage'){ echo "selected"; }?>><?php _e('Alaska','webinara'); ?></option>
					<option value="America/Vancouver" <?php if($value == 'America/Vancouver'){ echo "selected"; }?>><?php _e('Vancouver','webinara'); ?></option>
					<option value="America/Los_Angeles" <?php if($value == 'America/Los_Angeles'){ echo "selected"; }?>><?php _e('Pacific Time (US and Canada)','webinara'); ?></option>
					<option value="America/Tijuana" <?php if($value == 'America/Tijuana'){ echo "selected"; }?>><?php _e('Tijuana','webinara'); ?></option>
					<option value="America/Edmonton" <?php if($value == 'America/Edmonton'){ echo "selected"; }?>><?php _e('Edmonton','webinara'); ?></option>
					<option value="America/Denver" <?php if($value == 'America/Denver'){ echo "selected"; }?>><?php _e('Mountain Time (US and Canada)','webinara'); ?></option>
					<option value="America/Phoenix" <?php if($value == 'America/Phoenix'){ echo "selected"; }?>><?php _e('Arizona','webinara'); ?></option>
					<option value="America/Mazatlan" <?php if($value == 'America/Mazatlan'){ echo "selected"; }?>><?php _e('Mazatlan','webinara'); ?></option>
					<option value="America/Winnipeg" <?php if($value == 'America/Winnipeg'){ echo "selected"; }?>><?php _e('Winnipeg','webinara'); ?></option>
					<option value="America/Regina" <?php if($value == 'America/Regina'){ echo "selected"; }?>><?php _e('Saskatchewan','webinara'); ?></option>
					<option value="America/Chicago" <?php if($value == 'America/Chicago'){ echo "selected"; }?>><?php _e('Central Time (US and Canada)','webinara'); ?></option>
					<option value="America/Mexico_City" <?php if($value == 'America/Mexico_City'){ echo "selected"; }?>><?php _e('Mexico City','webinara'); ?></option>
					<option value="America/Guatemala" <?php if($value == 'America/Guatemala'){ echo "selected"; }?>><?php _e('Guatemala','webinara'); ?></option>
					<option value="America/El_Salvador" <?php if($value == 'America/El_Salvador'){ echo "selected"; }?>><?php _e('El Salvador','webinara'); ?></option>
					<option value="America/Managua" <?php if($value == 'America/Managua'){ echo "selected"; }?>><?php _e('Managua','webinara'); ?></option>
					<option value="America/Costa_Rica" <?php if($value == 'America/Costa_Rica'){ echo "selected"; }?>><?php _e('Costa Rica','webinara'); ?></option>
					<option value="America/Montreal" <?php if($value == 'America/Montreal'){ echo "selected"; }?>><?php _e('Montreal','webinara'); ?></option>
					<option value="America/New_York" <?php if($value == 'America/New_York'){ echo "selected"; }?>><?php _e('Eastern Time (US and Canada)','webinara'); ?></option>
					<option value="America/Indianapolis" <?php if($value == 'America/Indianapolis'){ echo "selected"; }?>><?php _e('Indiana (East)','webinara'); ?></option>
					<option value="America/Panama" <?php if($value == 'America/Panama'){ echo "selected"; }?>><?php _e('Panama','webinara'); ?></option>
					<option value="America/Bogota" <?php if($value == 'America/Bogota'){ echo "selected"; }?>><?php _e('Bogota','webinara'); ?></option>
					<option value="America/Lima" <?php if($value == 'America/Lima'){ echo "selected"; }?>><?php _e('Lima','webinara'); ?></option>
					<option value="America/Halifax" <?php if($value == 'America/Halifax'){ echo "selected"; }?>><?php _e('Halifax','webinara'); ?></option>
					<option value="America/Puerto_Rico" <?php if($value == 'America/Puerto_Rico'){ echo "selected"; }?>><?php _e('Puerto Rico','webinara'); ?></option>
					<option value="America/Caracas" <?php if($value == 'America/Caracas'){ echo "selected"; }?>><?php _e('Caracas','webinara'); ?></option>
					<option value="America/Santiago" <?php if($value == 'America/Santiago'){ echo "selected"; }?>><?php _e('Santiago','webinara'); ?></option>
					<option value="America/St_Johns" <?php if($value == 'America/St_Johns'){ echo "selected"; }?>><?php _e('Newfoundland and Labrador','webinara'); ?></option>
					<option value="America/Montevideo" <?php if($value == 'America/Montevideo'){ echo "selected"; }?>><?php _e('Montevideo','webinara'); ?></option>
					<option value="America/Araguaina" <?php if($value == 'America/Araguaina'){ echo "selected"; }?>><?php _e('Brasilia','webinara'); ?></option>
					<option value="America/Argentina/Buenos_Aires" <?php if($value == 'America/Argentina/Buenos_Aires'){ echo "selected"; }?>><?php _e('Buenos Aires, Georgetown','webinara'); ?></option>
					<option value="America/Godthab" <?php if($value == 'America/Godthab'){ echo "selected"; }?>><?php _e('Greenland','webinara'); ?></option>
					<option value="America/Sao_Paulo" <?php if($value == 'America/Sao_Paulo'){ echo "selected"; }?>><?php _e('Sao Paulo','webinara'); ?></option>
					<option value="Atlantic/Azores" <?php if($value == 'Atlantic/Azores'){ echo "selected"; }?>><?php _e('Azores','webinara'); ?></option>
					<option value="Canada/Atlantic" <?php if($value == 'Canada/Atlantic'){ echo "selected"; }?>><?php _e('Atlantic Time (Canada)','webinara'); ?></option>
					<option value="Atlantic/Cape_Verde" <?php if($value == 'Atlantic/Cape_Verde'){ echo "selected"; }?>><?php _e('Cape Verde Islands','webinara'); ?></option>
					<option value="UTC" <?php if($value == 'UTC'){ echo "selected"; }?>><?php _e('Universal Time UTC','webinara'); ?></option>
					<option value="Etc/Greenwich" <?php if($value == 'Etc/Greenwich'){ echo "selected"; }?>><?php _e('Greenwich Mean Time','webinara'); ?></option>
					<option value="Europe/Belgrade" <?php if($value == 'Europe/Belgrade'){ echo "selected"; }?>><?php _e('Belgrade, Bratislava, Ljubljana','webinara'); ?></option>
					<option value="CET" <?php if($value == 'CET'){ echo "selected"; }?>><?php _e('Sarajevo, Skopje, Zagreb','webinara'); ?></option>
					<option value="Atlantic/Reykjavik" <?php if($value == 'Atlantic/Reykjavik'){ echo "selected"; }?>><?php _e('Reykjavik','webinara'); ?></option>
					<option value="Europe/Dublin" <?php if($value == 'Europe/Dublin'){ echo "selected"; }?>><?php _e('Dublin','webinara'); ?></option>
					<option value="Europe/London" <?php if($value == 'Europe/London'){ echo "selected"; }?>><?php _e('London','webinara'); ?></option>
					<option value="Europe/Lisbon" <?php if($value == 'Europe/Lisbon'){ echo "selected"; }?>><?php _e('Lisbon','webinara'); ?></option>
					<option value="Africa/Casablanca" <?php if($value == 'Africa/Casablanca'){ echo "selected"; }?>><?php _e('Casablanca','webinara'); ?></option>
					<option value="Africa/Nouakchott" <?php if($value == 'Africa/Nouakchott'){ echo "selected"; }?>><?php _e('Nouakchott','webinara'); ?></option>
					<option value="Europe/Oslo" <?php if($value == 'Europe/Oslo'){ echo "selected"; }?>><?php _e('Oslo','webinara'); ?></option>
					<option value="Europe/Copenhagen" <?php if($value == 'Europe/Copenhagen'){ echo "selected"; }?>><?php _e('Copenhagen','webinara'); ?></option>
					<option value="Europe/Brussels" <?php if($value == 'Europe/Brussels'){ echo "selected"; }?>><?php _e('Brussels','webinara'); ?></option>
					<option value="Europe/Berlin" <?php if($value == 'Europe/Berlin'){ echo "selected"; }?>><?php _e('Amsterdam, Berlin, Rome, Stockholm, Vienna','webinara'); ?></option>
					<option value="Europe/Helsinki" <?php if($value == 'Europe/Helsinki'){ echo "selected"; }?>><?php _e('Helsinki','webinara'); ?></option>
					<option value="Europe/Amsterdam" <?php if($value == 'Europe/Amsterdam'){ echo "selected"; }?>><?php _e('Amsterdam','webinara'); ?></option>
					<option value="Europe/Rome" <?php if($value == 'Europe/Rome'){ echo "selected"; }?>><?php _e('Rome','webinara'); ?></option>
					<option value="Europe/Stockholm" <?php if($value == 'Europe/Stockholm'){ echo "selected"; }?>><?php _e('Stockholm','webinara'); ?></option>
					<option value="Europe/Vienna" <?php if($value == 'Europe/Vienna'){ echo "selected"; }?>><?php _e('Vienna','webinara'); ?></option>
					<option value="Europe/Luxembourg" <?php if($value == 'Europe/Luxembourg'){ echo "selected"; }?>><?php _e('Luxembourg','webinara'); ?></option>
					<option value="Europe/Paris" <?php if($value == 'Europe/Paris'){ echo "selected"; }?>><?php _e('Paris','webinara'); ?></option>
					<option value="Europe/Zurich" <?php if($value == 'Europe/Zurich'){ echo "selected"; }?>><?php _e('Zurich','webinara'); ?></option>
					<option value="Europe/Madrid" <?php if($value == 'Europe/Madrid'){ echo "selected"; }?>><?php _e('Madrid','webinara'); ?></option>
					<option value="Africa/Bangui" <?php if($value == 'Africa/Bangui'){ echo "selected"; }?>><?php _e('West Central Africa','webinara'); ?></option>
					<option value="Africa/Algiers" <?php if($value == 'Africa/Algiers'){ echo "selected"; }?>><?php _e('Algiers','webinara'); ?></option>
					<option value="Africa/Tunis" <?php if($value == 'Africa/Tunis'){ echo "selected"; }?>><?php _e('Tunis','webinara'); ?></option>
					<option value="Africa/Harare" <?php if($value == 'Africa/Harare'){ echo "selected"; }?>><?php _e('Harare, Pretoria','webinara'); ?></option>
					<option value="Africa/Nairobi" <?php if($value == 'Africa/Nairobi'){ echo "selected"; }?>><?php _e('Nairobi','webinara'); ?></option>
					<option value="Europe/Warsaw" <?php if($value == 'Europe/Warsaw'){ echo "selected"; }?>><?php _e('Warsaw','webinara'); ?></option>
					<option value="Europe/Prague" <?php if($value == 'Europe/Prague'){ echo "selected"; }?>><?php _e('Prague Bratislava','webinara'); ?></option>
					<option value="Europe/Budapest" <?php if($value == 'Europe/Budapest'){ echo "selected"; }?>><?php _e('Budapest','webinara'); ?></option>
					<option value="Europe/Sofia" <?php if($value == 'Europe/Sofia'){ echo "selected"; }?>><?php _e('Sofia','webinara'); ?></option>
					<option value="Europe/Istanbul" <?php if($value == 'Europe/Istanbul'){ echo "selected"; }?>><?php _e('Istanbul','webinara'); ?></option>
					<option value="Europe/Athens" <?php if($value == 'Pacific/Samoa'){ echo "selected"; }?>><?php _e('Athens','webinara'); ?></option>
					<option value="Europe/Bucharest" <?php if($value == 'Europe/Bucharest'){ echo "selected"; }?>><?php _e('Bucharest','webinara'); ?></option>
					<option value="Asia/Nicosia" <?php if($value == 'Asia/Nicosia'){ echo "selected"; }?>><?php _e('Nicosia','webinara'); ?></option>
					<option value="Asia/Beirut" <?php if($value == 'Asia/Beirut'){ echo "selected"; }?>><?php _e('Beirut','webinara'); ?></option>
					<option value="Asia/Damascus" <?php if($value == 'Asia/Damascus'){ echo "selected"; }?>><?php _e('Damascus','webinara'); ?></option>
					<option value="Asia/Jerusalem" <?php if($value == 'Asia/Jerusalem'){ echo "selected"; }?>><?php _e('Jerusalem','webinara'); ?></option>
					<option value="Asia/Amman" <?php if($value == 'Asia/Amman'){ echo "selected"; }?>><?php _e('Amman','webinara'); ?></option>
					<option value="Africa/Tripoli" <?php if($value == 'Africa/Tripoli'){ echo "selected"; }?>><?php _e('Tripoli','webinara'); ?></option>
					<option value="Africa/Cairo" <?php if($value == 'Africa/Cairo'){ echo "selected"; }?>><?php _e('Cairo','webinara'); ?></option>
					<option value="Africa/Johannesburg" <?php if($value == 'Africa/Johannesburg'){ echo "selected"; }?>><?php _e('Johannesburg','webinara'); ?></option>
					<option value="Europe/Moscow" <?php if($value == 'Europe/Moscow'){ echo "selected"; }?>><?php _e('Moscow','webinara'); ?></option>
					<option value="Asia/Baghdad" <?php if($value == 'Asia/Baghdad'){ echo "selected"; }?>><?php _e('Baghdad','webinara'); ?></option>
					<option value="Asia/Kuwait" <?php if($value == 'Asia/Kuwait'){ echo "selected"; }?>><?php _e('Kuwait','webinara'); ?></option>
					<option value="Asia/Riyadh" <?php if($value == 'Asia/Riyadh'){ echo "selected"; }?>><?php _e('Riyadh','webinara'); ?></option>
					<option value="Asia/Bahrain" <?php if($value == 'Asia/Bahrain'){ echo "selected"; }?>><?php _e('Bahrain','webinara'); ?></option>
					<option value="Asia/Qatar" <?php if($value == 'Pacific/Samoa'){ echo "selected"; }?>><?php _e('Qatar','webinara'); ?></option>
					<option value="Asia/Aden" <?php if($value == 'Asia/Aden'){ echo "selected"; }?>><?php _e('Aden','webinara'); ?></option>
					<option value="Asia/Tehran" <?php if($value == 'Asia/Tehran'){ echo "selected"; }?>><?php _e('Tehran','webinara'); ?></option>
					<option value="Africa/Khartoum" <?php if($value == 'Africa/Khartoum'){ echo "selected"; }?>><?php _e('Khartoum','webinara'); ?></option>
					<option value="Africa/Djibouti" <?php if($value == 'Africa/Djibouti'){ echo "selected"; }?>><?php _e('Djibouti','webinara'); ?></option>
					<option value="Africa/Mogadishu" <?php if($value == 'Africa/Mogadishu'){ echo "selected"; }?>><?php _e('Mogadishu','webinara'); ?></option>
					<option value="Asia/Dubai" <?php if($value == 'Asia/Dubai'){ echo "selected"; }?>><?php _e('Dubai','webinara'); ?></option>
					<option value="Asia/Muscat" <?php if($value == 'Asia/Muscat'){ echo "selected"; }?>><?php _e('Muscat','webinara'); ?></option>
					<option value="Asia/Baku" <?php if($value == 'Asia/Baku'){ echo "selected"; }?>><?php _e('Baku, Tbilisi, Yerevan','webinara'); ?></option>
					<option value="Asia/Kabul" <?php if($value == 'Asia/Kabul'){ echo "selected"; }?>><?php _e('Kabul','webinara'); ?></option>
					<option value="Asia/Yekaterinburg" <?php if($value == 'Asia/Yekaterinburg'){ echo "selected"; }?>><?php _e('Yekaterinburg','webinara'); ?></option>
					<option value="Asia/Tashkent" <?php if($value == 'Asia/Tashkent'){ echo "selected"; }?>><?php _e('Islamabad, Karachi, Tashkent','webinara'); ?></option>
					<option value="Asia/Calcutta" <?php if($value == 'Asia/Calcutta'){ echo "selected"; }?>><?php _e('India','webinara'); ?></option>
					<option value="Asia/Kathmandu" <?php if($value == 'Asia/Kathmandu'){ echo "selected"; }?>><?php _e('Kathmandu','webinara'); ?></option>
					<option value="Asia/Novosibirsk" <?php if($value == 'Asia/Novosibirsk'){ echo "selected"; }?>><?php _e('Novosibirsk','webinara'); ?></option>
					<option value="Asia/Almaty" <?php if($value == 'Asia/Almaty'){ echo "selected"; }?>><?php _e('Almaty','webinara'); ?></option>
					<option value="Asia/Dacca" <?php if($value == 'Asia/Dacca'){ echo "selected"; }?>><?php _e('Dacca','webinara'); ?></option>
					<option value="Asia/Krasnoyarsk" <?php if($value == 'Asia/Krasnoyarsk'){ echo "selected"; }?>><?php _e('Krasnoyarsk','webinara'); ?></option>
					<option value="Asia/Dhaka" <?php if($value == 'Asia/Dhaka'){ echo "selected"; }?>><?php _e('Astana, Dhaka','webinara'); ?></option>
					<option value="Asia/Bangkok" <?php if($value == 'Asia/Bangkok'){ echo "selected"; }?>><?php _e('Bangkok','webinara'); ?></option>
					<option value="Asia/Saigon" <?php if($value == 'Asia/Saigon'){ echo "selected"; }?>><?php _e('Vietnam','webinara'); ?></option>
					<option value="Asia/Jakarta" <?php if($value == 'Asia/Jakarta'){ echo "selected"; }?>><?php _e('Jakarta','webinara'); ?></option>
					<option value="Asia/Irkutsk" <?php if($value == '"Asia/Irkutsk'){ echo "selected"; }?>><?php _e('Irkutsk, Ulaanbaatar','webinara'); ?></option>
					<option value="Asia/Shanghai" <?php if($value == 'Asia/Shanghai'){ echo "selected"; }?>><?php _e('Beijing, Shanghai','webinara'); ?></option>
					<option value="Asia/Hong_Kong" <?php if($value == 'Asia/Hong_Kong'){ echo "selected"; }?>><?php _e('Hong Kong','webinara'); ?></option>
					<option value="Asia/Taipei" <?php if($value == 'Asia/Taipei'){ echo "selected"; }?>><?php _e('Taipei','webinara'); ?></option>
					<option value="Asia/Kuala_Lumpur" <?php if($value == 'Asia/Kuala_Lumpur'){ echo "selected"; }?>><?php _e('Kuala Lumpur','webinara'); ?></option>
					<option value="Asia/Singapore" <?php if($value == 'Asia/Singapore'){ echo "selected"; }?>><?php _e('Singapore','webinara'); ?></option>
					<option value="Australia/Perth" <?php if($value == 'Australia/Perth'){ echo "selected"; }?>><?php _e('Perth','webinara'); ?></option>
					<option value="Asia/Yakutsk" <?php if($value == 'Asia/Yakutsk'){ echo "selected"; }?>><?php _e('Yakutsk','webinara'); ?></option>
					<option value="Asia/Seoul" <?php if($value == 'Asia/Seoul'){ echo "selected"; }?>><?php _e('Seoul','webinara'); ?></option>
					<option value="Asia/Tokyo" <?php if($value == 'Asia/Tokyo'){ echo "selected"; }?>><?php _e('Osaka, Sapporo, Tokyo','webinara'); ?></option>
					<option value="Australia/Darwin" <?php if($value == 'Australia/Darwin'){ echo "selected"; }?>><?php _e('Darwin','webinara'); ?></option>
					<option value="Australia/Adelaide" <?php if($value == 'Australia/Adelaide'){ echo "selected"; }?>><?php _e('Adelaide','webinara'); ?></option>
					<option value="Asia/Vladivostok" <?php if($value == 'Asia/Vladivostok'){ echo "selected"; }?>><?php _e('Vladivostok','webinara'); ?></option>
					<option value="Pacific/Port_Moresby" <?php if($value == 'Pacific/Port_Moresby'){ echo "selected"; }?>><?php _e('Guam, Port Moresby','webinara'); ?></option>
					<option value="Australia/Brisbane" <?php if($value == 'Australia/Brisbane'){ echo "selected"; }?>><?php _e('Brisbane','webinara'); ?></option>
					<option value="Australia/Sydney" <?php if($value == 'Australia/Sydney'){ echo "selected"; }?>><?php _e('Canberra, Melbourne, Sydney','webinara'); ?></option>
					<option value="Australia/Hobart" <?php if($value == 'Australia/Hobart'){ echo "selected"; }?>><?php _e('Hobart','webinara'); ?></option>
					<option value="Asia/Magadan" <?php if($value == 'Asia/Magadan'){ echo "selected"; }?>><?php _e('Magadan','webinara'); ?></option>
					<option value="SST" <?php if($value == 'SST'){ echo "selected"; }?>><?php _e('Solomon Islands','webinara'); ?></option>
					<option value="Pacific/Noumea" <?php if($value == 'Pacific/Noumea'){ echo "selected"; }?>><?php _e('New Caledonia','webinara'); ?></option>
					<option value="Asia/Kamchatka" <?php if($value == 'Asia/Kamchatka'){ echo "selected"; }?>><?php _e('Kamchatka','webinara'); ?></option>
					<option value="Pacific/Fiji" <?php if($value == 'Pacific/Fiji'){ echo "selected"; }?>><?php _e('Fiji Islands, Marshall Islands','webinara'); ?></option>
					<option value="Pacific/Auckland" <?php if($value == 'Pacific/Auckland'){ echo "selected"; }?>><?php _e('Auckland, Wellington','webinara'); ?></option>
					<option value="Asia/Kolkata" <?php if($value == 'Asia/Kolkata'){ echo "selected"; }?>><?php _e('Mumbai, Kolkata, New Delhi','webinara'); ?></option>
					<option value="Europe/Kiev" <?php if($value == 'Europe/Kiev'){ echo "selected"; }?>><?php _e('Kiev','webinara'); ?></option>
					<option value="America/Tegucigalpa" <?php if($value == 'America/Tegucigalpa'){ echo "selected"; }?>><?php _e('Tegucigalpa','webinara'); ?></option>
					<option value="Pacific/Apia" <?php if($value == 'Pacific/Apia'){ echo "selected"; }?>><?php _e('Independent State of Samoa','webinara'); ?></option>
		 			</select>
				</p>
		<?php
		}				
		
		public function webi_show_label($field)
		{
			?>
			<p class="form-field <?php echo esc_attr( isset( $field['class'] ) ? $field['class'] : '' ); ?>">
				<label><?php esc_html_e($field['message'], 'webinara'); ?></label>
			</p>
			<?php
		}
		
		
		
		/**
		 * input_number function.
		 *
		 * @param mixed $key
		 * @param mixed $field
		 */
		public function webi_input_number( $key, $field ) {
			global $post;
			if ( ! isset( $field['value'] ) ) {
				$field['value'] = get_post_meta( $post->ID, $key, true );
			}
			if ( ! empty( $field['name'] ) ) {
				$name = $field['name'];
			} else {
				$name = $key;
			}
			?>
				<p class="form-field">
					<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?> <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
					<input type="number" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" />
				</p>
				<?php
			}
			/**
			 * input_button function.
			 *
			 * @param mixed $key
			 * @param mixed $field
			 */
			public function webi_input_button( $key, $field ) {
				global $post;
				if ( ! isset( $field['value'] ) ) {
					$field['value'] = $field['placeholder'];
				}
			
				if ( ! empty( $field['name'] ) ) {
					$name = $field['name'];
				} else {
					$name = $key;
				}
				?>
						<p class="form-field">
							<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?> <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
							<input type="button" class="button button-small" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" />
						</p>
						<?php
		}				

	/**
	 * input_radio function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public function webi_input_radio( $key, $field ) {
		global $post;
		if ( empty( $field['value'] ) ) {
			$field['value'] = get_post_meta( $post->ID, $key, true );
		}
		if(isset($field['default'])){
			if ( empty( $field['value'] ) ) {
				$field['value'] = $field['default'];
			}
		}	
		if ( ! empty( $field['name'] ) ) {
			$name = $field['name'];
		} else {
			$name = $key;
		}
		?>
		<p class="form-field form-field-checkbox">
			<label><?php esc_html_e($field['label'], 'webinara'); ?></label>
			<?php foreach ( $field['options'] as $option_key => $value ) : ?>
				<label><input type="radio" class="radio" name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>" value="<?php echo esc_attr( $option_key ); ?>" <?php checked( $field['value'], $option_key ); ?> /> <?php esc_html_e( $value, 'webinara' ); ?></label>
			<?php endforeach; ?>
			<?php if ( ! empty( $field['description'] ) ) : ?><span class="description"><?php echo $field['description']; ?></span><?php endif; ?>
		</p>
		<?php
	}

    /**
     * save_meta_box function.
     *
     * @since   1.0.0
     * 
     * @return  void
     */
    public function webi_save_meta_box($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }		
		
		$event_type = sanitize_text_field($_POST['post_type']);
		if(count($_POST) != 0 && ($event_type == 'webinar' || $event_type == 'event'))
		{			
			$event_data_arr = array();
			delete_post_meta($post_id,'_webi_featured');
			delete_post_meta($post_id,'_webi_all_day');
			foreach ($_POST as $key => $value) {
				if (strstr($key, '_webi_')) {
					if($key == '_webi_subtitle' || $key == '_webi_timezone' || $key == '_webi_speaker_first_name' || $key == '_webi_speaker_last_name' || $key == '_webi_speaker_company' || $key == '_webi_speaker_title' || $key == '_webi_speaker_twitter' || $key == '_webi_gotowebinar_id')
					{
						if(is_array($value))
						{
							$update_value = array();
							foreach($value as $data)
							{
								$update_value[] = sanitize_text_field($data);
							}
							update_post_meta($post_id, $key, $update_value);		
							$event_data_arr[$key] = $update_value;
						}
						else
						{
							update_post_meta($post_id, $key, sanitize_text_field($value));
							$event_data_arr[$key] = sanitize_text_field($value);
						}						
					}
					else if($key == '_webi_why_attened' || $key == '_webi_who_attened' || $key == '_webi_speaker_bio')
					{
						if(is_array($value))
						{
							$update_value = array();
							foreach($value as $data)
							{
								$update_value[] = sanitize_textarea_field($data);
							}
							update_post_meta($post_id, $key, $update_value);
							$event_data_arr[$key] = $update_value;							
						}
						else
						{
							update_post_meta($post_id, $key, sanitize_textarea_field($value));
							$event_data_arr[$key] = sanitize_textarea_field($value);
						}							
					}
					else if($key == '_webi_platform')
					{
						update_post_meta($post_id, $key, (int)trim($value));
						$event_data_arr[$key] = (int)trim($value);
					}
					else if($key == '_webi_start_date' || $key == '_webi_end_date')
					{
						$date = preg_replace("([^0-9/])", "", trim($value));
						update_post_meta($post_id, $key, $date);
						$event_data_arr[$key] = $date;
					}
					else if($key == '_webi_registration_url' || $key == '_webi_speaker_image' || $key == '_webi_attachment' || $key == '_webi_promotional_video' || $key == '_webi_sponser' || $key == '_webi_speaker_facebook' || $key == '_webi_speaker_linkedin' || $key == '_webi_speaker_website')
					{			
						if(is_array($value))
						{
							$update_value = array();
							foreach($value as $data)
							{
								$update_value[] = esc_url($data);
							}
							update_post_meta($post_id, $key, $update_value);
							$event_data_arr[$key] = $update_value;								
						}
						else
						{
							update_post_meta($post_id, $key, esc_url($value));
							$event_data_arr[$key] = esc_url($value);
						}						
					}					
					else
					{
						update_post_meta($post_id, $key, sanitize_text_field($value));		
						$event_data_arr[$key] = sanitize_text_field($value);
					}					
				}
				
				
				if($key == '_thumbnail_id')
				{
					if($_POST[$key] != 0)
					{
						$event_data_arr[$key] = wp_get_attachment_url($_POST[$key],'large');					
					}
				}
				
				if($key == 'tax_input')
				{
					$event_cats = $_POST[$key]['event_categories'];
					if(count($event_cats) != 0)
					{
						$term_details_arr = array();
						foreach($event_cats as $event_cat)
						{
							if($event_cat != 0)
							{
								$term_details = get_term($event_cat);
								$term_details_arr[] = $term_details->name;
							}
						}
						if(count($term_details_arr) != 0)
						{
							$event_data_arr['_webi_event_cat'] = implode(",",$term_details_arr);
						}
					}
				}
				if($key == 'post_status' || $key =='post_ID' || $key == 'post_title' || $key == '_webi_event_type' || $key == 'post_content' || $key == '_wp_original_http_referer')
				{
					$event_data_arr[$key] = $value;
				}					
			}						
			
			if(isset($_POST['_webi_event_type']) == 'webinar' && ($_POST['_webi_platform'] == 1 || $_POST['_webi_platform'] == 2 || $_POST['_webi_platform'] == 4 || $_POST['_webi_platform'] == 7))
			{
				if ($_POST['_webi_platform'] == 1 && !empty($_POST['_webi_gotowebinar_id'])) {					
					$goto_organizer_key = get_option('_webi_goto_organizer_key');
					$goto_access_token = get_option('_webi_goto_account_key');
					update_post_meta($post_id, '_webi_gotowebinar_id', sanitize_text_field($_POST['_webi_gotowebinar_id']));
					update_post_meta($post_id, '_webi_goto_organizer_key', $goto_organizer_key);
					update_post_meta($post_id, '_webi_goto_access_token', $goto_access_token);
					$event_data_arr['_webi_gotowebinar_id'] = sanitize_text_field($_POST['_webi_gotowebinar_id']);
					$event_data_arr['_webi_goto_organizer_key'] = get_option('_webi_goto_organizer_key');					
					$event_data_arr['_webi_goto_access_token'] = get_option('_webi_goto_access_token');
					$event_data_arr['_webi_goto_refresh_token'] = get_option('_webi_goto_refresh_token'); 
					$event_data_arr['_webi_goto_refresh_token_expire_on'] = get_option('_webi_goto_refresh_token_expire_on');
					$event_data_arr['_webi_goto_access_token_expire_on'] = get_option('_webi_goto_access_token_expire_on');
				}
				if(($_POST['original_post_status'] == 'auto-draft' && $_POST['post_status'] == 'publish'))
				{
					if ($_POST['_webi_platform'] == 2) {
						$username = get_option('_webi_onstream_username');
						$password = get_option('_webi_onstream_password');
						//date_default_timezone_set($_POST['_webi_timezone']);
						$start_time = sanitize_text_field($_POST['_webi_start_date']) . ' ' . sanitize_text_field($_POST['_webi_start_time']);
						$to_time = strtotime($start_time);
						$end_time = sanitize_text_field($_POST['_webi_end_date']) . ' ' . sanitize_text_field($_POST['_webi_end_time']);
						$from_time = strtotime($end_time);										
						$duration = round(abs($from_time - $to_time) / 60, 2);
						$webinar_title = sanitize_title($_POST['post_title']);
						$timezone_onstream = sanitize_text_field($_POST['_webi_timezone']);
						$event_data = array(
							'topic' => $webinar_title,
							'duration' => $duration,
							'start_time' => date('Y-m-d H:i:s', $to_time),
							'private_access' => 1,
						);
						
						$current_user_arr = wp_get_current_user();															
						if(empty($current_user_arr->user_firstname) || empty($current_user_arr->user_lastname))
						{
							$display_name_arr = explode(" ",$current_user_arr->display_name);
							$fname = $display_name_arr[0];
							$lname = $display_name_arr[1];
							if(empty($lname))
							{
								$lname = $display_name_arr[0];
							}
						}
						else
						{
							$fname = $current_user_arr->user_firstname;
							$lname = $current_user_arr->user_lastname;
						}						
						$organizer_data = array(							
							'email' => $current_user_arr->user_email ,
							'first_name' => $fname ,
							'last_name' => $lname ,
							'send_email_invitation' => 0,
							'role' => 1
						);
						
						$response = wp_remote_post( WEBINARA_API_URL, array(
							'headers' => array(),
							'body' => array('event_action' => 'create', 'event_platform' => 'onstream', 'auth_data' => base64_encode($username.':'.$password), 'event_data' => $event_data, 'organizer_data' => $organizer_data)
						));	

						if ( is_wp_error( $response ) ) {
							echo "Error";
						}
						else						
						{
							$response = @json_decode(wp_remote_retrieve_body($response), true);
							if(!empty($response['event_id']) && $response['event_id'] != 0)
							{
								update_post_meta($post_id, '_webi_onstreamwebinar_id', $response['event_id']);
								update_post_meta($post_id, '_webi_onstream_username', $username);
								update_post_meta($post_id, '_webi_onstream_password', $password);
								$event_data_arr['_webi_onstreamwebinar_id'] = $response['event_id'];
								$event_data_arr['_webi_onstream_username'] = $username;
								$event_data_arr['_webi_onstream_password'] = $password;
							}
						}
					}
					else if ($_POST['_webi_platform'] == 4) {					
						$readytalk_access_number = get_option('_webi_readytalk_access_number');
						$readytalk_access_code = get_option('_webi_readytalk_access_code');
						$readytalk_passcode = get_option('_webi_readytalk_passcode');
						
						//date_default_timezone_set($_POST['_webi_timezone']);
						$start_time = sanitize_text_field($_POST['_webi_start_date']) . ' ' . sanitize_text_field($_POST['_webi_start_time']);
						$to_time = strtotime($start_time);
						$end_time = sanitize_text_field($_POST['_webi_end_date']) . ' ' . sanitize_text_field($_POST['_webi_end_time']);
						$from_time = strtotime($end_time);										
						$duration = round(abs($from_time - $to_time) / 60, 2);
						$webinar_title = sanitize_title($_POST['post_title']);
						$timezone_readytalk = sanitize_text_field($_POST['_webi_timezone']);
						$duration_in_seconds = $duration * 60;					

						$current_user_arr = wp_get_current_user();

						$time_readytalk = new DateTime(date("Y-m-d", $to_time), new DateTimeZone($timezone_readytalk));
						$timezoneOffset_readytalk = $time_readytalk->format('P');
						$ai1ec_tz_name = $timezone;
						$ai1ec_tz_name_sec = (string)$ai1ec_tz_name;
						$url = 'https://cc.readytalk.com/api/1.3/svc/rs/meetings.json';
						$event_data = array(
							'title' => urlencode($webinar_title),
							'hostName' => urlencode($current_user_arr->display_name),
							'fromEmail' => urlencode($current_user_arr->user_email),
							'startDateIso8601' => urlencode(date("Y-m-d", $to_time) . 'T' . date("H:i:s", $to_time) . '.000' . $timezoneOffset_readytalk),
							'durationInSeconds' => urlencode($duration_in_seconds),
							'timeZone' => urlencode($timezone_readytalk),
							'registration' => urlencode('PRE_REG_AUTOMATIC_CONFIRMATION_WITH_NOTIFICATION'),
							'type' => urlencode('WEB_AND_AUDIO'),
							'audio.onDemand' => urlencode('DISPLAY_TOLLFREE_HIDE_TOLL')
						);
						
						$response = wp_remote_post( WEBINARA_API_URL, array(
							'headers' => array(),
							'body' => array('event_action' => 'create', 'event_platform' => 'readytalk', 'auth_data' => base64_encode($readytalk_access_number.':'.$readytalk_access_code.':'.$readytalk_passcode), 'event_data' => $event_data)
						));	

						if ( is_wp_error( $response ) ) {
							echo "Error";
						}
						else						
						{
							$response = @json_decode(wp_remote_retrieve_body($response), true);
							if(!empty($response['event_id']) && $response['event_id'] != 0)
							{
								update_post_meta($post_id, '_webi_readytalkwebinar_id', $response['event_id']);
								update_post_meta($post_id, '_webi_readytalk_accessnumber', $readytalk_access_number);
								update_post_meta($post_id, '_webi_readytalk_accesscode', $readytalk_access_code);
								update_post_meta($post_id, '_webi_readytalk_passcode', $readytalk_passcode);
								$event_data_arr['_webi_readytalkwebinar_id'] = $response['event_id'];
								$event_data_arr['_webi_readytalk_accessnumber'] = $readytalk_access_number;
								$event_data_arr['_webi_readytalk_accesscode'] = $readytalk_access_code;
								$event_data_arr['_webi_readytalk_passcode'] = $readytalk_passcode;
							}
						}						
					}
					else if ($_POST['_webi_platform'] == 7) {					
						$webinaraPlugin = new Webinara();
						$zoom_access_token = $webinaraPlugin->get_zoom_access_token();
						$webinar_title = sanitize_title($_POST['post_title']);
						$start_time = sanitize_text_field($_POST['_webi_start_date']) . ' ' . sanitize_text_field($_POST['_webi_start_time']);
						$to_time = strtotime($start_time);
						$end_time = sanitize_text_field($_POST['_webi_end_date']) . ' ' . sanitize_text_field($_POST['_webi_end_time']);
						$from_time = strtotime($end_time);										
						$duration = round(abs($from_time - $to_time) / 60, 2);
						$webi_start_time = date("Y-m-d",$to_time).'T'.date("H:i:s",$to_time).'Z';	
						
						$fields = array(
							'topic' => $webinar_title,
							'type' => 5,
							'start_time' => $webi_start_time,
							'duration' => $duration,
							'timezone' => sanitize_text_field($_POST['_webi_timezone']),
							'settings' => array(
								'approval_type' => 0,
								'registration_type' => 1,
								'allow_multiple_devices' =>	true						
							)
						);
						
						$response = wp_remote_post( 'https://api.zoom.us/v2/users/me/webinars', array(
							'headers' => array(
								'Authorization: Bearer '.$zoom_access_token,
								'Content-type: application/json'
							),
							'body' => json_encode($fields),
						) );	

						$response = wp_remote_retrieve_body( $response );

						if ( ! $response ) {
							echo "Error";
						}
						else						
						{
							$response_arr = json_decode($response);
							
							update_post_meta($post_id, '_webi_zoom_id', $response_arr['id']);	
							$event_data_arr['_webi_zoom_id'] = $response_arr['id'];
							$event_data_arr['_webi_zoom_key'] = get_option('_webi_zoom_key');
							$event_data_arr['_webi_zoom_secret'] = get_option('_webi_zoom_secret');
							$event_data_arr['_webi_zoom_refresh_token'] = get_option('_webi_zoom_refresh_token');
							$event_data_arr['_webi_zoom_refresh_token_expire_on'] = get_option('_webi_zoom_refresh_token_expire_on');
							$event_data_arr['_webi_zoom_access_token_expire_on'] = get_option('_webi_zoom_access_token_expire_on');
						}										
					}
				}
			}
			
			$event_data_arr['_webi_license_user_id'] = get_option('_webi_license_user_id');
			if(get_option('_webi_publish_events') == 1)
			{
				if(isset($_POST['_webi_publish_event']) && $_POST['_webi_publish_event'] == 1)
				{
					if(($_POST['original_post_status'] == 'auto-draft' && $_POST['post_status'] == 'publish') || ($_POST['original_post_status'] == 'publish' && $_POST['post_status'] == 'publish'))
					{
						if($_POST['original_post_status'] == 'publish' && $_POST['post_status'] == 'publish')
						{
							if(get_post_meta($post_id, '_webi_sync', true) == 1)
							{
								$event_data_arr['webi_exist'] = get_post_meta($post_id, '_webi_sync_id', true);
							}
						}						
							
						$event_data_arr['evt_action'] = 'submit';
						$evt_data_request = wp_remote_post(WEBINARA_API_URL, array(							
							'headers'     => array(),
							'body'        => $event_data_arr,
							'cookies'     => array()
							)
						);
						
						if ( is_wp_error( $evt_data_request ) ) {							
							update_post_meta($post_id, '_webi_sync', 2);
							update_post_meta($post_id, '_webi_sync_err', $evt_data_request->get_error_message());						
							// Otherwise, display the data and save the post meta data
						} else {
							$evt_data_response = @json_decode(wp_remote_retrieve_body($evt_data_request), true);
							if($evt_data_response['valid'] != 0)
							{
								if($evt_data_response['ei'] != 0)
								{
									update_post_meta($post_id, '_webi_sync', 1);
									update_post_meta($post_id, '_webi_sync_id', $evt_data_response['ei']);
								}
							}								
						}					
					}
				}
				else
				{
					$event_data_arr = array();		
					if(get_post_meta($post_id,'_webi_sync',true)== 1 && !empty(get_post_meta($post_id,'_webi_sync_id',true)))
					{
						$event_data_arr['evt_action'] = 'remove';
						$event_data_arr['_webi_sync_id'] = get_post_meta($post_id,'_webi_sync_id',true);
						$evt_data_request = wp_remote_post(WEBINARA_API_URL, array(							
							'headers'     => array(),
							'body'        => $event_data_arr,
							'cookies'     => array()
							)
						);
						
						if ( is_wp_error( $evt_data_request ) ) {														
							update_post_meta($post_id, '_webi_remove_err', $evt_data_request->get_error_message());						
							// Otherwise, display the data and save the post meta data
						} else {
							$evt_data_response = @json_decode(wp_remote_retrieve_body($evt_data_request), true);
							if($evt_data_response['remove_webinar'] == 1)
							{								
								delete_post_meta($post_id, '_webi_sync');
								delete_post_meta($post_id, '_webi_sync_id');							
							}								
						}	
					}
				}
			}			
		}
    }	 
}
new Webinara_Meta_Box();