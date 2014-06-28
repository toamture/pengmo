<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");

if($dopost == "change"){
	$uptime = date("Y-m-d H:i:s",time());
	$id = intval($id);			
	$inquery = "UPDATE `#@__order` SET name='$name', phone='$phone', template='$template', price='$price', message='$message', uptime='$uptime', domain='$domain' WHERE id='$id'";
					   
	//$dsql->ExecuteNoneQuery("UPDATE `#@__channeltype` SET fieldset='$oksetting' WHERE id='$id' ");
	$rs = $dsql->ExecuteNoneQuery($inquery);
	if(!$rs)
	{
		echo "修改失败";
		exit();
	}else{
		echo $uptime;
	}
}elseif($dopost == "drop"){
	$rs = $dsql->ExecuteNoneQuery("DELETE FROM `#@__order` WHERE id = '$uid' ");
	if(!$rs)
	{
		echo "删除失败";
		exit();
	}else{
		echo "删除成功";
	}
}


?>