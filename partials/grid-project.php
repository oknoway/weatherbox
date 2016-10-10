<?php
/**
 * Template part for displaying Projects.
 *
 * @package weatherbox
 */

 $project_details = get_post_meta( get_the_ID(), 'project_details', true );
?>

<article id="project-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php if ( !empty( $project_details['url' ] ) ) : ?>
  <a href="<?php echo esc_url( $project_details['url' ] ); ?>" class="project-container">
  <?php else : ?>
  <div class="project-container">
  <?php endif; ?>
    <header class="project-header">
      <?php the_title( sprintf( '<h1 class="project-title">', '</h1>' ) ); ?>
    </header><!-- .project-header -->

  <?php if ( has_post_thumbnail() ) : ?>
    <figure id="project-img-<?php the_ID(); ?>" class="project-image">
      <?php include get_template_directory() . '/assets/svg/browser.svg'; ?>
      <?php noscript_image( 'project_small' ); ?>
      <?php the_responsive_bg_img_styles( 'project_small', '#project-img-' . get_the_ID() ); ?>

    </figure><!-- .project-image -->
  <?php endif; ?>

    <div class="project-content">
      <?php
        /* translators: %s: Name of current post */
        the_content( sprintf(
          wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'weatherbox' ), array( 'span' => array( 'class' => array() ) ) ),
          the_title( '<span class="screen-reader-text">"', '"</span>', false )
        ) );
      ?>

    </div><!-- .project-content -->

    <footer class="project-footer">
      <div class="project-meta">
        <?php the_project_meta(); ?>
      </div>
    </footer><!-- .project-footer -->
    <?php if ( !empty( $project_details['url' ] ) ) : ?>
    </a>
    <?php else : ?>
    </div>
    <?php endif; ?>
</article><!-- #project-## -->
