<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS Theme</title>
     <link rel="stylesheet" href="wp-content/themes/ds_theme/style.css">
      <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">

</head>
<body>
    <nav>

       <?php
       wp_nav_menu(array('theme_location'=>'primary'));
       ?>
    </nav>
    
</body>
