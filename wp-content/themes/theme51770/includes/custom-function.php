<?php
	// Loads child theme textdomain
	load_child_theme_textdomain( CURRENT_THEME, CHILD_DIR . '/languages' );

	// Loads custom scripts.
	// require_once( 'custom-js.php' );

	add_action( 'wp_enqueue_scripts', 'cherry_child_custom_scripts' );
	function cherry_child_custom_scripts() {
		wp_enqueue_script( 'themescript', CHILD_URL . '/js/themeScript.js', array( 'jquery' ), '1.0' );
	}

	add_filter( 'cherry_stickmenu_selector', 'cherry_change_selector' );
	function cherry_change_selector($selector) {
		$selector = '.nav-wrapper';
		return $selector;
	}

	add_action( 'after_setup_theme', 'after_cherry_child_setup' );
	function after_cherry_child_setup() {
		$nfu_options = get_option( 'nsu_form' );
		if ( !$nfu_options ) {
			$nfu_options_array = array();
			$nfu_options_array['email_label']         = 'Subscribe to Our newsletter';
			$nfu_options_array['email_default_value'] = 'your email address';
			$nfu_options_array['submit_button']       = 'submit';
			update_option( 'nsu_form', $nfu_options_array );
		}
	}	

	/**
	 * Service Box
	 *
	 */
	if (!function_exists('service_box_shortcode')) {

		function service_box_shortcode( $atts, $content = null, $shortcodename = '' ) {
			extract(shortcode_atts(
				array(
					'title'        => '',
					'subtitle'     => '',
					'icon'         => '',
					'text'         => '',
					'btn_text'     => __('Read more', CHERRY_PLUGIN_DOMAIN),
					'btn_link'     => '',
					'btn_size'     => '',
					'target'       => '',
					'custom_class' => ''
			), $atts));

			$output =  '<div class="service-box '.$custom_class.'">';

			$output .= '<div class="service-box_header">';

				if($icon != 'no'){
					$icon_url = CHERRY_PLUGIN_URL . 'includes/images/' . strtolower($icon) . '.png' ;
					if( defined ('CHILD_DIR') ) {
						if(file_exists(CHILD_DIR.'/images/'.strtolower($icon).'.png')){
							$icon_url = CHILD_URL.'/images/'.strtolower($icon).'.png';
						}
					}
					$output .= '<figure class="icon"><img src="'.$icon_url.'" alt="" /></figure>';
				}
				if ($title!="") {
					$output .= '<h2 class="title">';
					$output .= $title;
					$output .= '</h2>';
				}

			$output .= '</div>';

			$output .= '<div class="service-box_body">';
			
			if ($subtitle!="") {
				$output .= '<h5 class="sub-title">';
				$output .= $subtitle;
				$output .= '</h5>';
			}
			if ($text!="") {
				$output .= '<div class="service-box_txt">';
				$output .= $text;
				$output .= '</div>';
			}
			if ($btn_link!="") {
				$output .=  '<div class="btn-align"><a href="'.$btn_link.'" title="'.$btn_text.'" class="btn btn-inverse btn-'.$btn_size.' btn-primary " target="'.$target.'"><span>';
				$output .= $btn_text;
				$output .= '</span></a></div>';
			}
			$output .= '</div>';
			$output .= '</div><!-- /Service Box -->';

			$output = apply_filters( 'cherry_plugin_shortcode_output', $output, $atts, $shortcodename );

			return $output;
		}
		add_shortcode('service_box', 'service_box_shortcode');

	}

	/**
	 * Banner
	 *
	 */
	if ( !function_exists( 'banner_shortcode' ) ) {

		function banner_shortcode( $atts, $content = null, $shortcodename = '' ) {
			extract( shortcode_atts(
				array(
					'img'          => '',
					'banner_link'  => '',
					'title'        => '',
					'text'         => '',
					'btn_text'     => '',
					'target'       => '',
					'custom_class' => ''
			), $atts));

			// Get the URL to the content area.
			$content_url = untrailingslashit( content_url() );

			// Find latest '/' in content URL.
			$last_slash_pos = strrpos( $content_url, '/' );

			// 'wp-content' or something else.
			$content_dir_name = substr( $content_url, $last_slash_pos - strlen( $content_url ) + 1 );

			$pos = strpos( $img, $content_dir_name );

			if ( false !== $pos ) {

				$img_new = substr( $img, $pos + strlen( $content_dir_name ), strlen( $img ) - $pos );
				$img     = $content_url . $img_new;

			}

			$output =  '<div class="banner-wrap '.$custom_class.'">';
			if ($img !="") {
				$output .= '<figure class="featured-thumbnail">';
				if ($banner_link != "") {
					$output .= '<a href="'. $banner_link .'" title="'. $title .'"><img src="' . $img .'" title="'. $title .'" alt="" /></a>';
				} else {
					$output .= '<img src="' . $img .'" title="'. $title .'" alt="" />';
				}
				$output .= '</figure>';
			}
			if ($title!="") {
				$output .= '<h5>';
				$output .= $title;
				$output .= '</h5>';
			}
			if ($text!="") {
				$output .= '<p>';
				$output .= $text;
				$output .= '</p>';
			}
			if ($btn_text!="") {
				$output .=  '<div class="link-align banner-btn"><a href="'.$banner_link.'" title="'.$btn_text.'" class="btn btn-link" target="'.$target.'">';
				$output .= $btn_text;
				$output .= '</a></div>';
			} else {
				$output .=  '<div class="link-align banner-btn"><a href="'.$banner_link.'" title="'.strip_tags($title).'" target="'.$target.'">';
				$output .= '<span class="arrow"></span>';
				$output .= '</a></div>';
			}
			$output .= '</div><!-- .banner-wrap (end) -->';

			$output = apply_filters( 'cherry_plugin_shortcode_output', $output, $atts, $shortcodename );

			return $output;
		}
		add_shortcode('banner', 'banner_shortcode');

	}

	/**
	 * Post Cycle
	 *
	 */
	if (!function_exists('shortcode_post_cycle')) {

		function shortcode_post_cycle( $atts, $content = null, $shortcodename = '' ) {
			extract(shortcode_atts(array(
					'num'              => '5',
					'type'             => 'post',
					'meta'             => '',
					'effect'           => 'slide',
					'thumb'            => 'true',
					'thumb_width'      => '200',
					'thumb_height'     => '180',
					'more_text_single' => '',
					'category'         => '',
					'custom_category'  => '',
					'excerpt_count'    => '15',
					'pagination'       => 'true',
					'navigation'       => 'true',
					'custom_class'     => ''
			), $atts));

			$type_post         = $type;
			$slider_pagination = $pagination;
			$slider_navigation = $navigation;
			$random            = gener_random(10);
			$i                 = 0;
			$rand              = rand();
			$count             = 0;
			if ( is_rtl() ) {
				$is_rtl = 'true';
			} else {
				$is_rtl = 'false';
			}

			$output = '<script type="text/javascript">
							jQuery(window).load(function() {
								jQuery("#flexslider_'.$random.'").flexslider({
									animation: "'.$effect.'",
									smoothHeight : true,
									directionNav: '.$slider_navigation.',
									controlNav: '.$slider_pagination.',
									rtl: '.$is_rtl.',
									pauseOnHover: true
								});
							});';
			$output .= '</script>';
			$output .= '<div id="flexslider_'.$random.'" class="flexslider no-bg '.$custom_class.'">';
				$output .= '<ul class="slides">';

				global $post;
				global $my_string_limit_words;

				// WPML filter
				$suppress_filters = get_option('suppress_filters');

				$args = array(
					'post_type'              => $type_post,
					'category_name'          => $category,
					$type_post . '_category' => $custom_category,
					'numberposts'            => $num,
					'orderby'                => 'post_date',
					'order'                  => 'DESC',
					'suppress_filters'       => $suppress_filters
				);

				$latest = get_posts($args);

				foreach($latest as $key => $post) {
					//Check if WPML is activated
					if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
						global $sitepress;

						$post_lang = $sitepress->get_language_for_element($post->ID, 'post_' . $type_post);
						$curr_lang = $sitepress->get_current_language();
						// Unset not translated posts
						if ( $post_lang != $curr_lang ) {
							unset( $latest[$key] );
						}
						// Post ID is different in a second language Solution
						if ( function_exists( 'icl_object_id' ) ) {
							$post = get_post( icl_object_id( $post->ID, $type_post, true ) );
						}
					}
					setup_postdata($post);
					$excerpt        = get_the_excerpt();
					$attachment_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
					$url            = $attachment_url['0'];
					$image          = aq_resize($url, $thumb_width, $thumb_height, true);

					$output .= '<li class="list-item-'.$count.'">';

						$output .= '<div class="column column-1">';

							if ($type == 'testi') {
								$output .= '<div class="quote"><i class="icon-quote-right"></i></div>';
							}

							if ($thumb == 'true') {

								if ( has_post_thumbnail($post->ID) ){
									$output .= '<figure class="thumbnail featured-thumbnail"><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
									$output .= '<img  src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
									$output .= '</a></figure>';
								} else {
									$output .= '<figure class="thumbnail featured-thumbnail empty"><i class="icon-user"></i></figure>';
								}
							}

						$output .= '</div>';

						$output .= '<div class="column column-2">';

						$output .= '<h5><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
						$output .= get_the_title($post->ID);
						$output .= '</a></h5>';

						if($meta == 'true'){
							$output .= '<span class="meta">';
							$output .= '<span class="post-date">';
							$output .= get_the_date();
							$output .= '</span>';
							$output .= '<span class="post-comments">'.__('Comments', CHERRY_PLUGIN_DOMAIN).": ";
							$output .= '<a href="'.get_comments_link($post->ID).'">';
							$output .= get_comments_number($post->ID);
							$output .= '</a>';
							$output .= '</span>';
							$output .= '</span>';
						}
						
						if($excerpt_count >= 1){
							$output .= '<p class="excerpt">';
							if ($type == 'testi') {
								$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">“ ';
							}
							$output .= my_string_limit_words($excerpt,$excerpt_count);
							if ($type == 'testi') {
								$output .= ' ”</a>';
							}
							$output .= '</p>';
						}

						//display post options
						$output .= '<div class="post_options">';

						switch( $type_post ) {

							case "team":
								$teampos    = get_post_meta( $post->ID, 'my_team_pos', true );
								$team_email = sanitize_email( get_post_meta( $post->ID, 'my_team_email', true ) );
								$teaminfo   = get_post_meta( $post->ID, 'my_team_info', true );

								if ( !empty( $teampos ) ) {
									$output .= "<span class='page-desc'>" . $teampos . "</span><br>";
								}

								if ( !empty( $team_email ) && is_email( $team_email ) ) {
									$output .= '<span class="team-email"><a href="mailto:' . antispambot( $team_email, 1 ) . '">' . antispambot( $team_email ) . ' </a></span><br>';
								}

								if ( !empty( $teaminfo ) ) {
									$output .= '<span class="team-content post-content team-info">' . esc_html( $teaminfo ) . '</span>';
								}

								$output .= cherry_get_post_networks(array('post_id' => $post->ID, 'display_title' => false, 'output_type' => 'return'));
								break;

							case "testi":
								$testiname  = get_post_meta( $post->ID, 'my_testi_caption', true );
								$testiurl   = esc_url( get_post_meta( $post->ID, 'my_testi_url', true ) );
								$testiinfo  = get_post_meta( $post->ID, 'my_testi_info', true );
								$testiemail = sanitize_email( get_post_meta($post->ID, 'my_testi_email', true ) );

								if ( !empty( $testiname ) ) {
									$output .= '<span class="user">' . $testiname . '</span>';
								}

								if ( !empty( $testiinfo ) ) {
									$output .= '<span class="info">, ' . $testiinfo . '<br></span>';
								}

								if ( !empty( $testiurl ) ) {
									$output .= '<a class="testi-url" href="' . $testiurl . '" target="_blank">' . $testiurl . '<br></a>';
								}

								if ( !empty( $testiemail ) && is_email( $testiemail ) ) {
									$output .= '<a class="testi-email" href="mailto:' . antispambot( $testiemail, 1 ) . '">' . antispambot( $testiemail ) . ' </a>';
								}
								break;

							case "portfolio":
								$portfolioClient = (get_post_meta($post->ID, 'tz_portfolio_client', true)) ? get_post_meta($post->ID, 'tz_portfolio_client', true) : "";
								$portfolioDate = (get_post_meta($post->ID, 'tz_portfolio_date', true)) ? get_post_meta($post->ID, 'tz_portfolio_date', true) : "";
								$portfolioInfo = (get_post_meta($post->ID, 'tz_portfolio_info', true)) ? get_post_meta($post->ID, 'tz_portfolio_info', true) : "";
								$portfolioURL = (get_post_meta($post->ID, 'tz_portfolio_url', true)) ? get_post_meta($post->ID, 'tz_portfolio_url', true) : "";
								$output .="<strong class='portfolio-meta-key'>".__('Client', CHERRY_PLUGIN_DOMAIN).": </strong><span> ".$portfolioClient."</span><br>";
								$output .="<strong class='portfolio-meta-key'>".__('Date', CHERRY_PLUGIN_DOMAIN).": </strong><span> ".$portfolioDate."</span><br>";
								$output .="<strong class='portfolio-meta-key'>".__('Info', CHERRY_PLUGIN_DOMAIN).": </strong><span> ".$portfolioInfo."</span><br>";
								$output .="<a href='".$portfolioURL."'>".__('Launch Project', CHERRY_PLUGIN_DOMAIN)."</a><br>";
								break;

							default:
								$output .="";
						};
						$output .= '</div>';

						if($more_text_single!=""){
							$output .= '<a href="'.get_permalink($post->ID).'" class="btn btn-primary" title="'.get_the_title($post->ID).'">';
							$output .= $more_text_single;
							$output .= '</a>';
						}

						$output .= '</div>';

					$output .= '</li>';
					$count++;
				}
				wp_reset_postdata(); // restore the global $post variable
				$output .= '</ul>';
			$output .= '</div>';

			$output = apply_filters( 'cherry_plugin_shortcode_output', $output, $atts, $shortcodename );

			return $output;
		}
		add_shortcode('post_cycle', 'shortcode_post_cycle');

	}

	//Recent Posts
	if (!function_exists('shortcode_recent_posts')) {

		function shortcode_recent_posts( $atts, $content = null, $shortcodename = '' ) {
			extract(shortcode_atts(array(
					'type'             => 'post',
					'category'         => '',
					'custom_category'  => '',
					'tag'              => '',
					'post_format'      => 'standard',
					'num'              => '5',
					'meta'             => 'true',
					'thumb'            => 'true',
					'thumb_width'      => '120',
					'thumb_height'     => '120',
					'more_text_single' => '',
					'excerpt_count'    => '0',
					'custom_class'     => ''
			), $atts));

			$output = '<ul class="recent-posts '.$custom_class.' unstyled">';

			global $post;
			global $my_string_limit_words;
			$item_counter = 0;
			// WPML filter
			$suppress_filters = get_option('suppress_filters');

			if($post_format == 'standard') {

				$args = array(
							'post_type'         => $type,
							'category_name'     => $category,
							'tag'               => $tag,
							$type . '_category' => $custom_category,
							'numberposts'       => $num,
							'orderby'           => 'post_date',
							'order'             => 'DESC',
							'tax_query'         => array(
							'relation'          => 'AND',
								array(
									'taxonomy' => 'post_format',
									'field'    => 'slug',
									'terms'    => array('post-format-aside', 'post-format-gallery', 'post-format-link', 'post-format-image', 'post-format-quote', 'post-format-audio', 'post-format-video'),
									'operator' => 'NOT IN'
								)
							),
							'suppress_filters' => $suppress_filters
						);

			} else {

				$args = array(
					'post_type'         => $type,
					'category_name'     => $category,
					'tag'               => $tag,
					$type . '_category' => $custom_category,
					'numberposts'       => $num,
					'orderby'           => 'post_date',
					'order'             => 'DESC',
					'tax_query'         => array(
					'relation'          => 'AND',
						array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => array('post-format-' . $post_format)
						)
					),
					'suppress_filters' => $suppress_filters
				);
			}

			$latest = get_posts($args);

			foreach($latest as $k => $post) {
					//Check if WPML is activated
					if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
						global $sitepress;

						$post_lang = $sitepress->get_language_for_element($post->ID, 'post_' . $type);
						$curr_lang = $sitepress->get_current_language();
						// Unset not translated posts
						if ( $post_lang != $curr_lang ) {
							unset( $latest[$k] );
						}
						// Post ID is different in a second language Solution
						if ( function_exists( 'icl_object_id' ) ) {
							$post = get_post( icl_object_id( $post->ID, $type, true ) );
						}
					}
					setup_postdata($post);
					$excerpt        = get_the_excerpt();
					$attachment_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
					$url            = $attachment_url['0'];
					$image          = aq_resize($url, $thumb_width, $thumb_height, true);

					$post_classes = get_post_class();
					foreach ($post_classes as $key => $value) {
						$pos = strripos($value, 'tag-');
						if ($pos !== false) {
							unset($post_classes[$key]);
						}
					}
					$post_classes = implode(' ', $post_classes);

					$output .= '<li class="recent-posts_li ' . $post_classes . '  list-item-' . $item_counter . ' clearfix">';

					//Aside
					if($post_format == "aside") {

						$output .= the_content($post->ID);

					} elseif ($post_format == "link") {

						$url =  get_post_meta(get_the_ID(), 'tz_link_url', true);

						$output .= '<a target="_blank" href="'. $url . '">';
						$output .= get_the_title($post->ID);
						$output .= '</a>';

					//Quote
					} elseif ($post_format == "quote") {

						$quote =  get_post_meta(get_the_ID(), 'tz_quote', true);

						$output .= '<div class="quote-wrap clearfix">';

								$output .= '<blockquote>';
									$output .= $quote;
								$output .= '</blockquote>';

						$output .= '</div>';

					//Image
					} elseif ($post_format == "image") {

					if (has_post_thumbnail() ) :

						// $lightbox = get_post_meta(get_the_ID(), 'tz_image_lightbox', TRUE);

						$src      = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), array( '9999','9999' ), false, '' );

						$thumb    = get_post_thumbnail_id();
						$img_url  = wp_get_attachment_url( $thumb,'full'); //get img URL
						$image    = aq_resize( $img_url, 200, 120, true ); //resize & crop img


						$output .= '<figure class="thumbnail featured-thumbnail large">';
							$output .= '<a class="image-wrap" rel="prettyPhoto" title="' . get_the_title($post->ID) . '" href="' . $src[0] . '">';
							$output .= '<img src="' . $image . '" alt="' . get_the_title($post->ID) .'" />';
							$output .= '<span class="zoom-icon"></span></a>';
						$output .= '</figure>';

					endif;


					//Audio
					} elseif ($post_format == "audio") {

						$template_url = get_template_directory_uri();
						$id           = $post->ID;

						// get audio attribute
						$audio_title  = get_post_meta(get_the_ID(), 'tz_audio_title', true);
						$audio_artist = get_post_meta(get_the_ID(), 'tz_audio_artist', true);
						$audio_format = get_post_meta(get_the_ID(), 'tz_audio_format', true);
						$audio_url    = get_post_meta(get_the_ID(), 'tz_audio_url', true);

						// Get the URL to the content area.
						$content_url = untrailingslashit( content_url() );

						// Find latest '/' in content URL.
						$last_slash_pos = strrpos( $content_url, '/' );

						// 'wp-content' or something else.
						$content_dir_name = substr( $content_url, $last_slash_pos - strlen( $content_url ) + 1 );

						$pos = strpos( $audio_url, $content_dir_name );

						if ( false === $pos ) {
							$file = $audio_url;
						} else {
							$audio_new = substr( $audio_url, $pos + strlen( $content_dir_name ), strlen( $audio_url ) - $pos );
							$file     = $content_url . $audio_new;
						}

						$output .= '<script type="text/javascript">
							jQuery(document).ready(function(){
								var myPlaylist_'. $id.'  = new jPlayerPlaylist({
								jPlayer: "#jquery_jplayer_'. $id .'",
								cssSelectorAncestor: "#jp_container_'. $id .'"
								}, [
								{
									title:"'. $audio_title .'",
									artist:"'. $audio_artist .'",
									'. $audio_format .' : "'. stripslashes(htmlspecialchars_decode($file)) .'"}
								], {
									playlistOptions: {enableRemoveControls: false},
									ready: function () {jQuery(this).jPlayer("setMedia", {'. $audio_format .' : "'. stripslashes(htmlspecialchars_decode($file)) .'", poster: "'. $image .'"});
								},
								swfPath: "'. $template_url .'/flash",
								supplied: "'. $audio_format .', all",
								wmode:"window"
								});
							});
							</script>';

						$output .= '<div id="jquery_jplayer_'.$id.'" class="jp-jplayer"></div>
									<div id="jp_container_'.$id.'" class="jp-audio">
										<div class="jp-type-single">
											<div class="jp-gui">
												<div class="jp-interface">
													<div class="jp-progress">
														<div class="jp-seek-bar">
															<div class="jp-play-bar"></div>
														</div>
													</div>
													<div class="jp-duration"></div>
													<div class="jp-time-sep"></div>
													<div class="jp-current-time"></div>
													<div class="jp-controls-holder">
														<ul class="jp-controls">
															<li><a href="javascript:;" class="jp-previous" tabindex="1" title="'.__('Previous', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Previous', CHERRY_PLUGIN_DOMAIN).'</span></a></li>
															<li><a href="javascript:;" class="jp-play" tabindex="1" title="'.__('Play', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Play', CHERRY_PLUGIN_DOMAIN).'</span></a></li>
															<li><a href="javascript:;" class="jp-pause" tabindex="1" title="'.__('Pause', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Pause', CHERRY_PLUGIN_DOMAIN).'</span></a></li>
															<li><a href="javascript:;" class="jp-next" tabindex="1" title="'.__('Next', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Next', CHERRY_PLUGIN_DOMAIN).'</span></a></li>
															<li><a href="javascript:;" class="jp-stop" tabindex="1" title="'.__('Stop', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Stop', CHERRY_PLUGIN_DOMAIN).'</span></a></li>
														</ul>
														<div class="jp-volume-bar">
															<div class="jp-volume-bar-value"></div>
														</div>
														<ul class="jp-toggles">
															<li><a href="javascript:;" class="jp-mute" tabindex="1" title="'.__('Mute', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Mute', CHERRY_PLUGIN_DOMAIN).'</span></a></li>
															<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="'.__('Unmute', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Unmute', CHERRY_PLUGIN_DOMAIN).'</span></a></li>
														</ul>
													</div>
												</div>
												<div class="jp-no-solution">
													<span>'.__('Update Required.', CHERRY_PLUGIN_DOMAIN).'</span>'.__('To play the media you will need to either update your browser to a recent version or update your ', CHERRY_PLUGIN_DOMAIN).'<a href="http://get.adobe.com/flashplayer/" target="_blank">'.__('Flash plugin', CHERRY_PLUGIN_DOMAIN).'</a>
												</div>
											</div>
										</div>
										<div class="jp-playlist">
											<ul>
												<li></li>
											</ul>
										</div>
									</div>';


					$output .= '<div class="entry-content">';
						$output .= get_the_content($post->ID);
					$output .= '</div>';

					//Video
					} elseif ($post_format == "video") {

						$template_url = get_template_directory_uri();
						$id           = $post->ID;

						// get video attribute
						$video_title  = get_post_meta(get_the_ID(), 'tz_video_title', true);
						$video_artist = get_post_meta(get_the_ID(), 'tz_video_artist', true);
						$embed        = get_post_meta(get_the_ID(), 'tz_video_embed', true);
						$m4v_url      = get_post_meta(get_the_ID(), 'tz_m4v_url', true);
						$ogv_url      = get_post_meta(get_the_ID(), 'tz_ogv_url', true);

						// Get the URL to the content area.
						$content_url = untrailingslashit( content_url() );

						// Find latest '/' in content URL.
						$last_slash_pos = strrpos( $content_url, '/' );

						// 'wp-content' or something else.
						$content_dir_name = substr( $content_url, $last_slash_pos - strlen( $content_url ) + 1 );

						$pos1     = strpos($m4v_url, $content_dir_name);
						if ($pos1 === false) {
							$file1 = $m4v_url;
						} else {
							$m4v_new  = substr($m4v_url, $pos1+strlen($content_dir_name), strlen($m4v_url) - $pos1);
							$file1    = $content_url.$m4v_new;
						}

						$pos2     = strpos($ogv_url, $content_dir_name);
						if ($pos2 === false) {
							$file2 = $ogv_url;
						} else {
							$ogv_new  = substr($ogv_url, $pos2+strlen($content_dir_name), strlen($ogv_url) - $pos2);
							$file2    = $content_url.$ogv_new;
						}

						// get thumb
						if(has_post_thumbnail()) {
							$thumb   = get_post_thumbnail_id();
							$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
							$image   = aq_resize( $img_url, 770, 380, true ); //resize & crop img
						}

						if ($embed == '') {
							$output .= '<script type="text/javascript">
								jQuery(document).ready(function(){
									var
										jPlayerObj = jQuery("#jquery_jplayer_'. $id.'")
									,	jPlayerContainer = jQuery("#jp_container_'. $id.'")
									,	isPause = true
									;
									jPlayerObj.jPlayer({
										ready: function () {
											jQuery(this).jPlayer("setMedia", {
												m4v: "'. stripslashes(htmlspecialchars_decode($file1)) .'",
												ogv: "'. stripslashes(htmlspecialchars_decode($file2)) .'",
												poster: "'. $image .'"
											});
										},
										swfPath: "'. $template_url .'/flash",
										solution: "flash, html",
										supplied: "ogv, m4v, all",
										cssSelectorAncestor: "#jp_container_'. $id.'",
										size: {
											width: "100%",
											height: "100%"
										}
									});
									jPlayerObj.on(jQuery.jPlayer.event.ready + ".jp-repeat", function(event) {
										jQuery("img", this).addClass("poster");
										jQuery("video", this).addClass("video");
										jQuery("object", this).addClass("flashObject");
										jQuery(".video", jPlayerContainer).on("click", function(){
											jPlayerObj.jPlayer("pause");
										})
									})
									jPlayerObj.on(jQuery.jPlayer.event.ended + ".jp-repeat", function(event) {
										isPause = true
										jQuery(".poster", jPlayerContainer).css({display:"inline"});
									    jQuery(".video", jPlayerContainer).css({width:"0%", height:"0%"});
									    jQuery(".flashObject", jPlayerContainer).css({width:"0%", height:"0%"});
									    jPlayerObj.siblings(".jp-gui").find(".jp-video-play").css({display:"block"});
									});
									jPlayerObj.on(jQuery.jPlayer.event.play + ".jp-repeat", function(event) {
									   isPause = false
									   emulSwitch(isPause);
									});
									jPlayerObj.on(jQuery.jPlayer.event.pause + ".jp-repeat", function(event) {
									   isPause = true
									   emulSwitch(isPause);
									});
									function emulSwitch(_pause){
										if(_pause){
											jQuery(".poster", jPlayerContainer).css({display:"none"});
									    	jQuery(".video", jPlayerContainer).css({width:"100%", height:"100%"});
									    	jQuery(".flashObject", jPlayerContainer).css({width:"100%", height:"100%"});
									    	jPlayerObj.siblings(".jp-gui").find(".jp-video-play").css({display:"block"});
										}else{
											jQuery(".poster", jPlayerContainer).css({display:"none"});
									    	jQuery(".video", jPlayerContainer).css({width:"100%", height:"100%"});
									    	jQuery(".flashObject", jPlayerContainer).css({width:"100%", height:"100%"});
									    	jPlayerObj.siblings(".jp-gui").find(".jp-video-play").css({display:"none"});
										}
									}
								});
								</script>';
								$output .= '<div id="jp_container_'. $id .'" class="jp-video fullwidth">';
								$output .= '<div class="jp-type-list-parent">';
								$output .= '<div class="jp-type-single">';
								$output .= '<div id="jquery_jplayer_'. $id .'" class="jp-jplayer"></div>';
								$output .= '<div class="jp-gui">';
								$output .= '<div class="jp-video-play">';
								$output .= '<a href="javascript:;" class="jp-video-play-icon" tabindex="1" title="'.__('Play', CHERRY_PLUGIN_DOMAIN).'">'.__('Play', CHERRY_PLUGIN_DOMAIN).'</a></div>';
								$output .= '<div class="jp-interface">';
								$output .= '<div class="jp-progress">';
								$output .= '<div class="jp-seek-bar">';
								$output .= '<div class="jp-play-bar">';
								$output .= '</div></div></div>';
								$output .= '<div class="jp-duration"></div>';
								$output .= '<div class="jp-time-sep">/</div>';
								$output .= '<div class="jp-current-time"></div>';
								$output .= '<div class="jp-controls-holder">';
								$output .= '<ul class="jp-controls">';
								$output .= '<li><a href="javascript:;" class="jp-play" tabindex="1" title="'.__('Play', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Play', CHERRY_PLUGIN_DOMAIN).'</span></a></li>';
								$output .= '<li><a href="javascript:;" class="jp-pause" tabindex="1" title="'.__('Pause', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Pause', CHERRY_PLUGIN_DOMAIN).'</span></a></li>';
								$output .= '<li class="li-jp-stop"><a href="javascript:;" class="jp-stop" tabindex="1" title="'.__('Stop', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Stop', CHERRY_PLUGIN_DOMAIN).'</span></a></li>';
								$output .= '</ul>';
								$output .= '<div class="jp-volume-bar">';
								$output .= '<div class="jp-volume-bar-value">';
								$output .= '</div></div>';
								$output .= '<ul class="jp-toggles">';
								$output .= '<li><a href="javascript:;" class="jp-mute" tabindex="1" title="'.__('Mute', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Mute', CHERRY_PLUGIN_DOMAIN).'</span></a></li>';
								$output .= '<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="'.__('Unmute', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Unmute', CHERRY_PLUGIN_DOMAIN).'</span></a></li>';
								$output .= '</ul>';
								$output .= '</div></div>';
								$output .= '<div class="jp-no-solution">';
								$output .= '<span>'.__('Update Required.', CHERRY_PLUGIN_DOMAIN).'</span>'.__('To play the media you will need to either update your browser to a recent version or update your ', CHERRY_PLUGIN_DOMAIN).'<a href="http://get.adobe.com/flashplayer/" target="_blank">'.__('Flash plugin', CHERRY_PLUGIN_DOMAIN).'</a>';
								$output .= '</div></div></div></div>';
								$output .= '</div>';
						} else {
							$output .= '<div class="video-wrap">' . stripslashes(htmlspecialchars_decode($embed)) . '</div>';
						}

						if($excerpt_count >= 1){
							$output .= '<div class="excerpt">';
								$output .= my_string_limit_words($excerpt,$excerpt_count);
							$output .= '</div>';
					}

					//Standard
					} else {

						if ($thumb == 'true') {
							if ( has_post_thumbnail($post->ID) ){
								$output .= '<figure class="thumbnail featured-thumbnail"><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
								$output .= '<img src="'.$image.'" alt="' . get_the_title($post->ID) .'"/>';
								$output .= '</a></figure>';
							}
						}

						if ($meta == 'true') {
							$output .= '<span class="meta">';
								$output .= '<span class="post-date">';
									$output .= get_the_date();
								$output .= '</span>';
								$output .= '<span class="post-comments">';
									$output .= '<a href="'.get_comments_link($post->ID).'">';
										$output .= get_comments_number($post->ID);
									$output .= '</a>';
								$output .= '</span>';
							$output .= '</span>';
						}	

						$output .= '<h5><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
								$output .= get_the_title($post->ID);
						$output .= '</a></h5>';
						
						$output .= cherry_get_post_networks(array('post_id' => $post->ID, 'display_title' => false, 'output_type' => 'return'));
						if ($excerpt_count >= 1) {
							$output .= '<div class="excerpt">';
								$output .= my_string_limit_words($excerpt,$excerpt_count);
							$output .= '</div>';
						}
						if ($more_text_single!="") {
							$output .= '<a href="'.get_permalink($post->ID).'" class="btn btn-primary" title="'.get_the_title($post->ID).'">';
							$output .= $more_text_single;
							$output .= '</a>';
						}
					}
				$output .= '<div class="clear"></div>';
				$item_counter ++;
				$output .= '</li><!-- .entry (end) -->';
			}
			wp_reset_postdata(); // restore the global $post variable
			$output .= '</ul><!-- .recent-posts (end) -->';

			$output = apply_filters( 'cherry_plugin_shortcode_output', $output, $atts, $shortcodename );

			return $output;
		}
		add_shortcode('recent_posts', 'shortcode_recent_posts');
	}

	/**
	 * Post List
	 *
	 */
	if (!function_exists('posts_list_shortcode')) {

		function posts_list_shortcode( $atts, $content = null, $shortcodename = '' ) {
			extract(shortcode_atts(array(
				'type'         => 'post',
				'thumbs'       => '',
				'thumb_width'  => '',
				'thumb_height' => '',
				'post_content' => '',
				'numb'         => '5',
				'order_by'     => '',
				'order'        => '',
				'link'         => '',
				'link_text'    => __('Read more', CHERRY_PLUGIN_DOMAIN),
				'tag'          => '',
				'tags'         => '',
				'custom_class' => ''
			), $atts));

			// check what order by method user selected
			switch ($order_by) {
				case 'date':
					$order_by = 'post_date';
					break;
				case 'title':
					$order_by = 'title';
					break;
				case 'popular':
					$order_by = 'comment_count';
					break;
				case 'random':
					$order_by = 'rand';
					break;
			}

			// check what order method user selected (DESC or ASC)
			switch ($order) {
				case 'DESC':
					$order = 'DESC';
					break;
				case 'ASC':
					$order = 'ASC';
					break;
			}

			global $post;
			global $my_string_limit_words;
			global $_wp_additional_image_sizes;

			// WPML filter
				$suppress_filters = get_option('suppress_filters');

			$args = array(
				'post_type'        => $type,
				'tag'              => $tag,
				'numberposts'      => $numb,
				'orderby'          => $order_by,
				'order'            => $order,
				'suppress_filters' => $suppress_filters
			);

			$posts = get_posts($args);
			$i = 0;

			// thumbnail size
			$thumb_x = 0;
			$thumb_y = 0;
			if ($thumbs == 'large') {
				$thumb_x = 620;
				$thumb_y = 300;
			} else {
				$thumb_x = $_wp_additional_image_sizes['post-thumbnail']['width'];
				$thumb_y = $_wp_additional_image_sizes['post-thumbnail']['height'];
			}

			// thumbnail class
			$thumbs_class = '';
			if ($thumbs == 'large')
				$thumbs_class = 'large';

			$output = '<div class="posts-list '.$custom_class.'">';

			foreach($posts as $key => $post) {
				// Unset not translated posts
				if ( function_exists( 'wpml_get_language_information' ) ) {
					global $sitepress;

					$check              = wpml_get_language_information( $post->ID );
					$language_code      = substr( $check['locale'], 0, 2 );
					if ( $language_code != $sitepress->get_current_language() ) unset( $posts[$key] );

					// Post ID is different in a second language Solution
					if ( function_exists( 'icl_object_id' ) ) $post = get_post( icl_object_id( $post->ID, $type, true ) );
				}
				setup_postdata($post);
				$excerpt        = get_the_excerpt();
				$attachment_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
				$url            = $attachment_url['0'];
				if (($thumb_width != '') && ($thumb_height != ''))
					$image = aq_resize($url, $thumb_width, $thumb_height, true);
				else
					$image = aq_resize($url, $thumb_x, $thumb_y, true);

				$mediaType = get_post_meta($post->ID, 'tz_portfolio_type', true);
				$format = get_post_format();

					$output .= '<div class="row-fluid list-item-'.$i.'">';
					$output .= '<article class="span12 post__holder">';

						// post meta
						$output .= '<div class="post_meta">';								

						// post date
						$output .= '<span class="post_date">';
						$output .= '<time datetime="'.get_the_time('Y-m-d\TH:i:s', $post->ID).'">' . get_the_time("d") . '<small>' .get_the_time("M"). '</small></time>';
						$output .= '</span>';							

						$output .= '</div>';

						//post header
						$output .= '<header class="post-header">';
							$output .= '<h2 class="post-title"><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
								$output .= get_the_title($post->ID);
							$output .= '</a></h2>';
							
							$output .= cherry_get_post_networks(array('post_id' => $post_id, 'display_title' => false, 'output_type' => 'return'));
						$output .= '</header>';						

						//post thumbnail
						if ((has_post_thumbnail($post->ID)) && ($format == 'image' || $mediaType == 'Image')) {

							$output .= '<figure class="featured-thumbnail thumbnail '.$thumbs_class.'">';
							$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
							$output .= '<img src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
							$output .= '</a></figure>';

						} elseif ($mediaType != 'Video' && $mediaType != 'Audio') {

							$thumbid = 0;
							$thumbid = get_post_thumbnail_id($post->ID);
							$images = get_children( array(
								'orderby'        => 'menu_order',
								'order'          => 'ASC',
								'post_type'      => 'attachment',
								'post_parent'    => $post->ID,
								'post_mime_type' => 'image',
								'post_status'    => null,
								'numberposts'    => -1
							) );

							if ( $images ) {

								$k = 0;
								//looping through the images
								foreach ( $images as $attachment_id => $attachment ) {

									$image_attributes_t = wp_get_attachment_image_src( $attachment_id); // returns an array (thumbnail size)
									$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' ); // returns an array (full size)
									if (($thumb_width != '') && ($thumb_height != '')) {
										$img = aq_resize($image_attributes[0], $thumb_width, $thumb_height, true);  //resize & crop img
									} else {
										$img = aq_resize($url, $thumb_x, $thumb_y, true);
									}

									$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
									$image_title = $attachment->post_title;

									if ( $k == 0 ) {
										$output .= '<figure class="featured-thumbnail thumbnail '.$thumbs_class.'">';
										$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
										$output .= '<img  src="'.$img.'" alt="'.get_the_title($post->ID).'" />';
									}
									$output .= '</a></figure>';
									break;
								}
							} elseif (has_post_thumbnail($post->ID)) {
								$output .= '<figure class="featured-thumbnail thumbnail '.$thumbs_class.'">';
								$output .= '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
								if (($thumb_width != '') && ($thumb_height != ''))
									$output .= '<img src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
								else {
									if ($thumbs == 'normal') {
										$output .= get_the_post_thumbnail($post->ID);
									} else {
										$output .= '<img src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
									}
								}
								$output .= '</a></figure>';
							}
						} else {

							// for Video and Audio post format - no lightbox
							$output .= '<figure class="featured-thumbnail thumbnail '.$thumbs_class.'"><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
							if (($thumb_width != '') && ($thumb_height != ''))
								$output .= '<img src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
							else {
								if ($thumbs == 'normal') {
									$output .= get_the_post_thumbnail($post->ID);
								} else {
									$output .= '<img src="'.$image.'" alt="'.get_the_title($post->ID).'" />';
								}
							}
							$output .= '</a></figure>';
						}

						// post content
						if ($post_content != 'none' || $link == 'yes') {
							$output .= '<div class="post_content">';

							switch ($post_content){
								case 'excerpt':
									$output .= '<p class="excerpt">';
										$output .= my_string_limit_words(get_the_excerpt(), 30);
									$output .= '</p>';
									break;
								case 'content':
									$output .= '<div class="full-post-content">';
										$output .= get_the_content();
									$output .= '</div>';
									break;
								case 'none':
									break;

							}
							if($link == 'yes'){
								$output .= '<a href="'.get_permalink($post->ID).'" class="btn btn-primary" title="'.get_the_title($post->ID).'">';
								$output .= $link_text;
								$output .= '</a>';
							}
							$output .= '</div>';
						}

						//post footer
						if ($tags == 'yes') {
							$posttags = get_the_tags();
							if ($posttags) {
								$output .= '<footer class="post_footer">'.__('Tags', CHERRY_PLUGIN_DOMAIN).": ";
								  foreach($posttags as $tag) {
								    $output .= '<a href="'.get_tag_link($tag->term_id).'" rel="tag">'.$tag->name . '</a> ';
								 }
								 $output .= '</footer>';
							}
						}

						$output .= '</article>';
						$output .= '</div><!-- .row-fluid (end) -->';

						$i++;

				} // end foreach
				wp_reset_postdata(); // restore the global $post variable
			$output .= '</div><!-- .posts-list (end) -->';

			$output = apply_filters( 'cherry_plugin_shortcode_output', $output, $atts, $shortcodename );

			return $output;
		}
		add_shortcode('posts_list', 'posts_list_shortcode');

	}

	//------------------------------------------------------
	//  Related Posts
	//------------------------------------------------------
	if(!function_exists('cherry_related_posts')){
		function cherry_related_posts($args = array()){
			global $post;
			$default = array(
				'post_type' => get_post_type($post),
				'class' => 'related-posts',
				'class_list' => 'related-posts_list',
				'class_list_item' => 'related-posts_item',
				'display_title' => true,
				'display_link' => true,
				'display_thumbnail' => true,
				'width_thumbnail' => 170,
				'height_thumbnail' => 194,
				'before_title' => '<h3 class="related-posts_h">',
				'after_title' => '</h3>',
				'posts_count' => 4
			);
			extract(array_merge($default, $args));

			$post_tags = wp_get_post_terms($post->ID, $post_type.'_tag', array("fields" => "slugs"));
			$tags_type = $post_type=='post' ? 'tag' : $post_type.'_tag' ;
			$suppress_filters = get_option('suppress_filters');// WPML filter
			$blog_related = apply_filters( 'cherry_text_translate', of_get_option('blog_related'), 'blog_related' );
			if ($post_tags && !is_wp_error($post_tags)) {
				$args = array(
					"$tags_type" => implode(',', $post_tags),
					'post_status' => 'publish',
					'posts_per_page' => $posts_count,
					'ignore_sticky_posts' => 1,
					'post__not_in' => array($post->ID),
					'post_type' => $post_type,
					'suppress_filters' => $suppress_filters
					);
				query_posts($args);
				if ( have_posts() ) {
					$output = '<div class="'.$class.'">';
					$output .= $display_title ? $before_title.$blog_related.$after_title : '' ;
					$output .= '<ul class="'.$class_list.' clearfix">';
					$excerpt = get_the_excerpt();

					while( have_posts() ) {
						the_post();
						$thumb   = has_post_thumbnail() ? get_post_thumbnail_id() : PARENT_URL.'/images/empty_thumb.gif';
						$blank_img = stripos($thumb, 'empty_thumb.gif');
						$img_url = $blank_img ? $thumb : wp_get_attachment_url( $thumb,'full');
						$image   = $blank_img ? $thumb : aq_resize($img_url, $width_thumbnail, $height_thumbnail, true) or $img_url;

						$output .= '<li class="'.$class_list_item.'">';
						$output .= $display_thumbnail ? '<figure class="thumbnail featured-thumbnail"><a href="'.get_permalink().'" title="'.get_the_title().'"><img data-src="'.$image.'" alt="'.get_the_title().'" /></a></figure>': '' ;
						$output .= $display_link ? '<h3><a href="'.get_permalink().'" >'.get_the_title().'</a></h3>' : '' ;
						$output .= '<p class="excerpt">';
							$output .= my_string_limit_words($excerpt,9);
						$output .= '</p>';
						$output .= '</li>';
					}
					$output .= '</ul></div>';
					echo $output;
				}
				wp_reset_query();
			}
		}
	}

	/*-----------------------------------------------------------------------------------*/
	/* Custom Comments Structure
	/*-----------------------------------------------------------------------------------*/
	if ( !function_exists( 'mytheme_comment' ) ) {
		function mytheme_comment($comment, $args, $depth) {
			$GLOBALS['comment'] = $comment;
		?>
		<li <?php comment_class('clearfix'); ?> id="li-comment-<?php comment_ID() ?>">
			<div id="comment-<?php comment_ID(); ?>" class="comment-body clearfix">
				<div class="wrapper">
					<div class="comment-author vcard">
						<?php echo get_avatar( $comment->comment_author_email, 80 ); ?>
						<?php printf('<span class="author">%1$s</span>', get_comment_author_link()) ?>
					</div>
					<?php if ($comment->comment_approved == '0') : ?>
						<em><?php echo theme_locals("your_comment") ?></em>
					<?php endif; ?>
					<div class="extra-wrap">
						<?php comment_text(); ?>

						<div class="extra-wrap">
							<div class="reply">
								<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
							</div>
							<div class="comment-meta commentmetadata"><?php printf('%1$s', get_comment_date()) ?></div>
						</div>
					</div>
				</div>				
			</div>
	<?php }
	}
?>