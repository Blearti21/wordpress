<?php get_header(); ?>
<main id="primary" class="site-main">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article <?php post_class(); ?>>
      <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; else : ?>
    <p><?php esc_html_e('No posts found.', 'blearti21'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
