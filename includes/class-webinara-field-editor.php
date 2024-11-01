<?php

/**
 * Webinara_Registrations_Form_Editor class.
 */
class WP_Webinara_Form_Field {
	
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'webi_admin_menu' ) );
	}
	
	/**
	 * Add form editor menu item
	 */
	public function webi_admin_menu() {
		if(get_option('_webi_enable_webinars') != 1){
			add_submenu_page( 'edit.php?post_type=event', __('Configure Fields', 'webinara'),  __('Configure Fields', 'webinara') , 'manage_options', 'webinara-form-field', array( $this, 'webi_field_output' ) );
		}
		else
		{
			add_submenu_page( 'edit.php?post_type=webinar', __('Configure Fields', 'webinara'),  __('Configure Fields', 'webinara') , 'manage_options', 'webinara-form-field', array( $this, 'webi_field_output' ) );
		}
	}	
	
	/**
	 * Output the screen
	 */
	public function webi_field_output() {		
	?>
		<div class="wrap">  
			<div id="icon-themes" class="icon32"></div>  
			<h2><?php esc_html_e('Configure Fields','webinara') ?></h2>  			

			<?php
				if(get_option('_webi_enable_webinars') != 1){
					$ptype = 'event';
				}
				else
				{
					$ptype = 'webinar';
				}
				$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'webinar';  
			?>  

			<!-- <h2 class="nav-tab-wrapper">  
				<a href="edit.php?post_type=webinar&page=webinara-form-field&tab=<?php echo $ptype; ?>" class="nav-tab <?php echo $active_tab == 'webinar' ? 'nav-tab-active' : ''; ?>">Configure Webinar Fields</a>  
				<a href="edit.php?post_type=webinar&page=webinara-form-field&tab=<?php echo $ptype; ?>" class="nav-tab <?php echo $active_tab == 'event' ? 'nav-tab-active' : ''; ?>">Configure Event Fields</a>  
			</h2> -->
			<h2 class="nav-tab-wrapper">
			<?php 			
			$active_sec = 'webinar';
			if(get_option('_webi_enable_webinars') == 1 && get_option('_webi_enable_events') == 1){
				?>
				<a href="edit.php?post_type=webinar&page=webinara-form-field&tab=webinar" class="nav-tab <?php echo $active_tab == 'webinar' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Configure Webinar Fields','webinara'); ?></a>  
				<a href="edit.php?post_type=webinar&page=webinara-form-field&tab=event" class="nav-tab <?php echo $active_tab == 'event' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Configure Event Fields','webinara'); ?></a>  
				<?php
			}
			else
			{	if(get_option('_webi_enable_webinars') == 1){
					$active_tab = 'webinar';
					?>
					<a href="edit.php?post_type=webinar&page=webinara-form-field&tab=webinar" class="nav-tab nav-tab-active"><?php esc_html_e('Configure Webinar Fields','webinara'); ?></a>  
					<?php
				}
				if(get_option('_webi_enable_events') == 1){
					$active_tab = 'event';
					$active_sec = 'event';
					?>
					<a href="edit.php?post_type=event&page=webinara-form-field&tab=event" class="nav-tab nav-tab-active"><?php esc_html_e('Configure Event Fields','webinara'); ?></a>  
					<?php
				}
				
			}
			?>
			</h2>
			<div class="wrap webinara-form-field-editor">
				<form method="post" id="mainform" action="edit.php?post_type=<?php echo $active_sec ?>&page=webinara-form-field&tab=<?php echo $active_tab; ?>">			
					<?php $this->webi_form_editor(); ?>
					<?php wp_nonce_field( 'save-wp-webinar-form-field' ); ?>
				</form>
			</div>
		</div>	
		<?php
	}
	
	private function webi_form_editor() {
		if ( ! empty( $_POST ) && ! empty( $_POST['_wpnonce'] ) ) {
			echo $this->webi_form_editor_save();
		}
		if(get_option('_webi_enable_webinars') == 1 && get_option('_webi_enable_events') == 1)
		{
			$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'webinar'; 
		}
		else
		{
			if(get_option('_webi_enable_webinars') == 1)
			{
				$active_tab = 'webinar';
			}
			else
			{
				$active_tab = 'event';
			}
		}
		$fields = get_option('_webi_'.$active_tab.'form_fields',true);			
		foreach($fields  as $group_key => $group_fields){ ?>
			<div class="webinara-form-field-editor">
				<?php 
				if($group_key == 'general'){ ?>
					<h3><?php 
					if($active_tab == 'event'){
						esc_html_e('General fields for events','webinara');						
					}
					else
					{
						esc_html_e('General fields for webinars','webinara');						
					}
					?></h3>
					<table class="webi_formtable">
						<thead>	
							<tr>
								<th><?php esc_html_e('Field Label','webinara') ?></th>
								<!-- <th>Type</th> -->
								<th class="field-actions"><?php esc_html_e('Enable','webinara') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(isset($group_fields))
							{
								foreach ( $group_fields as $field_key => $field ) {
									?>
									<tr>
										<td>
											<input type="text" class="input-text" name="<?php echo $group_key.'['.$field_key.'][label]'; ?>" value="<?php echo esc_attr( $field['label'] ); ?>" />
											<input type="hidden" name="<?php echo $group_key;?>[<?php echo $field_key;?>][type]" value="<?php echo $field['type']; ?>">
										</td>
										
										<td class="field-actions">										
											<!-- <a class="webi_remove_current_field" href='javascript:void(0);'>Remove</a>-->
											<input type="checkbox" name="<?php echo $group_key;?>[<?php echo $field_key;?>][enable]" value="1" <?php if( isset( $field['enable'] ) && $field['enable'] == 1 ){ echo "checked"; } ?>>
										</td>
									</tr>
									<?php
								}
							}
							?>																
						</tbody>
						<tfoot>
							<tr>
								<th colspan="4">
									<!-- <a class="button webi_add_new_field" href="javascript:void(0);">Add new</a> -->
									<input type="submit" class="save-fields button-primary" value="<?php esc_html_e('Save Changes','webinara') ?>" />
								</th>
							</tr>
						</tfoot>
					</table>
				<?php
				}
				if($group_key == 'speaker'){ ?>
					<h3><?php esc_html_e('Speaker fields','webinara'); ?></h3>
					<table class="webi_formtable" id="webi_table_speaker" data-field='<tr>
										<td>
											<input type="text" class="input-text" name="speaker[new_row][label]" value="">
										</td>
										<td class="field-type">
											<select name="speaker[new_row][type]" class="field_type">
												<option value="text" selected="selected">Text</option><option value="time">Time</option><option value="button">Button</option><option value="button-options">Button Options</option><option value="checkbox">Checkbox</option><option value="date">Date</option><option value="hidden">Hidden</option><option value="radio">Radio</option><option value="select">Select</option><option value="textarea">Textarea</option><option value="wp-editor">WP Editor</option>											</select>
										</td>
										<td>
											<input type="text" class="input-text" name="speaker[new_row][description]" value="" placeholder="N/A">
										</td>
										<td><input type="text" value="" readonly=""></td>									
										<td class="field-actions">										
											<a class="webi_remove_current_field" href="javascript:void(0);">Remove</a>											
										</td>
									</tr>'>
						<thead>	
							<tr>
								<th><?php esc_html_e('Field Label','webinara') ?></th>
								<th><?php esc_html_e('Placeholder/Field example text','webinara') ?></th>
								<th class="field-actions"><?php esc_html_e('Enable','webinara') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(isset($group_fields))
							{
								foreach ( $group_fields as $field_key => $field ) {
									?>
									<tr>
										<td>
											<input type="text" class="input-text" name="<?php echo $group_key.'['.$field_key.'][label]'; ?>" value="<?php echo esc_attr( $field['label'] ); ?>" />
											<input type="hidden" name="<?php echo $group_key;?>[<?php echo $field_key;?>][type]" value="<?php echo $field['type']; ?>">
										</td>
										
										<td>
											<input type="text" class="input-text" name="<?php echo $group_key;?>[<?php echo $field_key;?>][description]" value="<?php echo esc_attr( isset( $field['description'] ) ? $field['description'] : '' ); ?>" placeholder="<?php _e( 'N/A', 'webinara' ); ?>" />
										</td>
										<!-- <td><input type="text" value="_<?php echo $field_key; ?>" readonly=""></td> -->
										<!-- <td class="field-rules">										
											<div class="rules">
												<select name="<?php echo $group_key;?>[<?php echo $field_key;?>][required]">
													<?php $field['required'] =  ( isset( $field['required'] ) ? $field['required'] : false );?>
													<option value="0" <?php if($field['required'] == false) echo 'selected="selected"';?> ><?php  _e( 'Not Required' );?></option>
													<option value="1" <?php if($field['required'] == true) echo 'selected="selected"';?> ><?php  _e( 'Required' );?></option>
												</select>
											</div>																						
										</td> -->
										<td class="field-actions">										
											<!-- <a class="webi_remove_current_field" href='javascript:void(0);'>Remove</a>-->
											<input type="checkbox" name="<?php echo $group_key;?>[<?php echo $field_key;?>][enable]" value="1" <?php if( isset( $field['enable'] ) && $field['enable'] == 1 ){ echo "checked"; } ?>>
										</td>
									</tr>
									<?php
								}
							}
							?>																
						</tbody>
						<tfoot>
							<tr>
								<th colspan="4">
									<!-- <a class="button webi_add_new_field" href="javascript:void(0);">Add new</a> -->
									<input type="submit" class="save-fields button-primary" value="<?php esc_html_e( 'Save Changes', 'webinara' ); ?>" />
								</th>
							</tr>
						</tfoot>
					</table>
					<?php	
				}
				
				if($group_key == 'additional'){
				?>				
					<h3><?php esc_html_e('Speaker fields','webinara'); ?></h3>
					<table class="webi_formtable">
						<thead>	
							<tr>
								<th><?php esc_html_e('Field','webinara') ?></th>
								<th><?php esc_html_e('Enable','webinara') ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php esc_html_e('Attachments','webinara') ?></td>							
								<td><input type="checkbox" name="additional[attachments][enable]" value="1" <?php if( isset( $group_fields['attachments']['enable'] ) && $group_fields['attachments']['enable'] == 1 ){ echo "checked"; } ?>>
								<input type="hidden" name="additional[attachments][type]" value="repeater">
								</td>
							</tr>											
							<tr>
								<td><?php esc_html_e('YouTube or Vimeo embedded video','webinara') ?></td>														
								<td><input type="checkbox" name="additional[video][enable]" value="1" <?php if( isset( $group_fields['video']['enable'] ) && $group_fields['video']['enable'] == 1 ){ echo "checked"; } ?>>
								<input type="hidden" name="additional[video][type]" value="text"></td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="4">
									<!-- <a class="button webi_add_new_field" href="javascript:void(0);">Add new</a> -->
									<input type="submit" class="save-fields button-primary" value="<?php esc_html_e( 'Save Changes','webinara' ); ?>" />
								</th>
							</tr>
						</tfoot>
					</table>
					
					<h3><?php esc_html_e('Speaker fields','webinara'); ?></h3>
					<table class="webi_formtable">
						<thead>	
							<tr>
								<th><?php esc_html_e('Field','webinara') ?></th>
								<th><?php esc_html_e('Enable','webinara') ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><input type="text" class="input-text" name="additional[sponsor][label]" value="<?php echo esc_attr( $group_fields['sponsor']['label'] ); ?>" /></td>							
								<td><input type="checkbox" name="additional[sponsor][enable]" value="1" <?php if( isset( $group_fields['sponsor']['enable'] ) && $group_fields['sponsor']['enable'] == 1 ){ echo "checked"; } ?>>
								<input type="hidden" name="additional[sponsor][type]" value="sponsor">
								</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="4">
									<!-- <a class="button webi_add_new_field" href="javascript:void(0);">Add new</a> -->
									<input type="submit" class="save-fields button-primary" value="<?php esc_html_e( 'Save Changes','webinara' ); ?>" />
								</th>
							</tr>
						</tfoot>
					</table>
					<?php 
				}								
				?>				
			</div>
			<?php
		}		
	}
	
	function webi_form_editor_save(){
		if( wp_verify_nonce( $_POST['_wpnonce'], 'save-wp-webinar-form-field' ) )
		{
			$event_general	= ! empty( $_POST['general'] ) ?  $_POST['general'] 				: array();
			$event_speaker	= ! empty( $_POST['speaker'] ) ?  $_POST['speaker']   	: array();
			$event_additional	= ! empty( $_POST['additional'] ) ?  $_POST['additional']   	: array();
			
			if ( isset( $_GET['tab'] ) ) {
				$active_tab = trim( $_GET['tab'] );
				if ( ! empty( $active_tab ) ) {
					if ( ! in_array( $action, array( 'webinar', 'event' ) ) ) {
						$active_tab = 'webinar';
					}
				}
			} else {
				$active_tab = 'webinar';
			}								
			
			$index = 0;
			if(!empty($event_speaker)){
				$new_fields = array('general' => $event_general,'speaker' =>$event_speaker, 'additional' => $event_additional);
				
				//find the numers keys from the fields array and replace with lable if label not exist remove that field
				 foreach($new_fields as $group_key => $group_fields) {
					 foreach( $group_fields as $field_key => $field_value ) {
							$index++;
							$new_fields[$group_key][$field_key]['priority'] = $index;
							if ( isset($new_fields[$group_key][$field_key]['type']) && ! in_array($new_fields[$group_key][$field_key]['type'],  array('term-select', 'term-multiselect', 'term-checklist') ) ) {
								unset($new_fields[$group_key][$field_key]['taxonomy']);
							}
							if(isset($new_fields[$group_key][$field_key]['type']) && $new_fields[$group_key][$field_key]['type'] == 'select'  || $new_fields[$group_key][$field_key]['type'] == 'radio'  || $new_fields[$group_key][$field_key]['type'] == 'multiselect' || $new_fields[$group_key][$field_key]['type'] == 'button-options' ){
								if(isset($new_fields[$group_key][$field_key]['options'])){
									$new_fields[$group_key][$field_key]['options'] = explode( ' | ', $new_fields[$group_key][$field_key]['options']);
									$temp_options = array();
									foreach($new_fields[$group_key][$field_key]['options'] as $val){
										$temp_options[strtolower(str_replace(' ', '_', $val))] = $val;
									}
									$new_fields[$group_key][$field_key]['options'] = $temp_options;
								}
							}
							else{
								unset($new_fields[$group_key][$field_key]['options']);
							}
							
							if(!is_int($field_key)) continue;
							 
							if(isset($new_fields[$group_key][$field_key]['label'])){
								$label_key =  str_replace(' ',"_",$new_fields[$group_key][$field_key]['label']);
								$new_fields[$group_key][strtolower($label_key)]= $new_fields[$group_key][$field_key];
							}
							unset($new_fields[$group_key][$field_key]);
					}
				}								
				
				$result = update_option( '_webi_'.$active_tab.'form_fields', $new_fields );
			
			}	  
		}
		 
		 if ( isset($result) && true === $result ) {
				echo '<div class="updated"><p>' . __( 'The fields were successfully saved.', 'webinara' ) . '</p></div>';
		}
	}

	/**
	 * Sanitize a 2d array
	 * @param  array $array
	 * @return array
	 */
	private function webi_sanitize_array( $input ) {
		if ( is_array( $input ) ) {
			foreach ( $input as $k => $v ) {
				$input[ $k ] = $this->sanitize_array( $v );
			}
			return $input;
		} else {
			return sanitize_text_field( $input );
		}
	}
	
}

new WP_Webinara_Form_Field();