<?php
	include_once('functions.php'); 
?>

	<?php 
	
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
			
		
		if(isset($_POST['btnEdit'])){
			
		$staff_name = $_POST['staff_name'];
		$Email = $_POST['Email'];
		$tel = $_POST['tel'];
		$address = $_POST['address'];
		$position = $_POST['position'];
			
			
		// create array variable to handle error
		$error = array();


		if(empty($staff_name)){
			$error['staff_name'] = " <span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
		}

		if(empty($Email)){
			$error['Email'] = " <span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
		}

		if(empty($tel)){
			$error['tel'] = " <span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
		}else if(!is_numeric($tel)){
			$error['tel'] = " <span class='red darken-4 white-text text-darken-2'>&nbsp; phone in number! &nbsp;</span>";
		}

		if(empty($address)){
			$error['Password'] = " <span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
		}

		if(empty($position)){
			$error['Password'] = " <span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
		}


			
					
		if(!empty($staff_name) && !empty($Email) && !empty($tel) && !empty($address) && !empty($position)){


			$sql_query = "UPDATE tbl_user
						  SET staff_name = ? , Email = ?, tel = ?, address = ?, position = ?
						  WHERE ID = ?"; 


			$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('ssssss',  
								$staff_name,
								$Email,
								$tel,
								$address,
								$position,
								$ID
								);
					// Execute query
					$stmt->execute();
					// store result 
					$update_result = $stmt->store_result();
					$stmt->close();
				}

			if($update_result) {
					$error['update_data'] = "<div class='card-panel teal lighten-2'>
											    <span class='white-text text-darken-2'>
												   New Staff Updated Successfully
											    </span>
											</div>";
				} else {
					$error['update_data'] = "<div class='card-panel red darken-1'>
											    <span class='white-text text-darken-2'>
												    Updated Failed
											    </span>
											</div>";
				}

		}
			
	}
		
		// create array variable to store previous data
		$data = array();
			
		$sql_query = "SELECT * FROM tbl_user WHERE ID = ?";
			
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['ID'],
					$data['Username'],
					$data['Password'], 
					$data['staff_name'],
					$data['Email'],
					$data['tel'],
					$data['address'],
					$data['position']
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
               			<h5 class="breadcrumbs-title">Edit Staff</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a>
		                  </li>
		                  <li><a href="#" class="active">Edit Staff</a>
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
		                    				<?php echo isset($error['update_data']) ? $error['update_data'] : '';?>   
            
								              <div class="row">
								                <div class="col s12 m12 l5">
								                  <div class="card-panel">
								                  	<div class="row">

								                      <div class="input-field col s12">
								                        <input type="text" name="staff_name" id="staff_name" value="<?php echo $data['staff_name']; ?>" required/>
								                        <label for="staff_name">Name</label><?php echo isset($error['staff_name']) ? $error['staff_name'] : '';?>
								                      </div>							                   
													
								                      <div class="input-field col s12">
								                        <input type="text" name="Email" id="Email" value="<?php echo $data['Email']; ?>" required/>
						                        		<label for="Email">Email</label><?php echo isset($error['Email']) ? $error['Email'] : '';?>
								                      </div>
								                   
						                      <div class="input-field col s12">
						                        <input type="text" name="tel" id="tel" value="<?php echo $data['tel']; ?>"required/>
						                        <label for="tel">Phone Number</label><?php echo isset($error['tel']) ? $error['tel'] : '';?>
						                      </div>

						                      <div class="input-field col s12">
						                        <input type="text" name="address" id="address" value="<?php echo $data['address']; ?>"required/>
						                        <label for="address">Address</label><?php echo isset($error['address']) ? $error['address'] : '';?>
						                      </div>

						                      <div class="input-field col s12">
						                        <input type="text" name="position" id="position" value="<?php echo $data['position']; ?>" required/>
						                        <label for="position">Position</label><?php echo isset($error['position']) ? $error['position'] : '';?>
						                      </div>

						                     <div class="input-field col s12">
											  <button class="btn cyan waves-effect waves-light right"
	                                                type="submit" name="btnEdit">Update
	                                            <i class="mdi-content-send right"></i>
	                                        </button>		                                      
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