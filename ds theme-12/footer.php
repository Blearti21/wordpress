  </main>

  <footer class="site-footer mt-5">
    <p>Digital School</p>
    <?php
      if ( has_nav_menu( 'footer' ) ) {
        wp_nav_menu( array(
          'theme_location' => 'footer',
          'container'      => 'nav',
          'container_class'=> 'nav justify-content-center',
          'menu_class'     => 'nav'
        ) );
      }
    ?>
  </footer>
  <?php wp_footer(); ?>
</body>
</html>
