<?php
	include_once('../includes/config.php');
	include_once('../public/functions.php');
	
	if(isset($_GET['accesskey'])) {
		$access_key_received = $_GET['accesskey'];
		
		if($access_key_received == $access_key){
			// get all category data from category table
			$sql_query = "SELECT * 
					FROM tbl_category 
					ORDER BY category_name ASC ";
			
			$result = $connect->query($sql_query) or die ("Error :".mysql_error());
	 
			$categories = array();
			while($category = $result->fetch_assoc()) {
				$categories[] = array('category'=>$category);
			}
			
			// create json output
			$output = json_encode(array('data' => $categories));
		}else{
			die('accesskey is incorrect.');
		}
	} else {
		die('accesskey is required.');
	}
 
	//Output the output.
	echo $output;

?>