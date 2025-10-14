<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <h1><?php the_title(); ?></h1>
    <div><?php the_content(); ?></div>
    <p><strong>Çmimi:</strong> €<?php echo esc_html(get_post_meta(get_the_ID(), '_car_price', true)); ?></p>
    <p><strong>Viti:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_car_year', true)); ?></p>
    <p><strong>Kilometrazha:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_car_km', true)); ?> km</p>
<?php endwhile; endif; ?>

<?php get_footer(); ?>
