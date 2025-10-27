<!-- header.php -->
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <?php wp_head(); ?>
</head>
<body>
<header>
  <div class="logo"><a href="<?php echo home_url(); ?>" style="color:white;">MyMarketplace</a></div>
  <nav>
    <a href="<?php echo home_url(); ?>">Kryefaqja</a>
    <a href="/cart">Shporta</a>
  </nav>
</header>
