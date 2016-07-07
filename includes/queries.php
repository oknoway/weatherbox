<?php
/**
 * Weatherbox Special Queries
 *
 * @package weatherbox
 */

if ( ! function_exists( 'home_featured_posts' ) ) :
  /**
   * Return X posts.
   *
   * @uses WP_Query
   *
   * @since 0.1.0
   */
   
  function home_featured_posts() {
    
    $home_featured_posts_args = array(
      
    );
    
    return new WP_Query( $home_featured_posts_args );

  }

endif; //home_featured_posts
