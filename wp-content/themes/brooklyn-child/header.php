<?php

/*
 * The header for our theme
 * by www.unitedthemes.com
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<!--
##########################################################################################

BROOKLYN THEME BY UNITED THEMES 
WWW.UNITEDTHEMES.COM

BROOKLYN THEME DESIGNED BY MARCEL MOERKENS
BROOKLYN THEME DEVELOPED BY MARCEL MOERKENS & MATTHIAS NETTEKOVEN 

POWERED BY UNITED THEMES - WEB DEVELOPMENT FORGE EST.2011

COPYRIGHT 2011 - 2015 ALL RIGHTS RESERVED BY UNITED THEMES

##########################################################################################
-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1, maximum-scale=1">
  
    <?php ut_meta_hook(); //action hook, see inc/ut-theme-hooks.php ?>
    
        
    <?php if ( defined('WPSEO_VERSION') ) : ?>
		
        <!-- Title -->
        <title><?php wp_title(); ?></title>

	<?php else : ?>
    	
   		<?php ut_meta_theme_hook(); ?>
    
    <?php endif; ?>
    
    <!-- RSS & Pingbacks -->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <link rel="profile" href="http://gmpg.org/xfn/11">
    
    <!-- Favicon -->
	<?php if( ot_get_option( 'ut_favicon' ) ) : ?>
        
        <?php 
        
        /* get icon info */
        $favicon = ot_get_option( 'ut_favicon' );
        $favicon_info = pathinfo( $favicon ); 
        $type = NULL;
        
        if( isset($favicon_info['extension']) && $favicon_info['extension'] == 'png' ) {
            $type = 'type="image/png"';
        }
        
         if( isset($favicon_info['extension']) && $favicon_info['extension'] == 'ico' ) {
            $type = 'type="image/x-icon"';
        }
        
         if( isset($favicon_info['extension']) && $favicon_info['extension'] == 'gif' ) {
            $type = 'type="image/gif"';
        }
        
        ?>
                
        <link rel="shortcut&#x20;icon" href="<?php echo $favicon; ?>" <?php echo $type; ?> />
        <link rel="icon" href="<?php echo $favicon; ?>" <?php echo $type; ?> />
        
    <?php endif; ?>
    
    <!-- Apple Touch Icons -->    
    <?php if( ot_get_option( 'ut_apple_touch_icon_iphone' ) ) :?>
    <link rel="apple-touch-icon" href="<?php echo ot_get_option( 'ut_apple_touch_icon_iphone' ); ?>">
    <?php endif; ?>
    
    <?php if( ot_get_option( 'ut_apple_touch_icon_ipad' ) ) : ?>
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo ot_get_option( 'ut_apple_touch_icon_ipad' ); ?>" />
    <?php endif; ?>
    
    <?php if( ot_get_option( 'ut_apple_touch_icon_iphone_retina' ) ) : ?>
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo ot_get_option( 'ut_apple_touch_icon_iphone_retina' ); ?>" />
    <?php endif; ?>
    
    <?php if( ot_get_option( 'ut_apple_touch_icon_ipad_retina' ) ) :?>
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo ot_get_option( 'ut_apple_touch_icon_ipad_retina' ); ?>" />
    <?php endif; ?>
        
    <!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]--> 
    	
    <?php wp_head(); ?>
     <!-- <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
   <script>
   	$(document).ready(function(e) {
        alert($( window ).width());
    });
   </script>-->
		<script src="/wp-content/themes/brooklyn-child/js/app.js"></script>
    <script src="/wp-content/themes/brooklyn-child/js/verge.min.js"></script>
    <script src="/wp-content/themes/brooklyn-child/js/jquery.scrollTo.min.js"></script>

</head>
 <style>
 @media screen and (-webkit-min-device-pixel-ratio:0) {
	 .parallax-overlay{
		 top: -1px;
	 }
 } 
 		.lead, .taxonomy-description {
    font-family: ralewayregular, "Helvetica Neue", Helvetica, Arial, sans-serif !important;
}
		.v_poster{ display:none}
		@media only screen and (max-width: 1024px) {
				
				 .v_poster{    display: block;
    float: left;
    background-size: cover;
    height: 500px;
    background-repeat: no-repeat;
	background-position: center center;
    width: 100%;}
				 .grid-container{ padding:0}
				 .ut-video-container{ display:none}
				 .ut-hero-video-position{ top:0 !important}
				 .hero{ height:auto; min-height:auto}
				 #ut-video-hero-control{ display:none}
			}
			@media only screen and (width: 414px) {
				 .hero{ height:500px !important; min-height:500px !important}
			}
			@media only screen and (max-width: 375px) {
				 .hero{ height:500px !important; min-height:500px !important}
			}
		@media only screen and (max-width: 360px) {
				 .hero{ height:500px !important; min-height:500px !important}
			}
			@media only screen and (width: 320px) {
				 .hero{ height:500px !important; min-height:500px !important}
			}
			@media only screen and (max-width: 300px) {
				 .hero{ height:500px !important; min-height:500px !important}
			}
	</style>
<?php 

/*
|--------------------------------------------------------------------------
| Needed Settings
|--------------------------------------------------------------------------
*/

$scrollto 		= ot_get_option('ut_scrollto_effect');
$scrollto 		= !empty( $scrollto['easing'] ) ? $scrollto['easing'] : 'linear' ;
$scrollspeed 	= ot_get_option('ut_scrollto_speed'  , '650'); 

$ut_site_border_body_class = ot_get_option( 'ut_site_border', 'hide' ) == 'show' ? 'ut-site-border' : '';
$ut_site_top_header_body_class = ot_get_option( 'ut_top_header', 'hide' ) == 'show' ? 'ut-has-top-header' : '';
$ut_site_border_header_class = ot_get_option( 'ut_site_border', 'hide' ) == 'show' ? 'bordered-navigation' : '';
$ut_top_header_class = ot_get_option( 'ut_top_header' , 'hide' ) == 'show' ? 'bordered-top' : '';

?>

<body id="ut-sitebody" <?php body_class( $ut_site_border_body_class . ' ' .  $ut_site_top_header_body_class ); ?> data-scrolleffect="<?php echo $scrollto; ?>" data-scrollspeed="<?php echo $scrollspeed; ?>">

<a class="ut-offset-anchor" id="top" style="top:0px !important;"></a>

<?php 

/*
|--------------------------------------------------------------------------
| Pre Loader Overlay
|--------------------------------------------------------------------------
*/

if( ot_get_option('ut_use_image_loader') == 'on' ) : 
	
	if( ut_dynamic_conditional('ut_use_image_loader_on') ) : ?>
	
	<div class="ut-loader-overlay"></div>

	<?php endif; ?>

<?php endif; ?>


<?php ut_before_header_hook(); // action hook, see inc/ut-theme-hooks.php ?> 


<?php

/*
|--------------------------------------------------------------------------
| Navigation Setting
|--------------------------------------------------------------------------
*/

/* skin */
$ut_navigation_skin = ot_get_option('ut_navigation_skin' , 'ut-header-light');

/* visibility */
$headerstate = NULL;

if( is_home() || is_front_page() || is_singular('portfolio') || get_post_meta( get_the_ID() , 'ut_activate_page_hero' , true ) == 'on' ) {
	
	if( ot_get_option('ut_navigation_state' , 'off') == 'off' ) {
		$headerstate = 'ha-header-hide';
	}

}

/* width */
$navigation_width = ot_get_option('ut_navigation_width' , 'centered');
$logo_push = ( $navigation_width == 'fullwidth' ) ? 'push-5' : '';
$navigation_pull = ( $navigation_width == 'fullwidth' ) ? 'pull-5' : '';
			
/* main navigation settings*/
$mainmenu = array('echo'             => false,
                  'container'        => 'nav',
                  'container_id'     => 'navigation',
                  'fallback_cb' 	 => 'ut_default_menu',
                  'container_class'  => 'grid-60 hide-on-tablet hide-on-mobile ' . $navigation_pull ,
                  'items_wrap'       => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                  'theme_location'   => 'primary', 
                  'walker'           => new ut_menu_walker()
);

/* mobile navigation settings */						 
$mobilemenu = array('echo'              => false,
                    'container'        	=> 'nav',
                    'container_id'    	=> 'ut-mobile-nav',
                    'menu_id'		   	=> 'ut-mobile-menu',
                    'menu_class'	   	=> 'ut-mobile-menu',
                    'fallback_cb' 	   	=> 'ut_default_menu',
                    'container_class'  	=> 'ut-mobile-menu mobile-grid-100 tablet-grid-100 hide-on-desktop',
                    'items_wrap'       	=> '<div class="ut-scroll-pane"><ul id="%1$s" class="%2$s">%3$s</ul></div>',
                    'theme_location'   	=> 'primary', 
                    'walker'           	=> new ut_menu_walker()
);

/* check if current page has an option tp show a hero */
$ut_activate_page_hero = get_post_meta( get_the_ID() , 'ut_activate_page_hero' , true );                    				

?>

<?php get_template_part( 'partials/top', 'header' ); ?> 

<!-- header section -->
<header id="header-section" class="ha-header <?php echo $ut_site_border_header_class; ?> <?php echo $ut_top_header_class; ?> <?php echo $navigation_width; ?> <?php echo ( ot_get_option('ut_navigation_state' , 'off') == 'on_transparent' && ( is_home() || is_front_page() || is_singular('portfolio') || ( is_page() && $ut_activate_page_hero == 'on' ) ) ) ? 'ha-transparent' : $ut_navigation_skin; ?> <?php echo $headerstate; ?>">
            
    <?php if( $navigation_width == 'centered' ) :?>
    
    <div class="grid-container">
    
	<?php endif; ?>	
        
        <div class="ha-header-perspective">
        	<div class="ha-header-front">
            	
                <div class="grid-20 tablet-grid-30 mobile-grid-30 <?php echo $logo_push; ?>">
                
					<?php if ( get_theme_mod( 'ut_site_logo' ) ) : ?>
                        
                        <?php 
                        
                        $sitelogo = !is_front_page() && !is_home() && ( $ut_activate_page_hero == 'off' || empty($ut_activate_page_hero) ) ? get_theme_mod( 'ut_site_logo_alt' ) : get_theme_mod( 'ut_site_logo' );                        
                        
                        $alternate_logo = get_theme_mod( 'ut_site_logo_alt' ) ? get_theme_mod( 'ut_site_logo_alt' ) : get_theme_mod( 'ut_site_logo' ) ;?>
                        
                        <div class="site-logo">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img data-altlogo="<?php echo $alternate_logo; ?>" src="<?php echo $sitelogo; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a>
                        </div>
                        
                    <?php else : ?>
                    
                    	<div class="site-logo">
                        	<h1 class="logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                        </div>
                        
                    <?php endif; ?>             	
                
                </div> 
                <div class="grid-20 tablet-grid-60 mobile-grid-60 <?php echo $logo_push; ?>">
                <?php 
                        
                        $social = ot_get_option('ut_footer_social_icons');
                        
                        if( is_array( $social ) && !empty( $social ) ) {
                            
                            echo '<ul class="ut-footer-so">';    
                                
                                foreach( $social as $icon => $value) {
                                    
                                    $link  = !empty( $value["link"] )  ? esc_url( $value["link"] ) : '#' ;
                                    $title = !empty( $value["title"] ) ? 'title="' . esc_attr( $value["title"] ) . '"' : '' ;
                                    
                                    echo '<li>';
                                        echo '<a '.$title.' href="'.$link.'"><i class="fa '.$value["icon"].' fa-lg"></i></a>';
                                    echo '</li>';
                                    
                                }
                            
                            echo '</ul>';    
                        
                        } 
                        
                        unset($social);
                        
                    ?>   
                </div>
                <?php
                
                /* main and mobile menu cache */
                if( ot_get_option('ut_use_cache' , 'off') == 'on' ) {
                    
                    $language_prefix =  defined('ICL_LANGUAGE_CODE') ? '_' . ICL_LANGUAGE_CODE : '';
                    
                    $main_menu      = get_transient('ut_main_menu' . get_the_ID() . $language_prefix );
                    $mobile_menu    = get_transient('ut_mobile_menu' . get_the_ID() . $language_prefix  );
                    $cacheTime      = ot_get_option('ut_cache_ltime' , '10');
                    
                    if ($main_menu === false) {
                        
                        $main_menu = wp_nav_menu( $mainmenu );                        
                        set_transient('ut_main_menu' . get_the_ID() . $language_prefix , $main_menu, 60*$cacheTime);
                        
                    } 
                    
                    if ($mobile_menu === false) {
                        
                        $mobile_menu = wp_nav_menu( $mobilemenu );
                        set_transient('ut_mobile_menu' . get_the_ID() . $language_prefix  , $mobile_menu, 60*$cacheTime);
                        
                    } 
                                       
                    
                } else {
                    
                    $main_menu   = wp_nav_menu( $mainmenu );
                    $mobile_menu = wp_nav_menu( $mobilemenu );
                    
                } ?>                
                
                <?php if ( has_nav_menu( 'primary' ) ) : ?>
                	
					<?php echo $main_menu; ?>
                    
                    <div class="ut-mm-trigger tablet-grid-10 mobile-grid-10 hide-on-desktop">
                    	<button class="ut-mm-button"></button>
                    </div>
                    
					<?php echo $mobile_menu; ?>
                                        
                <?php endif; ?>
                                                        
                </div>
            </div><!-- close .ha-header-perspective -->
    
	<?php if( $navigation_width == 'centered') :?>        
	
    </div> 
    
    <?php endif; ?>
    
</header><!-- close header -->

<div class="clear"></div>

<?php get_template_part( 'template-part', 'hero' ); ?>       

<?php ut_before_content_hook(); // action hook, see inc/ut-theme-hooks.php ?>

<div id="main-content" class="wrap ha-waypoint" data-animate-up="ha-header-hide" data-animate-down="ha-header-small">
	
    <a class="ut-offset-anchor" id="to-main-content"></a>
		
        <div class="main-content-background">

