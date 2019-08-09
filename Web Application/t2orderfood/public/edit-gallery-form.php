<?php
	include_once('functions.php'); 
	require_once("thumbnail_images.class.php");
?>

	<?php 
	
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
		// create array variable to store category data
		$category_data = array();
			
		$sql_query = "SELECT gallery_image FROM tbl_gallery WHERE gid = ?";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($previous_gallery_image);
			$stmt->fetch();
			$stmt->close();
		}
		
		
		if(isset($_POST['btnEdit'])){
			
			$gallery_name = $_POST['gallery_name'];
			$gallery_description = $_POST['gallery_description'];
			
			// get image info
			$gallery_image = $_FILES['gallery_image']['name'];
			$image_error = $_FILES['gallery_image']['error'];
			$image_type = $_FILES['gallery_image']['type'];
				
			// create array variable to handle error
			$error = array();
			
			if(empty($gallery_name)){
				$error['gallery_name'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}										

			if(empty($gallery_description)){
				$error['gallery_description'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}
			
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			error_reporting(E_ERROR | E_PARSE);
			$extension = end(explode(".", $_FILES["gallery_image"]["name"]));
			
			if(!empty($gallery_image)){
				if(!(($image_type == "image/gif") || 
					($image_type == "image/jpeg") || 
					($image_type == "image/jpg") || 
					($image_type == "image/x-png") ||
					($image_type == "image/png") || 
					($image_type == "image/pjpeg")) &&
					!(in_array($extension, $allowedExts))){
					
					$error['gallery_image'] = "*<span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
				}
			}
			
					
			if( !empty($gallery_name) && 
				!empty($gallery_description) && 
				empty($error['gallery_image'])){
				
				if(!empty($gallery_image)){
					
					// create random image file name
					$string = '0123456789';
					$file = preg_replace("/\s+/", "_", $_FILES['gallery_image']['name']);
					$function = new functions;
					$gallery_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
				
					// delete previous image
					$delete = unlink('upload/gallery/'."$previous_gallery_image");
					$delete = unlink('upload/gallery/thumbs/'."$previous_gallery_image");
					
					// upload new image
					$unggah = 'upload/gallery/'.$gallery_image;
					$upload = move_uploaded_file($_FILES['gallery_image']['tmp_name'], $unggah);

					error_reporting(E_ERROR | E_PARSE);
					copy($gallery_image, $unggah);
									 
											$thumbpath= 'upload/gallery/thumbs/'.$gallery_image;
											$obj_img = new thumbnail_images();
											$obj_img->PathImgOld = $unggah;
											$obj_img->PathImgNew =$thumbpath;
											$obj_img->NewWidth = 256;
											$obj_img->NewHeight = 256;
											if (!$obj_img->create_thumbnail_images()) 
												{
												echo "Thumbnail not created... please upload image again";
													exit;
												}	 
	  
					// updating all data
					$sql_query = "UPDATE tbl_gallery 
							SET gallery_name = ?, gallery_image = ?, gallery_description = ? 
							WHERE gid = ?";
					
					$upload_image = $gallery_image;
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('ssss', 
									$gallery_name,
									$upload_image,
									$gallery_description,
									$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}else{
					
					// updating all data except image file
					$sql_query = "UPDATE tbl_gallery 
							SET gallery_name = ?, gallery_description = ? 
							WHERE gid = ?";
							
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('sss', 
									$gallery_name,
									$gallery_description,
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
												    Gallery Successfully Updated
											    </span>
											</div>";
				} else {
					$error['update_data'] = "<div class='card-panel red darken-1'>
											    <span class='white-text text-darken-2'>
												    Update Failed
											    </span>
											</div>";
				}
			}
			
		}
		
		// create array variable to store previous data
		$data = array();
			
		$sql_query = "SELECT * FROM tbl_gallery WHERE gid = ?";
			
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['gid'], 
					$data['gallery_name'], 
					$data['cat_id'],
					$data['gallery_image'],
					$data['gallery_description']
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
               			<h5 class="breadcrumbs-title">Edit Gallery</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a>
		                  </li>
		                  <li><a href="#" class="active">Edit Gallery</a>
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

							                <div class="col s12 m12 l12">
							                  	<div class="card-panel">

													<div class="row">
									                    <div class="input-field col s12">
									                        <input type="text" name="gallery_name" id="gallery_name" value="<?php echo $data['gallery_name']; ?>" required/>
									                        <label for="gallery_name">Image Name</label><?php echo isset($error['gallery_name']) ? $error['gallery_name'] : '';?>
									                    </div>
									                </div> 

									                <div class="row">
									                    <div class="input-field col s12">
									                        <input type="text" name="gallery_description" id="gallery_description" value="<?php echo $data['gallery_description']; ?>" required/>
									                        <label for="gallery_description">Image Description</label><?php echo isset($error['gallery_description']) ? $error['gallery_description'] : '';?>
									                    </div>
									                </div> 						                  			

								                    <img src="upload/gallery/<?php echo $data['gallery_image']; ?>" width="210" height="130"/>
								                    <div class="row">
														<div class="file-field input-field col s12">
															<div class="input-field col s12">
															<?php echo isset($error['gallery_image']) ? $error['gallery_image'] : '';?>
								                                <input class="file-path validate" type="text" disabled/>
								                                <div class="btn">
								                                    <span>Change</span>
								                                    <input type="file" name="gallery_image" id="gallery_image" value="" />
								                                </div>	
							                            </div>					                    
									                  	</div>
								                  	</div>

													<div class="row">
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
						    </form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>