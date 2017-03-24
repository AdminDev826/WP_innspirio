<?php
require_once('includes/paralax.php');
require_once('includes/bg_player.php');

if( !function_exists( 'autoplay_vimeo_oembed' ) ) {

    function autoplay_vimeo_oembed( $provider, $url, $args ) {

        if (strpos($provider, 'vimeo')!==FALSE) {
            $provider = esc_url_raw( add_query_arg('autoplay', 0, $provider) );
        }
        return $provider;

    }
    add_filter('oembed_fetch_url', 'autoplay_vimeo_oembed', 100000, 3);

}

add_filter( 'woocommerce_get_item_data', 'wc_checkout_description_so_15127954', 10, 2 );

function wc_checkout_description_so_15127954( $other_data, $cart_item )
{
    $post_data = get_post( $cart_item['product_id'] );
    $other_data[] = array( 'name' =>  nl2br($post_data->post_excerpt ));
    return $other_data;
}

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
function login_redirect( $redirect_to, $request, $user ){
    return home_url();
}
add_filter( 'login_redirect', 'login_redirect', 10, 3 );

/* logout code */
add_action('wp_logout','wppbc_redirect_logout');
function wppbc_redirect_logout(){
	wp_redirect( home_url() );
	exit();
}




if( !function_exists('ut_quote_alt_override') ) {

	function ut_quote_alt_override( $atts, $content ){

		extract(shortcode_atts(array(
      'author' => '',
      'image' => '',
      'linkedin' => '',
      'position' => ''
		), $atts));

    $quote = '<li><i class="ut-rq-icon fa fa-quote-right"></i><div class="img-cont"><a class="fa fa-linkedin fa-lg" href="'.$linkedin.'"></a><img src="'.$image.'" /></div><h2 class="ut-quote-comment">' . do_shortcode( $content ) . '</h2><img class="img-mobile" src="'.$image.'" /><span class="v-center"><span class="ut-quote-name">' . $author . ' - '.$position.'</span></span></li>';

		return $quote;

	}

	remove_shortcode( 'ut_quote_alt', 'ut_quote_alt' );
  add_shortcode( 'ut_quote_alt', 'ut_quote_alt_override' );

}

function partners_logos( $atts ) {
  $atts = shortcode_atts( array(
    'urls' => ''
  ), $atts, 'partners-logos' );
  //return '';
  $return = '';
  if ($atts['urls']) {
     wp_enqueue_script( 'slick-script',  get_stylesheet_directory_uri(). '/slick/js/slick.min.js', array(), '1.0.0', true );
     wp_enqueue_script( 'easing-script',  get_stylesheet_directory_uri(). '/slick/js/jquery.easing.min.js', array(), '1.0.0', true );
     wp_enqueue_script( 'waypoints-script',  get_stylesheet_directory_uri(). '/slick/js/waypoints.min.js', array(), '1.0.0', true );
     wp_enqueue_script( 'slick-plugins',  get_stylesheet_directory_uri(). '/slick/js/plugins.js', array(), '1.0.0', true );
     wp_enqueue_style( 'slick-style',  get_stylesheet_directory_uri(). '/slick/css/slick.css');
     wp_enqueue_style( 'slick-styletheme',  get_stylesheet_directory_uri(). '/slick/css/slick-theme.css');
     wp_enqueue_style( 'slick-stylecustom',  get_stylesheet_directory_uri(). '/slick/css/style17.css');
     wp_enqueue_style( 'slick-responsive',  get_stylesheet_directory_uri(). '/slick/css/responsive.css');
     add_action( 'wp_footer', 'slick_js', 100 );
    ob_start();
    ?>
    <div class="slick-fullwidthcontent" style="position: relative; min-height: 100px; ">
      <div class="slick-fullwidth" style="position: absolute;">
        <div class="slick-innercontent" style="width: 90%; margin: auto;">
          <div class="w-clients-list slick-loading one-time responsive" data-columns="5" data-autoPlay="0" data-autoPlaySpeed="1000">
              <?php if ($atts['urls']) {
                $urls = explode(',', $atts['urls']);
                $i  = 0;
                foreach ($urls as $url) {
                  echo '<div class="w-clients-item"><span class="w-clients-item-h"><img data-lazy="'.$url.'"></span></div>';
                }
              } ?>
          </div>
        </div>
      </div>
    </div>
    <?php
    $return = ob_get_contents();
    ob_clean();
  }
  return $return;
}
add_shortcode( 'partners-logos', 'partners_logos' );

function slick_js()
{
  echo '<script type="text/javascript">
      brand_logo();
      function brand_logo() {
        if (jQuery(window).width() > 960) {
          var leftposition = ( jQuery(window).width() - jQuery(".grid-70").width() ) / 2 ;
          jQuery(".slick-fullwidth").css("left", "-"+leftposition+"px");
          jQuery(".slick-fullwidth").css("width", jQuery(window).width());
        }
      }

      jQuery(window).resize(function () {
        brand_logo();
      });
      /*jQuery(document).ready(function(e) {*/


        jQuery(".one-time").slick({
        dots: false,
        slidesToShow: 5,
        slidesToScroll: 1,
        touchMove: false,
        // dots: true,
              infinite: true,
              speed: 300,
              slidesToShow: 5,
              slidesToScroll: 1,
              responsive: [{
                  breakpoint: 1024,
                  settings: {
                      slidesToShow: 6,
                      slidesToScroll: 1,
                      // centerMode: true,

                  }

              }, {
                  breakpoint: 800,
                  settings: {
                      slidesToShow: 3,
                      slidesToScroll: 2,
                      dots: false,
                      infinite: true,

                  }


              }, {
                  breakpoint: 600,
                  settings: {
                      slidesToShow: 2,
                      slidesToScroll: 2,
                      dots: false,
                      infinite: true,

                  }
              }, {
                  breakpoint: 480,
                  settings: {
                      slidesToShow: 1,
                      slidesToScroll: 1,
                      dots: false,
                      infinite: true,
                      autoplay: true,
                      autoplaySpeed: 2000,
                  }
              }]
        });
    /*});*/
    </script>';
}
?>
