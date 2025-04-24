<?php include 'header.php';?>

<div style="background-color: Khaki;">
  <h1 style="text-align:center;"><?php echo $formname??'' ?></h1>
</div>
<div style="margin-left: 15px; margin-top: 10px; margin-bottom: 10px;">
  <?php echo $switch_en??'' ?>
  <?php echo $switch_es??'' ?>
  <?php echo $switch_de??'' ?>
</div>
<div class="container-fluid">
  <?php echo $form??'' ?> 
</div>

<?php include 'footer.php';?>