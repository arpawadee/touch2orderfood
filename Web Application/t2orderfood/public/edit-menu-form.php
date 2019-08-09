<?php
	include_once('functions.php'); 
?>

	<?php 
	
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
		// create array variable to store category data
		$category_data = array();
			
		$sql_query = "SELECT category_id, category_name 
				FROM tbl_category 
				ORDER BY category_id ASC";
				
		$stmt_category = $connect->stmt_init();
		if($stmt_category->prepare($sql_query)) {	
			// Execute query
			$stmt_category->execute();
			// store result 
			$stmt_category->store_result();
			$stmt_category->bind_result($category_data['category_id'], 
				$category_data['category_name']
				);
				
		}
			
		$sql_query = "SELECT menu_image FROM tbl_menu WHERE menu_id = ?";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($previous_menu_image);
			$stmt->fetch();
			$stmt->close();
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
		
		
		if(isset($_POST['btnEdit'])){
			
			$menu_name = $_POST['menu_name'];
			$category_id = $_POST['category_id'];
			$price = $_POST['price'];
			$menu_status = $_POST['menu_status'];
			$menu_description = $_POST['menu_description'];
			$serve_for = $_POST['serve_for'];
			
			// get image info
			$menu_image = $_FILES['menu_image']['name'];
			$image_error = $_FILES['menu_image']['error'];
			$image_type = $_FILES['menu_image']['type'];
				
			// create array variable to handle error
			$error = array();
			
			if(empty($menu_name)){
				$error['menu_name'] = "<span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
			}
				
			if(empty($category_id)){
				$error['category_id'] = "<span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
			}				
				
			if(empty($price)){
				$error['price'] = "<span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
			}else if(!is_numeric($price)){
				$error['price'] = "<span class='red darken-4 white-text text-darken-2'>&nbsp; price in number! &nbsp;</span>";
			}

			if(empty($serve_for)){
				$error['serve_for'] = "<span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
			}else if(!is_numeric($serve_for)){
				$error['serve_for'] = "<span class='red darken-4 white-text text-darken-2'>&nbsp; serve_for in number! &nbsp;</span>";
			}
				
			if(empty($menu_status)){
				$error['menu_status'] = "<span class='red darken-4 white-text text-darken-2'>&nbsp; Not choosen &nbsp;</span>";
			}			

			if(empty($menu_description)){
				$error['menu_description'] = "<span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
			}
			
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			error_reporting(E_ERROR | E_PARSE);
			$extension = end(explode(".", $_FILES["menu_image"]["name"]));
			
			if(!empty($menu_image)){
				if(!(($image_type == "image/gif") || 
					($image_type == "image/jpeg") || 
					($image_type == "image/jpg") || 
					($image_type == "image/x-png") ||
					($image_type == "image/png") || 
					($image_type == "image/pjpeg")) &&
					!(in_array($extension, $allowedExts))){
					
					$error['menu_image'] = "*<span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
				}
			}
			
					
			if(!empty($menu_name) && !empty($category_id) && !empty($price) && is_numeric($price) &&
				!empty($menu_status) && !empty($menu_description) && empty($error['menu_image']) && !empty($serve_for) && is_numeric($serve_for)){
				
				if(!empty($menu_image)){
					
					// create random image file name
					$string = '0123456789';
					$file = preg_replace("/\s+/", "_", $_FILES['menu_image']['name']);
					$function = new functions;
					$menu_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
				
					// delete previous image
					$delete = unlink("$previous_menu_image");
					
					// upload new image
					$upload = move_uploaded_file($_FILES['menu_image']['tmp_name'], 'upload/images/'.$menu_image);
	  
					// updating all data
					$sql_query = "UPDATE tbl_menu 
							SET menu_name = ? , category_id = ?, price = ?, menu_status = ?, menu_image = ?, menu_description = ?, serve_for = ? 
							WHERE menu_id = ?";
					
					$upload_image = 'upload/images/'.$menu_image;
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('ssssssss', 
									$menu_name, 
									$category_id, 
									$price, 
									$menu_status, 
									$upload_image,
									$menu_description,
									$serve_for,
									$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}else{
					
					// updating all data except image file
					$sql_query = "UPDATE tbl_menu 
							SET menu_name = ? , category_id = ?, 
							price = ?, menu_status = ?, menu_description = ?, serve_for = ? 
							WHERE menu_id = ?";
							
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('sssssss', 
									$menu_name, 
									$category_id, 
									$price, 
									$menu_status, 
									$menu_description,
									$serve_for,
									$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}
					
				// check update result
				if($update_result) {
					$error['update_data'] = "<div class='card-panel teal lighten-2'>
											    <span class='white-text text-darken-2'>
												   Menu Updated Successfully
											    </span>
											</div>";
				} else {
					$error['update_data'] = "<div class='card-panel red darken-1'>
											    <span class='white-text text-darken-2'>
												    Added Failed
											    </span>
											</div>";
				}
			}
			
		}
		
		// create array variable to store previous data
		$data = array();
			
		$sql_query = "SELECT * FROM tbl_menu WHERE menu_id = ?";
			
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['menu_id'], 
					$data['menu_name'], 
					$data['category_id'], 
					$data['price'], 
					$data['menu_status'], 
					$data['menu_image'],
					$data['menu_description'],
					$data['serve_for']
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
               			<h5 class="breadcrumbs-title">Edit Menu</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a>
		                  </li>
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
		                    				<?php echo isset($error['update_data']) ? $error['update_data'] : '';?>   
            
								              <div class="row">
								                <div class="col s12 m12 l5">
								                  <div class="card-panel">
								                  	<div class="row">

								                      <div class="input-field col s12">
								                        <input type="text" name="menu_name" id="menu_name" value="<?php echo $data['menu_name']; ?>" required/>
								                        <label for="menu_name">Menu Name</label><?php echo isset($error['menu_name']) ? $error['menu_name'] : '';?>
								                      </div>							                   
													
								                      <div class="input-field col s12">
								                        <input type="text" name="price" id="price" value="<?php echo $data['price'];?>" required/>
								                        <label for="price">price (<?php echo $currency;?>)</label><?php echo isset($error['price']) ? $error['price'] : '';?>
								                      </div>
								                   

								                      <div class="input-field col s12">
								                        <input type="text" name="serve_for" id="serve_for" value="<?php echo $data['serve_for'];?>" required/>
								                        <label for="serve_for">Serve for (People)</label><?php echo isset($error['serve_for']) ? $error['serve_for'] : '';?>
								                      </div>

									                    <div class="input-field col s12">
			                                            <select name="menu_status">
			                                            	<option>Available</option>
															<option>Sold Out</option>
			                                            </select>
			                                            <label>Status</label><?php echo isset($error['menu_status']) ? $error['menu_status'] : '';?></div>	
		                                        

									                    <div class="input-field col s12">
				                                            <select name="category_id">
																<?php while($stmt_category->fetch()){ 
																if($category_data['category_id'] == $data['category_id']){?>
																	<option value="<?php echo $category_data['category_id']; ?>" selected="<?php echo $data['category_id']; ?>" ><?php echo $category_data['category_name']; ?></option>
																<?php }else{ ?>
																	<option value="<?php echo $category_data['category_id']; ?>" ><?php echo $category_data['category_name']; ?></option>
																<?php }} ?>
				                                            </select>
				                                            <label>Category</label><?php echo isset($error['category_id']) ? $error['category_id'] : '';?>
			                                            </div>			                                         

		                                            <img src="<?php echo $data['menu_image']; ?>" width="210" height="160"/>		                                        	
														<div class="file-field input-field col s12">
														<?php echo isset($error['menu_image']) ? $error['menu_image'] : '';?>
				                                            <input class="file-path validate" type="text" disabled/>
				                                            <div class="btn">
				                                                <span>Image</span>
				                                                <input type="file" name="menu_image" id="menu_image" value="" />
				                                            </div>
				                                        </div>
				                                        
		                                            </div>

							                  </div>
							                </div>

						                <!-- Form with placeholder -->
						                <div class="col s12 m12 l7">
						                  <div class="card-panel">

						                    <div class="row">
						                      <div class="input-field col s12">
						                       <?php echo isset($error['menu_description']) ? $error['menu_description'] : '';?>
												<textarea name="menu_description" id="menu_description" class="materialize-textarea" rows="16"><?php echo $data['menu_description']; ?></textarea>
												<script type="text/javascript" src="assets/js/ckeditor/ckeditor.js"></script>
												<script type="text/javascript">                        
										            CKEDITOR.replace( 'menu_description' );
										            CKEDITOR.config.height=285;
										        </script>		
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