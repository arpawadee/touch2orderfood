<?php include('session.php'); ?>
<?php include('public/menubar.php'); ?>
<?php include('public/edit-news-form.php'); ?>
<?php include('public/footer.php'); ?>
    	
<script>
	$('.datepicker').pickadate({			
		selectMonths: true,
		selectYears: 16,
		format: 'dd mmmm, yyyy'
	});
</script>