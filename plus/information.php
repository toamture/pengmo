<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");

if(!isset($vdcode)){
	$vdcode = '';
}
$svali = GetCkVdValue();
if(preg_match("/2/",$safe_gdopen)){
	if(strtolower($vdcode)!=$svali || $svali==''){
		//ResetVdValue();
		//ShowMsg('验证码错误！', 'index.php');
		echo "vdcode";
		exit();
	}
}

$uptime = date("Y-m-d H:i:s",time());

//echo $template;

$inquery = "INSERT INTO `#@__information`(`name`,`email`,`message`,`uptime`)
                   VALUES ('$name','$email','$message','$uptime'); ";
$rs = $dsql->ExecuteNoneQuery($inquery);
if(!$rs)
{
	ShowMsg(' 发表评论错误! ', '-1');
	echo $dsql->GetError();
	exit();
}
?>