<?php
	include_once('functions.php'); 
	require_once("thumbnail_images.class.php");
?>

	<?php 
	
			
		//$max_serve = 10;
			
		if(isset($_POST['btnAdd'])){
			$news_heading = $_POST['news_heading'];
			$news_date = $_POST['news_date'];
			$news_description = $_POST['news_description'];
				
			// get image info
			$news_image = $_FILES['news_image']['name'];
			$image_error = $_FILES['news_image']['error'];
			$image_type = $_FILES['news_image']['type'];
			
				
			// create array variable to handle error
			$error = array();
			
			if(empty($news_heading)){
				$error['news_heading'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}						
				
			if(empty($news_date)){
				$error['news_date'] = "<span class='red darken-4 white-text text-darken-2'>&nbsp; Required &nbsp;</span>";
			}			

			if(empty($news_description)){
				$error['news_description'] = "<span class='red darken-4 white-text text-darken-2'>&nbsp; Required &nbsp;</span>";
			}
			
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			error_reporting(E_ERROR | E_PARSE);
			$extension = end(explode(".", $_FILES["news_image"]["name"]));
					
			if(!empty($news_image)){
				if(!(($image_type == "image/gif") || 
					($image_type == "image/jpeg") || 
					($image_type == "image/jpg") || 
					($image_type == "image/x-png") ||
					($image_type == "image/png") || 
					($image_type == "image/pjpeg")) &&
					!(in_array($extension, $allowedExts))){
					
					$error['news_image'] = "*<span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
				}
			}
				
			if( !empty($news_heading) && 
				!empty($news_date) && 
				empty($error['news_image']) && 
				!empty($news_description)) {
				
				if(!empty($news_image)){

				// create random image file name
				$string = '0123456789';
				$file = preg_replace("/\s+/", "_", $_FILES['news_image']['name']);
				$function = new functions;
				$news_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
					
				// upload new image
				$unggah = 'upload/news/'.$news_image;
				$upload = move_uploaded_file($_FILES['news_image']['tmp_name'], $unggah);

				error_reporting(E_ERROR | E_PARSE);
				copy($news_image, $unggah);
									 
											$thumbpath= 'upload/news/thumbs/'.$news_image;
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
				$sql_query = "INSERT INTO tbl_news (news_heading, news_date, news_image, news_description)
						VALUES(?, ?, ?, ?)";
						
				$upload_image = $news_image;
				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('ssss', 
								$news_heading,
								$news_date, 
								$upload_image,
								$news_description
								);
					// Execute query
					$stmt->execute();
					// store result 
					$result = $stmt->store_result();
					$stmt->close();
				}
			} else {

				// insert new data to menu table
				$sql_query = "INSERT INTO tbl_news (news_heading, news_date, news_description)
						VALUES(?, ?, ?)";
						
				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('sss', 
								$news_heading, 
								$news_date, 
								$news_description
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
												    New News Added Successfully
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
               			<h5 class="breadcrumbs-title">Add News</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a>
		                  </li>
		                  <li><a href="#" class="active">Add News</a>
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

							                <div class="col s12 m12 l5">
							                  	<div class="card-panel">

														<div class="row">
									                      <div class="input-field col s12">
									                        <input type="text" name="news_heading" id="news_heading" required/>
									                        <label for="news_heading">News Heading</label><?php echo isset($error['news_heading']) ? $error['news_heading'] : '';?>
									                      </div>
									                    </div> 						                  	   			                    


													<div class="row">
								                      <div class="input-field col s12">
								                        <input type="text" class="datepicker" name="news_date" id="news_date" required/>
								                        <label for="news_date">News Date <?php echo isset($error['news_date']) ? $error['news_date'] : '';?></label>
								                      </div>
								                    </div>  

								                    <div class="row">
														<div class="file-field input-field col s12">
															<div class="input-field col s12">
															<?php echo isset($error['news_image']) ? $error['news_image'] : '';?>
								                                <input class="file-path validate" type="text" disabled/>
								                                <div class="btn">
								                                    <span>Image</span>
								                                    <input type="file" name="news_image" id="news_image" value="" required/>
								                                </div>	
							                            </div>					                    
									                  	</div>
								                  	</div>
								                  	
							                	</div>
	              							</div>

								            <div class="col s12 m12 l7">
								                <div class="card-panel">

								                    <div class="row">
								                      <div class="input-field col s12">
								                      <span class="grey-text text-grey lighten-2">Description</span> 
								                       <?php echo isset($error['news_description']) ? $error['news_description'] : '';?>
														<textarea name="news_description" id="news_description" class="materialize-textarea" rows="16"></textarea>
														<script type="text/javascript" src="assets/js/ckeditor/ckeditor.js"></script>
														<script type="text/javascript">                      
												            CKEDITOR.replace( 'news_description' );
												        </script>		
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
