<?php get_header(); ?>
<h1 style="text-align:center;">Përfundimi i Porosisë</h1>
<div style="padding:40px; text-align:center;">
<?php
if(isset($_POST['checkout'])){
  $_SESSION['cart'] = [];
  echo "<h2>Faleminderit për porosinë tuaj!</h2>";
} else {
?>
<form method="post">
  <input type="text" name="emri" placeholder="Emri i plotë" required><br><br>
  <input type="text" name="adresa" placeholder="Adresa" required><br><br>
  <button type="submit" name="checkout" class="btn">Konfirmo Porosinë</button>
</form>
<?php } ?>
</div>
<?php get_footer(); ?>
