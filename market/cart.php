<?php
get_header();
global $products;

if(isset($_GET['add_to_cart'])){
  $id = (int)$_GET['add_to_cart'];
  if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
  $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
}

if(isset($_GET['remove'])){
  unset($_SESSION['cart'][$_GET['remove']]);
}
?>

<h1 style="text-align:center;">Shporta</h1>
<div style="padding:30px;">
<table style="width:100%; border-collapse:collapse;">
<tr><th>Produkt</th><th>Sasia</th><th>Totali</th><th></th></tr>
<?php
$total = 0;
if(!empty($_SESSION['cart'])):
foreach($_SESSION['cart'] as $id=>$qty):
  $prod = $products[$id];
  $subtotal = $prod['price'] * $qty;
  $total += $subtotal;
  ?>
  <tr style="border-bottom:1px solid #ddd;">
    <td><?php echo $prod['title']; ?></td>
    <td><?php echo $qty; ?></td>
    <td>$<?php echo $subtotal; ?></td>
    <td><a href="?remove=<?php echo $id; ?>">Hiq</a></td>
  </tr>
<?php endforeach; ?>
</table>
<p style="text-align:right;">Totali: <strong>$<?php echo $total; ?></strong></p>
<a class="btn" href="/checkout">Vazhdo te pagesa</a>
<?php else: ?>
<p>Shporta është bosh.</p>
<?php endif; ?>
</div>


<?php get_footer(); ?>
