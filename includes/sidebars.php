<?php
/**
 *  Register Sidebars.
 *
 * @package WordPress
 * @subpackage weatherbox
 */


/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function weatherbox_widgets_init() {
  register_sidebar( array(
    'name'          => esc_html__( 'Sidebar', 'weatherbox' ),
    'id'            => 'sidebar-1',
    'description'   => '',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h1 class="widget-title">',
    'after_title'   => '</h1>',
  ) );

  register_sidebar( array(
    'name'          => esc_html__( 'Footer Widgets', 'weatherbox' ),
    'id'            => 'footer-widgets',
    'description'   => '',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h5 class="widget-title">',
    'after_title'   => '</h5>',
  ) );
}
add_action( 'widgets_init', 'weatherbox_widgets_init' );
