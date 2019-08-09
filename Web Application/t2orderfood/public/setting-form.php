<?php
	include_once('functions.php'); 
?>

	<?php 
		if(isset($_POST['btnChange'])){
			$tax = $_POST['tax'];
			$currency = $_POST['currency'];
			
			// create array variable to handle error
			$error = array();
			
			if(empty($tax)){
				$tax = 0;
			}else if(!is_numeric($tax)){
				$error['tax'] = "*Tax should be in numeric.";
			}
				
			if(empty($currency)){
				$currency = "USD";
			}else{
				
				// update currency symbol in setting table
				$sql_query = "UPDATE tbl_setting
						SET Value = ? 
						WHERE Variable = 'Currency'";
				
				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('s', $currency);
					// Execute query
					$stmt->execute();
					// store result 
					$update_result = $stmt->store_result();
					$stmt->close();
				}
				
			}
			
			if(is_numeric($tax)){
			
				// update tax in setting table
				$sql_query = "UPDATE tbl_setting 
						SET Value = ? 
						WHERE Variable = 'Tax'";
				
				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('s', $tax);
					// Execute query
					$stmt->execute();
					// store result 
					$update_result = $stmt->store_result();
					$stmt->close();
				}
			}
			
			
			// check update result
			if($update_result) {
				$error['update_setting'] = "<div class='card-panel teal lighten-2'>
											    <span class='white-text text-darken-2'>
												    Setting Successfully Updated
											    </span>
											</div>";
			} else {
				$error['update_setting'] = "<div class='card-panel red darken-1'>
											    <span class='white-text text-darken-2'>
												    Update Failed
											    </span>
											</div>";
			}
			
		}		
		
		// get previous tax from setting table
		$sql_query = "SELECT Value 
				FROM tbl_setting 
				WHERE Variable = 'Tax'";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($previous_tax);
			$stmt->fetch();
			$stmt->close();
		}	
		
		// get previous currency symbol from setting table
		$sql_query = "SELECT Value 
				FROM tbl_setting 
				WHERE Variable = 'Currency'";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($previous_currency);
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
               			<h5 class="breadcrumbs-title">Tax & Currency</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a>
		                  </li>
		                  <li><a href="#" class="active">Tax & Currency</a>
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
		                 		<form method="post" class="col s12">
		                  			<div class="row">
		                    			<div class="input-field col s12">   
		                    				<?php echo isset($error['update_setting']) ? $error['update_setting'] : '';?>

											<div class="row">
						                      <div class="input-field col s12">
						                      <?php $previous_email = (isset($previous_email)) ? $previous_email : ''; ?>
												<input type="text" name="tax" value="<?php echo $previous_tax;?>" id="tax" value="<?php echo $previous_email; ?>" />
						                        <label for="tax">Tax (%)</label><?php echo isset($error['tax']) ? $error['tax'] : '';?>
						                      </div>
						                    </div>
																			
						                    <div class="row">
							                    <div class="input-field col s12">
													<select name="currency">
													<?php 
														$function = new functions;
														$arr_currency = $function->currency_info;
														$size = count($arr_currency);
														for($i=0;$i<$size;$i++){
														if($previous_currency == $arr_currency[$i]['code']){?>
															<option value="<?php echo $arr_currency[$i]['code']; ?>" selected="<?php echo $arr_currency[$i]['code']; ?>" ><?php echo $arr_currency[$i]['code']." - ".$arr_currency[$i]['name']; ?></option>
														<?php }else{ ?>
															<option value="<?php echo $arr_currency[$i]['code']; ?>" ><?php echo $arr_currency[$i]['code']." - ".$arr_currency[$i]['name']; ?></option>
														<?php }} ?>
													</select>
	                                            <label>Currency</label>
	                                            </div>	
                                            </div>

											<button class="btn cyan waves-effect waves-light right" type="submit" name="btnChange">Update
						                        <i class="mdi-content-send left"></i>
						                    </button>      					                     

						                </div>
						            </div>
						        </form>
						    </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>