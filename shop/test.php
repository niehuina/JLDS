<?php  
if (isset($_SERVER['HTTP_HOST']))
{
	$host = $_SERVER['HTTP_HOST'];
}
echo md5($host);
 
?>