<?php
/**
 * Weatherbox Body and Post Class filters
 *
 * @package weatherbox
 */


if ( ! function_exists( 'weatherbox_body_class' ) ) :
  /**
   * Some extra classes for the body.
   *
   * @param $classes
   *
   * @return $classes
   *
   * @since 0.1.0
   */

  function weatherbox_body_class( $classes ) {
    global $post;

    $postType = ( get_query_var( 'post_type' ) ) ? get_query_var( 'post_type' ) : 1;

    if ( is_page() )
      $classes[] = $post->post_type . '-' . $post->post_name;

    if ( is_page() && $post->post_parent > 0 )
      $classes[] = 'parent-page-' . basename( get_permalink( $post->post_parent ) );

    if ( is_home() || is_search() )
      $classes[] = 'archive';

    return $classes;
  }
endif; // weatherbox_body_class

add_filter( 'body_class', 'weatherbox_body_class' );


if ( ! function_exists( 'weatherbox_post_class' ) ) :
  /**
   * Some extra classes for posts.
   *
   * @param $classes
   *
   * @return $classes
   *
   * @since 0.1.0
   */

  function weatherbox_post_class( $classes ) {
    global $post;
    $fields = ( function_exists( 'get_fields' ) ) ? get_fields( $post->ID ) : null;

    if ( !empty( $fields[ 'gallery' ] ) || has_post_thumbnail( $post->ID ) )
      $classes[] = 'has-post-img';

    if ( in_array( 'weatherbox_project', $classes ) )
      $classes = str_replace( 'weatherbox_project', 'project', $classes );

    return $classes;
  }
endif; // weatherbox_post_class

add_filter( 'post_class', 'weatherbox_post_class' );


if ( ! function_exists( 'weatherbox_wp_nav_menu_args' ) ) :

  /**
   * Better defaults for wp_nav_menu
   *
   * @param $args (array)
   *
   * @return $args (array)
   *
   * @since 0.1.0
   */

  function weatherbox_wp_nav_menu_args( $args = '' ) {

    // Always nav, never div
    $args['container'] = 'nav';
    $args['container_class'] = 'navigation-menu';

    if ( 'Social' == $args['menu']->name ) :

      // Except for the social menu, because it's not navigation
      $args['container'] = 'div';

    endif;

    return $args;
  }

endif; // excerpt_length

add_filter( 'wp_nav_menu_args', 'weatherbox_wp_nav_menu_args' );


if ( ! function_exists( 'weatherbox_front_page_projects' ) ) :

  /**
   * Better defaults for wp_nav_menu
   *
   * @param $args (array)
   *
   * @return $args (array)
   *
   * @since 0.1.0
   */

  function weatherbox_front_page_projects( $query ) {

    if ( is_admin())
    	return;

	if ( $query->is_home() && $query->is_main_query() ) {
		$query->set( 'post_type', 'weatherbox_project' );
    $query->set( 'posts_per_page', -1 );

	}

    return $query;
  }

endif; // weatherbox_front_page_projects

add_action( 'pre_get_posts', 'weatherbox_front_page_projects' );
