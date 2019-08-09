<?php
	ob_start();
	include('session.php');
?>
<?php if($_SESSION['position'] == "Cashier") : ?>
	<?php include('public/menubar_c.php'); ?>
<?php else : ?>
	<?php include('public/menubar.php'); ?>
<?php endif; ?>
<?php include('public/confirm-delete-manage.php'); ?>
<?php include('public/footer.php'); ?>