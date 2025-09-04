<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <article <?php post_class('single'); ?> id="post-<?php the_ID(); ?>">
    <h1 class="mb-3"><?php the_title(); ?></h1>
    <small class="text-muted d-block mb-3">
      Posted on: <?php the_time('F j, Y'); ?> at <?php the_time('g:i a'); ?>, in <?php the_category(', '); ?>
    </small>
    <?php if ( has_post_thumbnail() ) : ?>
      <div class="thumbnail-img mb-3"><?php the_post_thumbnail('large', array('class'=>'img-fluid rounded')); ?></div>
    <?php endif; ?>
    <div class="content"><?php the_content(); ?></div>
    <?php comments_template(); ?>
  </article>
<?php endwhile; endif; ?>

<?php get_footer(); ?>
