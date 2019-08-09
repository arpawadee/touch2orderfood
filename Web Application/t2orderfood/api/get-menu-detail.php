<?php
	include_once('../includes/config.php');
	include_once('../public/functions.php');
	
	if(isset($_GET['accesskey']) && isset($_GET['menu_id'])) {
		$access_key_received = $_GET['accesskey'];
		$menu_id = $_GET['menu_id'];
		
		if($access_key_received == $access_key){
			// get menu data from menu table
			$sql_query = "SELECT menu_id, menu_name, menu_image, price, menu_status, menu_description, serve_for 
				FROM tbl_menu 
				WHERE menu_id = ".$menu_id;
				
			$result = $connect->query($sql_query) or die ("Error :".mysql_error());
	 
			$menus = array();
			while($menu = $result->fetch_assoc()) {
				$menus[] = array('menu_detail'=>$menu);
			}
		 
			// create json output
			$output = json_encode(array('data' => $menus));
		}else{
			die('accesskey is incorrect.');
		}
	} else {
		die('accesskey and menu id are required.');
	}
 
	//Output the output.
	echo $output;

?>