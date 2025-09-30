<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<?php
// Add some demo custom classes based on front page
if ( is_front_page() ) {
  $awesome_classes = array( 'ds-class', 'my-ds-class' );
} else {
  $awesome_classes = array( 'no-ds-class' );
}
?>
<body <?php body_class( $awesome_classes ); ?>>
  <header class="py-3 border-bottom mb-4">
    <div class="container d-flex align-items-center justify-content-between">
      <a class="navbar-brand fw-bold" href="<?php echo esc_url( home_url('/') ); ?>">DS Theme</a>
      <?php
        wp_nav_menu( array(
          'theme_location' => 'primary',
          'container'      => 'nav',
          'container_class'=> 'nav',
          'menu_class'     => 'nav',
          'fallback_cb'    => false
        ) );
      ?>
    </div>
  </header>
  <main class="container">
