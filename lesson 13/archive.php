
<?php get_header(); ?>
<main id="main" class="site-main" role="main">
  <h1><?php the_archive_title(); ?></h1>

  <!-- List all categories -->
  <ul>
    <?php wp_list_categories(
         array('title_li' => '',) ); ?>
  </ul>

  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <!-- Post content here -->
  <?php endwhile; ?>

    <!-- Simple Previous/Next Links -->
    <div class="pagination">
      <?php
        the_posts_pagination( array(
          'mid_size'  => 2,
          'prev_text' => __( '« Prev', 'textdomain' ),
          'next_text' => __( 'Next »', 'textdomain' ),
        ) );
      ?>
    </div>

  <?php else : ?>
    <p><?php _e('No posts found.'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>