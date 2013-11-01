<?php 
mb_internal_encoding("UTF-8"); 
?>
<?php include 'config.php' ?>
<?php
//TODO:for a certain buffer start address, fetch an array of words, transitions and images.
	function packjson($word)
	{
		return preg_replace("#\\\u([0-9a-f]+)#ie","iconv('UCS-2','UTF-8',pack('H4','\\1'))",$word);
	}
	if(!empty($_GET))
	{
		if(array_key_exists("id", $_GET))
		{
			if(array_key_exists("size", $_GET))
			{
				$start=$_GET["id"];
				$size=$_GET["size"];
			}
			else
			{
				$start=$_GET["id"];
				$size=1;
			}
		}
		else
		{
			die("235");
		}
	}
	else
	{
		die("234");
	}
	$start=intval($start);
	$size=intval($size);
	if(is_int($start)&&is_int($size))
	{
		$con = mysql_connect($host,$username,$password);
		mysql_set_charset("utf8",$con);
		if (!$con)
		{
	  		die('Could not connect: ' . mysql_error());
		}
		mysql_select_db($db,$con);
		$fetch=mysql_query("SELECT * FROM `test` WHERE  location>=".$start." AND location <".($start+$size),$con);
		$results=array();
		$i=0;
		while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) 
		{
		    array_push($results,$row);
		}

		$ret=json_encode($results);
		$ans= packjson($ret);
		echo $ans;
	}
	else
	{
		die("233");
	}
?>
