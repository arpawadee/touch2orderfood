<?php include('session.php'); ?>
<?php if($_SESSION['position'] == "Cashier") : ?>
	<?php include('public/menubar_c.php'); ?>
<?php else : ?>
	<?php include('public/menubar.php'); ?>
<?php endif; ?>
<?php include('public/table-manage.php'); ?>
<?php include('public/footer.php'); ?>