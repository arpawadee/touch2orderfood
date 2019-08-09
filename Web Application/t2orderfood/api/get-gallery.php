<?php
 
 	include_once ('../includes/config.php');
 	$connect->set_charset('utf8');
	
	if(isset($_GET['cat_id']))
	{		
			$query = "SELECT * FROM tbl_gallery_category c,tbl_gallery n WHERE c.cid=n.cat_id and c.cid='".$_GET['cat_id']."' ORDER BY n.gid DESC";			
			$resouter = mysqli_query($connect, $query);
			
	}
	
	else if(isset($_GET['gid']))
	{		
			$id = $_GET['gid'];

			$query="SELECT * FROM tbl_gallery WHERE gid = '$id'";					
			$resouter = mysqli_query($connect, $query);
			
	}	
	
	else if(isset($_GET['latest_gallery']))
	{	
			$query = "SELECT * FROM tbl_gallery ORDER BY gid DESC";			
			$resouter = mysqli_query($connect, $query);
	}

	else
	{	
			$query = "SELECT * FROM tbl_gallery ORDER BY gid DESC";			
			$resouter = mysqli_query($connect, $query);
	}
     
    $set = array();
     
    $total_records = mysqli_num_rows($resouter);
    if($total_records >= 1){
     
      while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
	   
        $set['data'][] = $link;
      }
    }
     
	header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set)); 	 
	 
?>