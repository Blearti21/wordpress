<?php get_header(); ?>


<?php if ( have_posts() ) : ?>
  <div class="row">
    <div class="col-md-8">
      <div class="row g-4">
      <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class('col-12'); ?> id="post-<?php the_ID(); ?>">
          <div class="card h-100">
            <?php if ( has_post_thumbnail() ) : ?>
              <div class="thumbnail-img"><?php the_post_thumbnail('medium_large', array('class'=>'card-img-top')); ?></div>
            <?php endif; ?>
            <div class="card-body">
              <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <small class="text-muted d-block mb-2">
                Posted on: <?php the_time('F j, Y'); ?> at <?php the_time('g:i a'); ?>, in <?php the_category(', '); ?>
              </small>
              <div class="card-text"><?php the_excerpt(); ?></div>
            </div>
          </div>
        </article>
      <?php endwhile; ?>
      </div>
      <div class="my-4"><?php the_posts_pagination(); ?></div>
    </div>
    <aside class="col-md-4">
      <?php get_sidebar( 'primary' ); ?>
    </aside>
  </div>
<?php endif; ?>

<?php get_footer(); ?>

