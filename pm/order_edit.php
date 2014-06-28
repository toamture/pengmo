<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");

$uptime = date("Y-m-d H:i:s",time());

//echo $template;

$inquery = "INSERT INTO `#@__order`(`name`,`phone`,`template`,`domain`,`price`,`message`,`uptime`)
                   VALUES ('$name','$phone','$template','$domain','$price','$message','$uptime'); ";
$rs = $dsql->ExecuteNoneQuery($inquery);
if(!$rs)
{
	ShowMsg(' 修改错误! ', '-1');
	echo $dsql->GetError();
	exit();
}else{
	echo $uptime;
}
?>