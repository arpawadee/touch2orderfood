<?php 
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
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


	if (isset($_POST['btnDiscount'])) {

	$sql_query = "SELECT t.table_id, t.table_no, t.table_status, o.ID, o.order_list, o.total_price, o.status
				FROM tbl_table t, tbl_order o
				WHERE t.table_id = ? AND t.table_no = o.table_no AND o.bill_status = 0 AND o.status <> 'CANCELED' ";


		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['table_id'], 
					$data['table_no'], 
					$data['table_status'],
					$data['ID'], 
					$data['order_list'],
					$data['total_price'],
					$data['status']
					);
			$stmt->fetch();
			$stmt->close();
		}

	if( isset($_POST['bill_cash']) ){
    $_SESSION['value_one'] = $_POST['bill_cash'];
	}

	$discount = $_POST['bill_cash'];
	$tor =  $data['total_price'];
	$tot = $tor - (($discount*$tor)/100);
	$discount_i = (($discount*$tor)/100);

	}	



	if(isset($_POST['btnCash'])){



		$sql_query = "SELECT t.table_id, t.table_no, t.table_status, o.ID, o.order_list, o.total_price, o.status
				FROM tbl_table t, tbl_order o
				WHERE t.table_id = ? AND t.table_no = o.table_no AND o.bill_status = 0 AND o.status <> 'CANCELED' ";


		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['table_id'], 
					$data['table_no'], 
					$data['table_status'],
					$data['ID'], 
					$data['order_list'],
					$data['total_price'],
					$data['status']
					);
			$stmt->fetch();
			$stmt->close();
		}

			$r1 = $data['table_no'];
			$r2 = $data['order_list'];
			$r3 = $data['total_price'];
			date_default_timezone_set("Asia/Bangkok");
			$t = date("Y-m-d H:i:s"); 

			//รับเงินทอน
			$bill_cash = $_POST['bill_cash'];
			$error = array();

			if(empty($bill_cash)){
				$error['bill_cash'] = " <span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
			}else if(!is_numeric($bill_cash)){
				$error['bill_cash'] = " <span class='red darken-4 white-text text-darken-2'>&nbsp; price in number! &nbsp;</span>";
			}



			if (!empty($_SESSION['value_one'])) {
				$bill_change = $bill_cash - ($data['total_price'] - (($_SESSION['value_one']*$data['total_price'])/100));
				$to_t = ($data['total_price'] - (($_SESSION['value_one']*$data['total_price'])/100));
				$discount_i2 = (($_SESSION['value_one']*$data['total_price'])/100);

				$sql_query = "INSERT INTO tbl_bill (table_no, order_list, total_price, discount, total_amount, bill_cash, bill_change, bill_time) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
				// Bind your variables to replace the ?s
				$stmt->bind_param('ssssssss', 
									$r1,   
									$r2,
									$r3,
									$discount_i2,
									$to_t,
									$bill_cash,
									$bill_change,
									$t
									);
				// Execute query
				$stmt->execute();
				$result = $stmt->affected_rows;
				// store result 
				//$result = $stmt->store_result();
				$stmt->close();
				}

					if($result){
						$error['add_bill'] = "<div class='card-panel teal lighten-2'>
											    <span class='white-text text-darken-2'>
												   New Bill Added Successfully
											    </span>
											</div>";

						$bill_status 		= 1;
						$table_status       = "OFFLINE";



						$sql_query2 = "UPDATE tbl_order o,tbl_table t 
								SET o.bill_status = ? , t.table_status = ? 
								WHERE o.table_no = t.table_no AND t.table_id = ? AND o.status <> 'CANCELED'";

						$stmt = $connect->stmt_init();
						if($stmt->prepare($sql_query2)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('sss', 
								$bill_status,
								$table_status,
								$ID
								);
						// Execute query
						$stmt->execute();
						$update_result = $stmt->affected_rows;
	
						$stmt->close();
						}
					} 

					else {
						$error['add_bill'] = "<div class='card-panel red darken-1'>
											    <span class='white-text text-darken-2'>
												    Added Failed
											    </span>
											</div>";
					}
			}
			else{
				$bill_change = $bill_cash - $r3;
				$sql_query = "INSERT INTO tbl_bill (table_no, order_list, total_price, total_amount, bill_cash, bill_change, bill_time) 
					VALUES (?, ?, ?, ?, ?, ?, ?)";

				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
				// Bind your variables to replace the ?s
				$stmt->bind_param('sssssss', 
								$r1,   
								$r2,
								$r3,
								$r3,
								$bill_cash,
								$bill_change,
								$t
								);
				// Execute query
				$stmt->execute();
				$result = $stmt->affected_rows;
				// store result 
				//$result = $stmt->store_result();
				$stmt->close();
				}

					if($result){
						$error['add_bill'] = "<div class='card-panel teal lighten-2'>
											    <span class='white-text text-darken-2'>
												   New Bill Added Successfully
											    </span>
											</div>";

						$bill_status 		= 1;
						$table_status       = "OFFLINE";



						$sql_query2 = "UPDATE tbl_order o,tbl_table t 
								SET o.bill_status = ? , t.table_status = ? 
								WHERE o.table_no = t.table_no AND t.table_id = ? AND o.status <> 'CANCELED'";

						$stmt = $connect->stmt_init();
						if($stmt->prepare($sql_query2)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('sss', 
								$bill_status,
								$table_status,
								$ID
								);
						// Execute query
						$stmt->execute();
						$update_result = $stmt->affected_rows;
	
						$stmt->close();
						}
					} 

					else {
						$error['add_bill'] = "<div class='card-panel red darken-1'>
											    <span class='white-text text-darken-2'>
												    Added Failed
											    </span>
											</div>";
					}
				}
			



		$r2 = explode(',',$data['order_list']);
		
		

		}

		
		
		// get data from table and order 
		$sql_query = "SELECT t.table_id, t.table_no, t.table_status, o.order_list, o.total_price, o.status
				FROM tbl_table t, tbl_order o
				WHERE t.table_id = ? AND t.table_no = o.table_no AND o.bill_status = 0 AND o.status <> 'CANCELED' ";


		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['table_id'], 
					$data['table_no'], 
					$data['table_status'], 
					$data['order_list'],
					$data['total_price'],
					$data['status']
					);
			$stmt->fetch();
			$stmt->close();
		}

		// parse order list into array
		$order_list = explode(',',$data['order_list']);

		
		if (empty($data['table_no'])) {

			$sql_query = "SELECT table_id, table_no, table_status
				FROM tbl_table 
				WHERE table_id = ? ";

			$stmt = $connect->stmt_init();
			if($stmt->prepare($sql_query)) {	
				// Bind your variables to replace the ?s
				$stmt->bind_param('s', $ID);
				// Execute query
				$stmt->execute();
				// store result 
				$stmt->store_result();
				$stmt->bind_result($data['table_id'], 
					$data['table_no'], 
					$data['table_status']
					);
				$stmt->fetch();
				$stmt->close();
			}	

		}

	?>



  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>


	<!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          	<div class="container">
            	<div class="row">
              		<div class="col s12 m12 l12">
               			<h5 class="breadcrumbs-title">Edit Menu</h5>
		                <ol class="breadcrumb">
                <?php if($_SESSION['position'] == "Cashier") : ?>
                  <li><a href="dashboard_c.php">Dashboard</a>
                  </li>
                <?php else : ?>
                  <li><a href="dashboard.php">Dashboard</a>
                  </li>
                <?php endif; ?>
		                  <li><a href="#" class="active">Edit Menu</a>
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
		                	<div class="row">
		                 		<form method="post" class="col s12" enctype="multipart/form-data">
		                  			<div class="row">
		                    			<div class="input-field col s12">   
		                    				<?php echo isset($error['add_bill']) ? $error['add_bill'] : '';?>   
            
								              <div class="row">
								                <div class="col s12 m12 l7">
								                  <div class="card-panel">
								                  	<div class="row">
								                  			<table class="bordered">
												<tr>
													<th>Table No.</th>
													<td><?php echo $data['table_no']; ?>
													</td>
												</tr>
												<tr>
													<th>Status</th>
													<td><?php echo $data['table_status']; ?></td>
												</tr>
												

												<?php if($data['total_price'] == NULL) : ?>	
												<table class="bordered">
													<tr>
														<td>
															<br><br>
															<center>
																 Don't Have Order !
															</center>
															<br><br>
														</td>
													</tr>

												</table>				
												
												<?php else : ?>
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
													<th>Total Price</th>
													<td><?php echo number_format($data['total_price'],2)." ".$currency; ?>
													</td>
												
												</tr>
												<?php endif; ?>
							
											</table>
				                                        
		                                            </div>

							                  </div>
							                </div>

						                <!-- Form with placeholder -->
						                <div class="col s12 m12 l5">
						                  <div class="card-panel">

						                    <div class="row">
						                     

									<!-- 	<div class="input-field col s12">
											<center>
											  <button class="btn light-blue waves-effect waves-light"
	                                                 type="submit" name="btnPrint">Print Invoice
	                                            <i class="mdi-maps-local-print-shop right"></i>
	                                        </button>	
	                                        </center>


						                    </div>  -->

						                     <center>
						                    <button type="submit" name="btnPrint" class="btn light-blue waves-effect waves-light" data-toggle="modal" data-target="#myModal">Print Invoice<i class="mdi-maps-local-print-shop right"></i></button>

						                    </center>

						                 
								            <div class="input-field col s12">
								                <input type="text" name="bill_cash" id="bill_cash" required/>
								                <label for="bill_cash">Text for exact amount / Discount</label><?php echo isset($error['bill_cash']) ? $error['bill_cash'] : '';?>
								            </div>
								             
								             


						                  <div class="input-field col s12">
											
											  <button class="btn deep-orange waves-effect waves-light right"
	                                                type="submit" name="btnCash" style="margin-right: 60px; width: 160px;">Cash
	                                            <i class="mdi-maps-local-atm right"></i>
	                                        </button>

	                                        <button class="btn grey waves-effect waves-light"
	                                                type="submit" name="btnDiscount" style="margin-left: 60px; width: 160px;">Discount &nbsp;%
	                                           <!--  <i class="mdi-maps-local-atm right"></i> -->
	                                        </button>


	                                       	                                      
						                    </div> 



						                      <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog" style="width:300px; background:#FEFEFE; top: 50px; max-height: 1000px;">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <object align="right">
          <button type="button" class="close" data-dismiss="modal" style="border: #FFFFFF; background:#FEFEFE;">&times;</button>
       </object>
       <center>
       	<br>
          <h6 class="modal-title"><strong>PIZZA BAE</strong></h6>
       </center>
        </div>
        <div class="modal-body">
<p>------------------------------------------------------------
Table#<?php echo $data['table_no'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo $_SESSION['c_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php date_default_timezone_set("Asia/Bangkok"); 
	  echo date("Y-m-d H:i:s");

?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-------------------------------------------------------------</p>
<?php if(empty($bill_cash)) : ?>	
<center>												<ul>
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

</center>
<p>------------------------------------------------------------</p>
	<?php if(empty($discount)) : ?>
<strong><p style="font-size: 16px;">TOTAL AMOUNT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;฿<?php echo number_format($data['total_price'],2); ?></p></strong>
	<?php else : ?>
<p>TOTAL ORDER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;฿<?php echo number_format($data['total_price'],2); ?></p>
<p>Discount&nbsp;<?php echo $discount; ?>%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-฿<?php echo number_format($discount_i,2); ?></p>
<strong><p style="font-size: 16px;">TOTAL AMOUNT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;฿<?php echo number_format($tot,2); ?></p></strong>
	<?php endif; ?>


<?php else : ?>
<center>												<ul>
														<?php

															$count = count($r2);
															for($i = 0;$i<$count;$i++){
																// if($i == ($count -1)){
																// 	echo "<br /><li><strong>".$r2[$i]."</strong></li>";
																// }else{
																	echo "<li>".$r2[$i]."</li>";
																//}
															}

														?>
														</ul>

</center>
<p>------------------------------------------------------------</p>
<?php if(empty(($_SESSION['value_one']))) : ?> 
<strong><p style="font-size: 16px;">TOTAL AMOUNT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;฿<?php echo number_format($r3,2); ?></p></strong>
<p>CASH&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;฿<?php echo number_format($bill_cash,2); ?></p>
<p>CHANGE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;฿<?php echo number_format($bill_change,2); ?></p>
	<?php else : ?>
<p>TOTAL ORDER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;฿<?php echo $r3; ?>.00</p>
<p>Discount&nbsp;<?php echo $_SESSION['value_one']; ?>%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-฿<?php echo number_format($discount_i2,2); ?></p>
<strong><p style="font-size: 16px;">TOTAL AMOUNT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;฿<?php echo number_format($to_t,2); ?></p></strong>
<p>CASH&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;฿<?php echo number_format($bill_cash,2); ?></p>
<p>CHANGE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;฿<?php echo number_format($bill_change,2); ?></p>
<?php unset($_SESSION['value_one']); ?>

	<?php endif; ?>


<?php endif; ?>

<p>------------------------------------------------------------</p>
<p><center>******THANK YOU!******</center></p>

        </div>
  
      </div>
      
    </div>
  </div>




						                    </div>					                    			                    

						                  </div>
						                </div>

              						</div>


									</div>
						        </div>
						    </form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>