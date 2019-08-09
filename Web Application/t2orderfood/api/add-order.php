<?php
	include_once('../includes/config.php');
	
	// get data from android app
	$name 				= $_POST['name'];
	$table_no		 	= $_POST['table_no'];
	$date_time 			= $_POST['date_time'];
	$order_list 		= $_POST['order_list'];
	$total_price 		= $_POST['total_price'];
	$comment 			= $_POST['comment'];

		
	$sql_query = "set names 'utf8'";
	$stmt = $connect->stmt_init();
	if($stmt->prepare($sql_query)) {	
		// Execute query
		$stmt->execute();
		// store result 
		$stmt->close();
	}
	

			$sql_query = "SELECT ID, table_no, status, bill_status
				FROM tbl_order 
				WHERE table_no = ? AND status <> 'CANCELED' AND bill_status = 0";
						
			$stmt = $connect->stmt_init();
			if($stmt->prepare($sql_query)) {
				// Bind your variables to replace the ?s
				$stmt->bind_param('s', $table_no);
				// Execute query
				$stmt->execute();
				/* store result */
				$stmt->store_result();
				$num = $stmt->num_rows;
				$stmt->close();
				if($num == 1){					
					$status = "PENDING";
					$sql_query3 = "UPDATE tbl_order
									SET name = ?, date_time = ?, order_list = ?, total_price = ?, status = ?, comment = ? 
									WHERE table_no = ? AND bill_status = 0 AND status <> 'CANCELED'";

					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query3)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('sssssss', 
									$name, 
									$date_time,  
									$order_list,
									$total_price,
									$status,
									$comment,
									$table_no
									);
					// Execute query
					$stmt->execute();
					$result = $stmt->affected_rows;
					$stmt->close();
					}

					
					
				}else{

					$sql_query4 = "INSERT INTO tbl_order (name, table_no, date_time, order_list, total_price, comment) 
									VALUES (?, ?, ?, ?, ?, ?)";
	
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query4)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('ssssss', 
									$name,
									$table_no, 
									$date_time,  
									$order_list,
									$total_price,
									$comment
									);
					// Execute query
					$stmt->execute();
					$result = $stmt->affected_rows;
					// store result 
					//$result = $stmt->store_result();
					$stmt->close();
					}

				
				}
			}






	$table_status 		= $_POST['table_status'];
    $bill_status 		= $_POST['bill_status'];

	$sql_query2 = "set names 'utf8'";
	$stmt = $connect->stmt_init();
	if($stmt->prepare($sql_query2)) {	
		// Execute query
		$stmt->execute();
	 
		$stmt->close();
	}

	$sql_query2 = "UPDATE tbl_table t,tbl_order o 
					SET t.table_status = 'ONLINE' 
					WHERE t.table_no = o.table_no AND o.bill_status = 0 AND o.status <> 'CANCELED'";

	$stmt = $connect->stmt_init();
	if($stmt->prepare($sql_query2)) {	
		// Bind your variables to replace the ?s
		$stmt->bind_param('ss', 
					$table_status,
					$table_no
					);
		// Execute query
		$stmt->execute();
		$result = $stmt->affected_rows;
	
		$stmt->close();
	}

if($result) {
		echo "OK";
	} else {
		echo "Failed";
	}



?>