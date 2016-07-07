<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package weatherbox
 */

?><!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 9]><html class="no-js lt-ie10" <?php language_attributes(); ?>><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta http-equiv="X-UA-Compatible" content="IE=10;IE=9;IE=8;IE=7;IE=EDGE,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

  <meta name="apple-mobile-web-app-title" content="OK/No Way" />

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
  <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'weatherbox' ); ?></a>

  <header id="masthead" class="site-header" role="banner">
    <div class="site-branding">
      <h1 class="site-title">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
          <span class="visuallyhidden"><?php bloginfo( 'name' ); ?></span>
          <?php include get_template_directory() . '/assets/svg/oknoway_logo.svg'; ?>
        </a>
      </h1>
      <p class="site-description"><?php bloginfo( 'description' ); ?></p>
    </div><!-- .site-branding -->
  </header><!-- #masthead -->

  <div id="content" class="site-content">
