<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");

$name = $_POST["name"];
$phone = $_POST["phone"];
$template = $_POST["template"];
$domain = $_POST["domain"];
$price = $_POST["price"];
$message = $_POST["message"];
$uptime = time();

//echo $template;

$inquery = "INSERT INTO `#@__order`(`name`,`phone`,`template`,`domain`,`price`,`message`,`uptime`)
                   VALUES ('$name','$phone','$template','$domain','$price','$message','$uptime'); ";
$rs = $dsql->ExecuteNoneQuery($inquery);
if(!$rs)
{
	ShowMsg(' 发表评论错误! ', '-1');
	echo $dsql->GetError();
	exit();
}
?>