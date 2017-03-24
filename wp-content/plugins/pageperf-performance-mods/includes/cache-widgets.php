<?php
  add_filter( 'widget_display_callback', 
    function($instance, $widget, $args){

      $a = $widget;
      $b = $widget;

      $widget_key = md5( 
        serialize(
          array_merge(
            $instance,
            $args,
            array('changemetoresettransient'),
            array($_SERVER['REQUEST_URI'])
      )));

      if ( ! has_filter('pageperf_cache_widgets') ) {
        return $instance;
      }



      $cache_this = apply_filters('pageperf_cache_widgets',
        array());

      if (! in_array( $args['widget_id'], $cache_this ))
      {
        return $instance;
      }

      if ( $cached_outout = get_transient( $widget_key ) )
      {
        echo $cached_outout;
        return false;
      }
      else {
        ob_start();
        //this renders the widget
        $widget->widget( $args, $instance );
        //get rendered widget from buffer
        $cached_widget = ob_get_clean();

        set_transient( $widget_key, $cached_widget, 60*60*24*7);

        echo $cached_widget;
        return false;
      }

      return $instance;

  }, 10, 3 );
