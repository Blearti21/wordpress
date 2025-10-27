<?php get_header(); ?>
<h1 style="text-align:center; margin:20px;">Produktet</h1>
<div class="products">
<?php global $products;
foreach($products as $p): ?>
  <div class="product">
    <img src="<?php echo $p['image']; ?>" alt="">
    <h3><?php echo $p['title']; ?></h3>
    <p>Çmimi: $<?php echo $p['price']; ?></p>
    <a class="btn" href="?add_to_cart=<?php echo $p['id']; ?>">Shto në Shportë</a>
  </div>
<?php endforeach; ?>
</div>
<?php get_footer(); ?>
