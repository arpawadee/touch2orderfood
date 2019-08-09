<?php
	include_once('functions.php'); 
?>

	<?php 
	$sum = 0;
	$_SESSION['value_two'] = $sum;
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

		// get currency symbol from setting table
		$sql_query = "SELECT Value 
				FROM tbl_setting 
				WHERE Variable = 'Currency'";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($currency);
			$stmt->fetch();
			$stmt->close();
		}	
			
		if(empty($keyword)){
			$sql_query = "SELECT bill_id, table_no, total_price, discount, total_amount, bill_time
					FROM tbl_bill
					ORDER BY bill_id DESC";

		}else{
			$sql_query = "SELECT bill_id, table_no, total_price, discount, total_amount, bill_time
					FROM tbl_bill
					WHERE bill_time LIKE ? 
					ORDER BY bill_id DESC";
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
			$stmt->bind_result($data['bill_id'], 
					$data['table_no'],
					$data['total_price'],
					$data['discount'],
					$data['total_amount'],
					$data['bill_time']
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
			$sql_query = "SELECT bill_id, table_no, total_price, discount, total_amount, bill_time
					FROM tbl_bill
					ORDER BY bill_id DESC LIMIT ?, ?";
		}else{
			$sql_query = "SELECT bill_id, table_no, total_price, discount, total_amount, bill_time
					FROM tbl_bill
					WHERE bill_time LIKE ? 
					ORDER BY bill_id DESC LIMIT ?, ?";
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
			$stmt_paging->bind_result($data['bill_id'], 
					$data['table_no'],
					$data['total_price'],
					$data['discount'],
					$data['total_amount'],
					$data['bill_time']
					);
			// for paging purpose
			$total_records_paging = $total_records; 


		}


		// if no data on database show "No Bill is Available"
		if($total_records_paging == 0){
	
	?>

	<!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          	<div class="container">
            	<div class="row">
              		<div class="col s12 m12 l12">
               			<h5 class="breadcrumbs-title">bill List</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a>
		                  </li>
		                  <li><a href="#" class="active">Sales Report</a>
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
				                <table style="margin-left: 50px;">
				                	<tr>

<!-- 				                		<td width="40%">
				                			<div class="input-field col s12">
							                    <input type="text" class="validate" name="keyword">
							                    <label for="first_name">Search by Date :</label>
							                </div>
				                		</td> -->

				                		<?php date_default_timezone_set("Asia/Bangkok");  ?>

				                		<td style="width: 100px;"><input type="date" name="keyword" value="<?php echo date('Y-m-d'); ?>" /></td>
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
				                    <h5>No Bill Found!</h5>
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
               			<h5 class="breadcrumbs-title">Bill List</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a>
		                  </li>
		                  <li><a href="#" class="active">Sales Report</a>
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
				         
				                <table style="margin-left: 50px;">
				                	<tr>

<!-- 				                		<td width="40%">
				                			<div class="input-field col s12">
							                    <input type="text" class="validate" name="keyword">
							                    <label for="first_name">Search by Date :</label>
							                </div>
				                		</td> -->

				                		<?php date_default_timezone_set("Asia/Bangkok");  ?>

				                		<td style="width: 100px;"><input type="date" name="keyword" value="<?php echo date('Y-m-d'); ?>" /></td>
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
		            <div class="col s12 m12 l7">
		              	<div class="card-panel">
		                	<div class="row">
		                  		<div class="row">
		                    		<div class="input-field col s12">
	
										<table class='hoverable bordered'>
										<thead>
											<tr>
												<th>Bill No.</th>
												<th>Date & Time</th>
												<th>Table No.</th>
												<th>Total Price</th>
												<th>Discount</th>
												<th>Total Amount</th>
												<th>Action</th>
											</tr>
										</thead>

											<?php 
												while ($stmt_paging->fetch()){ ?>

												<?php $_SESSION['value_two'] += $data['total_amount'];  ?>


												<tbody>
													<tr>
														<td><center><?php echo $data['bill_id'];?></center></td>
														<td><?php echo $data['bill_time'];?></td>
														<td><center><?php echo $data['table_no'];?></center></td>
														<td><?php echo number_format($data['total_price'],2)." ".$currency;?></td>
														<td style="color: red">-<?php echo number_format($data['discount'],2)." ".$currency;?></td>
														<td style="color: green"><strong><?php echo number_format($data['total_amount'],2)." ".$currency;?></strong></td>
														<td>
															<a href="detail-bill.php?id=<?php echo $data['bill_id'];?>">
															<i class="mdi-action-pageview"></i>
															</a>
															<a href="delete-bill.php?id=<?php echo $data['bill_id'];?>">
														<i class="mdi-action-delete"></i>
															</a>
														</td>
													</tr>
												</tbody>
												<?php 
												

												?>

													<?php 
													} 


												}

											?>

										</table>

										

										<h4><?php $function->doPages($offset, 'bill.php', '', $total_records, $keyword); ?></h4>

		                    		</div>
		                  		</div>
		                	</div>
		              	</div>
		            </div>
<!-- Form with placeholder -->
						                <div class="col s12 m12 l5">
						                  <div class="card-panel">
						                    <div class="row">

						                    <h5><center><strong>Total Sales</strong></center></h5>
											<h3 style="color:green;"><center>฿&nbsp;<?php echo number_format($_SESSION['value_two'],2);  ?></center></h3>

						                     
						                     </div>
						                 </div>
						             </div>


		        </div>
        	</div>
        </div>

	</section>
					
				