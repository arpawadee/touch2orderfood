<?php
	include_once('functions.php'); 
?>

<?php 
	
	if(isset($_POST['btnAdd'])){

		$Username = $_POST['Username'];
		$Password = hash('sha256',$Username.$_POST['Password']);
		$confirm_password = hash('sha256',$Username.$_POST['confirm_password']);
		$staff_name = $_POST['staff_name'];
		$Email = $_POST['Email'];
		$tel = $_POST['tel'];
		$address = $_POST['address'];
		$position = $_POST['position'];


		// create array variable to handle error
		$error = array();


		if(empty($Username)){
			$error['Username'] = " <span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
		}

		if(empty($Password)){
			$error['Password'] = " <span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
		}

		if(empty($confirm_password)){
			$error['confirm_password'] = " <span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
		}

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


		if(!empty($Username) && !empty($_POST['Password']) && !empty($_POST['confirm_password']) && !empty($staff_name) && !empty($Email) && !empty($tel) && !empty($address) && !empty($position)){

			if($Password == $confirm_password){

			$sql_query = "INSERT INTO tbl_user (Username, Password, staff_name, Email, tel, address, position)
						VALUES(?, ?, ?, ?, ?, ?, ?)";


			$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('sssssss', 
								$Username,
								$Password, 
								$staff_name,
								$Email,
								$tel,
								$address,
								$position
								);
					// Execute query
					$stmt->execute();
					// store result 
					$result = $stmt->store_result();
					$stmt->close();
				}

			if($result) {
					$error['add_staff'] = "<div class='card-panel teal lighten-2'>
											    <span class='white-text text-darken-2'>
												   New Staff Added Successfully
											    </span>
											</div>";
				} else {
					$error['add_staff'] = "<div class='card-panel red darken-1'>
											    <span class='white-text text-darken-2'>
												    Added Failed
											    </span>
											</div>";
				}

			}


		}
	}

?>

	<!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          	<div class="container">
            	<div class="row">
              		<div class="col s12 m12 l12">
               			<h5 class="breadcrumbs-title">Add New Staff</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a>
		                  </li>
		                  <li><a href="#" class="active">Add New Staff</a>
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
		                 		<form method="post" class="col s12" enctype="multipart/form-data">
		                  			<div class="row">
		                    			<div class="input-field col s12">   
		                    				<?php echo isset($error['add_staff']) ? $error['add_staff'] : '';?>       
											
											<div class="row">
						                      <div class="input-field col s12">
						                        <input type="text" name="Username" id="Username" required/>
						                        <label for="Username">Username</label><?php echo isset($error['Username']) ? $error['Username'] : '';?>
						                      </div>
						                    </div>

											<div class="row">
						                      <div class="input-field col s12">
						                        <input type="text" name="Password" id="Password" required/>
						                        <label for="Password">Password</label><?php echo isset($error['Password']) ? $error['Password'] : '';?>
						                      </div>
						                    </div> 						                	

											<div class="row">
						                      <div class="input-field col s12">
						                        <input type="text" name="confirm_password" id="confirm_password" required/>
						                        <label for="confirm_password">Confirm Password</label><?php echo isset($error['confirm_password']) ? $error['confirm_password'] : '';?>
						                      </div>
						                    </div>

						                    <div class="row">
						                      <div class="input-field col s12">
						                        <input type="text" name="staff_name" id="staff_name" required/>
						                        <label for="staff_name">Name</label><?php echo isset($error['staff_name']) ? $error['staff_name'] : '';?>
						                      </div>
						                    </div>

											<div class="row">
						                      <div class="input-field col s12">
						                        <input type="text" name="Email" id="Email" required/>
						                        <label for="Email">Email</label><?php echo isset($error['Email']) ? $error['Email'] : '';?>
						                      </div>
						                    </div> 

											<div class="row">
						                      <div class="input-field col s12">
						                        <input type="text" name="tel" id="tel" required/>
						                        <label for="tel">Phone Number</label><?php echo isset($error['tel']) ? $error['tel'] : '';?>
						                      </div>
						                    </div>

											<div class="row">
						                      <div class="input-field col s12">
						                        <input type="text" name="address" id="address" required/>
						                        <label for="address">Address</label><?php echo isset($error['address']) ? $error['address'] : '';?>
						                      </div>
						                    </div>

											<div class="row">
						                      <div class="input-field col s12">
						                        <input type="text" name="position" id="position" required/>
						                        <label for="position">Position</label><?php echo isset($error['position']) ? $error['position'] : '';?>
						                      </div>
						                    </div> 						                     



		                                     <div class="input-field col s12">
											  <button class="btn cyan waves-effect waves-light right"
	                                                type="submit" name="btnAdd">Add New Staff
	                                            <i class="mdi-content-send right"></i>
	                                        </button>		                                      
						                    </div>  
											
                               

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