	<?php 
			$username = $_SESSION['user'];
			$sql_query = "SELECT Password, staff_name 
					FROM tbl_user 
					WHERE Username = ?";
			
			// create array variable to store previous data
			$data = array();
			
			$stmt = $connect->stmt_init();
			if($stmt->prepare($sql_query)) {
				// Bind your variables to replace the ?s
				$stmt->bind_param('s', $username);			
				// Execute query
				$stmt->execute();
				// store result 
				$stmt->store_result();
				$stmt->bind_result($data['Password'], $data['staff_name']);
				$stmt->fetch();
				$stmt->close();
			}
			
			$previous_password = $data['Password'];
			
			if(isset($_POST['btnChange'])){
				$old_password = hash('sha256',$username.$_POST['old_password']);
				$new_password = hash('sha256',$username.$_POST['new_password']);
				$confirm_password = hash('sha256',$username.$_POST['confirm_password']);
				
				// create array variable to handle error
				$error = array();
				
				// check password
				if(!empty($_POST['old_password']) || !empty($_POST['new_password']) || !empty($_POST['confirm_password'])){
					if(!empty($_POST['old_password'])){
						if($old_password == $previous_password){
							if(!empty($_POST['new_password']) || !empty($_POST['confirm_password'])){
								if($new_password == $confirm_password){
									// update password in user table
									$sql_query = "UPDATE tbl_user 
											SET Password = ?
											WHERE Username = ?";
									
									$stmt = $connect->stmt_init();
									if($stmt->prepare($sql_query)) {	
										// Bind your variables to replace the ?s
										$stmt->bind_param('ss', 
													$new_password, 
													$username);
										// Execute query
										$stmt->execute();
										// store result 
										$update_result = $stmt->store_result();
										$stmt->close();
									}
								}else{
									$error['confirm_password'] = " <span class='label label-danger'>New password don't match!</span>";
								}
							}else{
								$error['confirm_password'] = " <span class='label label-danger'>New password and re new password required!</span>";
							}
						}else{
							$error['old_password'] = " <span class='label label-danger'>Old password wrong!</span>";
						}
					}else{
						$error['old_password'] = " <span class='label label-danger'>Old password required!</span>";
					}
				}
				
				
				// check update result
				if($update_result) {
					$error['update_user'] = "<div class='card-panel teal lighten-2'>
											    <span class='white-text text-darken-2'>
												    User Data Successfully Changed
											    </span>
											</div>";
				} else {
					$error['update_user'] = "<div class='card-panel red darken-1'>
											    <span class='white-text text-darken-2'>
												    Added Failed
											    </span>
											</div>";
				}
			}		

			$sql_query = "SELECT staff_name FROM tbl_user WHERE Username = ?";
			
			$stmt = $connect->stmt_init();
			if($stmt->prepare($sql_query)) {
				// Bind your variables to replace the ?s
				$stmt->bind_param('s', $username);			
				// Execute query
				$stmt->execute();
				// store result 
				$stmt->store_result();
				$stmt->bind_result($data['staff_name']);
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
               			<h5 class="breadcrumbs-title">Profile</h5>
		                <ol class="breadcrumb">
                <?php if($_SESSION['position'] == "Cashier") : ?>
                  <li><a href="dashboard_c.php">Dashboard</a>
                  </li>
                <?php else : ?>
                  <li><a href="dashboard.php">Dashboard</a>
                  </li>
                <?php endif; ?>
		                  <li><a href="#" class="active">Profile</a>
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
		                    				<?php echo isset($error['update_user']) ? $error['update_user'] : '';?>

											<div class="row">
						                      <div class="input-field col s12">
						                        <input type="text" name="username" id="username" value="<?php echo $username; ?>" disabled/>
						                        <label for="username">Username</label>
						                      </div>
						                    </div> 

										<div class="row">
						                      <div class="input-field col s12">
						                        <input type="text" name="staff_name" id="staff_name" value="<?php echo $data['staff_name']; ?>" disabled/>
						                        <label for="staff_name">Name</label>
						                      </div>
						                    </div> 
										
											<div class="row">
						                      <div class="input-field col s12">
						                        <input type="password" name="old_password" id="old_password" value="" />
						                        <label for="old_password">Old Password</label><?php echo isset($error['old_password']) ? $error['old_password'] : '';?>
						                      </div>
						                    </div>

											<div class="row">
						                      <div class="input-field col s12">
						                        <input type="password" name="new_password" id="new_password" value="" />
						                        <label for="new_password">New Password</label><?php echo isset($error['new_password']) ? $error['new_password'] : '';?>
						                      </div>
						                    </div>  

											<div class="row">
						                      <div class="input-field col s12">
						                        <input type="password" name="confirm_password" id="confirm_password" value="" />
						                        <label for="confirm_password">Re Type New Password</label><?php echo isset($error['confirm_password']) ? $error['confirm_password'] : '';?>
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
