<?php 
	include 'config.php' 
?>
<?php
//TODO:for a certain buffer start address, fetch an array of words, transitions and images.
	function myjson($code)
	{
		$code = json_encode(urlencodeAry($code));
		return urldecode($code);
	}

	function urlencodeAry($data)
	{
		if(is_array($data))
		{
			foreach($data as $key=>$val)
			{
				$data[$key] = urlencodeAry($val);
			}
			return $data;
		}
		else
		{
			return urlencode($data);
		}
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

		//$ret=json_encode($results);
		$ans=myjson($results);
		echo $ans;
	}
	else
	{
		die("233");
	}
?>
