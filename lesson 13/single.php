<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <div class="row">
    <div class="col-md-8">
      <article <?php post_class('single'); ?>
         id="post-<?php the_ID(); ?>">

        <header class="mb-3">
          <h1 class="mb-1"><?php the_title(); ?></h1>
          <small class="text-muted d-block mb-2">
            Posted on: <?php the_time('F j, Y'); ?>
             at <?php the_time('g:i a'); ?>,
            in <?php the_category(', '); ?>
            <?php
              $tags_list = get_the_tag_list( '<span class="ms-2">Tags: ', ', ', '</span>' );
              if ( $tags_list ) { echo $tags_list; }
            ?>
          </small>

          <?php
          // Edit link for users who can edit this post
          edit_post_link( __( 'Edit This', 'dstheme' ), '<p class="mb-2">', '</p>', 0, 'btn btn-sm btn-outline-secondary' );
          ?>
        </header>

        <?php if ( has_post_thumbnail() ) : ?>
          <div class="thumbnail-img mb-3">
            <?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded' ) ); ?>
          </div>
        <?php endif; ?>

        <div class="content mb-4">
          <?php the_content(); ?>
          <?php
            // In case the post uses <!--nextpage--> for pagination
            wp_link_pages( array(
              'before' => '<nav class="page-links">',
              'after'  => '</nav>',
            ) );
          ?>
        </div>

        <nav class="d-flex justify-content-between my-4">
          <div class="prev"><?php previous_post_link(); ?></div>
          <div class="next"><?php next_post_link(); ?></div>
        </nav>

      </article>

      <?php
      // Comments: show template if open or there is at least one comment.
      if ( comments_open() || get_comments_number() ) {
        comments_template();
      }
      ?>
    </div>

    <aside class="col-md-4">
      <?php get_sidebar( 'primary' ); ?>
    </aside>
  </div>
<?php endwhile; endif; ?>

<?php get_footer(); ?>
