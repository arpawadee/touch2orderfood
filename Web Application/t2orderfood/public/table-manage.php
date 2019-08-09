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
			$sql_query = "SELECT table_id, table_no, table_status
					FROM tbl_table
					ORDER BY table_id ASC";
		}else{
			$sql_query = "SELECT table_id, table_no, table_status
					FROM tbl_table
					WHERE table_no LIKE ? 
					ORDER BY table_id ASC";
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
			$stmt->bind_result($data['table_id'], 
					$data['table_name'],
					$data['table_image']
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
			$sql_query = "SELECT table_id, table_no, table_status 
					FROM tbl_table
					ORDER BY table_id ASC LIMIT ?, ?";
		}else{
			$sql_query = "SELECT table_id, table_no, table_status 
					FROM tbl_table
					WHERE table_no LIKE ? 
					ORDER BY table_id ASC LIMIT ?, ?";
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
			$stmt_paging->bind_result($data['table_id'], 
					$data['table_no'],
					$data['table_status']
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
               			<h5 class="breadcrumbs-title">Table List</h5>
		                <ol class="breadcrumb">
               			 <?php if($_SESSION['position'] == "Cashier") : ?>
                 			 <li><a href="dashboard_c.php">Dashboard</a>
                  			</li>
                		<?php else : ?>
                  			<li><a href="dashboard.php">Dashboard</a>
                  			</li>
                		<?php endif; ?>
		                  <li><a href="#" class="active">Manage Table</a>
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
				        <form method="get" class="col s12">
				            <div class="row">
				                <table>
				                	<tr>
				                		<td width="25%">
				                			<div class="input-field col s12">
							                    <a href="add-manage.php" class="btn waves-effect waves-light indigo">Add New Table</a>
							                </div>
				                		</td>
				                		<td width="40%">
				                			<div class="input-field col s12">
							                    <input type="text" class="validate" name="keyword">
							                    <label for="first_name">Search by Table no. :</label>
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

				<div class="col s12 m12 l9">
				    <div class="row">
				        <form method="get" class="col s12">
				            <div class="row">
								<div class="input-field col s12">
				                    <h5>No Table Found!</h5>
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
               			<h5 class="breadcrumbs-title">Table List</h5>
		                <ol class="breadcrumb">
               			 <?php if($_SESSION['position'] == "Cashier") : ?>
                 			 <li><a href="dashboard_c.php">Dashboard</a>
                  			</li>
                		<?php else : ?>
                  			<li><a href="dashboard.php">Dashboard</a>
                  			</li>
                		<?php endif; ?>
		                  <li><a href="#" class="active">Manage Table</a>
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
				        <form method="get" class="col s12">
				            <div class="row">
				                <table>
				                	<tr>
				                		<td width="25%">
				                			<div class="input-field col s12">
							                    <a href="add-manage.php" class="btn waves-effect waves-light indigo">Add New Table</a>
							                </div>
				                		</td>
				                		<td width="40%">
				                			<div class="input-field col s12">
							                    <input type="text" class="validate" name="keyword">
							                    <label for="first_name">Search by Table no. :</label>
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
            

				<div class="row">
		            <div class="col s12 m12 l12">
		              	<div class="card-panel">
		                	<div class="row">
		                  		<div class="row">
		                    		<div class="input-field col s12">
	
										<table class='hoverable bordered'>
										<thead>
											<tr>
												<th>Table No.</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>

											<?php 
												while ($stmt_paging->fetch()){ ?>
												<tbody>
													<tr>
														<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data['table_no'];?></td>
														<td>
														<?php if($data['table_status'] == "OFFLINE") { ?>
															<span class='white-text grey dark-2'>&nbsp;&nbsp;OFFLINE&nbsp;&nbsp;</span>
															<?php } else if($data['table_status'] == "ONLINE") {?>
															<span class='white-text green lighten-2'>&nbsp;&nbsp;ONLINE&nbsp;&nbsp;</span>
															<?php } else {?>
															<span class='white-text black lighten-2'>&nbsp;&nbsp;UNKNOWN&nbsp;&nbsp;</span>
															<?php } ?>
														</td>
														<td>
															<a href="detail-manage.php?id=<?php echo $data['table_id'];?>">
															<i class="mdi-action-pageview"></i>
															</a>
															<a href="delete-manage.php?id=<?php echo $data['table_id'];?>">
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

										<h4><?php $function->doPages($offset, 'manage.php', '', $total_records, $keyword); ?></h4>

		                    		</div>
		                  		</div>
		                	</div>
		              	</div>
		            </div>
		        </div>
        	</div>
        </div>

	</section>
					
				