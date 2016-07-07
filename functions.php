<?php
/**
 * Weatherbox functions and definitions
 *
 * @package weatherbox
 */

if ( ! function_exists( 'weatherbox_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function weatherbox_setup() {

  // Define VERSION if not using wp.scaffold
  if ( !defined( 'VERSION' ) ) :
    global $wp_version;
    define( 'VERSION', $wp_version );
  endif;

  /*
   * Make theme available for translation.
   * Translations can be filed in the /languages/ directory.
   * If you're building a theme based on weatherbox, use a find and replace
   * to change 'weatherbox' to the name of your theme in all the template files
   */
  load_theme_textdomain( 'weatherbox', get_template_directory() . '/languages' );

  // Add default posts and comments RSS feed links to head.
  add_theme_support( 'automatic-feed-links' );

  /*
   * Let WordPress manage the document title.
   * By adding theme support, we declare that this theme does not use a
   * hard-coded <title> tag in the document head, and expect WordPress to
   * provide it for us.
   */
  add_theme_support( 'title-tag' );

  /*
   * Enable support for Post Thumbnails on posts and pages.
   *
   * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
   */
  add_theme_support( 'post-thumbnails' );

  add_post_type_support( 'attachment:audio', 'thumbnail' );
  add_post_type_support( 'attachment:video', 'thumbnail' );

  // This theme uses wp_nav_menu() in one location.
  register_nav_menus( array(
    'primary' => esc_html__( 'Primary Menu', 'weatherbox' ),
  ) );

  /*
   * Switch default core markup for search form, comment form, and comments
   * to output valid HTML5.
   */
  add_theme_support( 'html5', array(
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
  ) );

  /*
   * Enable support for Post Formats.
   * See http://codex.wordpress.org/Post_Formats
   */
  add_theme_support( 'post-formats', array(
    'aside',
    'image',
    'video',
    'quote',
    'link',
  ) );

  /**
   * Custom image sizes
   */
   add_image_size( 'project_hero', 1000, 2000, false );
   add_image_size( 'project_large', 750, 1500, false );
   add_image_size( 'project_medium', 500, 1000, false );
   add_image_size( 'project_small', 250, 500, false );
   add_image_size( 'project_thumb', 150, 300, false );

  /**
   * Remove extraneous things
   */
  add_action( 'wp_head', 'remove_widget_action', 1);
  remove_action( 'wp_head', 'rsd_link' );
  remove_action( 'wp_head', 'wlwmanifest_link' );
  remove_action( 'wp_head', 'index_rel_link' );
  remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
  remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
  remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
  remove_action( 'wp_head', 'feed_links_extra', 3 );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
  remove_filter( 'the_content', 'prepend_attachment' );

  function remove_widget_action() {
    global $wp_widget_factory;

    remove_action( 'wp_head', array($wp_widget_factory->widgets[ 'WP_Widget_Recent_Comments' ], 'recent_comments_style') );
  }

  function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
      return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
      return array();
    }
  }
}
endif; // weatherbox_setup

add_action( 'after_setup_theme', 'weatherbox_setup' );


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function weatherbox_content_width() {
  $GLOBALS[ 'content_width' ] = apply_filters( 'weatherbox_content_width', 1280 );
}
add_action( 'after_setup_theme', 'weatherbox_content_width', 0 );


if ( ! function_exists( 'weatherbox_scripts_styles' ) ) :
  /**
   * Enqueue scripts and styles.
   *
   * @uses wp_enqueue_script
   * @uses wp_enqueue_style
   *
   * @since 0.1.0
   */

  function weatherbox_scripts_styles() {

    if ( !is_admin() ) {

      wp_enqueue_style( 'weatherbox-style', get_template_directory_uri() . "/assets/css/weatherbox.css", array(), VERSION, 'all' );

      wp_enqueue_script( 'weatherbox-head', get_template_directory_uri() . "/assets/js/head.js", array(), VERSION, false );
      wp_enqueue_script( 'weatherbox', get_template_directory_uri() . "/assets/js/weatherbox.js", array(), VERSION, true );

      $wpURLs = array(
        'template_directory_uri' => get_template_directory_uri(),
        'stylesheet_directory_uri' => get_stylesheet_directory_uri(),
        'siteurl' => get_option( 'siteurl' ),
        'wpurl' => get_bloginfo( 'wpurl' ),
        'url' => get_bloginfo( 'url' ),
      );

      wp_localize_script( 'weatherbox', 'wpURLs', $wpURLs );

    }
  }

endif; //weatherbox_scripts_styles

add_action( 'wp_enqueue_scripts', 'weatherbox_scripts_styles' );


if ( ! function_exists( 'weatherbox_admin_scripts_styles' ) ) :
  /**
   * Enqueue admin scripts and styles.
   *
   * @uses wp_enqueue_script
   * @uses wp_enqueue_style
   *
   * @since 0.1.0
   */

  function weatherbox_admin_scripts_styles() {

    wp_enqueue_script( 'weatherbox-admin', get_template_directory_uri() . "/assets/js/admin.js", array(), VERSION, true );

    wp_enqueue_style( 'weatherbox-admin', get_template_directory_uri() . "/assets/css/admin.css", array(), VERSION );
  }
endif; // weatherbox_admin_scripts_styles

add_action( 'admin_enqueue_scripts', 'weatherbox_admin_scripts_styles' );


if ( ! function_exists( 'weatherbox_editor_styles' ) ) :
  /**
   * Add Editor styles.
   *
   * @uses add_editor_style
   *
   * @since 0.1.0
   */

  function weatherbox_editor_styles() {

    add_editor_style( get_template_directory_uri() . "/assets/css/editor.css" );

  }
endif; // weatherbox_editor_styles

add_action( 'admin_init', 'weatherbox_editor_styles' );


/**
 * Custom Post Types.
 */

$post_types = glob( get_template_directory() . '/post-types/*.php', GLOB_NOSORT );

foreach ( $post_types as $post_type ) :

  require $post_type;

endforeach;


/**
 * Custom Taxonomies.
 */

$taxonomies = glob( get_template_directory() . '/taxonomies/*.php', GLOB_NOSORT );

foreach ( $taxonomies as $taxonomy ) :

  require $taxonomy;

endforeach;


/**
 * Custom Widgets.
 */

$widgets = glob( get_template_directory() . '/widgets/*.php', GLOB_NOSORT );

foreach ( $widgets as $widget ) :

  require $widget;

endforeach;


/**
 * Custom Shortcodes.
 */

$shortcodes = glob( get_template_directory() . '/shortcodes/*.php', GLOB_NOSORT );

foreach ( $shortcodes as $shortcode ) :

  require $shortcode;

endforeach;


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/includes/template-tags.php';


/**
 * Custom Queries.
 */
require get_template_directory() . '/includes/queries.php';


/**
 * Custom Fields.
 */
require get_template_directory() . '/includes/fields.php';


/**
 * Custom filters.
 */
require get_template_directory() . '/includes/filters.php';


/**
 * Custom Sidebars.
 */
require get_template_directory() . '/includes/sidebars.php';


/**
 * Utility functions.
 */
require get_template_directory() . '/includes/utilities.php';
