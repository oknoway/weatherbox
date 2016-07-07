<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package weatherbox
 */

?>

  </div><!-- #content -->

  <footer id="colophon" class="site-footer" role="contentinfo">

    <?php if ( is_active_sidebar( 'footer-widgets' ) ) {
      dynamic_sidebar( 'footer-widgets' );
    } ?>

  </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
