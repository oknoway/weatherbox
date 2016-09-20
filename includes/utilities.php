<?php
/**
 *  Utility functions.
 *
 * @package WordPress
 * @subpackage weatherbox
 */


if ( ! function_exists( 'get_all_the_terms' ) ):

  /**
   * Get a list of all terms across all taxonomies
   *
   * @param $post (object or ID)
   *
   * @return array of terms
   *
   * @since 0.1.0
   */
  function get_all_the_terms( $post = 0 ) {

    $post = get_post( $post );

    if ( !$post )
      return false;

    $taxonomies = get_object_taxonomies( $post->post_type, 'objects' );

    $output = array();

    foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) :

      $terms = get_the_terms( $post->ID, $taxonomy_slug );

      if ( !empty( $terms ) ) :

        foreach ( $terms as $term ) :

          $output[] = $term->name;

        endforeach;

      endif;

    endforeach;

    return $output;
  }

endif; // get_all_the_terms

if ( ! function_exists( 'get_page_id_by_slug' ) ) :

  /**
   * Quick way to get a page ID from a slug
   *
   * @param $slug
   *
   * @return int
   *
   * @since 0.1.0
   */

  function get_page_id_by_slug( $slug ) {
    $page = get_page_by_path( $slug );

    $id = ( !empty($page ) ) ? $page->ID : null;

    return $id;
  }

endif; // get_page_id_by_slug


if ( ! function_exists( 'get_the_slug' ) ) :

  /**
   * Quick way to get a slug from a post object
   *
   * @param $post
   *
   * @return string
   *
   * @since 0.1.0
   */

  function get_the_slug( $post ) {

    if ( $post == null )
      global $post;

    $slug = basename( get_permalink( $post ) );

    do_action('before_slug', $slug);

    $slug = apply_filters('slug_filter', $slug);

    do_action('after_slug', $slug);

    return $slug;
  }

endif; // get_the_slug

function get_hero_origin() {
  //@TODO: write this.

  global $post;

  $heroOwner = false;

  if ( !in_the_loop() ) {

    if ( is_category() ) {

      $heroOwner = 'category_' . get_cat_ID( single_cat_title( '', false ) );

    } elseif ( is_tag() ) {

      $tag = get_queried_object();
      $heroOwner = $tag->taxonomy . '_' . $tag->term_id;
    }

  } else {

    $heroOwner = $post->ID;

  }

  return $heroOwner;
}

function calculate_responsive_bg_img_styles( $size_array, $image_src, $image_meta, $attachment_id = 0, $selector ) {

    if ( empty( $image_meta['sizes'] ) ) {
      return false;
    }

    $image_sizes = $image_meta['sizes'];

    // Get the width and height of the image.
    $image_width = (int) $size_array[0];
    $image_height = (int) $size_array[1];

    // Bail early if error/no width.
    if ( $image_width < 1 ) {
      return false;
    }

    $image_basename = wp_basename( $image_meta['file'] );
    $upload_dir = wp_get_upload_dir();
    $image_baseurl = $upload_dir['baseurl'];

    //pre_printr($image_basename);
    //pre_printr($image_baseurl);
    //pre_printr($upload_dir);

    // Uploads are (or have been) in year/month sub-directories.
    if ( $image_basename !== $image_meta['file'] ) {
      $dirname = dirname( $image_meta['file'] );

      if ( $dirname !== '.' ) {
        $image_baseurl = trailingslashit( $image_baseurl ) . $dirname;
      }
    }

    //$image_baseurl = trailingslashit( $image_baseurl );

    /*
     * WordPress flattens animated GIFs into one frame when generating intermediate sizes.
     * To avoid hiding animation in user content, if src is a full size GIF, a srcset attribute is not generated.
     * If src is an intermediate size GIF, the full size is excluded from srcset to keep a flattened GIF from becoming animated.
     */
    if ( ! isset( $image_sizes['thumbnail']['mime-type'] ) || 'image/gif' !== $image_sizes['thumbnail']['mime-type'] ) {
      $image_sizes['full'] = array(
        'width'  => $image_meta['width'],
        'height' => $image_meta['height'],
        'file'   => $image_basename,
      );
    } elseif ( strpos( $image_src, $image_meta['file'] ) ) {
      return false;
    }


    // Calculate the image aspect ratio.
    $image_ratio = $image_height / $image_width;

    /*
     * Images that have been edited in WordPress after being uploaded will
     * contain a unique hash. Look for that hash and use it later to filter
     * out images that are leftovers from previous versions.
     */
    $image_edited = preg_match( '/-e[0-9]{13}/', wp_basename( $image_src ), $image_edit_hash );

    /**
     * Filter the maximum image width to be included in a 'srcset' attribute.
     *
     * @since 4.4.0
     *
     * @param int   $max_width  The maximum image width to be included in the 'srcset'. Default '1600'.
     * @param array $size_array Array of width and height values in pixels (in that order).
     */
    $max_srcset_image_width = apply_filters( 'max_srcset_image_width', 1600, $size_array );

    // Array to hold URL candidates.
    $sources = array();

    // holder for largest img value
    $biggestImg = 0;
    pre_printr( $image_sizes );
    /*
     * Loop through available images. Only use images that are resized
     * versions of the same edit.
     */
    foreach ( $image_sizes as $image ) {
      // Filter out images that are from previous edits.
      if ( $image_edited && ! strpos( $image['file'], $image_edit_hash[0] ) ) {
        continue;
      }

      // Filter out images that are wider than '$max_srcset_image_width'.
      if ( $max_srcset_image_width && $image['width'] > $max_srcset_image_width ) {
        continue;
      }

      // Calculate the new image ratio.
      if ( $image['width'] ) {
        $image_ratio_compare = $image['height'] / $image['width'];
      } else {
        $image_ratio_compare = 0;
      }

      // If the new ratio differs by less than 0.002, use it.
      if ( abs( $image_ratio - $image_ratio_compare ) < 0.5 ) {
        // Add the URL, descriptor, and value to the sources array to be returned.
        $sources[ $image['width'] ] = array(
          'url'        => $image_baseurl . $image['file'],
          'descriptor' => 'min-width',
          'value'      => $image['width'],
        );
        pre_printr( $image );
        // Replace the biggestImg value if this one is bigger
        if ( $image['width'] > $biggestImg )
          $biggestImg = $image['width'];
      }
    }
    //pre_printr( $sources );
    // Adjust last value & descriptor
    ksort( $sources );

    $lastSource = array_pop( $sources );

    $lastSource['descriptor'] = 'min-width';

    $lastSource['original-value'] = $lastSource['value'];

    if ( count( $sources ) > 1 ) {
      $secondLastSource = array_pop( $sources );

      $lastSource['value'] = $secondLastSource['value'];
      $sources[ $secondLastSource['value'] ] = $secondLastSource;

    } else {

      $lastSource['value'] = 767;

    }

    //pre_printr( $lastSource );

    $sources[ $lastSource['original-value'] ] = $lastSource;

    /**
     * Filter an image's 'srcset' sources.
     *
     * @since 4.4.0
     *
     * @param array  $sources {
     *     One or more arrays of source data to include in the 'srcset'.
     *
     *     @type array $width {
     *         @type string $url        The URL of an image source.
     *         @type string $descriptor The descriptor type used in the image candidate string,
     *                                  either 'w' or 'x'.
     *         @type int    $value      The source width if paired with a 'w' descriptor, or a
     *                                  pixel density value if paired with an 'x' descriptor.
     *     }
     * }
     * @param array  $size_array    Array of width and height values in pixels (in that order).
     * @param string $image_src     The 'src' of the image.
     * @param array  $image_meta    The image meta data as returned by 'wp_get_attachment_metadata()'.
     * @param int    $attachment_id Image attachment ID or 0.
     */
    $sources = apply_filters( 'calculate_responsive_bg_img_styles', $sources, $size_array, $image_src, $image_meta, $attachment_id );

    // Only return a 'srcset' value if there is more than one source.
    if ( count( $sources ) < 2 ) {
      //return false;
    }

    ksort( $sources );

    //pre_printr( $sources );

    $styles = '<style>';

    foreach ( $sources as $i=>$source ) {

      $styles .= '@media screen and (' . $source['descriptor'] . ':' . $source['value'] . 'px ) { ';
      $styles .= $selector . '{ background-image: url(' . $source['url'] . '); }';
      $styles .= ' }' . "\n";
    }

    $styles .= '</style>';

    return $styles;
}


function pre_printr( $output ) {

   if ( empty( $output ) )
    return;

  echo '<pre>';
  print_r( $output );
  echo '</pre>';
}
