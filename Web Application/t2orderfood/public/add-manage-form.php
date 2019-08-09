<?php
	include_once('functions.php'); 
?>

<?php 
	
	if(isset($_POST['btnAdd'])){

		$table_no = $_POST['table_no'];
		$table_status = $_POST['table_status'];

		// create array variable to handle error
		$error = array();


		if(empty($table_no)){
			$error['table_no'] = " <span class='red darken-4 white-text text-darken-2'>&nbsp; Required! &nbsp;</span>";
		}

		if(empty($table_status)){
			$error['table_status'] = " <span class='red darken-4 white-text text-darken-2'>&nbsp; Not choosen &nbsp;</span>";
		}

		if(!empty($table_no) && !empty($table_status)){

			$sql_query = "INSERT INTO tbl_table (table_no, table_status)
						VALUES(?, ?)";


			$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('ss', 
								$table_no, 
								$table_status
								);
					// Execute query
					$stmt->execute();
					// store result 
					$result = $stmt->store_result();
					$stmt->close();
				}

			if($result) {
					$error['add_table'] = "<div class='card-panel teal lighten-2'>
											    <span class='white-text text-darken-2'>
												   New Table Added Successfully
											    </span>
											</div>";
				} else {
					$error['add_table'] = "<div class='card-panel red darken-1'>
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
               			<h5 class="breadcrumbs-title">Add New Table</h5>
		                <ol class="breadcrumb">
		        <?php if($_SESSION['position'] == "Cashier") : ?>
                  <li><a href="dashboard_c.php">Dashboard</a>
                  </li>
                <?php else : ?>
                  <li><a href="dashboard.php">Dashboard</a>
                  </li>
                <?php endif; ?>
		                  <li><a href="#" class="active">Add New Table</a>
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
		                    				<?php echo isset($error['add_table']) ? $error['add_table'] : '';?>       
											
											<div class="row">
						                      <div class="input-field col s12">
						                        <input type="text" name="table_no" id="table_no" required/>
						                        <label for="table_no">Table No.</label><?php echo isset($error['table_no']) ? $error['table_no'] : '';?>
						                      </div>
						                    </div> 	

						                    <div class="row">
									           <div class="input-field col s12">
			                                        <select name="table_status">
			                                           <option>ONLINE</option>
													   <option>OFFLINE</option>
			                                        </select>
			                                        <label>Status</label><?php echo isset($error['table_status']) ? $error['table_status'] : '';?></div>	
		                                     </div>

		                                     <div class="input-field col s12">
											  <button class="btn cyan waves-effect waves-light right"
	                                                type="submit" name="btnAdd">Add New Table
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