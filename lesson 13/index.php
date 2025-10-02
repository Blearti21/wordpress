<?php get_header(); ?>


<div class="card mb-4">
  <div class="card-body">
    <h2 class="card-title">Add a New Movie</h2>
    <?php

    if ( isset($_POST['movie_submit']) && is_user_logged_in() ) {
      $movie_title = sanitize_text_field($_POST['movie_title']);
      $movie_image = $_FILES['movie_image'];

      if ( !empty($movie_title) && !empty($movie_image['name']) ) {
      
        $post_id = wp_insert_post(array(
          'post_title'   => $movie_title,
          'post_status'  => 'publish',
          'post_type'    => 'post'
        ));

        if ( $post_id && !function_exists('media_handle_upload') ) {
          require_once(ABSPATH . "wp-admin" . '/includes/image.php');
          require_once(ABSPATH . "wp-admin" . '/includes/file.php');
          require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        }
        if ( $post_id && $movie_image['size'] > 0 ) {
          $attach_id = media_handle_upload('movie_image', $post_id);
          if ( is_numeric($attach_id) ) {
            set_post_thumbnail($post_id, $attach_id);
          }
        }
        echo '<div class="alert alert-success mt-2">Movie added!</div>';
      } else {
        echo '<div class="alert alert-danger mt-2">Please fill all fields.</div>';
      }
    }
    ?>

    <?php if ( is_user_logged_in() ) : ?>
      <form method="post" enctype="multipart/form-data">
        <div class="mb-2">
          <label for="movie_title" class="form-label">Movie Name</label>
          <input type="text" name="movie_title" id="movie_title" class="form-control" required>
        </div>
        <div class="mb-2">
          <label for="movie_image" class="form-label">Movie Image</label>
          <input type="file" name="movie_image" id="movie_image" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" name="movie_submit" class="btn btn-primary">Add Movie</button>
      </form>
    <?php else : ?>
      <div class="alert alert-info">You must be logged in to add a movie.</div>
    <?php endif; ?>
  </div>
</div>

<?php if ( have_posts() ) : ?>
  <div class="row">
    <div class="col-md-8">
      <div class="row g-4">
      <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class('col-12'); ?> id="post-<?php the_ID(); ?>">
          <div class="card h-100">
            <?php if ( has_post_thumbnail() ) : ?>
              <div class="thumbnail-img">
                <?php the_post_thumbnail('medium', array('class'=>'card-img-top movie-thumb')); ?>
              </div>
            <?php endif; ?>
            <div class="card-body">
              <h3 class="card-title"><?php the_title(); ?></h3>
            </div>
          </div>
        </article>
      <?php endwhile; ?>
      </div>
      <div class="my-4"><?php mytheme_pagination(); ?></div>
    </div>
    <aside class="col-md-4">
      <?php get_sidebar( 'primary' ); ?>
    </aside>
  </div>
<?php else : ?>
  <p><?php _e('No movies found.', 'your-textdomain'); ?></p>
<?php endif; ?>

<div class="container">


  <h1><?php the_field('title') ?></h1>
    <p><?php the_field('description') ?></p>


  <?php
 
  if (function_exists('get_field_objects')) {
    $fields = get_field_objects();
    if ($fields) {
      echo '<div class="acf-fields-group">';
      foreach ($fields as $field) {
        echo '<div class="acf-field acf-field-' . esc_attr($field['name']) . '">';
        echo '<strong>' . esc_html($field['label']) . ':</strong> ';
        echo esc_html($field['value']);
        echo '</div>';
      }
      echo '</div>';
    }
  }
  ?>
</div>

<div class="box-rounded">This box has rounded corners.</div>
<div class="box-ellipse">Elliptical border radius.</div>
<div class="box-border-image">This box uses a border image.</div>
<div class="bg-example">Background with image + gradient overlay.</div>
<div class="shadow-box">Box with shadow.</div>
<div class="inner-shadow">Box with inner shadow.</div>

<?php get_footer(); ?>
