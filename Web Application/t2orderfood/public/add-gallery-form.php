<?php
	include_once('functions.php'); 
	require_once("thumbnail_images.class.php");
?>

	<?php 
			
		//$max_serve = 10;
			
		if(isset($_POST['btnAdd'])){
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
				$error['gallery_description'] = "<span class='red darken-4 white-text text-darken-2'>&nbsp; Required &nbsp;</span>";
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
				empty($error['gallery_image']) && 
				!empty($gallery_description)) {
				
				if(!empty($gallery_image)){

				// create random image file name
				$string = '0123456789';
				$file = preg_replace("/\s+/", "_", $_FILES['gallery_image']['name']);
				$function = new functions;
				$gallery_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
					
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
		
				// insert new data to menu table
				$sql_query = "INSERT INTO tbl_gallery (gallery_name, gallery_image, gallery_description)
						VALUES(?, ?, ?)";
						
				$upload_image = $gallery_image;
				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('sss', 
								$gallery_name, 
								$upload_image,
								$gallery_description
								);
					// Execute query
					$stmt->execute();
					// store result 
					$result = $stmt->store_result();
					$stmt->close();
				}
			} else {

				// insert new data to menu table
				$sql_query = "INSERT INTO tbl_gallery (gallery_name, gallery_description)
						VALUES(?, ?)";
						
				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('ss', 
								$gallery_name,
								$gallery_description
								);
					// Execute query
					$stmt->execute();
					// store result 
					$result = $stmt->store_result();
					$stmt->close();

			}
		}
				
				if($result) {
					$error['add_menu'] = "<div class='card-panel teal lighten-2'>
											    <span class='white-text text-darken-2'>
												    New Gallery Added Successfully
											    </span>
											</div>";
				} else {
					$error['add_menu'] = "<div class='card-panel red darken-1'>
											    <span class='white-text text-darken-2'>
												    Added Failed
											    </span>
											</div>";
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
               			<h5 class="breadcrumbs-title">Add Gallery</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a>
		                  </li>
		                  <li><a href="#" class="active">Add Gallery</a>
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
		                    		<?php echo isset($error['add_menu']) ? $error['add_menu'] : '';?>   
            
								        <div class="row">

							                <div class="col s12 m12 l12">
							                  	<div class="card-panel">

													<div class="row">
									                    <div class="input-field col s12">
									                    	<input type="text" name="gallery_name" id="gallery_name" required/>
									                        <label for="gallery_name">Name</label><?php echo isset($error['gallery_name']) ? $error['gallery_name'] : '';?>
									                    </div>
									                </div>

													<div class="row">
									                    <div class="input-field col s12">
									                    	<input type="text" name="gallery_description" id="gallery_description" required/>
									                        <label for="gallery_description">Description</label><?php echo isset($error['gallery_description']) ? $error['gallery_description'] : '';?>
									                    </div>
									                </div> 	


								                    <div class="row">
														<div class="file-field input-field col s12">
															<div class="input-field col s12">
															<?php echo isset($error['gallery_image']) ? $error['gallery_image'] : '';?>
								                                <input class="file-path validate" type="text" disabled/>
								                                <div class="btn">
								                                    <span>Image</span>
								                                    <input type="file" name="gallery_image" id="gallery_image" value="" required/>
								                                </div>	
							                            </div>					                    
									                  	</div>
								                  	</div>

													<div class="row">
														<div class="input-field col s12">
					                                        <button class="btn cyan waves-effect waves-light right"
					                                                type="submit" name="btnAdd">Publish
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
