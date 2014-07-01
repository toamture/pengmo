<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");

if($dopost == "change"){
	$uptime = date("Y-m-d H:i:s",time());
	$id = intval($id);			
	$inquery = "UPDATE `#@__information` SET name='$name', email='$email', message='$message', uptime='$uptime' WHERE id='$id'";
					   
	//$dsql->ExecuteNoneQuery("UPDATE `#@__channeltype` SET fieldset='$oksetting' WHERE id='$id' ");
	$rs = $dsql->ExecuteNoneQuery($inquery);
	if(!$rs)
	{
		echo "fail";
		exit();
	}else{
		echo $uptime;
	}
}elseif($dopost == "drop"){
	$rs = $dsql->ExecuteNoneQuery("DELETE FROM `#@__information` WHERE id = '$uid' ");
	if(!$rs)
	{
		echo "fail";
		exit();
	}else{
		echo "删除成功";
	}
}


?>