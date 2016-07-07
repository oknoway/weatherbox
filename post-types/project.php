<?php

function project_init() {
  register_post_type( 'weatherbox_project', array(
    'labels'            => array(
      'name'                => __( 'Projects', 'weatherbox' ),
      'singular_name'       => __( 'Project', 'weatherbox' ),
      'all_items'           => __( 'All Projects', 'weatherbox' ),
      'new_item'            => __( 'New Project', 'weatherbox' ),
      'add_new'             => __( 'Add New', 'weatherbox' ),
      'add_new_item'        => __( 'Add New Project', 'weatherbox' ),
      'edit_item'           => __( 'Edit Project', 'weatherbox' ),
      'view_item'           => __( 'View Project', 'weatherbox' ),
      'search_items'        => __( 'Search Projects', 'weatherbox' ),
      'not_found'           => __( 'No Projects found', 'weatherbox' ),
      'not_found_in_trash'  => __( 'No Projects found in trash', 'weatherbox' ),
      'parent_item_colon'   => __( 'Parent Project', 'weatherbox' ),
      'menu_name'           => __( 'Projects', 'weatherbox' ),
    ),
    'public'            => true,
    'hierarchical'      => false,
    'show_ui'           => true,
    'show_in_nav_menus' => true,
    'supports'          => array( 'title', 'editor', 'thumbnail', 'revisions' ),
    'has_archive'       => false,
    'rewrite'           => false,
    'query_var'         => true,
    'menu_icon'         => 'dashicons-welcome-widgets-menus',
    'show_in_rest'      => true,
    'rest_base'         => 'project',
    'rest_controller_class' => 'WP_REST_Posts_Controller',
  ) );

}
add_action( 'init', 'project_init' );

function project_updated_messages( $messages ) {
  global $post;

  $permalink = get_permalink( $post );

  $messages['project'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Project updated. <a target="_blank" href="%s">View Project</a>', 'weatherbox'), esc_url( $permalink ) ),
    2 => __('Custom field updated.', 'weatherbox'),
    3 => __('Custom field deleted.', 'weatherbox'),
    4 => __('Project updated.', 'weatherbox'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Project restored to revision from %s', 'weatherbox'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Project published. <a href="%s">View Project</a>', 'weatherbox'), esc_url( $permalink ) ),
    7 => __('Project saved.', 'weatherbox'),
    8 => sprintf( __('Project submitted. <a target="_blank" href="%s">Preview Project</a>', 'weatherbox'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
    9 => sprintf( __('Project scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Project</a>', 'weatherbox'),
    // translators: Publish box date format, see http://php.net/date
    date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
    10 => sprintf( __('Project draft updated. <a target="_blank" href="%s">Preview Project</a>', 'weatherbox'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
  );

  return $messages;
}
add_filter( 'post_updated_messages', 'project_updated_messages' );
