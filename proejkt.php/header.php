<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
<style>
/* Stil bazik navbar */
.navbar {
  background-color: #333;
  padding: 10px 20px;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
}

.navbar-brand {
  color: white;
  font-weight: bold;
  font-size: 1.5em;
  text-decoration: none;
}

.navbar-menu {
  list-style: none;
  display: flex;
  margin: 0;
  padding: 0;
}

.navbar-menu li {
  position: relative;
}

.navbar-menu li a {
  color: white;
  padding: 10px 15px;
  text-decoration: none;
  display: block;
}

.navbar-menu li a:hover {
  background-color: #555;
}

.navbar-menu .sub-menu {
  display: none;
  position: absolute;
  background: #444;
  top: 100%;
  left: 0;
  min-width: 160px;
  z-index: 1000;
  border-radius: 0 0 4px 4px;
}

.navbar-menu li:hover > .sub-menu {
  display: block;
}

.sub-menu li a {
  padding: 10px;
  color: #fff;
}

.sub-menu li a:hover {
  background-color: #666;
}

/* Mobile navbar toggle button */
.navbar-toggle {
  display: none;
  flex-direction: column;
  cursor: pointer;
}

.navbar-toggle span {
  height: 3px;
  width: 25px;
  background: white;
  margin-bottom: 4px;
  border-radius: 2px;
}

/* Responsive */
@media (max-width: 768px) {
  .navbar-menu {
    flex-direction: column;
    width: 100%;
    display: none;
  }
  .navbar-menu.active {
    display: flex;
  }
  .navbar-menu li {
    width: 100%;
  }
  .navbar-toggle {
    display: flex;
  }
}

/* Search form styling */
.navbar-search {
  margin-left: 20px;
}

.navbar-search input[type="search"] {
  padding: 5px 10px;
  border-radius: 3px;
  border: none;
  outline: none;
}

.navbar-search button {
  padding: 5px 10px;
  border: none;
  background-color: #555;
  color: white;
  border-radius: 3px;
  cursor: pointer;
}

.navbar-search button:hover {
  background-color: #777;
}
</style>
</head>
<body <?php body_class(); ?>>
<header>
  <nav class="navbar">
    <a href="<?php echo home_url(); ?>" class="navbar-brand">CarSales</a>
    <div class="navbar-toggle" id="navbar-toggle">
      <span></span>
      <span></span>
      <span></span>
    </div>
    <?php
      wp_nav_menu(array(
        'theme_location' => 'primary-menu',
        'container' => false,
        'menu_class' => 'navbar-menu',
        'fallback_cb' => false,
      ));
    ?>
    <form role="search" method="get" class="navbar-search" action="<?php echo home_url('/'); ?>">
      <input type="search" placeholder="Kërko veturë..." value="<?php echo get_search_query(); ?>" name="s" />
      <input type="hidden" name="post_type" value="car" />
      <button type="submit">Kërko</button>
    </form>
  </nav>
</header>

<script>
document.getElementById('navbar-toggle').addEventListener('click', function(){
  document.querySelector('.navbar-menu').classList.toggle('active');
});
</script>

