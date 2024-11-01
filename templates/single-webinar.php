<?php
/**
 * The Template for displaying standard post type content
 *
 */ 

get_header();

while ( have_posts() ) : the_post();
	$webi_start_date = str_replace("/","/",get_post_meta(get_the_ID(), '_webi_start_date', true));
	$webi_start_time = get_post_meta(get_the_ID(), '_webi_start_time', true);
	$webi_start_time = str_replace("am"," am", $webi_start_time);
	$webi_start_time = str_replace("pm"," pm", $webi_start_time);
	$webi_end_date = str_replace("/","/",get_post_meta(get_the_ID(), '_webi_end_date', true));
	$webi_end_time = get_post_meta(get_the_ID(), '_webi_end_time', true);
	$webi_end_time = str_replace("am"," am", $webi_end_time);
	$webi_end_time = str_replace("pm"," pm", $webi_end_time);	
	$webi_timezone = get_post_meta(get_the_ID(),'_webi_timezone',true);
	$webi_subtitle = get_post_meta(get_the_ID(),'_webi_subtitle',true);	
	
	if(strtotime($webi_start_date) == strtotime($webi_end_date)){
		if(get_post_meta(get_the_ID(),'_webi_all_day',true) == 1){
			$evt_time = date('M j, Y',strtotime($webi_start_date)).' (All day)';
		}
		else
		{
			$evt_time = date('M j, Y',strtotime($webi_start_date)).' '.$webi_start_time.' - '.$webi_end_time;
		}
	}
	else
	{
		if(get_post_meta(get_the_ID(),'_webi_all_day',true) == 1){
			$evt_time = date('M j, Y',strtotime($webi_start_date)).' - '.date('M j, Y',strtotime($webi_end_date)).' (All day)';
		}
		else
		{
			$evt_time = date('M j, Y',strtotime($webi_start_date)).' '.$webi_start_time.' - '.date('M j, Y',strtotime($webi_end_date)).' '.$webi_end_time;
		}
	}
?>
<div class="webi_sep_page">
	<div class="webi_sep_details">
		<div class="event-header">
			<div class="event-info-header" <?php if(!empty($webi_banner_theme)){ echo 'style="background-color:'.$webi_banner_theme.'"'; }?>>
				<div class="event-info-header-container">
					<?php 
					$event_cats = get_the_terms(get_the_ID(),'event_categories');
					$event_tags = get_the_terms(get_the_ID(),'event_tag');
					if(count($event_cats) != 0 && !empty($event_cats)){
						$i = 1;
						echo '<div class="event-categories">';
						foreach($event_cats as $event_cat)
						{
							if(count($event_cats) > $i)
							{
								echo '<a href="javascript:void(0);">'.$event_cat->name.'</a>, ';
							}
							else
							{
								echo '<a href="javascript:void(0);">'.$event_cat->name.'</a>';
							}
							$i++;
						}
						echo '</div>';
					}					
					?>																 
					<h1 class="event-title"><?php echo get_the_title(); ?></h1>
					<?php 
					if(!empty($webi_subtitle))
					{
						echo '<p class="event-subtitle">'.$webi_subtitle.'</p>';
					}
					?>					
					<div class="event-byline"> 
						<div class="event-date"><?php echo $evt_time.' ('.$webi_timezone.')'; ?></div> 
					</div> 					
				</div>
			</div>
			<div class="event-photo-header">
				<div class="pusher"></div>
				<a href="#">
					<?php the_post_thumbnail('full'); ?>
				</a>
			</div>
			<div class="clr"></div>
		</div>		
		<div class="webi-entry-content">
			<div class="webi_content">
				<div class="webi_body">
					<div class="webi_body_left">
						<div class="webi_subsec mobile_register register_btn">
							<?php 
							if(!empty($webi_banner_theme)){ $style_btn = 'style="background:'.$webi_banner_theme.'"'; }
							$platform = get_post_meta(get_the_ID(),'_webi_platform',true);								
							if($platform == 3)
							{
								?>
								<a href="<?php echo get_post_meta(get_the_ID(),'_webi_registration_url',true); ?>" target="_blank" <?php if(!empty($webi_banner_theme)){ echo 'style="background:'.$webi_banner_theme.'"'; }?>><?php esc_html_e('Register','webinara'); ?></a>
								<?php
							}
							else if($platform == 7 || $platform == 4 || $platform == 2 || $platform == 1)
							{									
								echo '<a class="webi_reg_btn" href="#webi-register-form" rel="modal:open" '.$style_btn.'>'.__('Register','webinara').'</a>';
								if($platform == 7)
								{
									$reg_info = get_post_meta(get_the_ID(),'_webi_zoom_id',true);
								}
								else if($platform == 4)
								{
									$reg_info = get_post_meta(get_the_ID(),'_webi_readytalkwebinar_id',true);
								}
								else if($platform == 2)
								{
									$reg_info = get_post_meta(get_the_ID(),'_webi_onstreamwebinar_id',true);
								}
								else if($platform == 1)
								{
									$reg_info = get_post_meta(get_the_ID(),'_webi_gotowebinar_id',true);
								}
							}
							
							?>								
						</div>	
						<?php the_content(); 
						if(!empty(trim(get_post_meta(get_the_ID(),'_webi_why_attened', true))))
						{
							echo '<div class="webi_subsec">
								<h6>'.__('Why attened?','webinara').'</h6>
								<div class="content_desc">
								'.nl2br(esc_html(get_post_meta(get_the_ID(),'_webi_why_attened', true))).'
								</div>
							</div>';
						}
						if(!empty(trim(get_post_meta(get_the_ID(),'_webi_who_attened', true))))
						{
							echo '<div class="webi_subsec">
								<h6>'.__('Who should attend?','webinara').'</h6>
								<div class="content_desc">
								'.nl2br(esc_html(get_post_meta(get_the_ID(),'_webi_who_attened', true))).'
								</div>
							</div>';
						}
						
						$videourl = get_post_meta(get_the_ID(),'_webi_promotional_video',true);
						if (!empty($videourl))
						{
							?>

							<div class="webi_subsec webi_video">
								<?php
								if(preg_match('/(www\.)*vimeo\.com\/.*/',$videourl)){
									$video_arr = explode("vimeo.com",$videourl);
									?>
									<iframe src="https://player.vimeo.com/video<?php echo $video_arr[1] ?>" width="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
									<?php
								}
								else
								{
									preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $videourl, $matches);
									?>
									<iframe src="https://www.youtube.com/embed/<?php echo $matches[0] ?>" width="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
									<?php
								}
								?>
							</div>
							<?php
						}						
							$speakers_fname_arr = get_post_meta(get_the_ID(),'_webi_speaker_first_name', true);
							$speakers_lname_arr = get_post_meta(get_the_ID(),'_webi_speaker_last_name', true);
							$speakers_company_arr = get_post_meta(get_the_ID(),'_webi_speaker_company', true);
							$speakers_title_arr = get_post_meta(get_the_ID(),'_webi_speaker_title', true);
							$speakers_bio_arr = get_post_meta(get_the_ID(),'_webi_speaker_bio', true);
							$speakers_img_arr = get_post_meta(get_the_ID(),'_webi_speaker_image', true);
							$speakers_website_arr = get_post_meta(get_the_ID(),'_webi_speaker_website', true);
							$speakers_twitter_arr = get_post_meta(get_the_ID(),'_webi_speaker_twitter', true);
							$speakers_facebook_arr = get_post_meta(get_the_ID(),'_webi_speaker_facebook', true);
							$speakers_linkedin_arr = get_post_meta(get_the_ID(),'_webi_speaker_linkedin', true);
		
							if(count($speakers_fname_arr) != 0 && !empty($speakers_fname_arr))
							{
								
								echo '<div class="webi_subsec">
									<h6>'.__('Speakers','webinara').'</h6>';
									for($speaker_count = 0; $speaker_count <= count($speakers_fname_arr); $speaker_count++){
										if(!empty(trim($speakers_fname_arr[$speaker_count])) && !empty(trim($speakers_lname_arr[$speaker_count])))
										{											
											$speaker_title = $speakers_fname_arr[$speaker_count].' '.$speakers_lname_arr[$speaker_count].', '.$speakers_title_arr[$speaker_count].' at '.$speakers_company_arr[$speaker_count];
											$speaker_social_sec = '';
											$social_arr = array();
											if (!empty($speakers_website_arr[$speaker_count])) {
														$host_website = $speakers_website_arr[$speaker_count];
														if (!preg_match("~^(?:f|ht)tps?://~i", $speakers_website_arr[$speaker_count])) {
															$host_website = "http://" . $speakers_website_arr[$speaker_count];
														}
														$social_arr[] = '<li><a href="'.$host_website.'" target="_blank" rel="nofollow">
																						<svg class="svg_cj" width="20" height="20" viewBox="0 0 300.000000 300.000000" preserveAspectRatio="xMidYMid meet">
																							<g transform="translate(0.000000,300.000000) scale(0.100000,-0.100000)" fill="#343434" stroke="none"> <path class="node" id="node1" d="M2125 2961 c-82 -21 -184 -66 -252 -112 -47 -31 -633 -606 -633 -621 0 -4 12 -3 26 2 73 28 298 -3 387 -53 l37 -21 168 165 c197 195 239 230 310 258 79 31 196 29 262 -4 65 -33 117 -86 147 -151 45 -98 28 -241 -39 -338 -30 -43 -558 -575 -646 -652 -53 -45 -91 -69 -135 -83 -119 -41 -248 -20 -335 54 -74 64 -139 79 -210 50 -103 -43 -150 -161 -102 -254 37 -74 147 -154 291 -214 59 -25 194 -40 290 -34 131 10 258 55 373 134 69 48 717 693 772 769 124 172 172 392 129 593 -40 190 -183 375 -355 458 -47 23 -116 49 -153 57 -90 21 -242 19 -332 -3z"></path> <path class="node" id="node2" d="M1238 2040 c-98 -17 -210 -64 -302 -127 -69 -48 -717 -693 -772 -769 -125 -174 -174 -403 -129 -599 45 -191 185 -369 355 -452 107 -52 196 -73 311 -73 149 0 301 47 426 131 47 31 633 606 633 621 0 4 -12 3 -26 -2 -72 -28 -299 2 -382 51 -18 10 -37 19 -41 19 -5 0 -82 -73 -172 -163 -196 -194 -238 -229 -307 -256 -79 -31 -196 -29 -262 4 -163 83 -218 263 -134 442 23 48 76 107 313 347 349 352 412 408 494 435 119 41 248 20 335 -54 74 -64 139 -79 210 -50 102 43 150 161 102 254 -36 71 -159 165 -270 206 -105 40 -267 54 -382 35z"></path> </g>
																						</svg>
																					</a></li>';
													}
													if (!empty($speakers_facebook_arr[$speaker_count])) {
														$host_facebook = $speakers_facebook_arr[$speaker_count];
														if (!preg_match("~^(?:f|ht)tps?://~i", $speakers_facebook_arr[$speaker_count])) {
															$host_facebook = "http://" . $speakers_facebook_arr[$speaker_count];
														}
														$social_arr[] = '<li><a href="'.$host_facebook.'" target="_blank" rel="nofollow">
																						<svg class="svg_fb" width="18px" height="19px" viewBox="0 0 430.113 430.114" style="enable-background:new 0 0 430.113 430.114;" xml:space="preserve">
																							<path id="Facebook" d="M158.081,83.3c0,10.839,0,59.218,0,59.218h-43.385v72.412h43.385v215.183h89.122V214.936h59.805 c0,0,5.601-34.721,8.316-72.685c-7.784,0-67.784,0-67.784,0s0-42.127,0-49.511c0-7.4,9.717-17.354,19.321-17.354 c9.586,0,29.818,0,48.557,0c0-9.859,0-43.924,0-75.385c-25.016,0-53.476,0-66.021,0C155.878-0.004,158.081,72.48,158.081,83.3z"/>
																						</svg>
																					</a></li>';
													}
													if (!empty($speakers_twitter_arr[$speaker_count])) {
															$host_twitter_array = explode("@", $speakers_twitter_arr[$speaker_count]);
															if (count($host_twitter_array) == 1 || $host_twitter_array[0] != '') {
																$twitter_link = 'https://twitter.com/' . $host_twitter_array[0];
															} else {
																$twitter_link = 'https://twitter.com/' . $host_twitter_array[1];
															}
															$social_arr[] = '<li><a href="'.$twitter_link.'" target="_blank" rel="nofollow">
																<svg class="svg_tw" width="22px" height="20px" viewBox="0 0 430.117 430.117" style="enable-background:new 0 0 430.117 430.117;" xml:space="preserve">
																								<path id="Twitter__x28_alt_x29_" d="M381.384,198.639c24.157-1.993,40.543-12.975,46.849-27.876 c-8.714,5.353-35.764,11.189-50.703,5.631c-0.732-3.51-1.55-6.844-2.353-9.854c-11.383-41.798-50.357-75.472-91.194-71.404 c3.304-1.334,6.655-2.576,9.996-3.691c4.495-1.61,30.868-5.901,26.715-15.21c-3.5-8.188-35.722,6.188-41.789,8.067 c8.009-3.012,21.254-8.193,22.673-17.396c-12.27,1.683-24.315,7.484-33.622,15.919c3.36-3.617,5.909-8.025,6.45-12.769 C241.68,90.963,222.563,133.113,207.092,174c-12.148-11.773-22.915-21.044-32.574-26.192 c-27.097-14.531-59.496-29.692-110.355-48.572c-1.561,16.827,8.322,39.201,36.8,54.08c-6.17-0.826-17.453,1.017-26.477,3.178 c3.675,19.277,15.677,35.159,48.169,42.839c-14.849,0.98-22.523,4.359-29.478,11.642c6.763,13.407,23.266,29.186,52.953,25.947 c-33.006,14.226-13.458,40.571,13.399,36.642C113.713,320.887,41.479,317.409,0,277.828 c108.299,147.572,343.716,87.274,378.799-54.866c26.285,0.224,41.737-9.105,51.318-19.39 C414.973,206.142,393.023,203.486,381.384,198.639z"/>
																							</svg>
															</a>
														</li>';
													}
													if (!empty($speakers_linkedin_arr[$speaker_count])) {
														$host_linkedin = $speakers_linkedin_arr[$speaker_count];
														if (!preg_match("~^(?:f|ht)tps?://~i", $speakers_linkedin_arr[$speaker_count])) {
															$host_linkedin = "http://" . $speakers_linkedin_arr[$speaker_count];
														}
														$social_arr[] = '<li><a href="'.$host_linkedin.'" target="_blank" rel="nofollow">
																						<svg class="svg_ln" width="17px" height="18px" viewBox="0 0 430.117 430.117" style="enable-background:new 0 0 430.117 430.117;" xml:space="preserve">
																							<path id="LinkedIn" d="M430.117,261.543V420.56h-92.188V272.193c0-37.271-13.334-62.707-46.703-62.707 c-25.473,0-40.632,17.142-47.301,33.724c-2.432,5.928-3.058,14.179-3.058,22.477V420.56h-92.219c0,0,1.242-251.285,0-277.32h92.21 v39.309c-0.187,0.294-0.43,0.611-0.606,0.896h0.606v-0.896c12.251-18.869,34.13-45.824,83.102-45.824 C384.633,136.724,430.117,176.361,430.117,261.543z M52.183,9.558C20.635,9.558,0,30.251,0,57.463 c0,26.619,20.038,47.94,50.959,47.94h0.616c32.159,0,52.159-21.317,52.159-47.94C103.128,30.251,83.734,9.558,52.183,9.558z M5.477,420.56h92.184v-277.32H5.477V420.56z"/>
																						</svg>
																					</a></li>';
													}
													if(count($social_arr) != 0)
													{
														$speaker_social_sec .= '<ul class="speaker_social_block">';
														foreach($social_arr as $social_element)
														{
															$speaker_social_sec .= $social_element;
														}		
														$speaker_social_sec .= '</ul>';	
													}	
											echo '<div class="speaker_section">';
												if(!empty($speakers_img_arr[$speaker_count])){												
													echo '<div class="speaker_sec_img"><img src="'.$speakers_img_arr[$speaker_count].'" alt="speaker"></div>';													
													$full_sec = '';
												}
												else
												{
													echo '<div class="speaker_sec_img"><img src="'.plugins_url('webinara') . '/includes/assets/images/default_speaker.png" alt="speaker"></div>';
												}	
													echo '<div class="speaker_sec_info">';	
													echo '<div class="speaker_title">'.$speaker_title.'</div>';
													if(!empty($speakers_bio_arr[$speaker_count]))
													{
														echo '<div class="speaker_bio">'.nl2br(esc_html($speakers_bio_arr[$speaker_count])).'</div>';											
													}
													echo $speaker_social_sec;
												echo '</div>';
												echo '<div class="clr"></div>';
											echo "</div>";
										}
									}	
								echo '</div>';								
							}

						$webi_attachments = get_post_meta(get_the_ID(),'_webi_attachment',true);
						if(count($webi_attachments) != 0 && !empty($webi_attachments) && !empty($webi_attachments[0]))
						{
							echo '<div class="webi_subsec">
								<h6>'.__('Attachments','webinara').'</h6>
								<ul class="attach_sec">';								
								foreach($webi_attachments as $webi_attachment)
								{
									echo '<li>
									<a href="'.basename($webi_attachment).'" download><img src="'.plugins_url('webinara') . '/includes/assets/images/attachment.png"><br><span>'.basename($webi_attachment).'</span></a>
									</li>';
								}
							echo '</ul></div>';
						}
										
						if(count($event_tags) != 0 && !empty($event_tags)){
							$tag_count = 1;
							echo '<p class="tag-container">'.__('Tags:','webinara').' ';
							foreach($event_tags as $event_tag)
							{
								if(count($event_tags) > $tag_count)
								{
									echo '<a href="javascript:void(0);">'.$event_tag->name.'</a>, ';
								}
								else
								{
									echo '<a href="javascript:void(0);">'.$event_tag->name.'</a>';
								}
								$tag_count++;
							}
							echo '</p>';
						}	?>
					</div>
					<div class="webi_body_right">
						
						<div class="webi_body_right_content">
							<p class="register_btn">
								<?php 
								if($platform == 3)
								{
									?>
									<a href="<?php echo get_post_meta(get_the_ID(),'_webi_registration_url',true); ?>" target="_blank" <?php if(!empty($webi_banner_theme)){ echo 'style="background-color:'.$webi_banner_theme.'"'; }?>><?php esc_html_e('Register','webinara'); ?></a>
									<?php
								}
								else if($platform == 7 || $platform == 4 || $platform == 2 || $platform == 1)
								{										
									echo '<a class="webi_reg_btn" href="#webi-register-form" rel="modal:open" '.$style_btn.'>'.__('Register','webinara').'</a>';										
								}
								?>									
							</p>
							<?php 							
							$org_logo = get_option('webi_organizer_logo');
							if(!empty($org_logo))
							{
								echo '<div class="webi_right_section webi_subsec">
								<h6>'.__('Organizer:','webinara').'</h6>
								<div class="webi_org_section">
									<img src="'.$org_logo.'" alt="Organizer logo">
								</div>';
								echo '</div>';
							}
							$webi_sponsar_arr = get_post_meta(get_the_ID(), '_webi_sponser', true);	
							if(count($webi_sponsar_arr) != 0 && !empty($webi_sponsar_arr))
							{	
								if(!empty($webi_sponsar_arr[0]))
								{
									$sponsor_count = 1;
									echo '<div class="webi_right_section webi_subsec">
									<h6>'.__('Organizer:','webinara').'</h6>
									<div class="webi_sponsor_section">';
									foreach($webi_sponsar_arr as $webi_sponsar)
									{
										if(count($webi_sponsar_arr) <= $sponsor_count)
										{
											echo '<img src="'.$webi_sponsar.'" alt="Sponsor" class="no_margin_bottom">';
										}
										else
										{
											echo '<img src="'.$webi_sponsar.'" alt="Sponsor">';
										}
										$sponsor_count++;
									}
									echo '</div></div>';
								}
							}
							?>
						</div>	
					</div>
					<div style="clear:both"></div>																	
				</div>
			</div>
		</div><!-- .entry-content -->						
	</div>
</div>	
<?php 
if($platform != 3)
{
?>
	<form id="webi-register-form" class="modal webi_register_form">
	  <h3 <?php echo $style_btn; ?>><?php esc_html_e('Register with webinar','webinara'); ?></h3>
	  <p><label><?php esc_html_e('First Name','webinara') ?>*:</label><input type="text" id="webi_rf_fname"/></p>
	  <p><label><?php esc_html_e('Last Name','webinara') ?>*:</label><input type="text" id="webi_rf_lname" /></p>
	  <p><label><?php esc_html_e('Email','webinara') ?>*:</label><input type="text" id="webi_rf_email" /></p>
	  <p class="register_form_btn">
	  <input type="hidden" id="webi_rf_platform" value="<?php echo $platform ?>">
	  <input type="hidden" id="webi_rf_info" value="<?php echo $reg_info ?>">
	  <input type="hidden" id="webi_rf_number" value="<?php echo get_the_ID() ?>">
	  <input type="submit" name="register_user" value="<?php esc_html_e('Register','webinara'); ?>" <?php echo $style_btn; ?>>	  
	  </p>
	  <div class="webi_register_response"></div>
	</form>		
<?php
}
endwhile;		
get_footer();