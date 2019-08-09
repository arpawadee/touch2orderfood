<?php 
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
			
		// create array variable to handle error
		$error = array();
			
		// create array variable to store data from database
		$data = array();

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

			

		// get data from order table
		$sql_query = "SELECT * 
				FROM tbl_bill 
				WHERE bill_id = ?";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['bill_id'], 
					$data['table_no'],
					$data['order_list'], 
					$data['total_price'], 
					$data['discount'],
					$data['total_amount'],
					$data['bill_cash'],
					$data['bill_change'],
					$data['bill_time']
					);
			$stmt->fetch();
			$stmt->close();
		}
		
		// parse order list into array
		$order_list = explode(',',$data['order_list']);




			
	?>


	<!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          	<div class="container">
            	<div class="row">
              		<div class="col s12 m12 l12">
               			<h5 class="breadcrumbs-title">Bill Detail</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a>
		                  </li>
		                  <li><a href="#" class="active">Bill Detail</a>
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
		                    		<div class="input-field col s12">
		                    			<form method="post" class="col s12">

											<table class='bordered'>
												<tr>
													<th>Bill No.</th>
													<td><?php echo $data['bill_id']; ?></td>
												</tr>
												<tr>
													<th>Date & Time</th>
													<td><?php echo $data['bill_time']; ?></td>
												</tr>
												<tr>
													<th>Table No.</th>
													<td><?php echo $data['table_no'];?></td>
												</tr>
												<tr>
													<th>Order list</th>
													<td>
														<ul>
														<?php
															$count = count($order_list);
															for($i = 0;$i<$count;$i++){
																// if($i == ($count -1)){
																// 	echo "<br /><li><strong>".$order_list[$i]."</strong></li>";
																// }else{
																	echo "<li>".$order_list[$i]."</li>";
																//}
															}
														?>
														</ul>
													</td>
												</tr>
												<tr>
													<th>Total Order</th>
													<td><?php echo number_format($data['total_price'],2)." ".$currency; ?></td>
												</tr>
												<tr>
													<th>Discount</th>
													<td style="color: red">-<?php echo number_format($data['discount'],2)." ".$currency;?></td>
												</tr>
												<tr>
													<th>Total Amount</th>
													<td><strong><?php echo number_format($data['total_amount'],2)." ".$currency;?></strong></td>
												</tr>
<!-- 												<tr>
													<th>Cash</th>
													<td><?php echo number_format($data['bill_cash'],2)." ".$currency; ?></td>
												</tr>
												<tr>
													<th>Change</th>
													<td><?php echo number_format($data['bill_change'],2)." ".$currency; ?></td>
												</tr> -->


											</table>
											<br>
										
										</form>		           
		                    		</div>
		                  		</div>
		                	</div>
		              	</div>
		            </div>
		        </div>
        	</div>
        </div>

	</section>