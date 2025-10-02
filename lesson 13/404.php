
<?php

get_header(); ?>
<main id="main" class="site-main" role="main">
  <section class="error-404 not-found">
    <h1><?php _e( 'Oops! That page can’t be found.', 'your-textdomain' ); ?></h1>
    <p><?php _e( 'It looks like nothing was found at this location. Try a search?', 'your-textdomain' ); ?></p>
    <?php get_search_form(); ?>
  </section>
</main>
<?php get_footer(); ?>