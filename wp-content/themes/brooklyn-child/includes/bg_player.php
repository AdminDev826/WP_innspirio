<?php
if( !function_exists('ut_create_bg_videoplayer') ) :

    function ut_create_bg_videoplayer( $call = '' ) {
                
        global $detect;
        
        if( $detect->isMobile() ) {
            return;
        }
        
        $player = NULL;
        $video_url = NULL;
        $youtube = false;
        $custom = false;
        $selfhosted = false;        
        $containment = ( ut_return_hero_config('ut_video_containment' , 'hero') == 'body' ) ? 'body' : '#ut-hero';
                          
        /* only create player for desktop devices */
        if( !$detect->isMobile() ) : 
                                                  
            /* check if youtube is active */
            if( ut_return_hero_config('ut_video_source' , 'youtube') == 'youtube' ) {
                $youtube = true;
            }
            
            /* check if youtube is active */
            if( ut_return_hero_config('ut_video_source' , 'youtube') == 'custom' ) {
                $custom = true;
            } 
                        
            /* check if selfhosted is active */
            if( ut_return_hero_config('ut_video_source' , 'youtube') == 'selfhosted' ) {
                $selfhosted = true;
            }           

            /* conditional to prevent selfhosted video displaying inside hero if it has been set to background */
            if( $selfhosted && ut_return_hero_config('ut_video_containment', 'hero') == 'body' && $call == 'section' ) {
                return;
            }
            
            /* conditional to prevent selfhosted video displaying inside the background if it has been set to hero */
            if( $selfhosted && ut_return_hero_config('ut_video_containment', 'hero') == 'hero' && $call == 'body' ) {
                return;
            }            
                                        
            if( $youtube ) {
                                
                $video_url = ut_return_hero_config('ut_video_url');
                
                if( !empty($video_url) ) :
                    
                    $muted   = ut_return_hero_config('ut_video_mute_state' , "off");
					$muted   = ($muted == 'off') ? 'mute : true' : 'mute : false';
                    $volume  = ut_return_hero_config('ut_video_volume' , "5") ;
                    $volume  = ($muted == 'off') ? 'vol : 0' : 'vol: ' . $volume;
                    $loop    = ut_return_hero_config('ut_video_loop' , "on") ;
                    $loop    = ($loop == 'on') ? 'loop : true' : 'loop : false';
                    
                    /* build player */
                    $player .= '<a id="ut-background-video-hero" class="ut-video-player" data-property="{ quality: \'highres\', videoURL : \'' . $video_url . '\' , containment : \'' . $containment . '\', showControls: false, autoPlay : true, '.$loop.', '.$muted.', '.$volume.', startAt : 0, opacity : 1}"></a>';                        
                        
                    return $player;
                
                endif;
            
            } 
            
            if( $selfhosted )  {
                
                $mp4 = $ogg = $webm = NULL;
                
                $mp4  = ut_return_hero_config('ut_video_mp4');
                $ogg  = ut_return_hero_config('ut_video_ogg');
                $webm = ut_return_hero_config('ut_video_webm');
                                                
                if( !empty($mp4) || !empty($ogg) || !empty($webm) ) :
                    
                    $volume  = ut_return_hero_config('ut_video_volume' , "5") ;
                    $muted   = ut_return_hero_config('ut_video_mute_state' , "off");
                    $muted   = ($muted == 'off') ? 'muted' : '';
                    $loop    = ut_return_hero_config('ut_video_loop' , "on") ;
                    $loop    = ($loop == 'on') ? 'loop' : '';
                    $preload = ut_return_hero_config('ut_video_preload' , "on") ;
                    $preload = ($loop == 'on') ? 'preload="auto"' : '';
                                                    
                    $player .= '<div class="ut-video-container"><video id="ut-video-hero" class="ut-selfvideo-player" autoplay '.$loop.' '.$muted.' '.$preload.' volume="'.$volume.'" autobuffer controls>';
                    
                        if( !empty( $mp4 ) ) :
                            
                            $player .= '<source src="' . $mp4 . '" type="video/mp4"> ';
                            
                        endif;
                        
                        if( !empty( $webm ) ) :
                            
                            $player .= '<source src="' . $webm . '" type="video/webm"> ';
                            
                        endif;    
                        
                        if( !empty( $ogg ) ) :
                            
                            $player .= ' <source src="' . $ogg . '" type="video/ogg ogv">';
                                            
                        endif;
                    
                    $player .= '</video></div><div class="ut-video-spacer"></div>';
                    
                    return $player;    
                
                endif; /* check for player files */
            
            }
            
            if( $custom )  {
                
                $video_embedded = ut_return_hero_config('ut_video_url_custom');
                $player .= do_shortcode($video_embedded);
                
            }
                    
        endif;
        
        return $player;
        
    }
    
endif;
