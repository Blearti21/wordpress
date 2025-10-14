<?php get_header(); ?>

<!-- Slider me foto veturash -->
<section class="car-slider">
  <h2>Veturat në Ofertë</h2>
  <div class="slider-container">
    <?php
    $args_slider = array(
      'post_type' => 'car',
      'posts_per_page' => 5,
      'meta_key' => '_car_price',
      'orderby' => 'meta_value_num',
      'order' => 'ASC',
    );
    $slider_query = new WP_Query($args_slider);

    if ($slider_query->have_posts()) :
      while ($slider_query->have_posts()) : $slider_query->the_post();
        if (has_post_thumbnail()) :
          ?>
          <div class="slide">
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail('medium'); ?>
              <div class="slide-info">
                <h3><?php the_title(); ?></h3>
                <p>Çmimi: €<?php echo esc_html(get_post_meta(get_the_ID(), '_car_price', true)); ?></p>
              </div>
            </a>
          </div>
          <?php
        endif;
      endwhile;
      wp_reset_postdata();
    else:
      echo '<p>Nuk ka vetura në ofertë aktualisht.</p>';
    endif;
    ?>
  </div>
</section>

<!-- Lista me veturat më të fundit -->
<section class="latest-cars">
  <h2>Veturat më të Fundit</h2>
  <?php
  $args_latest = array(
    'post_type' => 'car',
    'posts_per_page' => 6,
    'orderby' => 'date',
    'order' => 'DESC',
  );
  $latest_query = new WP_Query($args_latest);

  if ($latest_query->have_posts()) :
    echo '<ul class="car-listing">';
    while ($latest_query->have_posts()) : $latest_query->the_post();
      $price = get_post_meta(get_the_ID(), '_car_price', true);
      $year = get_post_meta(get_the_ID(), '_car_year', true);
      $km = get_post_meta(get_the_ID(), '_car_km', true);
      ?>
      <li>
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br>
        Çmimi: €<?php echo esc_html($price); ?><br>
        Viti: <?php echo esc_html($year); ?><br>
        Kilometrazha: <?php echo esc_html($km); ?> km
      </li>
      <?php
    endwhile;
    echo '</ul>';
    wp_reset_postdata();
  else:
    echo '<p>Nuk ka vetura të reja.</p>';
  endif;
  ?>
</section>

<!-- Pse të na zgjidhni -->
<section class="why-choose-us">
  <h2>Pse të na Zgjidhni</h2>
  <div class="features">
    <div class="feature-item">
      <h3>Cilësi e Garantuar</h3>
      <p>Të gjitha veturat tona kalojnë kontroll rigoroz para shitjes.</p>
    </div>
    <div class="feature-item">
      <h3>Çmime Konkurruese</h3>
      <p>Ofrojmë çmime të favorshme për çdo buxhet.</p>
    </div>
    <div class="feature-item">
      <h3>Mbështetje Profesionale</h3>
      <p>Stafi ynë është gati të ju ndihmojë në çdo hap.</p>
    </div>
  </div>
</section>

<!-- Kontakt i shpejtë -->
<section class="quick-contact">
  <h2>Na Kontaktoni</h2>
  <p>Telefon: <a href="tel:+355123456789">+355 12 345 6789</a></p>
  <p>Email: <a href="mailto:info@carsales.al">info@carsales.al</a></p>
</section>

<?php get_footer(); ?>

<section class="contact-section" id="contact-section">
  <h2>Na Kontaktoni</h2>

  <?php if ($message_sent) : ?>
    <div class="contact-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="icon-success" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
      <p>Faleminderit për mesazhin! Do t'ju kontaktojmë sa më shpejt.</p>
    </div>
  <?php else : ?>
    <?php if ($error) : ?>
      <div class="contact-error">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon-error" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        <p><?php echo esc_html($error); ?></p>
      </div>
    <?php endif; ?>

    <form class="contact-form" method="post" action="#contact-section" novalidate>
      <div class="input-group">
        <input type="text" id="contact_name" name="contact_name" required autocomplete="name" placeholder="Emri juaj *" value="<?php echo isset($_POST['contact_name']) ? esc_attr($_POST['contact_name']) : ''; ?>">
        <label for="contact_name">Emri</label>
      </div>

      <div class="input-group">
        <input type="email" id="contact_email" name="contact_email" required autocomplete="email" placeholder="Email-i juaj *" value="<?php echo isset($_POST['contact_email']) ? esc_attr($_POST['contact_email']) : ''; ?>">
        <label for="contact_email">Email</label>
      </div>

      <div class="input-group">
        <textarea id="contact_message" name="contact_message" rows="5" required placeholder="Mesazhi juaj *"><?php echo isset($_POST['contact_message']) ? esc_textarea($_POST['contact_message']) : ''; ?></textarea>
        <label for="contact_message">Mesazhi</label>
      </div>

      <button type="submit" name="contact_submit">Dërgo Mesazhin</button>
    </form>
  <?php endif; ?>
</section>

