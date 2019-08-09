<?php
	include_once('functions.php'); 
?>

	<?php 
		// create object of functions class
		$function = new functions;
		
		// create array variable to store data from database
		$data = array();
		
		if(isset($_GET['keyword'])){	
			// check value of keyword variable
			$keyword = $function->sanitize($_GET['keyword']);
			$bind_keyword = "%".$keyword."%";
		}else{
			$keyword = "";
			$bind_keyword = $keyword;
		}
			
		if(empty($keyword)){
			$sql_query = "SELECT gid, gallery_name, gallery_image, gallery_description 
					FROM tbl_gallery
					ORDER BY gid DESC";
		}else{
			$sql_query = "SELECT gid, gallery_name, gallery_image, gallery_description 
					FROM tbl_gallery
					WHERE gallery_name LIKE ? 
					ORDER BY gid DESC";
		}
		
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			if(!empty($keyword)){
				$stmt->bind_param('s', $bind_keyword);
			}
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['gid'], 
					$data['gallery_name'], 
					$data['gallery_image'], 
					$data['gallery_description']
					);
			// get total records
			$total_records = $stmt->num_rows;
		}
			
		// check page parameter
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}else{
			$page = 1;
		}
						
		// number of data that will be display per page		
		$offset = 10;
						
		//lets calculate the LIMIT for SQL, and save it $from
		if ($page){
			$from 	= ($page * $offset) - $offset;
		}else{
			//if nothing was given in page request, lets load the first page
			$from = 0;	
		}	
		
		if(empty($keyword)){
			$sql_query = "SELECT gid, gallery_name, gallery_image, gallery_description 
					FROM tbl_gallery
					ORDER BY gid DESC LIMIT ?, ?";
		}else{
			$sql_query = "SELECT gid, gallery_name, gallery_image, gallery_description 
					FROM tbl_gallery
					WHERE gallery_name LIKE ? 
					ORDER BY gid DESC LIMIT ?, ?";
		}
		
		$stmt_paging = $connect->stmt_init();
		if($stmt_paging ->prepare($sql_query)) {
			// Bind your variables to replace the ?s
			if(empty($keyword)){
				$stmt_paging ->bind_param('ss', $from, $offset);
			}else{
				$stmt_paging ->bind_param('sss', $bind_keyword, $from, $offset);
			}
			// Execute query
			$stmt_paging ->execute();
			// store result 
			$stmt_paging ->store_result();
			$stmt_paging->bind_result($data['gid'], 
					$data['gallery_name'], 
					$data['gallery_image'], 
					$data['gallery_description']
					);
			// for paging purpose
			$total_records_paging = $total_records; 
		}

		// if no data on database show "No Reservation is Available"
		if($total_records_paging == 0){
	
	?>
	
	<!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          	<div class="container">
            	<div class="row">
              		<div class="col s12 m12 l12">
               			<h5 class="breadcrumbs-title">Gallery List</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a>
		                  </li>
		                  <li><a href="#" class="active">Manage Gallery</a>
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
				<div class="col s12 m12 l9">
				    <div class="row">
				        <form method="get" class="col s12">
				            <div class="row">
				                <table>
				                	<tr>
				                		<td width="25%">
				                			<div class="input-field col s12">
							                    <a href="add-gallery.php" class="btn waves-effect waves-light indigo">Add New Gallery</a>
							                </div>
				                		</td>
				                		<td width="40%">
				                			<div class="input-field col s12">
							                    <input type="text" class="validate" name="keyword">
							                    <label for="first_name">Search</label>
							                </div>
				                		</td>
				                		<td>
				                			<div class="input-field col s12">
							                	<button type="submit" name="btnSearch" class="btn-floating btn-large waves-effect waves-light"><i class="mdi-action-search"></i></button>
							                </div>
				                		</td>
				                	</tr>
				                </table>
				             </div>
				        </form>
				    </div>
				</div>


				<div class="col s12 m12 l9">
				    <div class="row">
				        <form method="get" class="col s12">
				            <div class="row">
								<div class="input-field col s12">
				                    <h5>No Gallery Found!</h5>
				                </div>		              
				             </div>
				        </form>
				    </div>
				</div>
			</div>
		</div>
	</section>
	<br><br><br><br><br><br><br><br><br><br>

	<?php 
		// otherwise, show data
		}else{
			$row_number = $from + 1;
	?>

	<!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          	<div class="container">
            	<div class="row">
              		<div class="col s12 m12 l12">
               			<h5 class="breadcrumbs-title">Gallery List</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a>
		                  </li>
		                  <li><a href="#" class="active">Manage Gallery</a>
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
				<div class="col s12 m12 l9">
				    <div class="row">
				        <form method="get" class="col s12">
				            <div class="row">
				                <table>
				                	<tr>
				                		<td width="25%">
				                			<div class="input-field col s12">
							                    <a href="add-gallery.php" class="btn waves-effect waves-light indigo">Add New Gallery</a>
							                </div>
				                		</td>
				                		<td width="40%">
				                			<div class="input-field col s12">
							                    <input type="text" class="validate" name="keyword">
							                    <label for="first_name">Search</label>
							                </div>
				                		</td>
				                		<td>
				                			<div class="input-field col s12">
							                	<button type="submit" name="btnSearch" class="btn-floating btn-large waves-effect waves-light"><i class="mdi-action-search"></i></button>
							                </div>
				                		</td>
				                	</tr>
				                </table>
				             </div>
				        </form>
				    </div>
				</div>

				<div class="row">
		            <div class="col s12 m12 l12">
		              	<div class="card-panel">
		                	<div class="row">
		                  		<div class="row">
		                    		<div class="input-field col s12">
	
										<table class='hoverable bordered'>
										<thead>
											<tr>
												<th>Image</th>
												<th>Image Name</th>
												<th>Image Description</th>
												<th>Action</th>
											</tr>
										</thead>

											<?php 
												while ($stmt_paging->fetch()){ ?>
												<tbody>
													<tr>
														<td><img src="upload/gallery/thumbs/<?php echo $data['gallery_image']; ?>" width="50" height="50"/></td>
														<td><?php echo $data['gallery_name'];?></td>
														<td><?php echo $data['gallery_description'];?></td>
														<td>	
															<a href="detail-gallery.php?id=<?php echo $data['gid'];?>">
															<i class="mdi-action-pageview"></i>
															</a>																		
															<a href="edit-gallery.php?id=<?php echo $data['gid'];?>">
															<i class="mdi-editor-mode-edit"></i>
															</a>
															<a href="delete-gallery.php?id=<?php echo $data['gid'];?>">
															<i class="mdi-action-delete"></i>
															</a>															
														</td>
													</tr>
												</tbody>
													<?php 
													} 
												}
											?>
										</table>

										<h4><?php $function->doPages($offset, 'gallery.php', '', $total_records, $keyword); ?></h4>

		                    		</div>
		                  		</div>
		                	</div>
		              	</div>
		            </div>
		        </div>
        	</div>
        </div>

	</section>