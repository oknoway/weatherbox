<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package weatherbox
 */

if ( ! function_exists( 'the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_posts_navigation() {
  // Don't print empty markup if there's only one page.
  if ( $GLOBALS[ 'wp_query' ]->max_num_pages < 2 ) {
    return;
  }
  ?>
  <nav class="navigation posts-navigation" role="navigation">
    <h2 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'weatherbox' ); ?></h2>
    <div class="nav-links">

      <?php if ( get_next_posts_link() ) : ?>
      <div class="nav-previous"><?php next_posts_link( esc_html__( 'Older posts', 'weatherbox' ) ); ?></div>
      <?php endif; ?>

      <?php if ( get_previous_posts_link() ) : ?>
      <div class="nav-next"><?php previous_posts_link( esc_html__( 'Newer posts', 'weatherbox' ) ); ?></div>
      <?php endif; ?>

    </div><!-- .nav-links -->
  </nav><!-- .navigation -->
  <?php
}
endif;

if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_post_navigation() {
  // Don't print empty markup if there's nowhere to navigate.
  $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
  $next     = get_adjacent_post( false, '', false );

  if ( ! $next && ! $previous ) {
    return;
  }
  ?>
  <nav class="navigation post-navigation" role="navigation">
    <h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'weatherbox' ); ?></h2>
    <div class="nav-links">
      <?php
        previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
        next_post_link( '<div class="nav-next">%link</div>', '%title' );
      ?>
    </div><!-- .nav-links -->
  </nav><!-- .navigation -->
  <?php
}
endif;

if ( ! function_exists( 'weatherbox_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function weatherbox_posted_on() {
  $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
  if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
    $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
  }

  $time_string = sprintf( $time_string,
    esc_attr( get_the_date( 'c' ) ),
    esc_html( get_the_date() ),
    esc_attr( get_the_modified_date( 'c' ) ),
    esc_html( get_the_modified_date() )
  );

  $posted_on = sprintf(
    esc_html_x( 'Posted on %s', 'post date', 'weatherbox' ),
    '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
  );

  $byline = sprintf(
    esc_html_x( 'by %s', 'post author', 'weatherbox' ),
    '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
  );

  echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'weatherbox_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function weatherbox_entry_footer() {
  // Hide category and tag text for pages.
  if ( 'post' == get_post_type() ) {
    /* translators: used between list items, there is a space after the comma */
    $categories_list = get_the_category_list( esc_html__( ', ', 'weatherbox' ) );
    if ( $categories_list ) {
      printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'weatherbox' ) . '</span>', $categories_list ); // WPCS: XSS OK.
    }

    /* translators: used between list items, there is a space after the comma */
    $tags_list = get_the_tag_list( '', esc_html__( ', ', 'weatherbox' ) );
    if ( $tags_list ) {
      printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'weatherbox' ) . '</span>', $tags_list ); // WPCS: XSS OK.
    }
  }

  if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
    echo '<span class="comments-link">';
    comments_popup_link( esc_html__( 'Leave a comment', 'weatherbox' ), esc_html__( '1 Comment', 'weatherbox' ), esc_html__( '% Comments', 'weatherbox' ) );
    echo '</span>';
  }

  edit_post_link( esc_html__( 'Edit', 'weatherbox' ), '<span class="edit-link">', '</span>' );
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
  if ( is_category() ) {
    $title = sprintf( esc_html__( 'Category: %s', 'weatherbox' ), single_cat_title( '', false ) );
  } elseif ( is_tag() ) {
    $title = sprintf( esc_html__( 'Tag: %s', 'weatherbox' ), single_tag_title( '', false ) );
  } elseif ( is_author() ) {
    $title = sprintf( esc_html__( 'Author: %s', 'weatherbox' ), '<span class="vcard">' . get_the_author() . '</span>' );
  } elseif ( is_year() ) {
    $title = sprintf( esc_html__( 'Year: %s', 'weatherbox' ), get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'weatherbox' ) ) );
  } elseif ( is_month() ) {
    $title = sprintf( esc_html__( 'Month: %s', 'weatherbox' ), get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'weatherbox' ) ) );
  } elseif ( is_day() ) {
    $title = sprintf( esc_html__( 'Day: %s', 'weatherbox' ), get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'weatherbox' ) ) );
  } elseif ( is_tax( 'post_format' ) ) {
    if ( is_tax( 'post_format', 'post-format-aside' ) ) {
      $title = esc_html_x( 'Asides', 'post format archive title', 'weatherbox' );
    } elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
      $title = esc_html_x( 'Galleries', 'post format archive title', 'weatherbox' );
    } elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
      $title = esc_html_x( 'Images', 'post format archive title', 'weatherbox' );
    } elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
      $title = esc_html_x( 'Videos', 'post format archive title', 'weatherbox' );
    } elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
      $title = esc_html_x( 'Quotes', 'post format archive title', 'weatherbox' );
    } elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
      $title = esc_html_x( 'Links', 'post format archive title', 'weatherbox' );
    } elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
      $title = esc_html_x( 'Statuses', 'post format archive title', 'weatherbox' );
    } elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
      $title = esc_html_x( 'Audio', 'post format archive title', 'weatherbox' );
    } elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
      $title = esc_html_x( 'Chats', 'post format archive title', 'weatherbox' );
    }
  } elseif ( is_post_type_archive() ) {
    $title = sprintf( esc_html__( 'Archives: %s', 'weatherbox' ), post_type_archive_title( '', false ) );
  } elseif ( is_tax() ) {
    $tax = get_taxonomy( get_queried_object()->taxonomy );
    /* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
    $title = sprintf( esc_html__( '%1$s: %2$s', 'weatherbox' ), $tax->labels->singular_name, single_term_title( '', false ) );
  } else {
    $title = esc_html__( 'Archives', 'weatherbox' );
  }

  /**
   * Filter the archive title.
   *
   * @param string $title Archive title to be displayed.
   */
  $title = apply_filters( 'get_the_archive_title', $title );

  if ( ! empty( $title ) ) {
    echo $before . $title . $after;  // WPCS: XSS OK.
  }
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
  $description = apply_filters( 'get_the_archive_description', term_description() );

  if ( ! empty( $description ) ) {
    /**
     * Filter the archive description.
     *
     * @see term_description()
     *
     * @param string $description Archive description to be displayed.
     */
    echo $before . $description . $after;  // WPCS: XSS OK.
  }
}
endif;


if ( ! function_exists( 'the_project_meta' ) ) :
/**
 * Shim for `the_project_meta()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_project_meta() {

  $project_meta = get_post_meta( get_the_ID (), 'project_details', true );

  if ( !empty( $project_meta ) ) : ?>
  <dl>
    <?php // Project URL
    if( !empty( $project_meta[ 'url' ] ) ) : ?>
      <dt>See this project</dt>
      <dd><a href="<?php echo esc_url( $project_meta[ 'url' ] ); ?>" rel="nofollow" class="project-link"><?php echo esc_url( $project_meta[ 'url' ] ); ?></a></dd>
    <?php endif; ?>

    <?php // Client
    if( !empty( $project_meta[ 'client' ] ) ) : ?>
      <dt>Client</dt>
      <dd>
        <?php if ( !empty( $project_meta[ 'client_url' ] ) ) : ?>
        <a href="<?php echo esc_url( $project_meta[ 'client_url' ] ); ?>" rel="nofollow" class="client-link">
        <?php endif; ?>

        <?php echo $project_meta[ 'client' ]; ?></dd>

        <?php if ( !empty( $project_meta[ 'client_url' ] ) ) : ?>
        </a>
        <?php endif; ?>
      </dd>
    <?php endif; ?>

    <?php // Designer
    if( !empty( $project_meta[ 'designer' ] ) ) : ?>
      <dt>Designer</dt>
      <dd>
        <?php if ( !empty( $project_meta[ 'designer_url' ] ) ) : ?>
        <a href="<?php echo esc_url( $project_meta[ 'designer_url' ] ); ?>" rel="nofollow" class="designer-link">
        <?php endif; ?>

        <?php echo $project_meta[ 'designer' ]; ?></dd>

        <?php if ( !empty( $project_meta[ 'designer_url' ] ) ) : ?>
        </a>
        <?php endif; ?>
      </dd>
    <?php endif; ?>
  </dl>
<?php endif;
}
endif;



if ( ! function_exists( 'get_the_hero_image' ) ) :
/** @TODO: Update this
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function get_the_hero_image( $size, $hero_origin = false ) {

  if ( empty( $size ) )
    return;

  if ( !$hero_origin )
    $hero_origin = get_hero_origin();


  $heroImgID = get_post_thumbnail_id();
  //$heroImgID = $heroImgObject['ID'];
  $heroImgSrc = wp_get_attachment_image( $heroImgID, $size );
  $heroImgMeta = wp_get_attachment_metadata( $heroImgID );
  $heroImg = wp_image_add_srcset_and_sizes( $heroImgSrc, $heroImgMeta, $heroImgID );

  return $heroImg;

}
endif;


if ( ! function_exists( 'the_hero_image' ) ) :
/** @TODO: Update this
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_hero_image( $size = 'hero' ) {

  $heroImg = get_the_hero_image( $size );

  if ( strlen( $heroImg ) == 0 )
    return;

  echo $heroImg;

}
endif;


if ( ! function_exists( 'noscript_image' ) ) :
/** @TODO: Update this
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function noscript_image( $size = 'hero' ) {

  $heroImg = get_the_hero_image( $size );

  if ( strlen( $heroImg ) == 0 )
    return;

  echo '<noscript>' .  $heroImg . '</noscript>';

}
endif;




if ( ! function_exists( 'get_the_responsive_bg_img_styles' ) ) :
/** @TODO: Update this
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function get_the_responsive_bg_img_styles( $size, $selector, $post = '' ) {

  if ( empty( $size ) || empty( $selector ) )
    return;

  if ( empty( $post ) )
    global $post;

  if ( empty( $post ) )
    return;

  //$heroImgObject = get_field( $field, $post );

  $heroImgID = get_post_thumbnail_id( $post );

  $heroImgSrc = wp_get_attachment_image( $heroImgID, $size );
  $heroImgMeta = wp_get_attachment_metadata( $heroImgID );

  //Filter out non-project sizes
  foreach ( $heroImgMeta[ 'sizes' ] as $size=>$sizeData ) {

    //pre_printr( $size );
    //pre_printr( preg_match( '/^project/', $size) );

    if (( preg_match( '/^project/', $size) ) !== 1 ) {
      unset( $heroImgMeta[ 'sizes' ][ $size ] );

      //pre_printr( $size );
    }

  }


  //pre_printr( $heroImgMeta[ 'sizes' ] );





  $imgSizes = wp_calculate_image_sizes( $size, $heroImgSrc, $heroImgMeta, $heroImgID );

  $imgSizes = array(
    absint( $heroImgMeta['sizes'][ $size ][ 'width' ] ),
    absint( $heroImgMeta['sizes'][ $size ][ 'height' ] ),
  );

  $imgSrcSet = calculate_responsive_bg_img_styles( $imgSizes, $heroImgSrc, $heroImgMeta, $heroImgID, $selector );

  return $imgSrcSet;

}
endif;


if ( ! function_exists( 'the_responsive_bg_img_styles' ) ) :
/** @TODO: Update this
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_responsive_bg_img_styles( $size, $selector, $post = '' ) {

  if ( empty( $size ) || empty( $selector ) )
    return;

  if ( empty( $post ) )
    global $post;

  if ( empty( $post ) )
    return;

  $styles = get_the_responsive_bg_img_styles( $size, $selector, $post );

  if ( strlen( $styles ) == 0 )
    return;

  echo $styles;

}
endif;
