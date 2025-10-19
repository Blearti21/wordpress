<?php
/**
 * Main index template
 */
get_header();
?>
<section class="hero">
  <div class="container">
    <h1><?php bloginfo('name'); ?></h1>
    <p><?php bloginfo('description'); ?></p>
  </div>
</section>
<div class="container">
  <?php if (have_posts()) : ?>
    <div class="grid">
      <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
          <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
          <?php endif; ?>
          <div class="card-body">
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p><?php echo udhtime_trim_excerpt(24); ?></p>
            <a href="<?php the_permalink(); ?>"><?php _e('Lexo më shumë', 'udhtime'); ?> →</a>
          </div>
        </article>
      <?php endwhile; ?>
    </div>

    <nav class="pagination" aria-label="Pagination">
      <?php
      the_posts_pagination([
        'mid_size'           => 2,
        'prev_text'          => __('Më herët', 'udhtime'),
        'next_text'          => __('Më pas', 'udhtime'),
        'screen_reader_text' => __('Navigim faqeve', 'udhtime'),
      ]);
      ?>
    </nav>

  <?php else : ?>
    <p><?php _e('Nuk ka përmbajtje për t’u shfaqur ende.', 'udhtime'); ?></p>
  <?php endif; ?>
</div>
<?php get_footer(); ?>
