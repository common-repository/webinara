<?php if (!defined('ABSPATH')) { exit; } // Exit if accessed directly
/**
 * Webinara_Shortcode Class
 *
 * @package:    WebinaraPlugin
 */
class Webinara_Shortcode
{    
    public function __construct()
    {       
        add_shortcode('webinars', array($this, 'webi_callback_webinars'));		
		add_shortcode('events', array($this, 'webi_callback_events'));
    }
    
    public function webi_callback_webinars($atts, $content=null)
    {
		if(get_option('_webi_enable_webinars') == 1)
		{
			// Shortcode Default Array
			$shortcode_args = array("webinar_per_page" => get_option('_webi_webinars_per_page'),
									"category" => '',
									"layout" => 'grid');
			
			// Extract User Defined Shortcode Attributes
			$shortcode_args = shortcode_atts($shortcode_args, $atts);
			
			ob_start();
			
			$pagenum = ( isset($_GET[ 'pagenum' ] )) ? absint( $_GET[ 'pagenum' ] ) : 1;
			$args = array(
				'posts_per_page' => intval( $shortcode_args['webinar_per_page'] ),
				'post_type' => 'webinar',
				'webinars_category' => esc_attr( $shortcode_args['category'] ),
				'paged' => $pagenum
			);
			
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
			?>
				<div class="webi-wrap">
					<?php if(esc_attr($shortcode_args['layout']) == "grid") { ?>
					<div id="" class="grid-layout-wrapper">
						<?php
						while ( $the_query->have_posts() ) : $the_query->the_post();
						$webinara_img = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_id()), 'thumbnail'); 
						$speakers_fname = get_post_meta(get_the_id(), '_webi_speaker_firstname', true);	
						$speakers_lname = get_post_meta(get_the_id(), '_webi_speaker_lastname', true);	
						$speakers_company = get_post_meta(get_the_id(), '_webi_speaker_company', true);	
						$webi_timezone = get_post_meta(get_the_id(), '_webi_timezone', true);
						$webi_start_date = get_post_meta(get_the_id(), '_webi_start_date', true);	
						$webi_start_time = get_post_meta(get_the_id(), '_webi_start_time', true);	
						$webi_start_date = get_post_meta(get_the_id(), '_webi_end_date', true);	
						$webi_end_time = get_post_meta(get_the_id(), '_webi_end_time', true);	
						$webi_all_day = get_post_meta(get_the_id(), '_webi_all_day', true);					
						if(!empty($webi_timezone))
						{
							if(!strstr($webi_timezone, "UTC"))
							{					
								date_default_timezone_set($webi_timezone);
								$time = new DateTime('now', new DateTimeZone($webi_timezone));
								$timezoneOffset_home = $time->format('P');
								if(!empty($webi_all_day) && $webi_all_day == 1)
								{
									$webinar_time_sec = date('M j', strtotime($webi_start_date)).' (GMT '.$timezoneOffset_home.')';
								}
								else
								{
									$webinar_time_sec = date('M j, g.i A', strtotime($webi_start_date.' '.$webi_start_time)).' (GMT '.$timezoneOffset_home.')';
								}
							}
							else
							{
								if(!empty($webi_all_day) && $webi_all_day == 1){
									$webinar_time_sec = date('M j', strtotime($webi_start_date)).' ('.$webi_timezone.')';
								}
								else
								{
									$webinar_time_sec = date('M j, g.i A', strtotime($webi_start_date.' '.$webi_start_time)).' ('.$webi_timezone.')';
								}
							}
						}
						else
						{
							$webinar_time_sec = '';
						}
						$webi_categories = get_the_terms(get_the_id(), 'event_categories');			
						if(empty($webi_categories))
						{
							$webi_categories = array();
						}					
						?>
						<div class="grid-layout">
							<?php if ( !empty( $webinara_img[0] ) ) { 
								$img_url = $webinara_img[0];
							}
							else {
								$img_url = plugin_dir_url( __FILE__ ) . 'assets/images/default_blue-150x150.jpg';
							} ?>
							<a href="<?php echo get_permalink(); ?>">
								<div class="webi_img_section"><img src="<?php echo esc_url( $img_url ); ?>" class="img-square">
									<span><?php echo $webinar_time_sec; ?></span>
								</div>
							</a>	
							<h5 class="webinara-title"><a href="<?php echo get_permalink(); ?>"><?php echo esc_attr( get_the_title() ); ?></a></h5>                    
							<div class="webinara-desc">
								<?php $content = esc_attr( get_the_content() ); 
								$pos = strpos($content, ' ', 150);
								$content = substr($content,0,$pos );
								echo "<p>".$content."</p>";
								$speaker_count = 0;
								foreach($speakers_fname as $speaker_fname)
								{					
									if($speaker_count == 0)
									{
										echo "<p class='webi_tilespeaker_info'><b>".$speakers_company[$speaker_count]."</b><br>".__('Speaker','webinara')." - <b>".$speaker_fname." ".$speakers_lname[$speaker_count]."</b></p>";
									}
									$speaker_count++;
								}
								if(count($webi_categories) != 0)
								{
									echo "<ul class='webi_tags_group'>";
									foreach($webi_categories as $webi_category)
									{
										echo "<li>".$webi_category->name."</li>";
									}
									echo "</ul>";
								}
								?>
							</div>
							<a href="<?php echo get_permalink(); ?>" class="webi_readmore"><?php esc_html_e('Read more','webinara'); ?></a>
						</div>
						<?php endwhile; ?>
					</div>
					<?php } ?>
				</div>        
				<div class='webi_pagination_section'>
					<?php echo paginate_links( array(
						'base' => add_query_arg( 'pagenum', '%#%' ),
						'format' => '',
						'total' => $the_query->max_num_pages,
						'current' => $pagenum,
						'prev_text' => __('&laquo;'),
						'next_text' => __('&raquo;')
					) );
					?>	
				</div>
				<?php
			}
			$content = ob_get_contents();
			ob_end_clean();
			wp_reset_postdata();
			return $content;
		}
    }  



	public function webi_callback_events($atts, $content=null)
    {               
        // Shortcode Default Array
        $shortcode_args = array("event_per_page" => get_option('_webi_events_per_page'),
								"category" => '',
								"layout" => 'grid');
        
        // Extract User Defined Shortcode Attributes
        $shortcode_args = shortcode_atts($shortcode_args, $atts);
		
		ob_start();
		
		$pagenum = ( isset($_GET[ 'pagenum' ] )) ? absint( $_GET[ 'pagenum' ] ) : 1;
        $args = array(
            'posts_per_page' => intval( $shortcode_args['event_per_page'] ),
            'post_type' => 'event',
            'webinars_category' => esc_attr( $shortcode_args['category'] ),
			'paged' => $pagenum
        );
        
        $the_query = new WP_Query( $args );
        if ( $the_query->have_posts() ) {
        ?>
			<div class="webi-wrap">
				<?php if(esc_attr($shortcode_args['layout']) == "grid") { ?>
				<div id="" class="grid-layout-wrapper">
					<?php
					while ( $the_query->have_posts() ) : $the_query->the_post();
					$webinara_img = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_id()), 'thumbnail'); 
					$speakers_fname = get_post_meta(get_the_id(), '_webi_speaker_firstname', true);	
					$speakers_lname = get_post_meta(get_the_id(), '_webi_speaker_lastname', true);	
					$speakers_company = get_post_meta(get_the_id(), '_webi_speaker_company', true);	
					$webi_timezone = get_post_meta(get_the_id(), '_webi_timezone', true);
					$webi_start_date = get_post_meta(get_the_id(), '_webi_start_date', true);	
					$webi_start_time = get_post_meta(get_the_id(), '_webi_start_time', true);	
					$webi_start_date = get_post_meta(get_the_id(), '_webi_end_date', true);	
					$webi_end_time = get_post_meta(get_the_id(), '_webi_end_time', true);	
					$webi_all_day = get_post_meta(get_the_id(), '_webi_all_day', true);					
					if(!empty($webi_timezone))
					{
						if(!strstr($webi_timezone, "UTC"))
						{					
							date_default_timezone_set($webi_timezone);
							$time = new DateTime('now', new DateTimeZone($webi_timezone));
							$timezoneOffset_home = $time->format('P');
							if(!empty($webi_all_day) && $webi_all_day == 1)
							{
								$webinar_time_sec = date('M j', strtotime($webi_start_date)).' (GMT '.$timezoneOffset_home.')';
							}
							else
							{
								$webinar_time_sec = date('M j, g.i A', strtotime($webi_start_date.' '.$webi_start_time)).' (GMT '.$timezoneOffset_home.')';
							}
						}
						else
						{
							if(!empty($webi_all_day) && $webi_all_day == 1){
								$webinar_time_sec = date('M j', strtotime($webi_start_date)).' ('.$webi_timezone.')';
							}
							else
							{
								$webinar_time_sec = date('M j, g.i A', strtotime($webi_start_date.' '.$webi_start_time)).' ('.$webi_timezone.')';
							}
						}
					}
					else
					{
						$webinar_time_sec = '';
					}
					$webi_categories = get_the_terms(get_the_id(), 'event_categories');			
					if(empty($webi_categories))
					{
						$webi_categories = array();
					}					
					?>
					<div class="grid-layout">
						<?php if ( !empty( $webinara_img[0] ) ) { 
							$img_url = $webinara_img[0];
						}
						else {
							$img_url = plugin_dir_url( __FILE__ ) . 'assets/images/default_blue-150x150.jpg';
						} ?>
						<a href="<?php echo get_permalink(); ?>">
							<div class="webi_img_section"><img src="<?php echo esc_url( $img_url ); ?>" class="img-square">
								<span><?php echo $webinar_time_sec; ?></span>
							</div>
						</a>	
						<h5 class="webinara-title"><a href="<?php echo get_permalink(); ?>"><?php echo esc_attr( get_the_title() ); ?></a></h5>                    
						<div class="webinara-desc">
							<?php $content = esc_attr( get_the_content() ); 
							$pos = strpos($content, ' ', 150);
							$content = substr($content,0,$pos );
							echo "<p>".$content."</p>";
							$speaker_count = 0;
							foreach($speakers_fname as $speaker_fname)
							{					
								if($speaker_count == 0)
								{
									echo "<p class='webi_tilespeaker_info'><b>".$speakers_company[$speaker_count]."</b><br>".__('Speaker','webinara')." - <b>".$speaker_fname." ".$speakers_lname[$speaker_count]."</b></p>";
								}
								$speaker_count++;
							}
							if(count($webi_categories) != 0)
							{
								echo "<ul class='webi_tags_group'>";
								foreach($webi_categories as $webi_category)
								{
									echo "<li>".$webi_category->name."</li>";
								}
								echo "</ul>";
							}
							?>
						</div>
						<a href="<?php echo get_permalink(); ?>" class="webi_readmore"><?php esc_html_e('Read more','webinara'); ?></a>
					</div>
					<?php endwhile; ?>
				</div>
				<?php } ?>
			</div>        
			<div class='webi_pagination_section'>
				<?php echo paginate_links( array(
					'base' => add_query_arg( 'pagenum', '%#%' ),
					'format' => '',
					'total' => $the_query->max_num_pages,
					'current' => $pagenum,
					'prev_text' => __('&laquo;'),
					'next_text' => __('&raquo;')
				) );
				?>	
			</div>
			<?php
		}
        $content = ob_get_contents();
        ob_end_clean();
        wp_reset_postdata();
        return $content;
    } 	
}
new Webinara_Shortcode();