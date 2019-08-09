<?php
 
 	include_once ('../includes/config.php');
 	$connect->set_charset('utf8');
	
	if(isset($_GET['cat_id']))
	{		
			$query="SELECT * FROM tbl_news_category c,tbl_news n WHERE c.cid=n.cat_id and c.cid='".$_GET['cat_id']."' ORDER BY n.nid DESC";			
			$resouter = mysqli_query($connect, $query);
			
	}
	
	else if(isset($_GET['nid']))
	{		
			$id = $_GET['nid'];

			$query="SELECT * FROM tbl_news WHERE nid = '$id'";					
			$resouter = mysqli_query($connect, $query);
			
	}	
	
	else if(isset($_GET['latest_news']))
	{	
			$query = "SELECT * FROM tbl_news ORDER BY nid DESC";			
			$resouter = mysqli_query($connect, $query);
	}

	else
	{	
			$query = "SELECT * FROM tbl_news ORDER BY nid DESC";			
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