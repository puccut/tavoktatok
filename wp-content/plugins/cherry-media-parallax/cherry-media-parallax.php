<?php
/*
  Plugin Name: Cherry MediaParallax Plugin
  Version: 1.0
  Plugin URI: http://www.cherryframework.com/
  Description: Create MediaParallax effect
  Author: Cherry Team.
  Author URI: http://www.cherryframework.com/
  Text Domain: cherry-media-parallax
  Domain Path: languages/
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
if ( ! defined( 'ABSPATH' ) )
exit;

class cherry_media_parallax {
  
  public $version = '1.0';

  function __construct() {
    add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
    add_shortcode( 'cherry_media_parallax', array( $this, 'media_parallax_shortcode' ) );
    add_shortcode( 'cherry_fixed_parallax', array( $this, 'fixed_parallax_shortcode' ) );
  }

  function assets() {
    //if ( is_front_page() ) {}
    wp_enqueue_script( 'chrome-smoothing-scroll', $this->url('js/smoothing-scroll.js'), array('jquery'), '1.0', true );
    wp_enqueue_script( 'cherry-media-parallax', $this->url('js/cherry-media-parallax.js'), array('jquery'), $this->version, true );
    wp_enqueue_script( 'cherry-fixed-parallax', $this->url('js/cherry-fixed-parallax.js'), array('jquery'), $this->version, true );
    wp_enqueue_style( 'cherry-media-parallax-styles', $this->url('css/cherry-media-parallax-styles.css'), '', $this->version );

    //wp_register_script( 'googlemapapis', '//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array('jquery'), false, false );
    //wp_enqueue_script( 'googlemapapis' );
  }

  /**
   * return plugin url
   */
  function url( $path = null ) {
    $base_url = untrailingslashit( plugin_dir_url( __FILE__ ) );
    if ( !$path ) {
      return $base_url;
    } else {
      return esc_url( $base_url . '/' . $path );
    }
  }

  /**
   * return plugin dir
   */
  function dir( $path = null ) {
    $base_dir = untrailingslashit( plugin_dir_path( __FILE__ ) );
    if ( !$path ) {
      return $base_dir;
    } else {
      return esc_url( $base_dir . '/' . $path );
    }
  }

  /**
   * Shortcode
   */
  function media_parallax_shortcode( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'typemedia'       => 'video_html', //video_html/image
            'src_mp4'         => 'xxx.mp4',
            'src_webm'        => 'xxx.webm',
            'src_ogv'         => 'xxx.ogv',
            'src_poster'      => 'source_poster.jpg',
            'buffer_ratio'    => '1.5',
            'invert_value'    => 'yes',
            'custom_class'    => ''
        ), $atts, 'cherry_media_parallax'));

    $rand_id = uniqid();

    $videosrc_mp4 = $src_mp4;
    $videosrc_webm = $src_webm;
    $videosrc_ogv = $src_ogv;
    $videosrc_poster = $src_poster;
    $buffer_ratio = floatval($buffer_ratio);
    $invert_value = $invert_value == 'yes' ? true : false;

    $sourcesList = array(
      "mp4"    => $videosrc_mp4,
      "webm"   => $videosrc_webm,
      "ogv"    => $videosrc_ogv,
      "poster" => $videosrc_poster
    );

    $sourcesCheck = array(
      "mp4"    => "false",
      "webm"   => "false",
      "ogv"    => "false",
      "poster" => "false"
    );

    $sourcesUrlList = array(
      "mp4"    => array(
        "url"    => "",
        "width"  => "",
        "height" => ""
      ),
      "webm"   => array(
        "url"    => "",
        "width"  => "",
        "height" => ""
      ),
      "ogv"    => array(
        "url"    => "",
        "width"  => "",
        "height" => ""
      ),
      "poster" => array(
        "url"    => "",
        "width"  => "",
        "height" => ""
      )
    );

    // WP_Query arguments
      $args = array(
        'post_status'    =>'inherit'
      , 'post_type'      => 'attachment'
      , 'posts_per_page' => -1
      );
    // The Query
    $attachment_query = new WP_Query( $args );

    if ( $attachment_query->have_posts() ) :
      while ( $attachment_query->have_posts() ) : $attachment_query->the_post();
          $post_id = $attachment_query->post->ID;
          $post_meta = get_post_meta( $post_id );
          $attachment_url = wp_get_attachment_url( $post_id );
          $attachment_meta = wp_get_attachment_metadata( $post_id );
          $filename = esc_html( wp_basename( $attachment_query->post->guid ) );
          
          foreach ($sourcesList as $key => $value) {
            if($value == $filename){
              //$sourcesUrlList[$key] = $attachment_url;

              $sourcesUrlList[$key]['url'] = $attachment_url;
              $sourcesUrlList[$key]['width'] = $attachment_meta['width'];
              $sourcesUrlList[$key]['height'] = $attachment_meta['height'];
              $sourcesCheck[$key] = 'true';
            }
          }

      endwhile;
    endif; // have_posts
    // Restore original Post Data
    wp_reset_postdata();

    $resutlOutput = '<script type="text/javascript">
          jQuery(function() {
              jQuery("#mediaparallax_'.$rand_id.'").cherryMediaParallax({
                bufferRatio: '. $buffer_ratio .'
              });
          });
    </script>';
    $resutlOutput .= '';
      switch ($typemedia) {
        case 'video_html':
            $resutlOutput .= '<section id="mediaparallax_' .$rand_id. '" class="parallax_section '.$custom_class.'" data-type-media="'. $typemedia .'" data-mp4="' .$sourcesCheck['mp4']. '" data-webm="' .$sourcesCheck['webm']. '" data-ogv="' .$sourcesCheck['ogv']. '" data-poster="' .$sourcesCheck['poster']. '">';
              $resutlOutput .= '<div class="container parallax_content">' .do_shortcode( $content ). '</div>';
                $resutlOutput .= '<div class="parallax_inner">';
                  $resutlOutput .= '<video class="parallax_media" data-base-width="' .$sourcesUrlList['mp4']['width']. '" data-base-height="' .$sourcesUrlList['mp4']['height']. '" poster="' .$sourcesUrlList['poster']['url']. '" autoplay loop>';
                    $resutlOutput .= '<source src="' .$sourcesUrlList['mp4']['url']. '" type="video/mp4">';
                    $resutlOutput .= '<source src="' .$sourcesUrlList['webm']['url']. '" type="video/webm">';  
                    $resutlOutput .= '<source src="' .$sourcesUrlList['ogv']['url']. '" type="video/ogg">';  
                  $resutlOutput .= '</video>';
                $resutlOutput .= '</div>';
              $resutlOutput .= '</section>';
        break;
        case 'video_youtube':
            $resutlOutput .= '<section id="mediaparallax_' .$rand_id. '" class="parallax_section '.$custom_class.'" data-type-media="'. $typemedia .'" data-poster="' .$sourcesCheck['poster']. '">';
              $resutlOutput .= '<div class="container parallax_content">' .do_shortcode( $content ). '</div>';
                $resutlOutput .= '<div class="parallax_inner">';
                $resutlOutput .= '</div>';
              $resutlOutput .= '</section>';
        break;
        case 'image':
            $resutlOutput .= '<section id="mediaparallax_' .$rand_id. '" class="parallax_section '.$custom_class.'" data-type-media="'. $typemedia .'">';
              $resutlOutput .= '<div class="container parallax_content">' .do_shortcode( $content ). '</div>';
              $resutlOutput .= '<div class="parallax_inner">';
                $resutlOutput .= '<image class="parallax_media" src="' .$sourcesUrlList['poster']['url']. '" data-base-width="' .$sourcesUrlList['poster']['width']. '" data-base-height="' .$sourcesUrlList['poster']['height']. '">';
              $resutlOutput .= '</div>';
            $resutlOutput .= '</section>';
        break;
      }

    $resutlOutput = apply_filters( 'cherry_plugin_shortcode_output', $resutlOutput, $atts, 'cherry_media_parallax' );
    return $resutlOutput;//result DOM
  }

  function fixed_parallax_shortcode( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'src_poster'      => 'source_poster.jpg',
            'offset_value'    => 'yes',
            'fixed_value'     => 'yes',
            'invert_value'    => 'yes',
            'custom_class'    => ''
        ), $atts, 'cherry_fixed_parallax'));

    $rand_id = uniqid();

    $offset_value = $offset_value == 'yes' ? 'true' : 'false';
    $fixed_value = $fixed_value == 'yes' ? 'true' : 'false';
    $invert_value = $invert_value == 'yes' ? 'true' : 'false';

    $image_url = '';

    // WP_Query arguments
      $args = array(
        'post_status'    =>'inherit'
      , 'post_type'      => 'attachment'
      , 'posts_per_page' => -1
      );
    // The Query
    $attachment_query = new WP_Query( $args );

    if ( $attachment_query->have_posts() ) :
      while ( $attachment_query->have_posts() ) : $attachment_query->the_post();
          $post_id = $attachment_query->post->ID;
          $post_meta = get_post_meta( $post_id );
          $attachment_url = wp_get_attachment_url( $post_id );
          $attachment_meta = wp_get_attachment_metadata( $post_id );
          $filename = esc_html( wp_basename( $attachment_query->post->guid ) );

          if($src_poster == $filename){
            $image_url = $attachment_url;
            $image_width = $attachment_meta['width'];
            $image_height = $attachment_meta['height'];

          }

      endwhile;
    endif; // have_posts
    // Restore original Post Data
    wp_reset_postdata();

    $resutlOutput = '';
    $resutlOutput .= '<script type="text/javascript">
          jQuery(function() {
              jQuery("#fixedparallax_'.$rand_id.'").cherryFixedParallax({
                offset: '.$offset_value.',
                bgfixed: '.$fixed_value.',
                invert: '.$invert_value.'
              });
          });
    </script>';

    $resutlOutput .= '<section id="fixedparallax_' .$rand_id. '" class="fixed_parallax_section '.$custom_class.'" data-source-url="'. $image_url .'" data-source-width="'. $image_width .'" data-source-height="'. $image_height .'">';
      $resutlOutput .= '<div class="container parallax_content">' .do_shortcode( $content ). '</div>';
    $resutlOutput .= '</section>';

    $resutlOutput = apply_filters( 'cherry_plugin_shortcode_output', $resutlOutput, $atts, 'cherry_fixed_parallax' );
    return $resutlOutput;//result DOM
  }

}

new cherry_media_parallax();
?>