<?php
/**
 * 自定义模型管理
 *
 * @version        $Id: mychannel_main.php 1 15:26 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_List');
require_once(DEDEINC.'/datalistcp.class.php');
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");

$sql = "SELECT id,name,email,message,uptime FROM `#@__information` ORDER BY id DESC";
$dlist = new DataListCP();
$dlist->SetTemplet(DEDEADMIN."/templets/information.htm");
$dlist->SetSource($sql);
$dlist->display();