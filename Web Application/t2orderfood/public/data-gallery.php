	<?php 
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
		// create array variable to store data from database
		$data = array();
		
		// get all data from menu table and category table
		$sql_query = "SELECT gallery_image FROM tbl_gallery WHERE gid = ?";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['gallery_image']
					);
			$stmt->fetch();
			$stmt->close();
		}
		
	?>

	<!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          	<div class="container">
            	<div class="row">
              		<div class="col s12 m12 l12">
               			<h5 class="breadcrumbs-title">Image Preview</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a>
		                  </li>
		                  <li><a href="#" class="active">Image Preview</a>
		                  </li>
		                </ol>
              		</div>
            	</div>
          	</div>
        </div>
        <!--breadcrumbs end-->

		<!--start container-->
        <div class="container">
          	<div class="section">
				<div class="row">
		        	<div class="col s12 m12 l12">
		        		<div class="card-panel">
		                	<div class="row">
		                  			<div class="row">
		                  				<form method="post">
											<div class="row">
		                    					<div class="input-field col s12"> 
		                    					<center>
													<img src="upload/gallery/<?php echo $data['gallery_image']; ?>"/>
												</center>
												</div>
											</div>	
										</form>								
						            </div>
						    </div>
						</div>
					</div>
						<div class="col s12 m12 l12">
							<a href="edit-gallery.php?id=<?php echo $ID; ?>"><button class="btn waves-effect waves-light indigo">Edit</button></a>
							&nbsp;
							<a href="delete-gallery.php?id=<?php echo $ID; ?>"><button class="btn waves-effect waves-light indigo">Delete</button></a>
						</div>
				</div>
			</div>
		</div>
	</section>